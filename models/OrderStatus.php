<?php
require_once 'models/Model.php';
class OrderStatus extends Model
{
    public function getAll() :array
    {
        $obj_select_all = $this->connection->prepare("SELECT * FROM order_statuses");
        $obj_select_all->execute();
        return $obj_select_all->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStatus($id) {
        $obj_select_all = $this->connection->prepare("SELECT status FROM order_statuses WHERE id = :id");
        $arr = [
            ':id' => $id
        ];
        $obj_select_all->execute($arr);
        return $obj_select_all->fetch(PDO::FETCH_ASSOC);
    }
}