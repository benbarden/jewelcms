<?php
define('IS_CRON', 1);
chdir(dirname(__FILE__));
require_once '../../lib/Cms/Legacy/Header.php';

$entityManager = $cmsContainer->getServiceLocator()->getCmsEntityManager();
$themeEngine = $cmsContainer->getService('Theme.Engine');

$cmsSitemap = new \Cms\Content\Sitemap($entityManager, $themeEngine);
$cmsSitemap->generateAll();

exit;
