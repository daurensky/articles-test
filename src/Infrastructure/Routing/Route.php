<?php

namespace App\Infrastructure\Routing;

class Route
{
    public function __construct(
        public string $method,
        public string $path,
        public mixed  $handler,
    )
    {
    }

    public function matches(string $method, string $uri): bool
    {
        if ($this->method !== $method) return false;

        $pattern = "#^" . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $this->path) . "$#";
        return (bool)preg_match($pattern, $uri);
    }
}