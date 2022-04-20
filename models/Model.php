<?php
require_once 'configs/Database.php';

class Model
{
    public $connection;

    public function __construct()
    {
        try {
            $this->connection = new PDO(Database::DB_DSN, Database::DB_USERNAME, Database::DB_PASSWORD);
        } catch (PDOException $e) {
            die("Lỗi kết nối: " . $e->getMessage());
        }
    }
}