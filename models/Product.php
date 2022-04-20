<?php
require_once 'models/Model.php';

class Product extends Model
{

    public $id;
    public $category_id;
    public $name;
    public $image;
    public $cost;
    public $price;
    public $color;
    public $size;
    public $amount;
    public $description;
    public $created_at;
    public $updated_at;
    /*
     * Chuỗi search, sinh tự động dựa vào tham số GET trên Url
     */
    public $str_search = '';

    public function __construct()
    {
        parent::__construct();
        if (isset($_GET['name']) && !empty($_GET['name'])) {
            $this->str_search .= " AND products.name LIKE '%{$_GET['name']}%'";
        }
        if (isset($_GET['category_id']) && !empty($_GET['category_id'])) {
            if($_GET['category_id'] < 0) {
                $this->str_search = '';
            } else {
                $this->str_search .= " AND products.category_id = {$_GET['category_id']}";
            }
        }
        if (isset($_GET['categories']) && !empty($_GET['categories'])) {
            $size = count($_GET['categories']);
            $count = 0;
            $this->str_search .= " AND products.category_id IN (";
            foreach ($_GET['categories'] as $category) {
                $count++;
                if ($size == $count) {
                    $this->str_search .= "$category";
                } else {
                    $this->str_search .= "$category, ";
                }
            }
            $this->str_search .= ")";
        }
        if (isset($_GET['pr_sizes']) && !empty($_GET['pr_sizes'])) {
            $size = count($_GET['pr_sizes']);
            $count = 0;
            $this->str_search .= " AND products.size IN (";
            foreach ($_GET['pr_sizes'] as $pr_size) {
                $count++;
                if ($size == $count) {
                    $this->str_search .= "$pr_size";
                } else {
                    $this->str_search .= "$pr_size, ";
                }
            }
            $this->str_search .= ")";
        }

        if (isset($_GET['min-price'])) {
            $min = is_numeric($_GET['min-price']) ? $_GET['min-price'] : 0;
            $this->str_search .= " AND price >= $min";
        }

        if (isset($_GET['max-price'])) {
            $max = is_numeric($_GET['max-price']) ? $_GET['max-price'] : '1E100';
            $this->str_search .= " AND price <= $max";
        }
    }

    /**
     * Lấy thông tin của sản phẩm đang có trên hệ thống
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getAllByPaginate($start = 0, $limit = 1000000): array
    {
        $obj_select = $this->connection
            ->prepare("SELECT * FROM products 
                        WHERE TRUE $this->str_search
                        ORDER BY updated_at DESC
                        LIMIT $start, $limit
                        ");

        $arr_select = [];
        $obj_select->execute($arr_select);
        return $obj_select->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllNameByPaginate($start, $limit): array
    {
        $obj_select = $this->connection
            ->prepare("SELECT Distinct name, category_id FROM products 
                        WHERE TRUE $this->str_search
                        LIMIT $start, $limit
                        ");

        $arr_select = [];
        $obj_select->execute($arr_select);
        return $obj_select->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBestSelling($start, $limit): array
    {
        $obj_select = $this->connection
            ->prepare("SELECT name, category_id
                            FROM (SELECT product_id, SUM(quantity) quantity FROM order_details
                                    GROUP BY product_id
                                    ORDER BY quantity DESC
                                    LIMIT 12 ) AS a INNER JOIN products ON products.id = a.product_id
                            LIMIT $start, $limit
                        ");
        $arr_select = [];
        $obj_select->execute($arr_select);
        return $obj_select->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countTotalBestSelling()
    {
        $obj_select = $this->connection
            ->prepare("SELECT count(*)
                            FROM (SELECT product_id, SUM(quantity) quantity FROM order_details
                            GROUP BY product_id
                            ORDER BY quantity DESC
                            LIMIT 12 ) AS a");
        $obj_select->execute();

        return $obj_select->fetchColumn();
    }

    public function getMinMaxPrice($name, $category_id)
    {
        $obj_select = $this->connection
            ->prepare("SELECT MIN(price) as min, MAX(price) as max FROM products WHERE name =:name AND category_id = :category_id");

        $arr_select = [
            ':name' => $name,
            ':category_id' => $category_id,
        ];
        $obj_select->execute($arr_select);
        return $obj_select->fetch(PDO::FETCH_ASSOC);
    }

    public function getFirstImage($name, $category_id)
    {
        $obj_select = $this->connection
            ->prepare("SELECT image FROM products WHERE name =:name AND category_id = :category_id AND image != '' LIMIT 1");

        $arr_select = [
            ':name' => $name,
            ':category_id' => $category_id,
        ];
        $obj_select->execute($arr_select);
        return $obj_select->fetch(PDO::FETCH_ASSOC);
    }
    // Lấy ra tất cả sp theo catParent phục vụ sản phẩm cùng loại
    public function getAllByCatParent($category_id, $start = 0, $limit = 1000000000): array
    {
        $obj_select = $this->connection
            ->prepare("SELECT DISTINCT name, category_id FROM products
                                WHERE category_id IN (
                                    SELECT id FROM categories 
                                    WHERE parent_cat = (
                                        SELECT id FROM categories
                                        WHERE parent_cat = 0 AND id = :category_id)
                                    )   
                                LIMIT $start, $limit
                        ");
        $arr_select = [
            ':category_id' => $category_id,
        ];
        $obj_select->execute($arr_select);
        return $obj_select->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllByCatChild($category_id, $start = 0, $limit = 1000000000): array
    {
        $obj_select = $this->connection
            ->prepare("SELECT DISTINCT name, category_id FROM products
                                WHERE category_id = (
                                    SELECT id FROM categories 
                                    WHERE parent_cat != 0
                                    AND id = :category_id
                                )
                                LIMIT $start, $limit
                        ");
        $arr_select = [
            ':category_id' => $category_id,
        ];
        $obj_select->execute($arr_select);
        return $obj_select->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllByCatId($category_id, $start = 0, $limit = 1000000000): array
    {
        $obj_select = $this->connection
            ->prepare("SELECT * 
                        FROM products
                        WHERE category_id = :category_id
                        ORDER BY products.updated_at DESC
                        LIMIT $start, $limit
                        ");
        $arr_select = [
            ':category_id' => $category_id,
        ];
        $obj_select->execute($arr_select);
        return $obj_select->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countTotal()
    {
        $obj_select = $this->connection->prepare("SELECT COUNT(*) FROM products WHERE TRUE $this->str_search");
        $obj_select->execute();

        return $obj_select->fetchColumn();
    }

    /**
     * Tính tổng số bản ghi đang có trong bảng products
     * @return mixed
     */
    public function countTotalProduct()
    {
        $obj_select = $this->connection->prepare("SELECT COUNT(*) FROM (SELECT Distinct name, category_id FROM products WHERE TRUE $this->str_search) s");
        $obj_select->execute();

        return $obj_select->fetchColumn();
    }

    /**
     * @return mixed
     */
    public function checkExists($name, $category_id, $color, $size, $id = -10000)
    {
        $obj_select = $this->connection
            ->prepare("SELECT * FROM products 
                WHERE name=:name AND category_id=:category_id AND color=:color AND size=:size AND id!=:id");
        $data = [
            ':id' => $id,
            ':color' => $color,
            ':size' => $size,
            ':name' => $name,
            ':category_id' => $category_id,
        ];
        $obj_select->execute($data);
        return $obj_select->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Insert dữ liệu vào bảng products
     * @return bool
     */
    public function insert(): bool
    {
        $obj_insert = $this->connection
            ->prepare("INSERT INTO products(category_id, name, image, cost, price, color, size, amount, description) 
                                VALUES (:category_id, :name, :image, :cost, :price, :color, :size, :amount, :description)");
        $arr_insert = [
            ':category_id' => $this->category_id,
            ':name' => $this->name,
            ':image' => $this->image,
            ':cost' => $this->cost,
            ':price' => $this->price,
            ':color' => $this->color,
            ':size' => $this->size,
            ':amount' => $this->amount,
            ':description' => $this->description,
        ];
        return $obj_insert->execute($arr_insert);
    }

    /**
     * Lấy thông tin sản phẩm theo id
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        $obj_select = $this->connection
            ->prepare("SELECT products.*, categories.name AS category_name FROM products 
          INNER JOIN categories ON products.category_id = categories.id WHERE products.id = $id");

        $obj_select->execute();
        return $obj_select->fetch(PDO::FETCH_ASSOC);
    }

    public function getByNameAndCatId($name, $category_id) {
        $obj_select = $this->connection
            ->prepare("SELECT * FROM products 
                            WHERE name = :name AND category_id = :category_id ORDER BY updated_at DESC");
        $arr = [
            ':name' => $name,
            ':category_id' => $category_id,
        ];
        $obj_select->execute($arr);
        return $obj_select->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id): bool
    {
        $sql_update = "UPDATE products 
                        SET category_id=:category_id, name=:name, image=:image, color=:color, size=:size, cost=:cost, 
                            price=:price, amount=:amount, description=:description, updated_at=:updated_at
                        WHERE id=:id";
        $obj_update = $this->connection->prepare($sql_update);
        $arr_update = [
            ':id' => $id,
            ':category_id' => $this->category_id,
            ':name' => $this->name,
            ':image' => $this->image,
            ':cost' => $this->cost,
            ':price' => $this->price,
            ':color' => $this->color,
            ':size' => $this->size,
            ':amount' => $this->amount,
            ':description' => $this->description,
            ':updated_at' => $this->updated_at,
        ];
        return $obj_update->execute($arr_update);
    }

    public function updateAmount($id, $amount): bool
    {
        $obj_update = $this->connection->prepare("UPDATE products SET amount=:amount WHERE id=:id");
        $arr_update = [
            ':id' => $id,
            ':amount' => $amount,
        ];
        return $obj_update->execute($arr_update);
    }

    public function delete($id): bool
    {
        $obj_delete = $this->connection
            ->prepare("DELETE FROM products WHERE id = $id");
        return $obj_delete->execute();
    }

    public function getSize(): array
    {
        $obj_select = $this->connection
            ->prepare("SELECT DISTINCT(size) FROM products ORDER BY size");

        $obj_select->execute();
        return $obj_select->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSizeByProductId($name, $category_id): array
    {
        $obj_select = $this->connection
            ->prepare("SELECT DISTINCT(size) FROM products WHERE name=:name AND category_id=:category_id ORDER BY size");
        $arr = [
            ':name' => $name,
            ':category_id' => $category_id,
        ];
        $obj_select->execute($arr);
        return $obj_select->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getImageByProductId($name, $category_id): array
    {
        $obj_select = $this->connection
            ->prepare("SELECT image FROM products WHERE name=:name AND category_id=:category_id AND image != '' LIMIT 0,4");
        $arr = [
            ':name' => $name,
            ':category_id' => $category_id,
        ];
        $obj_select->execute($arr);
        return $obj_select->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMaxAmount($name, $category_id): array
    {
        $obj_select = $this->connection
            ->prepare("SELECT MAX(amount) maxAmount FROM products WHERE name=:name AND category_id=:category_id");
        $arr = [
            ':name' => $name,
            ':category_id' => $category_id,
        ];
        $obj_select->execute($arr);
        return $obj_select->fetch(PDO::FETCH_ASSOC);
    }

    public function getColorByProductId($name, $category_id): array
    {
        $obj_select = $this->connection
            ->prepare("SELECT DISTINCT(color) FROM products WHERE name=:name AND category_id=:category_id ORDER BY color");
        $arr = [
            ':name' => $name,
            ':category_id' => $category_id,
        ];
        $obj_select->execute($arr);
        return $obj_select->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPrice($name, $category_id, $size, $color)
    {
        $obj_select = $this->connection
            ->prepare("SELECT price FROM products WHERE name=:name AND category_id=:category_id AND size=:size AND color=:color");
        $arr = [
            ':name' => $name,
            ':category_id' => $category_id,
            ':size' => $size,
            ':color' => $color,
        ];
        $obj_select->execute($arr);

        return $obj_select->fetch(PDO::FETCH_ASSOC);
    }

    public function checkOrder($product_id) {
        $obj_select = $this->connection
            ->prepare("SELECT count(*) FROM order_details WHERE product_id = :product_id");
        $arr = [
            ':product_id' => $product_id,
        ];
        $obj_select->execute($arr);

        return $obj_select->fetchColumn();
    }
}