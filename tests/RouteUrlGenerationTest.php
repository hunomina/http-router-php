<?php

use hunomina\Routing\Router;
use hunomina\Routing\RoutingException;
use PHPUnit\Framework\TestCase;

class RouteUrlGenerationTest extends TestCase
{
    private const YAML_ROUTE_FILE = __DIR__ . '/routes.yml';

    /** @var Router $_router */
    private $_router;

    /**
     * RouteUrlGenerationTest constructor.
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     * @throws ReflectionException
     * @throws RoutingException
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $this->_router = new Router(self::YAML_ROUTE_FILE);
        parent::__construct($name, $data, $dataName);
    }

    /**
     * @throws RoutingException
     */
    public function testGenerateBasicRouteUrl(): void
    {
        $this->assertSame($this->_router->generate('test'), '/test');
    }

    /**
     * @throws RoutingException
     */
    public function testGenerateRouteUrlWithParameter(): void
    {
        $this->assertSame($this->_router->generate('test-int', [1]), '/test/1');
    }

    /**
     * @throws RoutingException
     */
    public function testGenerateRouteUrlWithParameters(): void
    {
        $this->assertSame($this->_router->generate('test-double-param', [1, 'aaa']), '/test/1/aaa');
    }

    /**
     * @throws RoutingException
     */
    public function testGenerateUndefinedRouteUrl(): void
    {
        $this->expectException(RoutingException::class);
        $this->_router->generate('this-route-does-not-exist'); // does not exist
    }

    /**
     * @throws RoutingException
     */
    public function testGenerateRouteUrlWithoutParameter(): void
    {
        $this->expectException(RoutingException::class);
        $this->_router->generate('test-int'); // no parameter
    }

    /**
     * @throws RoutingException
     */
    public function testGenerateRouteUrlWithoutEnoughParameters(): void
    {
        $this->expectException(RoutingException::class);
        $this->_router->generate('test-double-param', [1]); // not enough parameters
    }

    /**
     * @throws RoutingException
     */
    public function testGenerateRouteUrlWithInvalidParameters(): void
    {
        $this->expectException(RoutingException::class);
        $this->_router->generate('test-int', ['a']); // invalid parameter
    }

    /**
     * @throws RoutingException
     */
    public function testGenerateRouteUrlWithMultipleInvalidParameters(): void
    {
        $this->expectException(RoutingException::class);
        $this->_router->generate('test-double-param', ['a', 1]); // invalid parameters
    }
}