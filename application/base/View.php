<?php

class View
{
    use Lib;
    private $applicationRegistry;
    private $response;

    public function __construct()
    {
        $this->applicationRegistry = ApplicationRegistry::instance();
        $this->response = $this->applicationRegistry->getResponse();
    }

    public function render($template, $context)
    {
        include_once DIR_VIEWS . $template . '.php';
    }

    public function renderJson($data)
    {
        $this->response->setFormat(Response::FORMAT_JSON);
        $this->response->setContent($data);
    }

    public function renderXML($data)
    {
        $this->response->setFormat(Response::FORMAT_XML);
        $this->response->setContent($data);
    }

    public function renderText($data)
    {
        $this->response->setFormat(Response::FORMAT_TEXT);
        $this->response->setContent($data);
    }
}
