<?php

// This file exists to suppress PHPStan errors with constants. It doesn't do anything.
// https://github.com/phpstan/phpstan/issues/6662

define('APPROOT', dirname(__DIR__));
define('WP_ENVIRONMENT_TYPE', 'local');
define('SENTRY_DSN', '');
