<?php

namespace App\Theme;

use App\Core\Tools\AssetsFactory;
use Timber\Timber;

class ThemeSetup
{
    private static ?self $instance = null;

    public static function start(): self
    {
        self::$instance = new self;

        return self::$instance;
    }

    public static function getAssetsThemePath(): string
    {
        $dev = defined('WP_ENV') && WP_ENV === 'development';
        if ($dev) {
            return trailingslashit(get_stylesheet_directory());
        } else {
            return 'storeroom-theme/';
        }

    }

    public function __construct()
    {

        Timber::init();

        \add_action('after_setup_theme', [$this, '_addThemeFeatures']);
        \add_action('wp_enqueue_scripts', [$this, '_enqueueAssets']);

        \add_filter('timber/context', [TimberDefault::class, '_addNewContext']);

    }

    public function _addThemeFeatures(): void
    {
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('html5', ['comment-form', 'comment-list', 'gallery', 'caption']);

        register_nav_menus([
            'primary' => __('Menu principal', 'storeroom'),
        ]);

    }

    public function _enqueueAssets(): void
    {
        $themePath = self::getAssetsThemePath();

        AssetsFactory::enqueue($themePath.'src/main.js');
    }
}
