<?php

abstract class Response
{
    use Lib;

    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';
    const FORMAT_TEXT = 'text';
    const CONTENT_TYPE_JSON = 'Content-Type: application/json; charset=utf-8';
    const CONTENT_TYPE_XML = 'Content-type: text/xml; charset=utf-8';
    const CONTENT_TYPE_TEXT = 'Content-Type: text/html; charset=utf-8';
    const OK = '200 OK';

    protected $errorCode;
    protected $errorMessage;
    protected $errorId;
    protected $errorSubject;
    protected $errorValue;
    protected $statusCode = '200';
    protected $headers = [];
    protected $format;
    protected $content = [];
    protected $messages = [];

    abstract protected function send();

    public function setStatusCode($code)
    {
        if ($code) {
            $this->statusCode = $code;
        }
    }

    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    public function setErrorId($errorId)
    {
        $this->errorId = $errorId;
    }

    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    public function setErrorValue($errorValue)
    {
        $this->errorValue = $errorValue;
    }

    public function setErrorSubject($errorSubject)
    {
        $this->errorSubject = $errorSubject;
    }

    public function setHeader($header)
    {
        if ($header) {
            $this->headers[] = $header;
        }
    }

    public function setMessages($message)
    {
        $this->messages[] = $message;
    }

    public function writeHeaders()
    {
        switch ($this->format) {
            case self::FORMAT_JSON :
                header(self::CONTENT_TYPE_JSON);
                break;
            case self::FORMAT_XML :
                header(self::CONTENT_TYPE_XML);
                break;
            case self::FORMAT_TEXT :
                header(self::CONTENT_TYPE_TEXT);
                break;
        }

        http_response_code($this->statusCode);

        foreach ($this->headers as $header) {

            if ($header) {
                header($header);
            }
        }
    }

    public function setFormat($format = self::FORMAT_JSON)
    {
        $this->format = $format;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    protected function formatData($data, $type = self::FORMAT_JSON)
    {
        if(SHOW_RUNTIME) {
            Debug::setEndTime(microtime(true));
            $data['debug'] = Debug::getResultTime();
        }

        $result = [];

        $this->setHeader($type);

        switch ($type) {
            case self::FORMAT_JSON:
                $result = json_encode($data, JSON_UNESCAPED_UNICODE);
                break;
            case self::FORMAT_TEXT:
                $result = $data;
                break;
        }

        return $result;
    }

    public function hasErrors()
    {
        if($this->errorCode || $this->errorMessage) {
            return true;
        }

        return false;
    }
}
