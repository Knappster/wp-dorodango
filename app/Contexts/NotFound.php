<?php

namespace App\Contexts;

class NotFound extends Context
{
    /**
     * Twig templates.
     *
     * @var array<string>
     */
    protected array $templatePaths = [
        '404.php'
    ];

    public function __construct() {}
}
