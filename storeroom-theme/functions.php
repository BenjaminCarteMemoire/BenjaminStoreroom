<?php

use App\Theme\ThemeSetup;

ThemeSetup::start();

function theme_breadcrumb(): void
{
    if (is_front_page()) {
        return;
    }

    echo '<div class="breadcrumb">';
    echo '<a href="'.home_url().'">'.__('Home', 'storeroom').'</a>';

    if (is_page()) {
        echo ' :: <span class="current-page">'.get_the_title().'</span>';
    } elseif (is_single()) {
        $category = get_the_category()[0] ?? null;
        if ($category !== null) {
            echo ' :: <a href="'.get_term_link($category).'">'.$category->name.'</a>';
            echo ' :: <span class="current-page">'.get_the_title().'</span>';
        }
    }

    echo '</div>';
}
