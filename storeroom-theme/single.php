<?php

use Timber\Timber;

$context = Timber::context();
$post = Timber::get_post();
$context['post'] = $post;
$context['comments'] = $post->comments();

Timber::render('single.twig', $context);
