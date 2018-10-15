<?php

namespace hunomina\Routing;

use hunomina\Http\Response\Response;
use hunomina\Routing\RouteManager\JsonRouteManager;
use hunomina\Routing\RouteManager\RouteManager;
use hunomina\Routing\RouteManager\YamlRouteManager;

class Router
{
    /** @var string $route_file */
    protected $_route_file;

    /** @var RouteManager $_route_manager */
    protected $_route_manager;

    /**
     * Router constructor.
     * @param string $route_file
     * @param string $type
     * @throws RoutingException
     * @throws \ReflectionException
     */
    public function __construct(string $route_file, string $type = 'json')
    {
        if (file_exists($route_file)) {

            $this->_route_file = $route_file;
            switch ($type) {
                case 'yaml':
                    $this->_route_manager = new YamlRouteManager($route_file);
                    break;
                default: // default and json
                    $this->_route_manager = new JsonRouteManager($route_file);
                    break;
            }
        } else {
            throw new RoutingException('The route file does not exist');
        }
    }

    public function request(string $method, string $url): ?Response
    {
        $routes = $this->_route_manager->getRoutes();
        /** @var Route $route */
        foreach ($routes as $route) {
            if ($route->match(strtoupper($method), $url)) {
                return $route->call($url);
            }
        }

        return null;
    }

    public function getRouteManager(): RouteManager
    {
        return $this->_route_manager;
    }
}