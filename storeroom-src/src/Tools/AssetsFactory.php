<?php

namespace App\Core\Tools;

class AssetsFactory
{
    protected static function getViteDevUrl(): string
    {
        return 'http://localhost:3000/';
    }

    public static function getDistUrl(): string
    {
        return home_url('/dist/');
    }

    public static function getDistPath(): string
    {
        return ABSPATH.'../dist/';
    }

    public static function enqueue(string $file_name)
    {

        $dev_env = defined('WP_ENV') && WP_ENV === 'development';

        if ($dev_env) {
            wp_enqueue_script('vite-client', self::getViteDevUrl().'@vite/client', [], null, true);
            wp_enqueue_script(md5($file_name), self::getViteDevUrl().$file_name, [], null, true);
        } else {

            $manifest = self::getDistPath().'.vite/manifest.json';
            if (! file_exists($manifest)) {
                return;
            }

            $manifest = json_decode(file_get_contents($manifest), true);
            if (! isset($manifest[$file_name])) {
                return;
            }

            $js = self::getDistUrl().$manifest[$file_name]['file'];
            wp_enqueue_script(md5($file_name), $js, [], null, true);

            if (isset($manifest[$file_name]['css'])) {
                foreach ($manifest[$file_name]['css'] as $css) {
                    wp_enqueue_style(md5($css), self::getDistUrl().$css, [], null);
                }
            }
        }
    }

    public static function addModuleCompatibility(): void
    {

        \add_filter('script_loader_tag', function ($tag, $handle, $src) {
            if (str_contains($handle, 'vite') || strlen($handle) === 32) {
                $tag = str_replace('<script ', '<script type="module" ', $tag);
            }

            return $tag;
        }, 10, 3);
    }
}