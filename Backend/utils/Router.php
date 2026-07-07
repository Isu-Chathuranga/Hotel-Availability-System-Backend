<?php
namespace App\Utils;

class Router
{
    private array $routes = [];

    public function get(string $path, string $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, string $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function put(string $path, string $handler): void
    {
        $this->routes['PUT'][$path] = $handler;
    }

    public function delete(string $path, string $handler): void
    {
        $this->routes['DELETE'][$path] = $handler;
    }

    public function addRoute(string $method, string $path, string $handler): void
    {
        $this->routes[strtoupper($method)][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $basePath = '/Backend';
        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        $path = '/' . ltrim($path, '/');

        $method = strtoupper($method);

        if (isset($this->routes[$method][$path])) {
            $this->callHandler($this->routes[$method][$path]);
            return;
        }

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                $this->callHandler($handler, $matches);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(["message" => "Route not found", "path" => $path]);
    }

    private function callHandler(string $handler, array $params = []): void
    {
        [$controllerClass, $method] = explode('@', $handler);
        $controllerClass = 'App\\Controllers\\' . $controllerClass;

        if (!class_exists($controllerClass)) {
            http_response_code(500);
            echo json_encode(["message" => "Controller not found: {$controllerClass}"]);
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            http_response_code(500);
            echo json_encode(["message" => "Method not found: {$method}"]);
            return;
        }

        call_user_func_array([$controller, $method], $params);
    }
}
