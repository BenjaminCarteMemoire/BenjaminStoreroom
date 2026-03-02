<?php

namespace App\Core\Controllers;

class LaTeXController
{
    public static function _addLaTeXAdminPage(): void
    {
        \add_submenu_page(
            'post.php',
            'New with LaTeX',
            'New with LaTeX',
            'edit_posts',
            'post-latex',
            [self::class, '_laTeXAdminPage'],
            10
        );

    }

    public static function _laTeXAdminPage(): void
    {
        ?>
        <div class="wrap">
            <h1><?php _e('Create post with LaTeX HTML file', 'storeroom'); ?></h1>
            <p><?php echo wp_sprintf(__('Send an archive with %s', 'storeoom'), '<code>index.html, images/</code> folder'); ?></p>

            <form method="post" enctype="multipart/form-data" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="submit_latex_post">
                <?php wp_nonce_field('storeroom-latex-post'); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row"><label><?php _e('Title', 'storeroom'); ?></label></th>
                        <td><input name="post_title" type="text" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label><?php _e('ZIP Archive', 'storeroom'); ?></label></th>
                        <td><input name="latex_zip" type="file" accept=".zip" required></td>
                    </tr>
                </table>

                <?php submit_button(__('Submit', 'storeroom')); ?>
            </form>
        </div>
        <?php
    }

    protected static function processLaTeXPost(): int
    {
        require_once ABSPATH.'wp-admin/includes/file.php';
        require_once ABSPATH.'wp-admin/includes/image.php';

        $zip_file = $_FILES['latex_zip']['tmp_name'];
        $post_title = sanitize_text_field($_POST['post_title']);
        $upload_dir = wp_upload_dir();
        $temp_extract_path = $upload_dir['basedir'].'/temp-latex-'.uniqid();

        WP_Filesystem();
        $unzipped = unzip_file($zip_file, $temp_extract_path);

        if (is_wp_error($unzipped)) {
            wp_die('Error during file extractioh: '.$unzipped->get_error_message());
        }

        $html_content_path = $temp_extract_path.'/index.html';
        if (! file_exists($html_content_path)) {
            wp_die('index.html is required.');
        }

        $content = file_get_contents($html_content_path);
        $images_path = $temp_extract_path.'/images';
        self::processLaTeXImages($images_path);

        $post_id = wp_insert_post([
            'post_title' => $post_title,
            'post_content' => $content,
            'post_status' => 'draft',
            'post_type' => 'post', // ou ton CPT 'files'
        ]);

        array_map('unlink', glob("$temp_extract_path/images/*.*"));
        if (is_dir("$temp_extract_path/images")) {
            rmdir("$temp_extract_path/images");
        }
        unlink($html_content_path);
        rmdir($temp_extract_path);

        return $post_id;
    }

    protected static function processLaTeXImages(string $images_path): void
    {
        if (! is_dir($images_path)) {
            return;
        }

        $files = scandir($images_path);
        foreach ($files as $file) {
            if ($file[0] === '.') {
                continue;
            }

            $file_full_path = $images_path.'/'.$file;

            $file_array = [
                'name' => $file,
                'tmp_name' => $file_full_path,
            ];

            $id = media_handle_sideload($file_array, 0);

            if (! is_wp_error($id)) {
                $new_url = wp_get_attachment_url($id);
                $content = str_replace('./images/'.$file, $new_url, $content);
            }
        }
    }

    public static function _submitLaTeXPost(): void
    {
        check_admin_referer('storeroom-latex-post');
        if (current_user_can('edit_posts')) {
            wp_die("You don't have permission to edit posts.");
        }

        if (empty($_FILES['latex_zip']['tmp_name'])) {
            wp_die('ZIP Archive is required');
        }

        $post_id = self::processLaTeXPost();

        wp_redirect(admin_url('post.php?post='.$post_id.'&action=edit'));
        exit;
    }

    public function __construct()
    {

        \add_action('admin_menu', [self::class, '_addLaTeXAdminPage']);
        \add_action('wp_ajax_submit_latex_post', [self::class, '_submitLaTeXPost']);

    }
}
