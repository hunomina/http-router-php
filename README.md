# Http Router PHP

[![Build Status](https://travis-ci.com/hunomina/http-router-php.svg?branch=master)](https://travis-ci.com/hunomina/http-router-php)

Description : Implementation of Http Router classes for PHP7.1 or higher.

This library is mainly composed of 4 classes.

## Router

The Router *([hunomina\Routing\Router](https://github.com/hunomina/http-router-php/blob/master/src/Routing/Router.php))* class handles request by calling the *request(string $method, string $url)* method which must return a route action response.

Can be instantiate by passing a route file and a type (json, yaml... extend if you wanna add new ones).

The Router::request(*$method*, *$url*) method allows to execute a route action based on the method and the url parameters.

Example of route files in the */tests* folder.

## RouteManager

The RouteManager *([hunomina\Routing\RouteManager\RouteManager](https://github.com/hunomina/http-router-php/blob/master/src/Routing/RouteManager/RouteManager.php))* parses route files. You can then get Route objects by calling the *getRoutes()* method.

This class is *abstract*, so you have to extend it in order to add new route file types
(See *[hunomina\Routing\RouteManager\JsonRouteManager](https://github.com/hunomina/http-router-php/blob/master/src/Routing/RouteManager/JsonRouteManager.php)* or *[hunomina\Routing\RouteManager\YamlRouteManager](https://github.com/hunomina/http-router-php/blob/master/src/Routing/RouteManager/YamlRouteManager.php)* for examples).

## Route

A Route ([hunomina\Routing\Route](https://github.com/hunomina/http-router-php/blob/master/src/Routing/Route.php)) object is composed of these attributes :

- *array* $_methods : An array of HTTP methods handled by the route
- *string* $_url : The url of the route
- *string* $_pattern : A regexp representing the urls handled by the route
- *array* $_action : A two items array. The first is the FQN of a class (namespaces separated by ':'). The second one is the name of the method you want to call for this route. When setting the action attribute you have to pass a string which respects this syntax classname::method.

Finally, the Route::call() method execute the route action which has to return a [hunomina\Http\Response\Response](https://github.com/hunomina/http-router-php/blob/master/src/Http/Response/Response.php) object.

## Response

A Response ([hunomina\Http\Response\Response](https://github.com/hunomina/http-router-php/blob/master/src/Http/Response/Response.php)) object is composed of :

- *array* $_headers : An array of HTTP headers
- *string* $_content : Content of the response

The class is *abstract*, so you have to extend in order to add new response types
