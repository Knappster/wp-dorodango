<?php

declare(strict_types=1);

namespace App;

use App\App;
use App\Contexts\Context;

/**
 * Get the current app.
 *
 * @return App
 */
function app(): App
{
    /** @var App */
    return App::getInstance();
}

/**
 * Render template contents.
 *
 * @param class-string<Context> $template_class_name
 * @return void
 */
function render(string $template_class_name): void
{
    app()->container()->get($template_class_name)->render();
}
