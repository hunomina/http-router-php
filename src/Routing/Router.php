<?php

namespace hunomina\Routing;

use hunomina\Http\Response\HtmlResponse;
use hunomina\Http\Response\Response;
use hunomina\Routing\RouteManager\{JsonRouteManager, RouteManager, YamlRouteManager};

class Router
{
    /** @var string $route_file */
    protected $_route_file;

    /** @var RouteManager $_route_manager */
    protected $_route_manager;

    /** @var array<callable> $_pre_middleware */
    protected $_pre_middleware = [];

    /** @var array<callable> $_post_middleware */
    protected $_post_middleware = [];

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

    /**
     * @param string $method
     * @param string $url
     * @return Response
     */
    public function request(string $method, string $url): Response
    {
        $routes = $this->_route_manager->getRoutes();
        $method = strtoupper($method);

        $notFoundResponse = new HtmlResponse('404 Not Found');
        $notFoundResponse->setHttpCode(404);

        /** @var Route $route */
        foreach ($routes as $route) {
            if ($route->match($method, $url)) {

                foreach ($this->_pre_middleware as $middleware) {
                    if ($middleware($method, $url) !== true) {
                        return $notFoundResponse;
                    }
                }

                $response = $route->call($url);

                foreach ($this->_post_middleware as $middleware) {
                    if ($middleware($response) !== true) {
                        return $notFoundResponse;
                    }
                }

                return $response;
            }
        }

        return $notFoundResponse;
    }

    public function getRouteManager(): RouteManager
    {
        return $this->_route_manager;
    }

    /**
     * @param array $middlewares
     * @return Router
     * @throws RoutingException
     */
    public function setPreMiddleware(array $middlewares): self
    {
        foreach ($middlewares as $middleware) {
            if (!\is_callable($middleware)) {
                throw new RoutingException('A middleware must be callable and return a boolean');
            }
        }

        $this->_pre_middleware = $middlewares;
        return $this;
    }

    /**
     * @param array $middlewares
     * @return Router
     * @throws RoutingException
     */
    public function setPostMiddleware(array $middlewares): self
    {
        foreach ($middlewares as $middleware) {
            if (!\is_callable($middleware)) {
                throw new RoutingException('A middleware must be callable and take a Response object as parameter');
            }
        }

        $this->_post_middleware = $middlewares;
        return $this;
    }

    public function addPreMiddleware(callable $middleware): self
    {
        $this->_pre_middleware[] = $middleware;
        return $this;
    }

    public function addPostMiddleware(callable $middleware): self
    {
        $this->_post_middleware[] = $middleware;
        return $this;
    }

    /**
     * @param string $routeName
     * @param array $params
     * @param string $method
     * @return string
     * @throws RoutingException
     */
    public function generate(string $routeName, array $params = [], string $method = 'GET'): string
    {
        $method = strtoupper($method);
        $routes = $this->_route_manager->getRoutes();

        /** @var Route $route */
        foreach ($routes as $route) {
            if ($route->getName() === $routeName && in_array($method, $route->getMethods(), true)) {

                $url = $route->getUrl();
                preg_match_all(Route::URL_PARAMS_REGEX, $url, $urlParams); // get parameters from route url
                $urlParams = $urlParams[0];

                if (count($urlParams) === count($params)) {
                    foreach ($urlParams as $i => $param) {
                        if (!preg_match('/' . $param . '/', $params[$i])) {
                            throw new RoutingException('Invalid paramter type');
                        }

                        $pattern = '/' . preg_quote($param, '/') . '/';
                        $url = preg_replace($pattern, $params[$i], $url, 1);
                    }
                    return $url;
                }

                throw new RoutingException('You must pass as many parameter as needed in the route url : ' . count($urlParams));
            }
        }
        throw new RoutingException("The '" . $routeName . "' route does not exist");
    }
}