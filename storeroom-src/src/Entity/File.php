<?php

namespace App\Core\Entity;

use Timber\Timber;

class File extends \Timber\Post
{
    public static function getFilesWithFileSection(string $file_section)
    {

        $query = new \WP_Query([
            'post_type' => 'files',
            'tax_query' => [
                [
                    'taxonomy' => 'files-sections',
                    'field' => 'slug',
                    'terms' => $file_section,
                ],
            ],
            'status' => ['public', 'private'],
            'fields' => 'ids',
        ]);
        $posts = array_map(fn ($id) => Timber::get_post($id), $query->posts);
        return $posts;
    }

}
