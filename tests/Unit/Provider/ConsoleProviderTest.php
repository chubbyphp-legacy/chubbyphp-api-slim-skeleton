<?php

namespace Chubbyphp\Tests\ApiSkeleton\Unit\Provider;

use PHPUnit\Framework\TestCase;
use Chubbyphp\ApiSkeleton\Provider\ConsoleProvider;
use Pimple\Container;

/**
 * @covers \Chubbyphp\ApiSkeleton\Provider\ConsoleProvider
 */
class ConsoleProviderTest extends TestCase
{
    public function testRegister()
    {
        $container = new Container();

        $provider = new ConsoleProvider();
        $provider->register($container);

        self::assertArrayHasKey('console.name', $container);
        self::assertArrayHasKey('console.version', $container);
        self::assertArrayHasKey('console.helpers', $container);
        self::assertArrayHasKey('console.commands', $container);

        self::assertSame('chubbyphp-api-slim-skeleton', $container['console.name']);
        self::assertSame('1.0', $container['console.version']);
        self::assertSame([], $container['console.helpers']);
        self::assertSame([], $container['console.commands']);
    }
}
