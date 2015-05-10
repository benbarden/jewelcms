<?php
define('IS_CRON', 1);
chdir(dirname(__FILE__));
require_once '../../lib/Cms/Legacy/Header.php';

$entityManager = $cmsContainer->getServiceLocator()->getCmsEntityManager();
$themeEngine = $cmsContainer->getService('Theme.Engine');

$cmsFeed = new \Cms\Content\Feed($entityManager, $themeEngine);
$cmsFeed->generate();

exit;
