<?php

declare(strict_types=1);

namespace App\Configs;

interface ConfigInterface
{
    public static function init(): void;
}
