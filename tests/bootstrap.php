<?php

defined('DS') ?: define('DS', DIRECTORY_SEPARATOR);

//  Require Composer Autoloader
require_once(__DIR__ . DS . '..' . DS . 'vendor' . DS . 'autoload.php');

define('PATH_TEST', __DIR__);
define('PATH_ROOT', PATH_TEST . DS . 'stage');
define('PATH_SYSTEM', PATH_ROOT);

//  Create system staging area
if(!is_dir(PATH_ROOT))
{
  mkdir(PATH_ROOT, 0777);
}

//  Initialise environment
use Trypta\Liquid\Environment as Environment;

Environment::getInstance(PATH_ROOT, PATH_ROOT, PATH_ROOT);
