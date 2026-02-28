<?php

namespace App\Core\Taxonomies;

class FileSectionTaxonomy extends TaxonomySkeleton
{
    public static function getConfigArray(): array
    {
        return [
            'labels' => [
                'name' => __('Files Sections', 'storeroom'),
                'singular_name' => __('File Section', 'storeroom'),
                'search_items' => __('Search Files Sections', 'storeroom'),
            ],
            'public' => true,
            'show_tagcloud' => false,
        ];
    }

    public static function register(): void
    {
        \register_taxonomy( 'files-sections', [ 'files' ], self::getConfigArray() );
    }
}
