<?php
require_once 'models/Model.php';
class Order extends Model {
    public $user_id;
    public $order_status_id;
    public $fullname;
    public $phone;
    public $address;
    public $note;
    public $total_price;

    public $str_search = '';

    public function __construct()
    {
        parent::__construct();

        if (isset($_GET['order_status_id']) && !empty($_GET['order_status_id'])) {
            $this->str_search .= " AND order_status_id = {$_GET['order_status_id']}";
        }
    }

    public function insert(): bool
    {
        $sql_insert = "INSERT INTO orders(user_id, fullname, phone, address, note, total_price) VALUES (:user_id, :fullname, :phone, :address, :note, :total_price)";
        //cbi đối tượng truy vấn
        $obj_insert = $this->connection->prepare($sql_insert);
        //gán giá trị thật cho các placeholder
        $arr_insert = [
            ':user_id' => $this->user_id,
            ':fullname' => $this->fullname,
            ':phone' => $this->phone,
            ':address' => $this->address,
            ':note' => $this->note,
            ':total_price' => $this->total_price,
        ];
        return $obj_insert->execute($arr_insert);
    }

    public function getLastOrderId() {
        $obj_select = $this->connection->prepare("SELECT MAX(id) id FROM orders");
        $obj_select->execute();
        return $obj_select->fetchColumn();
    }

    public function countTotal()
    {
        $obj_select = $this->connection->prepare("SELECT COUNT(*) FROM orders WHERE TRUE $this->str_search");
        $obj_select->execute();

        return $obj_select->fetchColumn();
    }

    public function getAllByPaginate($start, $limit): array
    {
        $obj_select = $this->connection->prepare("SELECT * FROM orders WHERE TRUE $this->str_search 
                ORDER BY order_status_id ASC, created_at DESC 
                LIMIT $start, $limit");
        $obj_select->execute();
        return $obj_select->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderById($id) {
        $obj_select_one = $this->connection->prepare("SELECT * FROM orders WHERE id = :id");
        $arr = [
            ':id' => $id
        ];
        $obj_select_one->execute($arr);
        return $obj_select_one->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderByUserId($start, $limit, $id): array
    {
        $obj_select = $this->connection->prepare("SELECT * FROM orders WHERE user_id = :id $this->str_search 
                ORDER BY order_status_id ASC, created_at DESC 
                LIMIT $start, $limit");
        $arr = [
            ':id' => $id,
        ];
        $obj_select->execute($arr);
        return $obj_select->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id): bool
    {
        $obj_update = $this->connection->prepare("UPDATE orders SET order_status_id = :order_status_id WHERE id = :id");
        $arr = [
            ':order_status_id' => $this->order_status_id,
            ':id' => $id
        ];
        return $obj_update->execute($arr);
    }
}