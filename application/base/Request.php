<?php

class Request
{
    use Lib;

    private $get;
    private $post;
    private $headers;
    private $requestURI;
    private $urlData;
    private $method;
    private $phpinput;
    private $visitor; // Флаг для выбора точки входа в риложение (in - запросы от Эвотор, out - запросы от наших сервисов)
    private $queryData; // параметры запроса
    public $_argv;

    function __construct()
    {
        $this->init();
    }

    function init()
    {
        $this->get = $_GET;
        $this->post = $_POST;

        $applicationRegistry = ApplicationRegistry::instance();
        $this->_argv = $applicationRegistry->get('argv');

        if (php_sapi_name() === 'cli') {
            $this->headers['tokenGBS'] = $this->_argv[1];
            $this->headers['appIdGBS'] = $this->_argv[2];
            $this->headers['inn'] = $this->_argv[3];
            $this->headers['login'] = $this->_argv[4];
            $this->headers['password'] = $this->_argv[5];
            $this->headers['ofd_api_key'] = $this->_argv[6];
            $parseUrl = parse_url($this->_argv[7]);
        } else {
            $this->headers = apache_request_headers();
            $parseUrl = parse_url($_SERVER['REQUEST_URI']);
        }

        if(isset($this->headers['offline'])) {
            $this->offline = true;
        }

        $path = $parseUrl['path'];

        if(isset($parseUrl['query'])) {
            $query = urldecode($parseUrl['query']);
            $queryData = $this->buildQueryData($query);
            $this->queryData = $queryData;
        }

        $this->urlData = explode('/', $path);
        $this->method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null;
        $this->requestURI = REQUEST_URI;

        $this->phpinput = file_get_contents('php://input');
    }

    // получает часть строки параметров из запроса из них массив
    private function buildQueryData($query)
    {
        if(!$query) {
            return false;
        }

        $result = [];

        $queryEx = explode('&', $query);

        foreach($queryEx as $val) {
            $valEx = explode('=', $val);
            $result += [$valEx[0] => $valEx[1]];
        }

        return $result;
    }

    public function get($param = '')
    {
        if(!$param) {
            return $this->get;
        }

        return $this->get[$param];
    }

    public function post($param = '')
    {
        if(!$param) {
            return $this->post;
        }

        return $this->post[$param];
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getQueryData()
    {
        return $this->queryData;
    }

    public function getUrlData()
    {
        return $this->urlData;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPHPInput()
    {
        return $this->phpinput;
    }

    public function getVisitor()
    {
        return $this->visitor;
    }

    public function getRequestURI()
    {
        return $this->requestURI;
    }
}

