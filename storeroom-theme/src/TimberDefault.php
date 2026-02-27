<?php

namespace App\Theme;

use Timber\Timber;

class TimberDefault
{
    public static function _addNewContext(array $context): array
    {

        $context['menu'] = Timber::get_menu('primary');
        return $context;
    }
}