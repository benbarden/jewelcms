<?php


namespace Cms\Theme;

use Cms\Ia\Link\CategoryLink,
    Cms\Ia\Link\ArticleLink,
    Cms\Ia\Link\UserLink,
    Cms\Data\User\UserRepository,
    Cms\Entity\User,
    Cms\Repository\Category as RepoCategory,
    Cms\Repository\Article as RepoArticle,
    Cms\Exception\Theme\EngineException;


class Engine
{
    /**
     * @var string
     */
    private $themeDomain;

    /**
     * @var array
     */
    private $pathsArray;

    /**
     * @var array
     */
    private $cPanelPathsArray;

    /**
     * @var array
     */
    private $envArray;

    /**
     * @var string
     */
    private $publicThemePath;

    /**
     * @var CategoryLink
     */
    private $iaLinkCategory;

    /**
     * @var ArticleLink
     */
    private $iaLinkArticle;

    /**
     * @var UserLink
     */
    private $iaLinkUser;

    /**
     * @var RepoArticle
     */
    private $repoArticle;

    /**
     * @var RepoCategory
     */
    private $repoCategory;

    /**
     * @var UserRepository
     */
    private $repoUser;

    /**
     * @var User
     */
    private $loggedInUser;

    /**
     * @var string
     */
    private $dateFormat;

    /**
     * @param string $themeDomain
     * @param string $current
     * @param integer $cache
     * @throws \Exception
     */
    public function __construct($themeDomain, $current = "", $cache = 1)
    {
        $this->themeDomain = $themeDomain;

        // Validate theme path
        if (!$current) {
            throw new EngineException('Current theme not defined!');
        }
        $userThemePath = sprintf('%sthemes/user/%s', ABS_ROOT, $current);
        if (!is_dir($userThemePath)) {
            throw new EngineException(sprintf('Cannot find theme: %s', $userThemePath));
        }
        $this->pathsArray = array(
            $userThemePath,
            ABS_ROOT.'themes/system',
        );
        $this->cPanelPathsArray = array(
            ABS_ROOT.'themes/cpanel/jewelcms',
        );

        // Set up caching
        if ($cache == 1) {
            $this->envArray = array('cache' => ABS_ROOT.'data/cache');
        } else {
            $this->envArray = array();
        }

        // Save the theme path
        $this->publicThemePath = sprintf('%sthemes/user/%s/', URL_ROOT, $current);

        // Instantiate Twig
        \Twig_Autoloader::register();
    }

    public function setIALinkCategory(CategoryLink $iaLinkCategory)
    {
        $this->iaLinkCategory = $iaLinkCategory;
    }

    public function setIALinkArticle(ArticleLink $iaLinkArticle)
    {
        $this->iaLinkArticle = $iaLinkArticle;
    }

    public function setIALinkUser(UserLink $iaLinkUser)
    {
        $this->iaLinkUser = $iaLinkUser;
    }

    public function setRepoCategory(RepoCategory $repoCategory)
    {
        $this->repoCategory = $repoCategory;
    }

    public function setRepoArticle(RepoArticle $repoArticle)
    {
        $this->repoArticle = $repoArticle;
    }

    public function setRepoUser(UserRepository $repoUser)
    {
        $this->repoUser = $repoUser;
    }

    public function setLoggedInUser(User $user)
    {
        $this->loggedInUser = $user;
    }

    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * @return string
     */
    public function getPublicThemePath()
    {
        return $this->publicThemePath;
    }

    private function setupFunctions(\Twig_Environment $twig)
    {

        // cmsBlock
        $funcBlock = new \Twig_SimpleFunction('cmsBlock',
            array($this, 'cmsBlock'),
            array('is_safe' => array('html'),
            'needs_environment' => true,
            'needs_context' => true
        ));

        $twig->addFunction($funcBlock);

        // Functions list
        $cmsCustomFunctions = array(
            'cmsDataContentRecent',
            'cmsDataCategoryTopLevel',
            'cmsDomainFull',
            'cmsFormatDate',
            'cmsLinkArticle',
            'cmsLinkCategory',
            'cmsLinkUser',
            'cmsLinkPage',
        );

        // Map Twig functions
        foreach ($cmsCustomFunctions as $custFunc) {
            $twigFunction = new \Twig_SimpleFunction($custFunc,
                array($this, $custFunc),
                array('is_safe' => array('html')
                ));
            $twig->addFunction($twigFunction);
        }

        return $twig;
    }

    private function setupCPanelFunctions(\Twig_Environment $twig)
    {
        // cpLink
        $funcLink = new \Twig_SimpleFunction('cpLink',
            array($this, 'cpLink'),
            array('is_safe' => array('html'))
        );
        $twig->addFunction($funcLink);
        return $twig;
    }

    public function cmsBlock(\Twig_Environment $twig, $context, $blockFile, $customBindings = array())
    {
        if ($customBindings) {
            $context = array_merge($context, $customBindings);
        }
        $urlThemeRoot = $context['URL']['ThemeRoot'];
        $absSysBlockPath  = sprintf(ABS_ROOT.'themes/system/blocks/%s.twig', $blockFile);
        $absUserBlockPath = sprintf(ABS_ROOT.$urlThemeRoot.'blocks/%s.twig', $blockFile);
        $relBlockPath = sprintf('blocks/%s.twig', $blockFile);
        if (file_exists($absSysBlockPath) || file_exists($absUserBlockPath)) {
            return $twig->render($relBlockPath, $context);
        } else {
            return sprintf('<p><strong>MISSING: %s</strong></p>', $blockFile);
        }
    }

    public function cmsDataContentRecent($limit)
    {
        $contentArray = $this->repoArticle->getRecentPublic($limit);
        return $contentArray;
    }

    public function cmsDataCategoryTopLevel($limit)
    {
        $categoryArray = $this->repoCategory->getTopLevel();
        return $categoryArray;
    }

    public function cmsDomainFull()
    {
        return $this->themeDomain;
    }

    public function cmsFormatDate(\DateTime $date, $format = '')
    {
        if ($format) {
            return $date->format($format);
        } else {
            return $date->format($this->dateFormat);
        }
    }

    public function cmsLinkArticle($itemId)
    {
        $article = $this->repoArticle->getById($itemId);
        $categoryId = $article->getCategoryId();
        $category = $this->repoCategory->getById($categoryId);
        $this->iaLinkArticle->setCategory($category);
        $this->iaLinkArticle->setArticle($article);
        $outputHtml = $this->iaLinkArticle->generate();
        return $outputHtml;
    }

    public function cmsLinkCategory($itemId)
    {
        $category = $this->repoCategory->getById($itemId);
        $this->iaLinkCategory->setCategory($category);
        $outputHtml = $this->iaLinkCategory->generate();
        return $outputHtml;
    }

    public function cmsLinkUser($itemId)
    {
        $user = $this->repoUser->getById($itemId);
        $this->iaLinkUser->setUser($user);
        $outputHtml = $this->iaLinkUser->generate();
        return $outputHtml;
    }

    public function cmsLinkPage($link, $title = '', $tagOpen = 'li', $tagClose = 'li')
    {
        $outputHtml = ''; $url = '';

        $supportedLinkTypes = array('archives', 'register', 'login', 'logout', 'cp');

        if (!in_array($link, $supportedLinkTypes)) {
            return sprintf('Unknown link: %s', $link);
        }

        switch ($link) {
            case 'archives':
                if (!$title) {
                    $title = 'Archives';
                }
                $url = URL_ROOT.'cms/archives';
                break;
            case 'register':
                if (!$title) {
                    $title = 'Register';
                }
                if (!$this->loggedInUser) {
                    $url = URL_ROOT.'register.php';
                }
                break;
            case 'login':
                if (!$title) {
                    $title = 'Login';
                }
                if (!$this->loggedInUser) {
                    $url = URL_ROOT.'login.php';
                }
                break;
            case 'logout':
                if (!$title) {
                    $title = 'Logout';
                }
                if ($this->loggedInUser) {
                    $url = URL_ROOT.'logout.php';
                }
                break;
            case 'cp':
                if (!$title) {
                    $title = 'Control Panel';
                }
                if ($this->loggedInUser) {
                    $url = URL_ROOT.'cp/index.php';
                }
                break;
        }

        if ($url) {
            $outputHtml = sprintf('<%s><a href="%s">%s</a></%s>', $tagOpen, $url, $title, $tagClose);
        }
        return $outputHtml;
    }

    public function cpLink($link, $params = array())
    {
        $url = '';

        if (array_key_exists('action', $params)) {
            $action = $params['action'];
        } else {
            $action = "";
        }
        if (array_key_exists('id', $params)) {
            $id = $params['id'];
        } else {
            $id = "";
        }
        if (array_key_exists('type', $params)) {
            $type = $params['type'];
        } else {
            $type = "";
        }

        switch ($link) {
            case 'index':
                $url = URL_ROOT.'cp/index.php';
                break;
            case 'write':
                $url = sprintf(URL_ROOT.'cp/write.php?action=%s&id=%s', $action, $id);
                break;
            case 'users':
                $url = sprintf(URL_ROOT.'cp/users.php?action=%s', $action);
                break;
            case 'articles':
                $url = URL_ROOT.'cp/articles.php';
                break;
            case 'article':
                $url = sprintf(URL_ROOT.'cp/article.php?action=%s&id=%s', $action, $id);
                break;
            case 'content_manage':
                $url = URL_ROOT.'cp/content_manage.php?area=0&status=0&user=';
                break;
            case 'edit_profile':
                $url = URL_ROOT.'cp/edit_profile.php';
                break;
            case 'change_password':
                $url = URL_ROOT.'cp/change_password.php';
                break;
            case 'manage_avatars':
                $url = URL_ROOT.'cp/manage_avatars.php';
                break;
            case 'categories':
                $url = URL_ROOT.'cp/categories.php';
                break;
            case 'category':
                $url = sprintf(URL_ROOT.'cp/category.php?action=%s&id=%s', $action, $id);
                break;
            case 'files':
                $url = sprintf(URL_ROOT.'cp/files.php?type=%s', $type);
                break;
            case 'file_add':
                $url = URL_ROOT.'cp/files_site_upload.php?action=create';
                break;
            // Settings
            case 'settings_general':
                $url = URL_ROOT.'cp/general_settings.php';
                break;
            case 'settings_content':
                $url = URL_ROOT.'cp/content_settings.php';
                break;
            case 'settings_file':
                $url = URL_ROOT.'cp/files_settings.php';
                break;
            case 'settings_url':
                $url = URL_ROOT.'cp/url_settings.php';
                break;
            case 'themes':
                $url = URL_ROOT.'cp/themes.php';
                break;
            case 'permissions':
                $url = URL_ROOT.'cp/permission.php?action=edit&id=1';
                break;
            case 'user_roles':
                $url = URL_ROOT.'cp/user_roles.php';
                break;
            // Tools
            case 'tools_user_sessions':
                $url = URL_ROOT.'cp/tools_user_sessions.php';
                break;
            case 'tools_access_log':
                $url = URL_ROOT.'cp/access_log.php';
                break;
            case 'tools_error_log':
                $url = URL_ROOT.'cp/error_log.php';
                break;
            // Catchall
            default:
                $url = 'ERROR::'.$link;
                break;
        }

        return $url;
    }

    /**
     * @return \Twig_Environment
     */
    public function getEngine()
    {
        $loader = new \Twig_Loader_Filesystem($this->pathsArray);
        $twig = new \Twig_Environment($loader, $this->envArray);
        $twig = $this->setupFunctions($twig);
        return $twig;
    }

    /**
     * @return \Twig_Environment
     */
    public function getEngineCPanel()
    {
        $loader = new \Twig_Loader_Filesystem($this->cPanelPathsArray);
        $twig = new \Twig_Environment($loader, $this->envArray);
        $twig = $this->setupFunctions($twig);
        $twig = $this->setupCPanelFunctions($twig);
        return $twig;
    }

    /**
     * @return \Twig_Environment
     */
    public function getEngineUnitTesting()
    {
        $loader = new \Twig_Loader_Array(array(
            'index.html' => '<p>Hello {{ Name }}!</p>',
        ));
        return new \Twig_Environment($loader);
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        unset($this->engine);
        unset($this->iaLinkArea);
        unset($this->iaLinkArticle);
        unset($this->iaLinkUser);
        unset($this->loggedInUser);
        unset($this->repoArea);
        unset($this->repoArticle);
        unset($this->repoUser);
    }
} 