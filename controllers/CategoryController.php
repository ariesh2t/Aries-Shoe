<?php
require_once 'controllers/Controller.php';
require_once 'models/Product.php';
require_once 'models/Category.php';

class CategoryController extends Controller
{
    public function showProductByCatChild()
    {
        if (!isset($_GET['category_id']) || !is_numeric($_GET['category_id'])) {
            $_SESSION['error'] = "Id không hợp lệ";
            header('Location: index.php?controller=home&action=index');
            exit();
        }
        $id = $_GET['category_id'];
        $category_model = new Category();
        $category = $category_model->getCategoryById($id);
        if (empty($category) || $category_model->isParent($category['id'])) {
            $_SESSION['error'] = "Không tồn tại danh mục con #$id.";
            header('Location: index.php?controller=home&action=index');
            exit();
        }
        $product_model = new Product();
        $amount = $product_model->countTotalProduct();
        $pr_sizes = $product_model->getSize();

        // Phân trang
        $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $limit = 12;
        $total_page = ceil($amount / $limit);
        if ($current_page > $total_page){
            $current_page = $total_page;
        } elseif ($current_page < 1){
            $current_page = 1;
        }
        $start = ($current_page - 1) * $limit >= 0 ? ($current_page - 1) * $limit : 0;
        $products = $product_model->getAllNameByPaginate($start, $limit);

        $this->title = $category['name'];
        $this->content = $this->render('views/user/categories/showProductByCatChild.php', [
            //truyền biến $categories ra vew
            'products' => $products,
            'category' => $category,
            'total_page' => $total_page,
            'current_page' => $current_page,
            'title' => $this->title,
            'pr_sizes' => $pr_sizes,
        ]);

        //gọi layout để nhúng thuộc tính $this->content
        require_once 'views/userLayouts/main.php';
    }

    public function showProductByCatParent()
    {
        if (!isset($_GET['cat_parent']) || !is_numeric($_GET['cat_parent'])) {
            $_SESSION['error'] = "Id không hợp lệ";
            header('Location: index.php?controller=home&action=index');
            exit();
        }
        $id = $_GET['cat_parent'];
        $category_model = new Category();
        $catParents = $category_model->getAllCatParent();
        $category = $category_model->getCategoryById($id);
        if (empty($category) || !$category_model->isParent($category['id'])) {
            $_SESSION['error'] = "Không tồn tại danh mục cha #$id.";
            header('Location: index.php?controller=home&action=index');
            exit();
        }
        $catChilds = $category_model->getChildByCatParent($id);
        $this->title = $category['name'];
        $this->content = $this->render('views/user/categories/showProductByCatParent.php', [
            //truyền biến $categories ra vew
            'catChilds' => $catChilds,
            'category' => $category,
            'title' => $this->title,
            'catParents' => $catParents,
        ]);

        //gọi layout để nhúng thuộc tính $this->content
        require_once 'views/userLayouts/main.php';
    }
}