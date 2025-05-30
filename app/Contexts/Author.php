<?php

namespace App\Contexts;

class Author extends Context
{
    /**
     * Twig templates.
     *
     * @var array<string>
     */
    protected array $templatePaths = [
        'author.twig',
        'archive.twig'
    ];

    public function __construct()
    {
        $context = $this->getContext();

        if (isset($context['author'])) {
            $title = sprintf(__('Archive of %s', 'theme'), $context['author']->name());

            $this->setContext([
                'title' => $title,
            ]);
        }
    }
}
