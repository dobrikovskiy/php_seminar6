<?php

namespace Geekbrains\Application1\Application;

use Exception;
use Geekbrains\Application1\Infrastructure\Config;
use Geekbrains\Application1\Infrastructure\Storage;

class Application {

    private const APP_NAMESPACE = 'Geekbrains\Application1\Domain\Controllers\\';

    private string $controllerName;
    private string $methodName;

    public static Config $config;

    public static Storage $storage;

    public function __construct(){
        self::$config = new Config();
        self::$storage = new Storage();
    }

    public function run(): string{
    $routeArray = explode('/', $_SERVER['REQUEST_URI']);

    if (isset($routeArray[1]) && $routeArray[1] !== '') {
        $controllerName = $routeArray[1];
    } else {
        $controllerName = 'page';
    }

    $this->controllerName = self::APP_NAMESPACE . ucfirst($controllerName) . 'Controller';

    if (class_exists($this->controllerName)) {
        // Пытаемся вызвать метод
        if (isset($routeArray[2]) && $routeArray[2] !== '') {
            $methodName = $routeArray[2];
        } else {
            $methodName = 'index';
        }

        $this->methodName = 'action' . ucfirst($methodName);

        if (method_exists($this->controllerName, $this->methodName)) {
            $controllerInstance = new $this->controllerName();

            // Разбираем параметры
            $params = [];
            $requestParams = $_GET;

            foreach ($requestParams as $key => $value) {
                if ($key === 'id') {
                    $params[] = intval($value); // Приводим значение id к типу int
                } else {
                    $params[] = $value;
                }
            }

            // Передаем параметры в метод контроллера
            return call_user_func_array([$controllerInstance, $this->methodName], $params);
        } else {
            throw new Exception("Метод {$this->methodName} не существует", 404);
        }
    } else {
        throw new Exception("Класс {$this->controllerName} не существует", 404);
    }
}

}