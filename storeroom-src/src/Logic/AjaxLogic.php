<?php

namespace App\Core\Logic;

class AjaxLogic
{
    public static function enableCors(): void
    {
        $dev_env = defined('WP_ENV') && WP_ENV === 'development';
        if ($dev_env) {
            header('Access-Control-Allow-Origin: *');
        } else {
            header('Access-Control-Allow-Origin: '.$_ENV['WP_HOME']);
        }
    }
}
