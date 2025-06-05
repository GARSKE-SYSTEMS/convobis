<?php

namespace Convobis\Util;

require_once __DIR__ . '/ConfigHelper.php';

class Database {
    
    private static $instance = null;
    private $connection;

    private function __construct() {
        $this->connect();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    private function connect() {
        $type = ConfigHelper::getConfigValue("database.type", "sqlite", true);

        switch($type) {
            case "sqlite":
                $file = ConfigHelper::getConfigValue("database.file", "database.db", true);
                $this->connection = new \PDO("sqlite:" . $file);
                break;
            case "mysql":
                $host = ConfigHelper::getConfigValue("database.host", "localhost", true);
                $port = ConfigHelper::getConfigValue("database.port", 3306, true);
                $dbname = ConfigHelper::getConfigValue("database.name", "my_database", true);
                $username = ConfigHelper::getConfigValue("database.username", "root", true);
                $password = ConfigHelper::getConfigValue("database.password", "", true);
                $this->connection = new \PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
                break;
            default:
                throw new \Exception("Unsupported database type: " . $type);
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}