<?php
require_once 'controllers/Controller.php';
require_once 'models/Product.php';
require_once 'models/Category.php';

class ProductController extends Controller
{
    public function showAll()
    {
        $this->title = "Danh sách sản phẩm";
        $product_model = new Product();
        $amount = $product_model->countTotalProduct();
        $pr_sizes = $product_model->getSize();
        $category_model = new Category();
        $categories = $category_model->getAllCatChild();

        // Phân trang
        $current_page = $_GET['page'] ?? 1;
        $limit = 12;
        $total_page = ceil($amount / $limit);
        if ($current_page > $total_page){
            $current_page = $total_page;
        } elseif ($current_page < 1){
            $current_page = 1;
        }
        $start = ($current_page - 1) * $limit >= 0 ? ($current_page - 1) * $limit : 0;
        $products = $product_model->getAllNameByPaginate($start, $limit);
        $this->content = $this->render('views/user/products/showAll.php', [
            //truyền biến $categories ra vew
            'products' => $products,
            'categories' => $categories,
            'total_page' => $total_page,
            'current_page' => $current_page,
            'title' => $this->title,
            'pr_sizes' => $pr_sizes,
        ]);

        //gọi layout để nhúng thuộc tính $this->content
        require_once 'views/userLayouts/main.php';
    }

    public function detail()
    {
        if (!isset($_GET['category_id']) || !is_numeric($_GET['category_id'])) {
            $_SESSION['error'] = "Id không hợp lệ";
            header('Location: index.php?controller=home&action=index');
            exit();
        }
        $name = htmlspecialchars($_GET['name']);
        $category_id = $_GET['category_id'];
        $product_model = new Product();
        $product = $product_model->getByNameAndCatId($name, $category_id);
        if (empty($product)) {
            $_SESSION['error'] = "Không tồn tại sản phẩm #$id.";
            header('Location: index.php?controller=home&action=index');
            exit();
        }
        $sizes = $product_model->getSizeByProductId($name, $category_id);
        $images = $product_model->getImageByProductId($name, $category_id);
        $colors = $product_model->getColorByProductId($name, $category_id);
        $this->title = $product['name'];
        $this->content = $this->render('views/user/products/detail.php', [
            'title' => $this->title,
            'product' => $product,
            'sizes' => $sizes,
            'images' => $images,
            'colors' => $colors,
        ]);

        //gọi layout để nhúng thuộc tính $this->content
        require_once 'views/userLayouts/main.php';
    }

    public function getPrice() {
        $name = htmlspecialchars($_GET['name']);
        $category_id = $_GET['category_id'];
        $color = $_GET['color'];
        $size = $_GET['size'];
        $product_model = new Product();
        echo $product_model->getPrice($name, $category_id, $size, $color)['price'];
    }
}
