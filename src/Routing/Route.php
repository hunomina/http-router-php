<?php

namespace hunomina\Routing;

use hunomina\Http\Response\Response;

class Route
{
    public const URL_PARAMS_REGEX = '/\[[^\[\]]+\](?:[+*]|{\d+,?\d*})?/';

    /**
     * @var string $_url
     * Url of the route
     */
    private $_url;

    /** @var array $methods */
    private $_methods;

    /**
     * @var array $_action
     * [classname, method]
     */
    private $_action;

    /**
     * @var string $_name
     * Route name
     */
    private $_name;

    /**
     * @var string $_pattern
     * Regexp representing the url of the route, with '/' not escaped
     */
    private $_pattern;

    /**
     * Route constructor.
     * @param string $url
     * @param array $methods
     * @param string $action
     * @param string $name
     * @throws RoutingException
     * @throws \ReflectionException
     */
    public function __construct(string $url, array $methods, string $action, string $name)
    {
        $this->setUrl($url)
            ->setMethods($methods)
            ->setAction($action)
            ->setName($name);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->_url;
    }

    /**
     * @param string $url
     * @return Route
     */
    public function setUrl(string $url): Route
    {
        /**
         * $url is supposed to be a pseudo regex representing the route url
         * This regex match the component like these ones : [a] ; [0-9]+ ; [a-zA-Z]* ; [azer.|]{3} ; [uiop]{4,} ; [qsdf]{5,6}
         * This regex can be used to capture pseudo regex parameters
         */

        $this->_url = $url;

        $pattern = str_replace('/', '\/', $url);
        $pattern = '/^' . $pattern . '$/';
        $pattern = preg_replace(self::URL_PARAMS_REGEX, '($0)', $pattern);

        $this->_pattern = $pattern;
        return $this;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->_methods;
    }

    /**
     * @param array $methods
     * @return Route
     */
    public function setMethods(array $methods): Route
    {
        $this->_methods = $methods;
        return $this;
    }

    /**
     * @param string $method
     * @return Route
     */
    public function addMethod(string $method): Route
    {
        $this->_methods[] = strtoupper($method);
        return $this;
    }

    /**
     * @return array
     */
    public function getAction(): array
    {
        return $this->_action;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->_pattern;
    }

    /**
     * @param string $action
     * $action => classname::method
     * Namespaces must be separated by ':'
     * @return Route
     * @throws RoutingException
     * @throws \ReflectionException
     */
    public function setAction(string $action): Route
    {
        $explodedAction = explode('::', $action);
        if (\count($explodedAction) === 2) {

            [$class, $method] = $explodedAction;
            $class = str_replace(':', '\\', $class);

            if (class_exists($class)) {
                $reflexion = new \ReflectionClass($class);
                if ($reflexion->hasMethod($method)) {
                    $this->_action = [$class, $method];
                } else {
                    throw new RoutingException('The \'' . $class . '::' . $method . '\' method does not exist');
                }
            } else {
                throw new RoutingException('The \'' . $class . '\' class does not exist');
            }
        } else {
            throw new RoutingException('The route action has an invalid syntax');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->_name = $name;
    }

    public function match(string $method, string $url): bool
    {
        if (\in_array($method, $this->_methods, true)) {
            return preg_match($this->_pattern, $url);
        }

        return false;
    }

    public function call(string $url): Response
    {
        preg_match($this->_pattern, $url, $params);
        unset($params[0]); // unset the whole match to only get the parameters

        $action = [new $this->_action[0](), $this->_action[1]];
        return \call_user_func_array($action, $params);
    }
}