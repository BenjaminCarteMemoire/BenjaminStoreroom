<?php

namespace App\Core\Controllers;

use App\Core\Logic\AjaxLogic;

class ContactController
{
    public function __construct()
    {
        \add_action('wp_ajax_submit_contact_form', [self::class, '_submitContactForm']);
        \add_action('wp_ajax_nopriv_submit_contact_form', [self::class, '_submitContactForm']);
    }

    public static function _submitContactForm(): void
    {
        check_ajax_referer('storeroom-contact-form');
        AjaxLogic::enableCors();

        $fields = [
            'name' => isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '',
            'email' => isset($_POST['email']) ? sanitize_email($_POST['email']) : '',
            'message' => isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '',
        ];

        $errors = [];

        foreach ($fields as $field => $value) {
            if ($value === '') {
                $errors[] = $field.' cannot be empty.';
            }
        }

        if (! filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address.';
        }

        if (! empty($errors)) {
            wp_send_json_error($errors);
        }

        $to = get_bloginfo('admin_email');
        $subject = __('New Storeroom Contact Form by '.$fields['name'], 'storeroom');
        $body = $fields['message'];
        $headers = ["Reply-To: {$fields['name']} <{$fields['email']}>"];

        if (! wp_mail($to, $subject, $body, $headers)) {
            $errors[] = 'Failed to send email.';
            wp_send_json_error( $errors );
        }

        wp_send_json_success([]);

    }
}
