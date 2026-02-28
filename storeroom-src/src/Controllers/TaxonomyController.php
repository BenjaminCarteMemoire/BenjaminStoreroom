<?php

namespace App\Core\Controllers;

use App\Core\Logic\ACFFieldGroupInterface;
use App\Core\Taxonomies\FileSectionTaxonomy;

class TaxonomyController
{
    protected static function getTaxonomies(): array
    {

        return [
            FileSectionTaxonomy::class,
        ];
    }

    public function __construct()
    {
        \add_action('init', [self::class, '_registerCustomTaxonomies']);
    }

    public static function _registerCustomTaxonomies(): void
    {
        foreach (static::getTaxonomies() as $tax) {
            $tax::register();
        }
    }
}
