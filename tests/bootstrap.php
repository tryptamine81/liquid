<?php

//  Require Composer Autoloader
require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

define('PATH_TEST', __DIR__);
define('PATH_ROOT', PATH_TEST . DIRECTORY_SEPARATOR . 'stage');

//  Create system staging area
if (!is_dir(PATH_ROOT)) {
    mkdir(PATH_ROOT, 0777);
}

//  Initialise environment
Environment::getInstance(PATH_ROOT, PATH_ROOT, PATH_ROOT);
