<?php

abstract class Model
{
    use Lib;

    protected $dbConnect;
    protected $dbName = DB_NAME;
    protected $context = [];
    protected $applicationRegistry;
    protected $request;
    protected $response;
    protected $curlRequest;
    protected $error;
    protected $errorCode;
    protected $errorMessage;
    protected $errorId;
    protected $shell;

    public function __construct()
    {
        $this->applicationRegistry = ApplicationRegistry::instance();
        $this->request = $this->applicationRegistry->getRequest();
        $this->response = $this->applicationRegistry->getResponse();
        $dataBase = $this->applicationRegistry->getDatabase();
        $this->dbConnect = $dataBase->getDBConnect();
        $this->shell = $this->applicationRegistry->getShell();
    }

    public function __get($name)
    {
        $methodName = 'get' . ucfirst($name);
        return $this->$methodName();
    }

    public function __set($name, $value)
    {
        $methodName = 'get' . ucfirst($name);
        $this->$methodName($value);
    }

    public function query($data, $sql, $userId = null, $debug = false)
    {
        try {

            if ($debug) {
                $sqlDebug = $this->makeSqlDebug($data, $sql);
                //$this->debugText($sqlDebug, 'sql_debug.sql');
            }

            $this->dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //$this->dbConnect->beginTransaction();

            $query = $this->dbConnect->prepare($sql);

            if ($data) {
                foreach ($data as $key => $val) {

                    //if (strpos($sql, $key)) {
                        $query->bindValue(':' . $key, $val);
                    //}
                }
            }

            $query->execute();
            //$this->dbConnect->commit();

        } catch (PDOException $e) {

            //$this->dbConnect->rollBack();
            $sqlDebug = $this->makeSqlDebug($data, $sql);
            $debugBagtrace = debug_backtrace();
            $errorSubject = $debugBagtrace[1]['class'] . '::' . $debugBagtrace[1]['function'];

            $errorInfo = [
                'user_id' => $userId,
                'data' => $data,
                'sql' => $sqlDebug,
                'errorSubject' => $errorSubject,
                'errorInfo' => $query->errorInfo(),
            ];

            //$this->notifyDbError($errorInfo);

            $errorData = [
                'error_code' => '33',
                'error_text' => json_encode($query->errorInfo(), true),
                'error_subject' => $errorSubject,
                'data' => $data,
            ];

            return $this->error_($errorData);
        }

        return $query;
    }

    public function query_new($data, $sql, $userId = null, $debug = false)
    {
        if ($debug) {
            $sqlDebug = $this->makeSqlDebug($data, $sql);
        }

        $this->dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //$this->dbConnect->beginTransaction();

        $query = $this->dbConnect->prepare($sql);

        if ($data) {
            foreach ($data as $key => $val) {

                if (strpos($sql, $key)) {
                    $query->bindValue(':' . $key, $val);
                }
            }
        }

        if (!$query->execute()) {

            //$this->dbConnect->rollBack();
            $sqlDebug = $this->makeSqlDebug($data, $sql);
            $debugBagtrace = debug_backtrace();
            $errorSubject = $debugBagtrace[1]['class'] . '::' . $debugBagtrace[1]['function'];

            $errorInfo = [
                'user_id' => $userId,
                'data' => $data,
                'sql' => $sqlDebug,
                'errorSubject' => $errorSubject,
                'errorInfo' => $query->errorInfo(),
            ];

            //$this->notifyDbError($errorInfo);

            $errorData = [
                'error_code' => '33',
                'error_text' => json_encode($query->errorInfo(), true),
                'error_subject' => $errorSubject,
                'data' => $data,
            ];

            $this->error_($errorData);

            //throw new PDOException();

            //return $this->error_($errorData);
        }


        return $query;
    }

    // В строке запроса заменяет параметры значениями
    public function makeSqlDebug($data, $sql)
    {
        $sqlDebug = $sql;

        foreach ($data as $key => $val) {
            $sqlDebug = str_replace(':' . $key, "'" . $val . "'", $sqlDebug);
        }

        $sqlDebug = str_replace("\r\n", ' ', $sqlDebug);

        return $sqlDebug;
    }

    public function unique($tableName, $data)
    {
        if (!$countData = count($data)) {
            return false;
        }

        $sql = "SELECT
                    *
                FROM
                    $tableName
                WHERE ";

        $currentElem = 0;

        foreach ($data as $key => $val) {

            if ($currentElem === $countData - 1) {
                $sql .= " $key = :$key";
                break;
            }

            $sql .= " $key = :$key AND ";

            $currentElem++;
        }

        $query = $this->query($data, $sql);

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!$result) {
            return true;
        }

        return false;
    }

    public function error_($data)
    {
        $guid = $this->getGUID();
        $error = $this->getErrorFromCatalog($data['error_code']);
        $errorText = isset($data['error_text']) ? $data['error_text'] : '';
        $error['error_message'] .= $errorText != '' ? ' ' . $errorText : '';
        $this->response->setStatusCode($error['http_code']);
        $this->response->setErrorCode($error['error_code']);
        //$this->response->setErrorSubject(__METHOD__);
        $this->response->setErrorMessage($error['error_message']);

        $sql = 'INSERT INTO error (guid, user_id, error_code, error_message, error_subject, data, http_code) 
                VALUES (:guid, :user_id, :error_code, :error_message, :error_subject, :data, :http_code)';

        $addData = [
            'guid' => $guid,
            'user_id' => isset($data['user_id']) ? $data['user_id'] : 0,
            'error_code' => $error['error_code'],
            'error_message' => $error['error_message'],
            'error_subject' => $data['error_subject'],
            'data' => isset($data['data']) ? json_encode($data['data']) : '',
            'http_code' => $error['http_code'],
        ];

        $this->query($addData, $sql);

        $this->response->setErrorId($guid);

        return false;
    }

    public function getErrorFromCatalog($errorCode)
    {
        $sql = 'SELECT * 
                FROM error_catalog  
                WHERE error_code = :error_code';

        $query = $this->query(['error_code' => $errorCode], $sql);

        if (!$query) {
            return false;
        }

        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
