<?php

namespace App\Theme;

use Timber\Timber;

class TimberDefault
{
    public static function _addNewContext(array $context): array
    {

        $context['menu'] = Timber::get_menu('primary');
        $context['categories_list'] = Timber::get_terms([
            'taxonomy' => 'category',
            'count' => true,
            'hide_empty' => false,
        ]);
        $context['pages_list'] = Timber::get_posts([
            'post_type' => 'page',
        ]);

        return $context;
    }
}
