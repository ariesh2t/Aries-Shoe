<?php
require_once 'models/Model.php';
class Category extends Model {
    public $id;
    public $name;
    public $image;
    public $description;
    public $parent_cat;
    public $created_at;

    public $str_search = '';

    public function __construct()
    {
        parent::__construct();

        if (isset($_GET['parent_cat']) && !empty($_GET['parent_cat'])) {
            $this->str_search .= " AND parent_cat = {$_GET['parent_cat']}";
        }
        if (isset($_GET['name']) && !empty($_GET['name'])) {
            $name = htmlspecialchars($_GET['name']);
            $this->str_search .= " AND name LIKE '%$name%'";
        }
    }

    /**
     * Lấy tổng số bản ghi trong bảng categories
     * @return mixed
     */
    public function countTotal()
    {
        $obj_select = $this->connection->prepare("SELECT COUNT(*) FROM categories WHERE TRUE $this->str_search");
        $obj_select->execute();

        return $obj_select->fetchColumn();
    }

    /**
     * LẤy tất cả danh mục trên hệ thống và phân trang
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getAll($start = 0, $limit = 10000): array
    {
        $sql_select = "SELECT * FROM categories WHERE TRUE $this->str_search ORDER BY created_at DESC LIMIT $start, $limit";
        $obj_select = $this->connection->prepare($sql_select);
        $obj_select->execute();
        return $obj_select->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy category theo id truyền vào
     * @param $id
     * @return array
     */
    public function getCategoryById($id): array
    {
        $obj_select = $this->connection->prepare("SELECT * FROM categories WHERE id = :id");
        $data = [
            ':id' => $id,
        ];
        $obj_select->execute($data);
        return $obj_select->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllCatChild(): array
    {
        $obj_select_all = $this->connection->prepare("SELECT * FROM categories WHERE parent_cat != 0");
        $obj_select_all->execute();
        return $obj_select_all->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkName($name, $id = -1) {
        $sql_select_one = "SELECT * FROM categories WHERE name = :name AND id != :id";
        $obj_select_one = $this->connection->prepare($sql_select_one);
        $data = [
            ':id' => $id,
            ':name' => $name,
        ];
        $obj_select_one->execute($data);
        return $obj_select_one->fetch(PDO::FETCH_ASSOC);
    }

    public function isParent($id) {
        $sql_select_one = "SELECT * FROM categories WHERE parent_cat = 0 AND id = :id";
        $obj_select_one = $this->connection->prepare($sql_select_one);
        $data = [
            ':id' => $id,
        ];
        $obj_select_one->execute($data);
        return $obj_select_one->fetch(PDO::FETCH_ASSOC);
    }

    public function hasChild($parent_cat): array
    {
        $sql_select = "SELECT * FROM categories WHERE parent_cat = :parent_cat";
        $obj_select = $this->connection->prepare($sql_select);
        $data = [
            ':parent_cat' => $parent_cat,
        ];
        $obj_select->execute($data);
        return $obj_select->fetchAll(PDO::FETCH_ASSOC);
    }

    public function hasProduct($id): bool
    {
        $obj_select = $this->connection->prepare("SELECT * FROM products WHERE category_id = :id");
        $data = [
            ':id' => $id,
        ];
        $obj_select->execute($data);
        return $obj_select->fetchColumn() > 0;
    }


    public function getBrandOrderByCountProduct($start=0, $limit=10000): array
    {
        $sql_select = "SELECT parent_cat, SUM(countTotal) as totalQtt
            FROM (SELECT categories.*, IFNULL(COUNT(*), 0) as countTotal
                FROM products RIGHT JOIN categories ON categories.id = products.category_id
                WHERE parent_cat != 0
                GROUP BY categories.id) a
            GROUP BY parent_cat
            ORDER BY totalQtt DESC
            LIMIT $start, $limit
        ";
        $obj_select_all = $this->connection->prepare($sql_select);
        $obj_select_all->execute();
        return $obj_select_all->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChildByCatParent($id, $start=0, $limit=10000)
    {
        $obj_select_all = $this->connection->prepare("SELECT * FROM categories WHERE parent_cat = :parent_cat LIMIT $start, $limit");
        $datas = [
            ':parent_cat' => $id,
        ];
        $obj_select_all->execute($datas);
        return $obj_select_all->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCatParentForNavbar()
    {
        $obj_select_all = $this->connection->prepare("SELECT * FROM categories 
            WHERE parent_cat = 0 AND CHAR_LENGTH(name)<15 
            LIMIT 0,3");
        $obj_select_all->execute();
        return $obj_select_all->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCatParent($start = 0, $limit = 100000)
    {
        $obj_select_all = $this->connection->prepare("SELECT * FROM categories WHERE parent_cat = 0 LIMIT $start, $limit");
        $obj_select_all->execute();
        return $obj_select_all->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert() {
        $sql_insert = "INSERT INTO categories(image, name, description, parent_cat) VALUES (:image, :name, :description, :parent_cat)";
        //cbi đối tượng truy vấn
        $obj_insert = $this->connection->prepare($sql_insert);
        //gán giá trị thật cho các placeholder
        $arr_insert = [
            ':name' => $this->name,
            ':image' => $this->image,
            ':description' => $this->description,
            ':parent_cat' => $this->parent_cat,
        ];
        return $obj_insert->execute($arr_insert);
    }

    /**
     * Update bản ghi theo id truyền vào
     * @param $id
     * @return bool
     */
    public function update($id)
    {
        $obj_update = $this->connection->prepare("UPDATE categories 
            SET image = :image, name = :name, description = :description, parent_cat = :parent_cat
            WHERE id = :id");
        $arr_update = [
            ':name' => $this->name,
            ':image' => $this->image,
            ':description' => $this->description,
            ':parent_cat' => $this->parent_cat,
            ':id' => $id,
        ];

        return $obj_update->execute($arr_update);
    }

    /**
     * Xóa bản ghi theo id truyền vào
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $obj_delete = $this->connection->prepare("DELETE FROM categories WHERE id = :id");
        $data = [
            ':id' => $id,
        ];
        $is_delete = $obj_delete->execute($data);
        //để đảm bảo toàn vẹn dữ liệu, sau khi xóa category thì cần xóa cả các product nào đang thuộc về category này
        $obj_delete_product = $this->connection->prepare("DELETE FROM products WHERE category_id = :id");
        $obj_delete_product->execute($data);

        return $is_delete;
    }
}