<?php

namespace App\Contexts;

class Page extends Context
{
    /**
     * Twig templates.
     *
     * @var array<string>
     */
    protected array $templatePaths = [
        'page.twig'
    ];

    public function __construct() {}
}
