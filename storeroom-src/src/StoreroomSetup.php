<?php

namespace App\Core;

use App\Core\Controllers\ACFFieldsController;
use App\Core\Controllers\ContactController;
use App\Core\Controllers\FileController;
use App\Core\Controllers\LaTeXController;
use App\Core\Controllers\PostTypeController;
use App\Core\Controllers\TaxonomyController;
use App\Core\Entity\File;
use App\Core\Timber\User;
use App\Core\Tools\AssetsFactory;

class StoreroomSetup
{
    private static ?self $instance = null;

    public static function start(): self
    {
        self::$instance = new self;

        return self::$instance;
    }

    public static function getAssetsCorePath(): string
    {
        $dev = defined('WP_ENV') && WP_ENV === 'development';
        if ($dev) {
            return trailingslashit(plugin_dir_path(__FILE__).'../assets/');
        } else {
            return 'storeroom-src/assets/';
        }
    }

    public function __construct()
    {
        new PostTypeController;
        new TaxonomyController;
        new ACFFieldsController;
        new FileController;
        new ContactController;
        new LaTeXController;

        AssetsFactory::addModuleCompatibility();

        $this->changeTimberBehavior();
    }

    protected function changeTimberBehavior()
    {

        // Change user class.
        \add_filter('timber/user/class', function ($class) {
            return User::class;
        }, 10, 1);

        // Add files to Classmap.
        \add_filter('timber/post/classmap', function ($classmap) {
            return array_merge($classmap, [
                'files' => File::class,
            ]);
        });

        // Add file fetch function.
        \add_filter('timber/twig/functions', function ($functions) {

            $functions['fetch_files'] = [
                'callable' => [File::class, 'getFilesWithFileSection'],
            ];

            return $functions;
        });
    }
}
