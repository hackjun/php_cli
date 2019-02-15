<?php
/**
 * Created by PhpStorm.
 * User: jett
 * Date: 2018/12/27 0027
 * Time: 下午 03:00
 */

/**
 * 自加载方法，自动加载class目录下的类
 * @param $class
 */
function autoload($class){

    if (file_exists(APP_PATH."class/{$class}.php")) {
        require_once(APP_PATH."class/{$class}.php");
        return ;
    }else{
        die("{$class}类不存在！");
    }
}