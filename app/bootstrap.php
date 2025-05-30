<?php

declare(strict_types=1);

use App\App;
use DI\ContainerBuilder;

require_once __DIR__ . '/helpers.php';

$builder = new ContainerBuilder();

// @phpstan-ignore identical.alwaysFalse
if (WP_ENVIRONMENT_TYPE === 'production') {
    $builder->enableCompilation(APPROOT . '/app/storage/config/cache');
}

$container = $builder->build();

/** @var App */
$app = App::getInstance();
$app->init($container);
