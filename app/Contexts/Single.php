<?php

namespace App\Contexts;

class Single extends Context
{
    /**
     * Twig templates.
     *
     * @var array<string>
     */
    protected array $templatePaths = [
        'single.twig'
    ];

    public function __construct()
    {
        $context = $this->getContext();
        $post = $context['post'];

        $this->templatePaths = [
            'single-' . $post->post_type . '.twig',
            'single.twig'
        ];

        if (post_password_required($post->ID)) {
            $this->templatePaths = ['single-password.twig'];
        }
    }
}
