<?php

class Route
{
    use Lib;

    private $controller;
    private $action;
    private $applicationRegistry;
    private $request;
    private $response;

    public function __construct()
    {
        $this->applicationRegistry = ApplicationRegistry::instance();
        $this->request = $this->applicationRegistry->getRequest();
        $this->response = $this->applicationRegistry->getResponse();
    }

    public function init()
    {
        if ($this->parseUrl()) {

            if (class_exists($this->controller)) {

                $rc = new ReflectionClass($this->controller);

                if ($rc->hasMethod($this->action)) {

                    $controller = $rc->newInstance();
                    $method = $rc->getMethod($this->action);
                    $method->invoke($controller);

                } else {
                    $this->response->setStatusCode(400);
                    $this->response->setErrorCode(1);
                    $this->response->setErrorMessage('Не найдено действие');

                    return false;
                }

            } else {
                $this->response->setStatusCode(400);
                $this->response->setErrorCode(1);
                $this->response->setErrorMessage('Не найден контроллер');
                $this->response->setErrorSubject(__METHOD__);
                $this->response->setErrorValue($this->controller);

                return false;
            }
        } else {
            $this->response->setStatusCode(400);
            $this->response->setErrorCode(1);
            $this->response->setErrorMessage('Не верный url');
            $this->response->setErrorSubject(get_class($this));
            $this->response->setErrorValue($this->request->getRequestURI());

            return false;
        }

        return true;
    }

    private function parseUrl()
    {
        $method = $this->request->getMethod();
        $urlData = $this->request->getUrlData();

        $controllerIndex = 2;
        $actionIndex = 3;

        // получим контроллер
        if (isset($urlData[$controllerIndex]) && $urlData[$controllerIndex] !== '') {

            if (strpos($urlData[$controllerIndex], '-')) {

                $controllerEx = explode('-', $urlData[$controllerIndex]);

                foreach ($controllerEx as $val) {
                    $this->controller .= ucfirst($val);
                }

                $this->controller .= 'Controller';

            } else {
                $this->controller = ucfirst($urlData[$controllerIndex]) . 'Controller';
            }
        }

        // получим действие
        if (isset($urlData[$actionIndex]) && $urlData[$actionIndex] !== '') {

            if (strpos($urlData[$actionIndex], '-')) {

                $actionEx = explode('-', $urlData[$actionIndex]);
                $i = 0;

                foreach ($actionEx as $val) {

                    if ($i === 0) {
                        $this->action .= $val;
                    } else {
                        $this->action .= ucfirst($val);
                    }

                    $i++;
                }

                $this->action .= 'Action';

            } else {
                $this->action = $urlData[$actionIndex] . 'Action';
            }

        } else {

            $defaultAction = '';
            switch ($method) {
                case 'GET':
                    $defaultAction = 'getAction';
                    break;
                case 'POST':
                    $defaultAction = 'addAction';
                    break;
            }

            $this->action = $defaultAction;
        }

        /*var_dump($urlData);
        var_dump([$this->controller, $this->action]);
        exit;*/
        /*$this->debugArray($urlData);
        $this->debugArray([$this->controller, $this->action]);*/

        return true;
    }
}
