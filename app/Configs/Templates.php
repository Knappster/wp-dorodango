<?php

declare(strict_types=1);

namespace App\Configs;

use App\Configs\Extensions\Twig\StylesTokenParser;
use Roots\WPConfig\Config;

class Templates implements ConfigInterface
{
    /**
     * Initialise config.
     *
     * @return void
     */
    public static function init(): void
    {
        add_filter('timber/twig', function (\Twig\Environment $twig) {
            $twig->addTokenParser(new StylesTokenParser());

            return $twig;
        });
    }
}
