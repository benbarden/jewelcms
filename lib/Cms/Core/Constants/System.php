<?php
$wwwRoot    = dirname(__FILE__).'/../../../../';
$publicRoot = $wwwRoot.'public/';
$publicSys  = $wwwRoot.'public/sys/';

// Core constants
define('ABS_ROOT', $publicRoot);
define('ABS_SYS_ROOT', $publicSys);
define('WWW_ROOT', $wwwRoot);

// Hostname
if (isset($_SERVER['HTTP_HOST'])) {
    define('SVR_HOST', $_SERVER['HTTP_HOST']);
} else {
    define('SVR_HOST', '/');
}

// Root path MUST include a trailing forward slash!
define('URL_ROOT', "/");

// This stops template constants from being parsed
define('ZZZ_TEMP', 'XXX---IJ-PLACEHOLDER-TEXT---XXX');
