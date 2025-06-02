<?php

namespace App\Contexts;

use Timber\Timber;

abstract class Context
{
    /**
     * Twig templates.
     *
     * @var array<string>
     */
    protected array $templatePaths;

    /**
     * Get context.
     *
     * @return array<mixed>
     */
    public function getContext(): array
    {
        return Timber::context();
    }

    /**
     * Set context.
     *
     * @param array<mixed> $context
     * @return void
     */
    public function setContext(array $context): void
    {
        Timber::context($context);
    }

    /**
     * Render template with context.
     *
     * @return void
     */
    public function render(): void
    {
        $context = Timber::context();
        // Always add the context name.
        $context['context'] = $this::class;
        Timber::render($this->templatePaths, $context);
    }
}
