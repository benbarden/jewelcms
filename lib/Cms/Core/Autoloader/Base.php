<?php

function cmsAutoloader($className)
{
    $filePath = str_replace('\\', '/', $className);
    $fullPath = sprintf('%s../lib/%s.php', ABS_ROOT, $filePath);
    if (file_exists($fullPath)) {
        require_once $fullPath;
    }
}
spl_autoload_register('cmsAutoloader');