<?php

namespace App\Contexts;

class Index extends Context
{
    /**
     * Twig templates.
     *
     * @var array<string>
     */
    protected array $templatePaths = [
        'index.twig'
    ];

    public function __construct()
    {
        if (is_home()) {
            $this->templatePaths = [
                'front-page.twig',
                'home.twig',
                ...$this->templatePaths
            ];
        }

        $this->setContext([
            'foo' => 'bar',
        ]);
    }
}
