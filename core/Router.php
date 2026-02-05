<?php

class Router
{
    private array $routes = [];
    private string $baseUrl;

    public function __construct(string $baseUrl = '')
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function get(string $path, array $handler, array $roles = [])
    {
        $this->addRoute('GET', $path, $handler, $roles);
    }

    public function post(string $path, array $handler, array $roles = [])
    {
        $this->addRoute('POST', $path, $handler, $roles);
    }

    private function addRoute(string $method, string $path, array $handler, array $roles)
    {
        $this->routes[$method][$path] = [
            'handler' => $handler,
            'roles'   => $roles
        ];
    }

    public function dispatch(string $uri)
    {
        $path   = parse_url($uri, PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // убираем BASE_URL из пути
        if ($this->baseUrl && str_starts_with($path, $this->baseUrl)) {
            $path = substr($path, strlen($this->baseUrl));
            if ($path === '') {
                $path = '/';
            }
        }

        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        $route   = $this->routes[$method][$path];
        $handler = $route['handler'];
        $roles   = $route['roles'];

        // === проверка авторизации ===
        if (!empty($roles)) {
            if (!Auth::check()) {
                header('Location: ' . $this->baseUrl . '/login');
                exit;
            }

            $userRole = Auth::user()['role'] ?? null;
            if (!in_array($userRole, $roles, true)) {
                http_response_code(403);
                echo '403 Forbidden';
                exit;
            }
        }

        // === вызов контроллера ===
        [$controllerClass, $methodName] = $handler;

        $controller = new $controllerClass();
        $controller->$methodName();
    }
}

