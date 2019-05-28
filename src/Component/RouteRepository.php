<?php

namespace Snowdog\DevTest\Component;

use FastRoute\RouteCollector;

class RouteRepository
{
    private static $instance = null;
    private $routes = [];
    const HTTP_METHOD = 'http_method';
    const ROUTE = 'route';
    const CLASS_NAME = 'class_name';
    const METHOD_NAME = 'method_name';
    const AUTH_REQUIRED = 'auth_required';


    /**
     * @return RouteRepository
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function registerRoute($httpMethod, $route, $className, $methodName, $authRequired)
    {
        $instance = self::getInstance();
        $instance->addRoute($httpMethod, $route, $className, $methodName, $authRequired);
    }

    public function __invoke(RouteCollector $r)
    {
        foreach ($this->routes as $route) {
            $r->addRoute(
                $route[self::HTTP_METHOD],
                $route[self::ROUTE],
                [
                    $route[self::CLASS_NAME],
                    $route[self::METHOD_NAME],
                    $route[self::AUTH_REQUIRED]
                ]
            );
        }
    }

    private function addRoute($httpMethod, $route, $className, $methodName, $authRequired)
    {
        $this->routes[] = [
            self::HTTP_METHOD => $httpMethod,
            self::ROUTE => $route,
            self::CLASS_NAME => $className,
            self::METHOD_NAME => $methodName,
            self::AUTH_REQUIRED => $authRequired,
        ];
    }
}