<?php


namespace Cms\Core\Di;

use Cms\Data\AccessLog\AccessLogRepository,
    Cms\Data\Area\AreaRepository,
    Cms\Data\Article\ArticleRepository,
    Cms\Data\Category\CategoryRepository,
    Cms\Data\Permission\PermissionRepository,
    Cms\Data\Setting\SettingRepository,
    Cms\Data\UrlMapping\UrlMappingRepository,
    Cms\Data\User\UserRepository,
    Cms\Data\UserSession\UserSessionRepository;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class Factory
{
    /**
     * @var \Cms\Entity\User
     */
    private $currentUser;

    /**
     * @param EntityManager $entityManager
     */
    private function setupCurrentUserEntity(EntityManager $entityManager)
    {
        $accessLogin = new \Cms\Access\Login();
        $cookie = $accessLogin->getCookie();
        $validSession = $entityManager->getRepository('Cms\Entity\UserSession')->getFromCookie($cookie);
        if ($validSession) {
            /* @var \Cms\Entity\UserSession $validSession */
            $userId = $validSession->getUserId();
            $userEntity = $entityManager->getRepository('Cms\Entity\User')->getById($userId);
            if ($userEntity) {
                $this->currentUser = $userEntity;
            }
        }
    }

    public function buildContainer(Config $config)
    {
        // DB params
        $dbDsn  = $config->getByKey('Database.DSN');
        $dbSchema = $config->getByKey('Database.Schema');
        $dbUser = $config->getByKey('Database.User');
        $dbPass = $config->getByKey('Database.Pass');

        // Doctrine
        $dbParams = array(
            'driver' => 'pdo_mysql', 'user' => $dbUser, 'password' => $dbPass, 'dbname' => $dbSchema,
        );
        $paths = array(WWW_ROOT.'lib/Cms/Entity/');
        $proxyDir = WWW_ROOT.'data/cache/';
        $isDevMode = false;
        $doctrineConfig = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, $proxyDir, null, false);
        $doctrineConfig->setAutoGenerateProxyClasses(true);
        $entityManager = EntityManager::create($dbParams, $doctrineConfig);

        $this->setupCurrentUserEntity($entityManager);

        // Old data layer
        $pdo = new \PDO($dbDsn, $dbUser, $dbPass, array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ));

        $repoAccessLog = new AccessLogRepository($pdo);
        $repoArea = new AreaRepository($pdo);
        $repoArticle = new ArticleRepository($pdo);
        $repoCategory = new CategoryRepository($pdo);
        $repoPermission = new PermissionRepository($pdo);
        $repoSetting = new SettingRepository($pdo);
        $repoUrlMapping = new UrlMappingRepository($pdo);
        $repoUser = new UserRepository($pdo);
        $repoUserSession = new UserSessionRepository($pdo);

        $dateFormat = $repoSetting->getDateFormat();
        $linkStyle  = $repoSetting->getSettingLinkStyle();

        $iaOptimiser    = new \Cms\Ia\Tools\OptimiseUrl();
        $iaLinkArticle  = new \Cms\Ia\Link\ArticleLink($linkStyle, $iaOptimiser);
        $iaLinkCategory = new \Cms\Ia\Link\CategoryLink($linkStyle, $iaOptimiser);
        $iaLinkUser     = new \Cms\Ia\Link\UserLink($linkStyle, $iaOptimiser);

        $themeBinding = new \Cms\Theme\Binding();

        $themeDomain  = $config->getByKey('Theme.Domain');
        $themeCurrent = $config->getByKey('Theme.Current');
        $themeCache   = $config->getByKey('Theme.Cache');
        $engineCache  = ($themeCache == 'On') ? 1 : 0;

        $cmsThemeEngine = new \Cms\Theme\Engine($themeDomain, $themeCurrent, $engineCache);
        $cmsThemeEngine->setIALinkCategory($iaLinkCategory);
        $cmsThemeEngine->setIALinkArticle($iaLinkArticle);
        $cmsThemeEngine->setIALinkUser($iaLinkUser);
        $cmsThemeEngine->setRepoCategory($entityManager->getRepository('Cms\Entity\Category'));
        $cmsThemeEngine->setRepoArticle($entityManager->getRepository('Cms\Entity\Article'));
        $cmsThemeEngine->setRepoUser($repoUser);
        if ($this->currentUser) {
            $cmsThemeEngine->setLoggedInUser($this->currentUser);
        }
        $cmsThemeEngine->setDateFormat($dateFormat);
        $themeEngine       = $cmsThemeEngine->getEngine();
        $themeEngineCPanel = $cmsThemeEngine->getEngineCPanel();
        $themeEngineUT     = $cmsThemeEngine->getEngineUnitTesting();

        $serviceLocator = new ServiceLocator();
        if ($this->currentUser) {
            $serviceLocator->setAuthCurrentUser($this->currentUser);
        }
        $serviceLocator->setCmsConfig($config);
        $serviceLocator->setCmsThemeEngine($cmsThemeEngine);
        $serviceLocator->setCmsEntityManager($entityManager);
        $serviceLocator->setIALinkCategory($iaLinkCategory);
        $serviceLocator->setIALinkArticle($iaLinkArticle);
        $serviceLocator->set('Repo.AccessLog', $repoAccessLog);
        $serviceLocator->set('Repo.Area', $repoArea);
        $serviceLocator->set('Repo.Article', $repoArticle);
        $serviceLocator->set('Repo.Category', $repoCategory);
        $serviceLocator->set('Repo.Permission', $repoPermission);
        $serviceLocator->set('Repo.Setting', $repoSetting);
        $serviceLocator->set('Repo.UrlMapping', $repoUrlMapping);
        $serviceLocator->set('Repo.User', $repoUser);
        $serviceLocator->set('Repo.UserSession', $repoUserSession);
        $serviceLocator->set('Theme.Engine', $themeEngine);
        $serviceLocator->set('Theme.EngineCPanel', $themeEngineCPanel);
        $serviceLocator->set('Theme.EngineUT', $themeEngineUT);
        $serviceLocator->set('Theme.Binding', $themeBinding);

        $container = new Container($serviceLocator);

        // Save some settings
        $container->saveSetting('DateFormat', $dateFormat);
        $container->saveSetting('LinkStyle', $linkStyle);

        return $container;
    }
} 