<?php

namespace App\Database;

require_once __DIR__ . "/../config/consts.php";

class DB {

    private static $instance = null;
    public function __construct() 
    {
        try {
            $this->pdo = new \PDO("mysql:dbname=" . DB_NAME . ";host=" . DB_HOST, DB_USER, DB_PASS);
        } catch (\Exception $e) {
            file_put_contents(__DIR__ . "/../storage/logs.txt", "[" . date("Y-m-d H:i:s") . "]: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            echo "The database is down";
            die();
        }
    }

    public function execPrepared($statement, $values) 
    {
        $preparedStatement = $this->pdo->prepare($statement);
        return $preparedStatement->execute($values);
    }

    public function prepare($sql) 
    {
        return $this->pdo->prepare($sql);
    }

    public function execute($sql) 
    {
        return $this->pdo->execute($sql);
    }

    public static function connect() 
    {
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}


