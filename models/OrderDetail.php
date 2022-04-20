<?php
require_once 'models/Model.php';
class OrderDetail extends Model {
    public $product_id;
    public $order_id;
    public $quantity;
    public $price;

    public function insert(): bool
    {
        $sql_insert = "INSERT INTO order_details(product_id, order_id, quantity, price) VALUES (:product_id, :order_id, :quantity, :price)";
        //cbi đối tượng truy vấn
        $obj_insert = $this->connection->prepare($sql_insert);
        //gán giá trị thật cho các placeholder
        $arr_insert = [
            ':product_id' => $this->product_id,
            ':order_id' => $this->order_id,
            ':quantity' => $this->quantity,
            ':price' => $this->price,
        ];
        return $obj_insert->execute($arr_insert);
    }

    public function getAllProductByOrderId($id): array
    {
        $obj_select = $this->connection->prepare("SELECT * FROM order_details WHERE order_id = :id");
        $arr = [
            ':id' => $id
        ];
        $obj_select->execute($arr);
        return $obj_select->fetchAll(PDO::FETCH_ASSOC);
    }
}