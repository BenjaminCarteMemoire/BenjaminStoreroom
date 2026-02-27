<?php

namespace App\Core;

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

    public function __construct()
    {

        AssetsFactory::addModuleCompatibility();

        $this->changeTimberBehavior();
    }

    protected function changeTimberBehavior()
    {

        \add_filter('timber/user/class', function ($class) {
            return User::class;
        }, 10, 1);
    }
}
