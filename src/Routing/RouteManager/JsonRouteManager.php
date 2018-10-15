<?php

namespace hunomina\Routing\RouteManager;

use hunomina\Routing\RoutingException;

class JsonRouteManager extends RouteManager
{
    /**
     * RouteManager constructor.
     * @param string $route_file
     * @throws RoutingException
     * @throws \ReflectionException
     */
    public function __construct(string $route_file)
    {
        if ($content = file_get_contents($route_file)) {

            $routes = json_decode($content, true);
            if ($routes === null) {
                throw new RoutingException('This file can not be used for routing');
            }

            $this->_routes = $this->getRoutesFromArray($routes);
        } else {
            throw new RoutingException('This file can not be used for routing');
        }
    }
}