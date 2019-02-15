<?php
/**
 * Created by PhpStorm.
 * User: jett
 * Date: 2018/12/26 0026
 * Time: 下午 05:53
 */

define('APP_PATH', __DIR__.'/');
require_once APP_PATH.'vendor/autoload.php';
include_once APP_PATH."common/common.php";
$config = include(APP_PATH."config/config.php");

spl_autoload_register("autoload");