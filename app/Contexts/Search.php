<?php

namespace App\Contexts;

class Search extends Context
{
    /**
     * Twig templates.
     *
     * @var array<string>
     */
    protected array $templatePaths = [
        'search.twig',
        'archive.twig',
        'index.twig'
    ];

    public function __construct()
    {
        $this->setContext([
            'title' => 'Search results for ' . get_search_query(),
        ]);
    }
}
