<?php
namespace Engine;

class AppController
{
    private static $instance;

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function run()
    {
        $instance = self::getInstance();
        $instance->init();

        $applicationRegistry = ApplicationRegistry::instance();
        $response = $applicationRegistry->getResponse();

        // пишем лог
        $logRequest = $applicationRegistry->getLogRequest();
        $logRequest->write();
        $user = $applicationRegistry->getUser();

        if($user->verify() === false) {
            $response->send();
        }

        $route = $applicationRegistry->getRoute();

        if($route === false) {

            $response->setStatusCode(400);
            $response->setErrorCode(1);
            $response->setErrorMessage('Не верный запрос');

            $response->send();
        }

        $route->init();

        $response->send();
    }

    // здесь будем инициализировать параметры
    function init()
    {

    }
}
