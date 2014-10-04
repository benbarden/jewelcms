<?php


namespace Cms\Theme;


class Renderer
{
    const OBJECT_TYPE_AREA     = 'area';
    const OBJECT_TYPE_CATEGORY = 'category';
    const OBJECT_TYPE_ARTICLE  = 'article';
    const OBJECT_TYPE_FILE     = 'file';
    const OBJECT_TYPE_USER     = 'user';

    /**
     * @var \Cms\Core\Di\Container
     */
    private $container;

    /**
     * @var object
     */
    private $renderer;

    /**
     * @var string
     */
    private $objectType;

    /**
     * @var integer
     */
    private $itemId;

    /**
     * @param \Cms\Core\Di\Container $container
     * @return void
     */
    public function __construct(\Cms\Core\Di\Container $container)
    {
        $this->container = $container;
    }

    public function __destruct()
    {
        unset($this->container);
        unset($this->renderer);
    }

    public function setObjectCategory()
    {
        $this->objectType = self::OBJECT_TYPE_CATEGORY;
    }

    public function setObjectArticle()
    {
        $this->objectType = self::OBJECT_TYPE_ARTICLE;
    }

    public function setObjectFile()
    {
        $this->objectType = self::OBJECT_TYPE_FILE;
    }

    public function setObjectUser()
    {
        $this->objectType = self::OBJECT_TYPE_USER;
    }

    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
    }

    public function isObjectCategory()
    {
        return $this->objectType == self::OBJECT_TYPE_CATEGORY;
    }

    public function isObjectArticle()
    {
        return $this->objectType == self::OBJECT_TYPE_ARTICLE;
    }

    public function isObjectFile()
    {
        return $this->objectType == self::OBJECT_TYPE_FILE;
    }

    public function isObjectUser()
    {
        return $this->objectType == self::OBJECT_TYPE_USER;
    }

    public function getItemId()
    {
        return $this->itemId;
    }

    public function render()
    {
        $this->getRenderer();
        $themeFile = $this->renderer->getFile();
        $userBindings = $this->renderer->getBindings();

        if (array_key_exists('CMS', $userBindings)) {
            throw new \Exception('Illegal binding key! CMS-level bindings cannot be set at the theme level.');
        }

        // we may allow certain overrides here
        $globalBindings = $this->getGlobalBindings();

        $bindings = array_merge($globalBindings, $userBindings);
        $engine = $this->container->getService('Theme.Engine');
        $outputHtml = $engine->render($themeFile, $bindings);
        print($outputHtml);
        exit;
    }

    private function getRenderer()
    {
        switch ($this->objectType) {
            case self::OBJECT_TYPE_AREA:
            case self::OBJECT_TYPE_CATEGORY:

                // Area and subarea setup
                $areaRepo = $this->container->getService('Repo.Area');
                /* @var \Cms\Data\Area\AreaRepository $areaRepo */
                $area = $areaRepo->getById($this->itemId);
                $subareas = $areaRepo->getSubareas($this->itemId);

                // Renderer setup
                $this->renderer = new \Cms\Theme\User\Category($this->container);
                $this->renderer->setArea($area);
                if ($subareas) {
                    $this->renderer->setSubareas($subareas);
                }
                $this->renderer->setupBindings();
            break;
            case self::OBJECT_TYPE_ARTICLE:
                //$this->renderer = new \Cms\Theme\User\Article();
                //break;
            case self::OBJECT_TYPE_FILE:
                //$this->renderer = new \Cms\Theme\User\File();
                //break;
            case self::OBJECT_TYPE_USER:
                //$this->renderer = new \Cms\Theme\User\User();
                //break;
            default:
                throw new \Exception(sprintf('Unknown object type: %s', $this->objectType));
                break;
        }
    }

    private function getGlobalBindings()
    {
        $bindings = array();

        $cmsThemeEngine = $this->container->getService('Cms.ThemeEngine');
        $publicThemePath = $cmsThemeEngine->getPublicThemePath();

        $repoSetting = $this->container->getService('Repo.Setting');
        /* @var \Cms\Data\Setting\SettingRepository $repoSetting */
        $siteTitle = $repoSetting->getSettingSiteTitle();
        $siteDesc = $repoSetting->getSettingSiteDesc();
        $siteKeywords = $repoSetting->getSettingSiteKeywords();
        $siteFavicon = $repoSetting->getSettingSiteFavicon();
        $siteRSSArticlesUrl = $repoSetting->getSettingRSSArticlesURL();
        $siteCustomHeader = $repoSetting->getSettingSiteHeader();

        $repoArea = $this->container->getService('Repo.Area');
        /* @var \Cms\Data\Area\AreaRepository $repoArea */
        $navPrimary = $repoArea->getNavigation('Primary');
        $navSecondary = $repoArea->getNavigation('Secondary');
        $navTertiary = $repoArea->getNavigation('Tertiary');

        // Default RSS URL
        if (!$siteRSSArticlesUrl) {
            $siteRSSArticlesUrl = FN_FEEDS."?name=articles";
        }

        // Core styles URL
        $siteStylesCoreUrl = URL_SYS_ROOT."core.css";
        $siteScriptsCoreUrl = URL_SYS_ROOT."scripts.js";
        $siteScriptsInitUrl = URL_SYS_ROOT."init.js";

        $settingsArray = array(
            'SiteTitle' => $siteTitle,
            'SiteDesc' => $siteDesc,
            'SiteKeywords' => $siteKeywords,
            'SiteFavicon' => $siteFavicon,
            'SiteRSSArticlesUrl' => $siteRSSArticlesUrl,
            'SiteStylesCoreUrl' => $siteStylesCoreUrl,
            'SiteScriptsCoreUrl' => $siteScriptsCoreUrl,
            'SiteScriptsInitUrl' => $siteScriptsInitUrl,
            'SiteCustomHeader' => $siteCustomHeader
        );

        $bindings['CMS']['Settings'] = $settingsArray;

        $bindings['URL']['SiteRoot'] = URL_ROOT;
        $bindings['URL']['ThemeRoot'] = $publicThemePath;

        $bindings['Nav']['Primary'] = $this->processAreaData($navPrimary);
        $bindings['Nav']['Secondary'] = $this->processAreaData($navSecondary);
        $bindings['Nav']['Tertiary'] = $this->processAreaData($navTertiary);

        // User access
        if ($this->container->hasService('Auth.CurrentUser')) {
            $authCurrentUser = $this->container->getService('Auth.CurrentUser');
            $userArray = array(
                'Name' => $authCurrentUser->getUsername()
            );
            $bindings['Login']['User'] = $userArray;
        }

        return $bindings;
    }

    private function processAreaData($areaData)
    {
        $areaArray = array();

        if ($areaData) {
            foreach ($areaData as $areaItem) {
                $areaObject = new \Cms\Data\Area\Area($areaItem);
                /* @var \Cms\Data\Area\Area $areaObject */
                $areaRow = array(
                    'Id' => $areaObject->getAreaId(),
                    'Name' => $areaObject->getName(),
                    'Desc' => $areaObject->getAreaDescription()
                );
                $areaArray[] = $areaRow;
            }
        }

        return $areaArray;
    }
}