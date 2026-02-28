<?php

namespace App\Core\Controllers;

use App\Core\PostTypes\FilePostType;

class PostTypeController
{
    protected static function getPostTypes(): array
    {

        return [
            FilePostType::class,
        ];
    }

    public function __construct()
    {
        \add_action('init', [self::class, '_registerCustomPostTypes']);
    }

    public static function _registerCustomPostTypes(): void
    {
        foreach (static::getPostTypes() as $post_type) {
            $post_type::register();
        }
    }


}
