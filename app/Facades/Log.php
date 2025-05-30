<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\Log as ServicesLog;

/**
 * Log facade.
 * 
 * @method static void emergency(string|\Stringable $message, array<mixed> $context = [])
 * @method static void alert(string|\Stringable $message, array<mixed> $context = [])
 * @method static void critical(string|\Stringable $message, array<mixed> $context = [])
 * @method static void error(string|\Stringable $message, array<mixed> $context = [])
 * @method static void warning(string|\Stringable $message, array<mixed> $context = [])
 * @method static void notice(string|\Stringable $message, array<mixed> $context = [])
 * @method static void info(string|\Stringable $message, array<mixed> $context = [])
 * @method static void debug(string|\Stringable $message, array<mixed> $context = [])
 * @method static void log(mixed $level, string|\Stringable $message, array<mixed> $context = [])
 */
class Log extends Facade
{
    public function __construct() {}

    /**
     * Class name the facade is meant for.
     *
     * @return class-string
     */
    public static function getClassName(): string
    {
        return ServicesLog::class;
    }
}
