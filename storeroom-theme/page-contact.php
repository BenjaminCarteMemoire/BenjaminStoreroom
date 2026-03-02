<?php

use App\Core\Tools\AssetsFactory;
use Timber\Timber;

AssetsFactory::hookAndEnqueue(\App\Core\StoreroomSetup::getAssetsCorePath() . 'contact.js');

$context = Timber::context();
$context['sent'] = isset($_GET['sent']) ? sanitize_text_field($_GET['sent']) : 'none';
$context['_wpnonce'] = wp_create_nonce('storeroom-contact-form');

Timber::render('contact.twig', $context);
