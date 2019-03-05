<?php

use hunomina\Http\Response\HtmlResponse;
use hunomina\Http\Response\JsonResponse;
use hunomina\Routing\Route;
use hunomina\Routing\Router;
use PHPUnit\Framework\TestCase;

final class RoutingTest extends TestCase
{
    private const JSON_ROUTE_FILE = __DIR__ . '/routes.json';
    private const YAML_ROUTE_FILE = __DIR__ . '/routes.yml';

    /**
     * @throws ReflectionException
     * @throws \hunomina\Routing\RoutingException
     */
    public function testInstanciateJsonRouter(): void
    {
        $router = new Router(self::JSON_ROUTE_FILE);

        $this->assertInstanceOf(Router::class, $router);
        $this->assertContainsOnly(Route::class, $router->getRouteManager()->getRoutes());
    }

    /**
     * @throws ReflectionException
     * @throws \hunomina\Routing\RoutingException
     */
    public function testInstanciateYamlRouter(): void
    {
        $router = new Router(self::YAML_ROUTE_FILE, 'yaml');

        $this->assertInstanceOf(Router::class, $router);
        $this->assertContainsOnly(Route::class, $router->getRouteManager()->getRoutes());
    }

    /**
     * @throws ReflectionException
     * @throws \hunomina\Routing\RoutingException
     */
    public function testMatchJsonRoute(): void
    {
        $router = new Router(self::JSON_ROUTE_FILE);

        $method = 'get';
        $url = '/test';
        $urlAndParam = '/test/1';
        $urlAndParamAndString = '/test/1/lol';

        $this->assertInstanceOf(HtmlResponse::class, $router->request($method, $url));
        $this->assertInstanceOf(JsonResponse::class, $router->request($method, $urlAndParam));
        $this->assertInstanceOf(JsonResponse::class, $router->request($method, $urlAndParamAndString));
    }

    /**
     * @throws ReflectionException
     * @throws \hunomina\Routing\RoutingException
     */
    public function testMatchYamlRoute(): void
    {
        $router = new Router(self::YAML_ROUTE_FILE, 'yaml');

        $method = 'get';
        $url = '/test';
        $urlAndParam = '/test/1';
        $urlAndParamAndString = '/test/1/lol';

        $this->assertInstanceOf(HtmlResponse::class, $router->request($method, $url));
        $this->assertInstanceOf(JsonResponse::class, $router->request($method, $urlAndParam));
        $this->assertInstanceOf(JsonResponse::class, $router->request($method, $urlAndParamAndString));
    }
}