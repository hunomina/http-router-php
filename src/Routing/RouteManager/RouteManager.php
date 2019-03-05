<?php

namespace hunomina\Routing\RouteManager;

use hunomina\Routing\Route;
use hunomina\Routing\RoutingException;

/**
 * Class RouteManager
 * @package Routing
 * Class fetching the route from a json file
 */
abstract class RouteManager
{
    /** @var array $_routes */
    protected $_routes = [];

    /**
     * RouteManager constructor.
     * @param string $route_file
     * @throws RoutingException
     * @throws \ReflectionException
     */
    abstract public function __construct(string $route_file);

    /**
     * @param array $routes
     * @return array
     * @throws RoutingException
     * @throws \ReflectionException
     */
    protected function getRoutesFromArray(array $routes): array
    {
        $r = [];
        foreach ($routes as $route) {
            $r[] = $this->getRouteFromArray($route);
        }

        return $r;
    }

    /**
     * @param array $route
     * @return Route
     * @throws RoutingException
     * @throws \ReflectionException
     */
    protected function getRouteFromArray(array $route): Route
    {
        if (isset($route['url'], $route['methods'], $route['action'], $route['name'])) {

            $url = $route['url'];
            $methods = $route['methods'];
            $action = $route['action'];
            $name = $route['name'];

            if (\is_string($url) && (\is_string($methods) || \is_array($methods)) && \is_string($action) && is_string($name)) {
                if (\is_array($methods)) {
                    foreach ($methods as &$method) {
                        if (\is_string($method)) {
                            $method = strtoupper($method);
                        } else {
                            throw new RoutingException('Methods can only be string');
                        }
                    }
                } else {
                    $methods = [strtoupper($methods)];
                }

                return new Route($url, $methods, $action, $name);
            }
        }

        throw new RoutingException('The route configuration is invalid');
    }

    public function getRoutes(): array
    {
        return $this->_routes;
    }
}