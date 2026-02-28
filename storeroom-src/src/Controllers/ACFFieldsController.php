<?php

namespace App\Core\Controllers;

use App\Core\PostTypes\FilePostType;

class ACFFieldsController
{
    protected static function getACFFieldsExtenders(): array
    {

        return [
            FilePostType::class,
        ];

    }

    public function __construct()
    {
        \add_action('acf/init', [self::class, '_registerACFFields']);
    }

    public static function _registerACFFields(): void
    {

        foreach (self::getACFFieldsExtenders() as $class) {
            $class::registerACFFields();
        }
    }
}
