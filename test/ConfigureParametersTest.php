<?php

declare(strict_types=1);

use BlueCode\Application;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

require_once __DIR__ . '/../src/Application.php';

final class ConfigureParametersTest extends TestCase
{
    private function configure(array $parameters): Application
    {
        $application = (new ReflectionClass(Application::class))->newInstanceWithoutConstructor();
        (new ReflectionMethod(Container::class, '__construct'))->invoke($application);
        $application['parameters'] = $parameters;
        (new ReflectionMethod(Application::class, 'configureParameters'))->invoke($application);

        return $application;
    }

    public function testDefaultsAndProjectsAreConfigured(): void
    {
        $application = $this->configure([
            'projects' => ['relative' => 'example', 'absolute' => '/srv/project'],
        ]);

        self::assertFalse($application['debug']);
        self::assertSame(dirname(__DIR__) . '/src/../themes/default', $application['themePath']);
        self::assertSame([
            'relative' => dirname(__DIR__) . '/src/../example',
            'absolute' => '/srv/project',
        ], $application->projects);
    }

    public function testRelativeThemeAndTruthyDebugAreConfigured(): void
    {
        $application = $this->configure([
            'debug' => 1,
            'themePath' => 'themes/default',
            'projects' => [],
        ]);

        self::assertTrue($application['debug']);
        self::assertSame(dirname(__DIR__) . '/src/../themes/default', $application['themePath']);
        self::assertSame([], $application->projects);
    }

    public function testAbsoluteThemeAndFalseDebugAreConfigured(): void
    {
        $application = $this->configure([
            'debug' => 0,
            'themePath' => sys_get_temp_dir(),
            'projects' => [],
        ]);

        self::assertFalse($application['debug']);
        self::assertSame(sys_get_temp_dir(), $application['themePath']);
    }

    public function testInvalidThemeIsRejected(): void
    {
        $path = '/path-that-does-not-exist/bluecode-theme';
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('themePath invalid: ' . $path);

        $this->configure(['themePath' => $path, 'projects' => []]);
    }
}
