<?php

namespace App\Core\Taxonomies;

abstract class TaxonomySkeleton
{
    /**
     * Need to use register_taxonomy from WordPress.
     * @return void
     */
    abstract public static function register(): void;

    /**
     * Must return an array like register_taxonomy required.
     * @return array
     */
    abstract public static function getConfigArray(): array;
}
