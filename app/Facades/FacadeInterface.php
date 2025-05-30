<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\Service;

interface FacadeInterface
{
    /**
     * Call service method statically.
     *
     * @param string $name
     * @param array<mixed> $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments): mixed;

    /**
     * Get class name facade is used for.
     *
     * @return string
     */
    public static function getClassName(): string;
}
