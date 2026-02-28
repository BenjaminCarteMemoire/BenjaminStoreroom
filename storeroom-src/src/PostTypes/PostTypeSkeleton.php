<?php

namespace App\Core\PostTypes;

abstract class PostTypeSkeleton
{
    /**
     * Need to use register_post_type from WordPress.
     * @return void
     */
    abstract public static function register(): void;

    /**
     * Must return an array like register_post_type required.
     * @return array
     */
    abstract public static function getConfigArray(): array;
}
