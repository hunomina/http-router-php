<?php

namespace hunomina\Routing\RouteManager;

use hunomina\Routing\RoutingException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YamlRouteManager extends RouteManager
{
    /**
     * YamlRouteManager constructor.
     * @param string $route_file
     * @throws RoutingException
     * @throws \ReflectionException
     */
    public function __construct(string $route_file)
    {
        if ($content = file_get_contents($route_file)) {

            try {
                $routes = Yaml::parseFile($route_file);
                $this->_routes = $this->getRoutesFromArray($routes);
            } catch (ParseException $e) {
                throw new RoutingException('This file can not be used for routing');
            }

            // $this->_routes = $this->getRoutesFromArray($routes);
        } else {
            throw new RoutingException('This file can not be used for routing');
        }
    }
}