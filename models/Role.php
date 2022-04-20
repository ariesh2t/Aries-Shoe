<?php
require_once 'models/Model.php';

class Role extends Model
{
    public $id;
    public $name;

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll() {
        $obj_select = $this->connection->prepare("SELECT * FROM roles");
        $obj_select->execute();
        return $obj_select->fetchAll(PDO::FETCH_ASSOC);
    }
}