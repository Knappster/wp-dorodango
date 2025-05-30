<?php

declare(strict_types=1);

namespace App\Traits;

use Exception;

trait Singleton
{
    /**
     * Singleton instance.
     *
     * @var object
     */
    private static object $instance;

    /**
     * The Singleton's constructor should always be private to prevent direct
     * construction calls with the `new` operator.
     */
    final private function __construct() {}

    /**
     * Singletons should not be cloneable.
     */
    private function __clone() {}

    /**
     * Singletons should not be restorable from strings.
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }

    /**
     * Create or retrieve instance of singleton.
     *
     * @return object
     */
    public static function getInstance(): object
    {
        $class = static::class;
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }
}
