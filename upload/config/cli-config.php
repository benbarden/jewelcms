<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

define('IS_CRON', 1);
chdir(dirname(__FILE__));
require_once '../sys/header.php';

$entityManager = $cmsContainer->getServiceLocator()->getCmsEntityManager();

return ConsoleRunner::createHelperSet($entityManager);