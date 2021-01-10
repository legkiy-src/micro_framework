<?php

abstract class Log
{
    protected $dbConnect;
    protected $dbName = DB_NAME;
    protected $applicationRegistry;
    protected $request;

    public function __construct()
    {
        $this->applicationRegistry = ApplicationRegistry::instance();
        $this->request = $this->applicationRegistry->getRequest();
        $this->response = $this->applicationRegistry->getResponse();
        $dataBase = $this->applicationRegistry->getDatabase();
        $this->dbConnect = $dataBase->getDBConnect();
    }

    abstract public function write();
}
