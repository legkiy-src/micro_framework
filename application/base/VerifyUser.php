<?php

abstract class VerifyUser
{
    use Lib;

    protected $applicationRegistry;
    protected $request;
    protected $response;

    public function __construct()
    {
        $this->applicationRegistry = ApplicationRegistry::instance();
        $this->request = $this->applicationRegistry->getRequest();
        $this->response = $this->applicationRegistry->getResponse();
    }

    abstract public function verify();
}
