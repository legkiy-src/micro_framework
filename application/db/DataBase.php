<?php

class DataBase
{
    private $dbConnect;

    public function __construct()
    {
        $dsn = 'mysql:dbname=' . DB_NAME . ';charset=utf8;host=' . DB_HOST;

        try {
            $this->dbConnect = new PDO($dsn, DB_USER, DB_PASSWORD);
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }

    }

    public function getDBConnect()
    {
        return $this->dbConnect;
    }
}
