<?php

declare(strict_types=1);

namespace App\Facades;

use BadMethodCallException;
use ReflectionException;

use function App\app;

abstract class Facade implements FacadeInterface
{
    /**
     * Include this to shut PHPStan up about unsafe static().
     */
    abstract public function __construct();

    /**
     * Attempt to call methods.
     *
     * @param string $name
     * @param array<mixed> $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments): mixed
    {
        $instance = app()->container()->get(static::getClassName());

        try {
            $method = new \ReflectionMethod($instance, $name);

            if ($method->isPublic() && !$method->isStatic()) {
                return $method->invokeArgs($instance, $arguments);
            }

            throw new BadMethodCallException("Method {$name} is not accessible statically!");
        } catch (ReflectionException $e) {
            throw new BadMethodCallException("Method {$name} doesn't exist!");
        }
    }
}
