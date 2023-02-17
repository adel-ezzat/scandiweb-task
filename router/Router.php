<?php

declare(strict_types=1);

namespace Router;

/**
 * http router
 */
class Router
{
    private array $handlers;
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';

    /**
     * handle get requests
     * @param string $path
     * @param $handler
     * @return void
     */
    public function get(string $path, $handler): void
    {
        $this->addHandler(self::METHOD_GET, $path, $handler);
    }

    /**
     * handle post requests
     * @param string $path
     * @param $handler
     * @return void
     */
    public function post(string $path, $handler): void
    {
        $this->addHandler(self::METHOD_POST, $path, $handler);
    }

    /**
     * add handler before processing
     * @param string $method
     * @param string $path
     * @param $handler
     * @return void
     */
    private function addHandler(string $method, string $path, $handler): void
    {
        $this->handlers[$method . $path] = compact('path', 'method', 'handler');
    }

    /**
     * process request
     * @return void
     */
    public function run(): void
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        $method = $_SERVER['REQUEST_METHOD'];
        $callback = null;

        foreach ($this->handlers as $handler) {
            if ($handler['path'] === $requestPath && $handler['method'] === $method) {
                $callback = $handler['handler'];
            }
        }

        if (gettype($callback) === 'array') {
            $class = new $callback[0] ?? '';
            $method = $callback[1] ?? '';

            if (!method_exists($class, $method)) {
                die("Class Method $method Not Found");
            }

            $callback = [(new $class), $method];
        }

        if (!$callback) {
            die('404 Page Not Found');
        }

        call_user_func_array($callback, [
            array_merge($_GET, $_POST)
        ]);
    }

    /**
     * run process method
     */
    public function __destruct()
    {
        $this->run();
    }

}