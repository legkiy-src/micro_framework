<?php

class ApplicationRegistry extends Registry
{
    use Lib;

    private static $instance = null;
    private $values = [];
    private $request;
    private $response;
    private $requestLog;
    private $route;
    private $dataBase;
    private $verifyUser;
    private $user;

    private function __construct()
    {
    }

    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get($key)
    {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }
    }

    public function set($key, $val)
    {
        $this->values[$key] = $val;
    }

    public function getRequest()
    {
        $instance = self::instance();
        if (is_null($instance->request)) {
            $instance->request = new Request();
        }
        return $instance->request;
    }

    public function getResponse()
    {
        $instance = self::instance();

        if (is_null($instance->response)) {
            $instance->response = new ResponseGBS();
        }

        return $instance->response;
    }

    public function getRoute()
    {
        $instance = self::instance();

        if (is_null($instance->route)) {
            $instance->route = new Route();
        }

        return $instance->route;
    }

    public function getDatabase()
    {
        $instance = self::instance();

        if (is_null($instance->dataBase)) {
            $instance->dataBase = new DataBase();
        }

        return $instance->dataBase;
    }

    public function getUser()
    {
        $instance = self::instance();

        if (is_null($instance->user)) {
            $instance->user = new User();
        }

        return $instance->user;
    }

    public function getShell()
    {
        $instance = self::instance();

        if (is_null($instance->shell)) {
            $instance->shell = new Shell();
        }

        return $instance->shell;
    }
}
