<?php

declare(strict_types=1);

namespace NiceDumpTwig\Tests;

use NiceDump\NiceDump;
use NiceDumpTwig\NiceDumpTwigExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * Test NiceDumpTwig class.
 */
class NiceDumpTwigExtensionTest extends TestCase
{
    /**
     * Test nice_dump function.
     */
    public function testNiceDump()
    {
        $var = 'Foo Bar';
        $result = $this->twigEnvironment->render('test.twig', ['var' => $var]);

        self::assertSame('<!--' . PHP_EOL . NiceDump::create($var) . PHP_EOL . '-->', $result);
    }

    /**
     * Test nice_dump output is empty in non-debug mode.
     */
    public function testNiceDumpOutputIsEmptyInNonDebugMode()
    {
        $this->twigEnvironment->disableDebug();

        $var = 'Foo Bar';
        $result = $this->twigEnvironment->render('test.twig', ['var' => $var]);

        self::assertSame('', $result);
    }

    /**
     * Test nice_dump with name parameter.
     */
    public function testNiceDumpWithName()
    {
        $var = 'Foo Bar';
        $result = $this->twigEnvironment->render('test_with_name.twig', ['var' => $var]);

        self::assertSame('<!--' . PHP_EOL . NiceDump::create($var, 'My name') . PHP_EOL . '-->', $result);
    }

    /**
     * Test nice_dump with comment parameter.
     */
    public function testNiceDumpWithComment()
    {
        $var = 'Foo Bar';
        $result = $this->twigEnvironment->render('test_with_comment.twig', ['var' => $var]);

        self::assertSame('<!--' . PHP_EOL . NiceDump::create($var, 'My name', 'My comment') . PHP_EOL . '-->', $result);
    }

    /**
     * Test nice_dump when enabled in release mode.
     *
     * @dataProvider enableInReleaseModeDataProvider
     *
     * @param bool   $enableTwigDebug     True if Twig environment debug mode should be enabled, false otherwise.
     * @param bool   $enableInReleaseMode True if extension output should be enabled, false otherwise.
     * @param string $expectedOutput      The expected extension output.
     */
    public function testEnableInReleaseMode(bool $enableTwigDebug, bool $enableInReleaseMode, string $expectedOutput)
    {
        if ($enableTwigDebug) {
            $this->twigEnvironment->enableDebug();
        } else {
            $this->twigEnvironment->disableDebug();
        }

        if ($enableInReleaseMode) {
            $this->niceDumpTwigExtension->enableInReleaseMode();
        }

        $result = $this->twigEnvironment->render('test.twig', ['var' => null]);

        self::assertSame($expectedOutput, $result);
    }

    /**
     * Data provider for testEnableInReleaseMode.
     *
     * @return array
     */
    public function enableInReleaseModeDataProvider()
    {
        return [
            [false, false, ''],
            [false, true, '<!--' . PHP_EOL . NiceDump::create(null) . PHP_EOL . '-->'],
            [true, false, '<!--' . PHP_EOL . NiceDump::create(null) . PHP_EOL . '-->'],
            [true, true, '<!--' . PHP_EOL . NiceDump::create(null) . PHP_EOL . '-->'],
        ];
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        $arrayLoader = new ArrayLoader([
            'test.twig'              => '{{ nice_dump(var) }}',
            'test_with_name.twig'    => '{{ nice_dump(var, "My name") }}',
            'test_with_comment.twig' => '{{ nice_dump(var, "My name", "My comment") }}',
        ]);

        $this->niceDumpTwigExtension = new NiceDumpTwigExtension();

        $this->twigEnvironment = new Environment($arrayLoader, ['debug' => true]);
        $this->twigEnvironment->enableStrictVariables();
        $this->twigEnvironment->addExtension($this->niceDumpTwigExtension);
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        $this->twigEnvironment = null;
        $this->niceDumpTwigExtension = null;
    }

    /**
     * @var Environment My Twig environment.
     */
    private $twigEnvironment;

    /**
     * @var NiceDumpTwigExtension My NiceDump Twig extension.
     */
    private $niceDumpTwigExtension;
}
