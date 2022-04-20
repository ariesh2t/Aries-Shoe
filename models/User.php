<?php
require_once 'models/Model.php';

class User extends Model
{
    public $id;
    public $username;
    public $pswd;
    public $fullname;
    public $phone;
    public $address;
    public $avatar;
    public $status;
    public $created_at;
    public $updated_at;

    public $str_search = '';

    public function __construct()
    {
        parent::__construct();
        if (isset($_GET['fullname']) && !empty($_GET['fullname'])) {
            $fullname = addslashes($_GET['fullname']);
            $this->str_search .= " AND users.fullname LIKE '%$fullname%'";
        }
        if (isset($_GET['role']) && !empty($_GET['role'])) {
            $role = addslashes($_GET['role']);
            $this->str_search .= " AND users.role_id = '$role'";
        }
    }

    public function getAll($start = 0, $limit = 10000) {
        //tạo câu truy vấn
        //gắn chuỗi search nếu có vào truy vấn ban đầu
        $sql_select = "SELECT * FROM users WHERE TRUE $this->str_search LIMIT $start, $limit";
        //cbi đối tượng truy vấn
        $obj_select = $this->connection->prepare($sql_select);
        $obj_select->execute();
        return $obj_select->fetchAll(PDO::FETCH_ASSOC);
    }

    public function register() {
        // + Viết truy vấn
        $sql_insert = "INSERT INTO users(username, pswd, role_id) VALUES(:username, :pswd, :role_id)";
        // + Cbi obj truy vấn
        $obj_insert = $this->connection->prepare($sql_insert);
        // + Tạo mảng
        $inserts = [
            ':username' => $this->username,
            ':pswd' => $this->pswd,
            ':role_id' => '1',
        ];
        // + Thực thi
        return $obj_insert->execute($inserts);
    }

    public function getUser($username)
    {
        $sql_select_one =
            "SELECT * FROM users WHERE username = :username";
        $obj_select_one = $this->connection
            ->prepare($sql_select_one);
        $selects = [
            ':username' => $username,
        ];
        $obj_select_one->execute($selects);
        return $obj_select_one->fetch(PDO::FETCH_ASSOC);
    }

    public function getTotal()
    {
        $obj_select = $this->connection->prepare("SELECT COUNT(*) FROM users WHERE TRUE $this->str_search");
        $obj_select->execute();
        return $obj_select->fetchColumn();
    }

    public function getById($id)
    {
        $obj_select = $this->connection
            ->prepare("SELECT * FROM users WHERE id = $id");
        $obj_select->execute();
        return $obj_select->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByUsername($username, $id=-10000)
    {
        $obj_select = $this->connection
            ->prepare("SELECT COUNT(*) FROM users WHERE username=:username AND id!=:id");
        $data = [
            ':username' => $username,
            ':id' => $id,
        ];
        $obj_select->execute($data);
        return $obj_select->fetchColumn();
    }

    public function insertAdmin()
    {
        $obj_insert = $this->connection
            ->prepare("INSERT INTO users (username, pswd, role_id) VALUES(:username, :pswd, 1)");
        $arr_insert = [
            ':username' => $this->username,
            ':pswd' => $this->pswd,
        ];
        return $obj_insert->execute($arr_insert);
    }

    public function createUser()
    {
        $obj_insert = $this->connection
            ->prepare("INSERT INTO users (username, fullname, phone, pswd, role_id) VALUES(:username, :fullname, :phone, :pswd, 2)");
        $arr_insert = [
            ':username' => $this->username,
            ':pswd' => $this->pswd,
            ':fullname' => $this->fullname,
            ':phone' => $this->phone,
        ];
        return $obj_insert->execute($arr_insert);
    }

    public function updateStatus($id)
    {
        $obj_update = $this->connection->prepare("UPDATE users SET status=:status, updated_at=:updated_at WHERE id=:id");
        $arr_update = [
            ':status' => $this->status,
            ':id' => $id,
            ':updated_at' => $this->updated_at,
        ];
        return $obj_update->execute($arr_update);
    }

    public function update($id)
    {
        $obj_update = $this->connection
            ->prepare("UPDATE users 
                SET fullname=:fullname, username=:username, phone=:phone, address=:address, avatar=:avatar, pswd=:pswd, updated_at=:updated_at
                WHERE id = :id");
        $arr_update = [
            ':id' => $id,
            ':fullname' => $this->fullname,
            ':username' => $this->username,
            ':phone' => $this->phone,
            ':address' => $this->address,
            ':avatar' => $this->avatar,
            ':pswd' => $this->pswd,
            ':updated_at' => $this->updated_at,
        ];

        return $obj_update->execute($arr_update);
    }

    public function delete($id)
    {
        $obj_delete = $this->connection
            ->prepare("DELETE FROM users WHERE id = $id");
        return $obj_delete->execute();
    }

    public function getUserByUsernameAndPassword($username, $password)
    {
        $obj_select = $this->connection
            ->prepare("SELECT * FROM users WHERE username=:username AND password=:password LIMIT 1");
        $arr_select = [
            ':username' => $username,
            ':password' => $password,
        ];
        $obj_select->execute($arr_select);

        return $obj_select->fetch(PDO::FETCH_ASSOC);
    }
}