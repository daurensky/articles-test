<?php

namespace App\Infrastructure\Routing;

use Exception;
use DI\Container;
use DI\NotFoundException;
use DI\DependencyException;

class Router
{
    /** @var Route[] */
    private array $routes = [];

    public function __construct(
        private readonly Container $container
    )
    {
    }

    public function addRoute(string $method, string $path, callable|array $handler): void
    {
        $this->routes[] = new Route($method, $path, $handler);
    }

    /**
     * @throws Exception
     */
    public function dispatch(string $method, string $uri): mixed
    {
        foreach ($this->routes as $route) {
            if ($route->matches($method, $uri)) {
                return $this->execute($route, $uri);
            }
        }

        throw new Exception("Route not found", 404);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    private function execute(Route $route, string $uri): mixed
    {
        $pattern = "#^" . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route->path) . "$#";
        preg_match($pattern, $uri, $matches);
        array_shift($matches);

        $handler = $route->handler;

        [$class, $method] = $handler;
        $controller = $this->container->get($class);
        return $controller->$method(...$matches);
    }
}