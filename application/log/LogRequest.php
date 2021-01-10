<?php

class LogRequest extends Model
{
    public function write()
    {
        $body = null;

        if ($_POST) {
            $body = json_encode($_POST);
        }

        if ($this->request->getPHPInput()) {
            $body = $this->request->getPHPInput();
        }

        $data = [
            'request_url' => $this->request->getRequestURI(),
            'request_method' => $_SERVER['REQUEST_METHOD'],
            'body' => $body,
            'headers' => json_encode($this->request->getHeaders()),
            'remote_addr' => $_SERVER['REMOTE_ADDR'],
            'remote_host' => isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : null,
        ];

        $sql = "INSERT INTO 
                    {$this->dbName}.log_request (request_url, request_method, body, headers, remote_addr, remote_host)
                VALUES 
                    (:request_url, :request_method, :body, :headers, :remote_addr, :remote_host)";

        if (!$this->query($data, $sql)) {
            $this->response->setErrorMessage('Ошибка добавления данных ' . __METHOD__);
            return false;
        }

        $lastInsertId = $this->dbConnect->lastInsertId();
        $this->applicationRegistry->set('log_request_id', $lastInsertId);

        return $lastInsertId;
    }

    public function updateFields($data, $id)
    {
        if (!$countData = count($data)) {
            return false;
        }

        $sql = 'UPDATE 
                    log_request
                SET ';

        $currentElem = 0;

        foreach ($data as $key => $val) {

            if ($currentElem === $countData - 1) {
                $sql .= " $key = :$key";
                break;
            }

            $sql .= " $key = :$key, ";

            $currentElem++;
        }

        $sql .= ' WHERE 
                     id = :id';

        $data['id'] = $id;

        return $this->query($data, $sql);
    }
}
