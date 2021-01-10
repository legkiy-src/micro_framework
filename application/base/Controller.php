<?php

class Controller
{
    use Lib;

    protected $view;
    protected $applicationRegistry;
    protected $request;
    protected $response;
    protected $shell;

    public function __construct()
    {
        $this->view = new View();
        $this->applicationRegistry = ApplicationRegistry::instance();
        $this->request = $this->applicationRegistry->getRequest();
        $this->response = $this->applicationRegistry->getResponse();
        $this->shell = $this->applicationRegistry->getShell();
    }
}
