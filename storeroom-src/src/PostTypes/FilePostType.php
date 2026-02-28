<?php

namespace App\Core\PostTypes;

use App\Core\Logic\ACFFieldGroupInterface;

class FilePostType extends PostTypeSkeleton implements ACFFieldGroupInterface
{
    public static function getConfigArray(): array
    {
        return [
            'label' => __('Files', 'storeroom'),
            'labels' => [
                'name' => __('Files', 'storeroom'),
                'singular_name' => __('File', 'storeroom'),
                'add_new' => __('Add new file', 'storeroom'),
                'add_new_item' => __('Add new file', 'storeroom'),
                'edit_item' => __('Edit file', 'storeroom'),
                'new_item' => __('New file', 'storeroom'),
                'view_item' => __('Download file', 'storeroom'),
            ],
            'description' => '',
            'public' => true,
            'hierarchical' => false,
            'show_in_nav_menus' => false,
            'has_archive' => false,
            'supports' => [
                'title',
            ],
            'rewrite' => ['slug' => 'download', 'with_front' => false],
        ];
    }

    public static function register(): void
    {
        \register_post_type('files', self::getConfigArray());

    }

    public static function registerACFFields(): void
    {
        acf_add_local_field_group([
            'key' => 'group_files',
            'title' => __('File information', 'storeroom'),
            'fields' => [
                [
                    'key' => 'k_file',
                    'label' => __('File', 'storeroom'),
                    'name' => 'file',
                    'type' => 'file',
                    'library' => 'uploadedTo',
                    'return_format' => 'array',
                ],
                [
                    'key' => 'k_file_downloads',
                    'label' => __('File Downloads', 'storeroom'),
                    'name' => 'file_downloads',
                    'type' => 'number',
                    'default' => '0',
                ],
            ],
            'location' => [
                [['param' => 'post_type', 'operator' => '==', 'value' => 'files']],
            ],
        ]);

    }


}
