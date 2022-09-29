<?php

/**
 * This file is a part of the nicedump-twig package.
 *
 * Read more at https://github.com/themichaelhall/nicedump-twig
 */

declare(strict_types=1);

namespace NiceDumpTwig;

use NiceDump\NiceDump;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * NiceDump Twig extension.
 *
 * @since 1.0.0
 */
class NiceDumpTwigExtension extends AbstractExtension
{
    /**
     * Returns the functions.
     *
     * @since 1.0.0
     *
     * @return TwigFunction[] The functions.
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('nice_dump', [$this, 'niceDumpFunction'], ['is_safe' => ['html'], 'needs_environment' => true]),
        ];
    }

    /**
     * Enables extension output, even if Twig environment is not in debug mode (use with care!).
     *
     * @since 1.0.0
     */
    public function enableInReleaseMode(): void
    {
        $this->enabledInReleaseMode = true;
    }

    /**
     * Nice dump function.
     *
     * @since 1.0.0
     *
     * @param Environment $environment The Twig environment.
     * @param mixed       $var         The variable.
     * @param string      $name        The variable name (optional).
     * @param string      $comment     The variable comment (optional).
     *
     * @return string The result.
     */
    public function niceDumpFunction(Environment $environment, mixed $var, string $name = '', string $comment = ''): string
    {
        if (!$environment->isDebug() && !$this->enabledInReleaseMode) {
            return '';
        }

        $niceDump = NiceDump::create($var, $name, $comment);

        return '<!--' . PHP_EOL . $niceDump->__toString() . PHP_EOL . '-->';
    }

    /**
     * @var bool If true, output is enabled in non-debug mode.
     */
    private bool $enabledInReleaseMode = false;
}
