<?php

namespace App\Core\Controllers;

use Timber\Timber;
use WP_Post;

class FileController
{
    public static function handleFileDownload(): void
    {
        if (! function_exists('get_field')) {
            wp_die('Unable to download files');
        }

        $post_id = get_the_ID();
        $file = get_field('k_file', $post_id);
        if (! $file) {
            wp_die('No files are attached to this post');
        }

        if (get_post_status($post_id) !== 'publish') {
            wp_die("You can't download this file.");
        }

        $file_path = get_attached_file($file['ID']);
        if ($file_path && file_exists($file_path)) {

            if (! post_password_required($post_id)) {

                if (ob_get_level()) {
                    ob_end_clean();
                }

                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($file_path).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: '.filesize($file_path));

                readfile($file_path);
                exit;

            } else {

                $pass_form = get_the_password_form($post_id);
                $context = Timber::context();
                $context['form'] = $pass_form;
                Timber::render('files/password.twig', $context);
            }

        } else {

            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            get_template_part(404);
            exit;
        }

    }

    public static function _rewriteFileURLForPosts($post_url, WP_Post $post): string
    {
        if ($post->post_type === 'files') {
            return home_url('download/'.$post->ID);
        }

        return $post_url;
    }

    public static function _addRewriteRule(): void
    {
        add_rewrite_rule('^download/([0-9]+)/?$',
            'index.php?post_type=files&p=$matches[1]',
            'top'
        );
    }

    public static function _changeFolderDestinationIfFile($file)
    {
        if (
            isset($_REQUEST['post_type']) && $_REQUEST['post_type'] === 'files' ||
            isset($_REQUEST['post_id']) && get_post_type($_REQUEST['post_id']) === 'files') {
            \add_filter('upload_dir', [self::class, '_customDirectory']);
        }

        return $file;
    }

    public static function _customDirectory($dirs)
    {

        $dirs['path'] = $dirs['basedir'].'/storeroom-files/'.($_REQUEST['post_id'] ?? 0);
        $dirs['url'] = $dirs['baseurl'].'/storeroom-files/'.($_REQUEST['post_id'] ?? 0);
        $dirs['subdir'] = 'storeroom-files/'.($_REQUEST['post_id'] ?? 0);

        return $dirs;
    }

    public static function _avoidDirectLinksFiles(): void
    {
        if (is_attachment()) {
            $post = get_post();
            if ($post && get_post_type($post->post_parent) === 'files') {
                wp_redirect(home_url());
                exit;
            }
        }

    }

    public static function _fileListViewShortcode(array $args)
    {

        if (! isset($args['section'])) {
            return '';
        }

        return Timber::compile(['partials/files-list-view.twig'], [
            'file_section' => $args['section'],
        ]);
    }

    public function __construct()
    {

        \add_filter('post_type_link', [self::class, '_rewriteFileURLForPosts'], 10, 2);
        \add_action('init', [self::class, '_addRewriteRule']);
        \add_filter('wp_handle_upload_prefilter', [self::class, '_changeFolderDestinationIfFile']);
        \add_action('template_redirect', [self::class, '_avoidDirectLinksFiles']);
        \add_shortcode('files_list_view', [self::class, '_fileListViewShortcode']);
    }
}
