<?php

use Timber\Timber;

$context = Timber::context();

Timber::render('page.twig', $context);
