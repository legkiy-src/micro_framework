<?php

class ResponseGBS extends Response
{
    public function __construct()
    {
        $this->format = self::FORMAT_JSON;
    }

    public function send()
    {
        header(self::CONTENT_TYPE_JSON);
        http_response_code($this->statusCode);
        $this->writeHeaders();

        if ($this->errorCode || $this->errorMessage) {

            exit($this->formatData([
                'success' => 0,
                'errorCode' => $this->errorCode,
                'errorMessage' => $this->errorMessage,
                'errorId' => $this->errorId,
                'errorSubject' => $this->errorSubject,
                'errorValue' => $this->errorValue,
                'messages' => $this->messages,
            ], $this->format));
        }

        $responseData = $this->formatData([
            'success' => 1,
            'data' => $this->content === true ? [] : $this->content,
            'messages' => $this->messages,
        ], $this->format);

        $applicationRegistry = ApplicationRegistry::instance();

        $logRequest = new LogRequest();
        $logRequest->updateFields(['response' => $responseData], $applicationRegistry->get('log_request_id'));

        exit($responseData);
    }
}
