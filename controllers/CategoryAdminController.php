<?php
require_once 'controllers/Controller.php';
require_once 'models/Category.php';
require_once 'helpers/Helper.php';

class CategoryAdminController extends Controller
{
    public function index()
    {
        $this->title = "Danh sách category";
        $category_model = new Category();
        $amount = $category_model->countTotal();
        $parent_cats = $category_model->getAllCatParent();

        // Phân trang
        $current_page = $_GET['page'] ?? 1;
        $limit = 5;
        $total_page = ceil($amount / $limit);
        if ($current_page > $total_page){
            $current_page = $total_page;
        }
        elseif ($current_page < 1){
            $current_page = 1;
        }
        $start = ($current_page - 1) * $limit < 0 ? 0 : ($current_page - 1) * $limit;
        $categories = $category_model->getAll($start, $limit);
        $this->content = $this->render('views/admin/categories/index.php', [
            'categories' => $categories,
            'total_page' => $total_page,
            'current_page' => $current_page,
            'parent_cats' => $parent_cats,
            'title' => $this->title,
        ]);
        require_once 'views/adminLayouts/main.php';
    }

    public function create()
    {
        $this->title = "Tạo danh mục mới";
        $cat_model = new Category();
        $parent_cats = $cat_model->getAllCatParent();

        if (isset($_POST['submit'])) {
            $name = htmlspecialchars(trim($_POST['name']));
            $description = $_POST['description'];
            $parent_cat = $_POST['parent_cat'];

            if (empty($name)) {
                $this->error['name'] = 'Tên danh mục không được để trống';
            } elseif (strlen($name) > 255){
                $this->error['name'] = 'Tên danh mục quá dài (>255 ký tự)';
            } elseif ($cat_model->checkName($name)){
                $this->error['name'] = 'Tên danh mục đã tồn tại';
            }
            if (!$cat_model->isParent($parent_cat) && $parent_cat != 0 ) {
                $this->error['cat_parent'] = "Không có danh mục cha #$parent_cat";
            }
            if ($_FILES['image']['error'] == 0) {
                //validate khi có file upload lên thì bắt buộc phải là ảnh và dung lượng không quá 2 Mb
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $extension = strtolower($extension);
                $arr_extension = ['jpg', 'jpeg', 'png', 'gif'];

                $file_size_mb = $_FILES['image']['size'] / 1024 / 1024;
                //làm tròn theo đơn vị thập phân
                $file_size_mb = round($file_size_mb, 2);

                if (!in_array($extension, $arr_extension)) {
                    $this->error['image'] = 'Cần upload file định dạng ảnh jpg, jpeg, png, gif';
                } else if ($file_size_mb > 2) {
                    $this->error['image'] = 'File upload không được quá 2MB';
                }
            }

            if (empty($this->error)) {
                $filename = '';
                //xử lý upload file nếu có
                if ($_FILES['image']['error'] == 0) {
                    $dir_uploads = 'assets/uploads';
                    if (!file_exists($dir_uploads)) {
                        mkdir($dir_uploads);
                    }
                    //tạo tên file theo 1 chuỗi ngẫu nhiên để tránh upload file trùng lặp
                    $filename = time() . '-category-' . Helper::create_slug($name) . '.' . $extension;
                    move_uploaded_file($_FILES['image']['tmp_name'], $dir_uploads . '/' . $filename);
                }
                $category_model = new Category();

                $category_model->image = $filename;
                $category_model->name = $name;
                $category_model->description = $description;
                $category_model->parent_cat = $parent_cat;

                $is_insert = $category_model->insert();
                if ($is_insert) {
                    $_SESSION['success'] = 'Thêm mới thành công';
                } else {
                    $_SESSION['error'] = 'Thêm mới thất bại';
                }
                header('Location: index.php?controller=categoryAdmin');
                exit();
            }
        }

        $this->content = $this->render('views/admin/categories/create.php', [
            'parent_cats' => $parent_cats,
            'title' => $this->title,
        ]);

        require_once 'views/adminLayouts/main.php';
    }

    public function update()
    {
        $this->title = "Chỉnh sửa thông tin danh mục";
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error'] = 'ID category không hợp lệ';
            header('Location: index.php?controller=categoryAdmin&action=index');
            exit();
        }

        $id = $_GET['id'];
        $cat_model = new Category();
        $parent_cats = $cat_model->getAllCatParent();
        $category = $cat_model->getCategoryById($id);
        if (empty($category)) {
            $_SESSION['error'] = "Không tồn tại danh mục #$id.";
            header('Location: index.php?controller=categoryAdmin&action=index');
            exit();
        }

        if (isset($_POST['submit'])) {
            $name = htmlspecialchars(trim($_POST['name']));
            $description = $_POST['description'];
            $parent_cat = $_POST['parent_cat'];

            if (empty($name)) {
                $this->error['name'] = 'Tên danh mục không được để trống.';
            } elseif (strlen($name) > 255){
                $this->error['name'] = 'Tên danh mục quá dài (>255 ký tự).';
            } elseif ($cat_model->checkName($name, $id)){
                $this->error['name'] = 'Tên danh mục đã tồn tại.';
            }
            if (!$cat_model->isParent($parent_cat) && $parent_cat != 0) {
                $this->error['cat_parent'] = "Không có danh mục cha #$parent_cat";
            } elseif ($cat_model->hasChild($id) && $parent_cat != 0) {
                $this->error['cat_parent'] = "Không thể chuyển thành danh mục con vì đang chứa các danh mục con khác.";
            } elseif ($cat_model->hasProduct($id) && $parent_cat == 0) {
                $this->error['cat_parent'] = "Không thể chuyển thành danh mục cha vì đang chứa các sản phẩm.";
            } elseif ($id == $parent_cat) {
                $this->error['cat_parent'] = "Không thể chuyển thành danh mục con của chính nó.";
            }
            if ($_FILES['image']['error'] == 0) {
                //validate khi có file upload lên thì bắt buộc phải là ảnh và dung lượng không quá 2 Mb
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $extension = strtolower($extension);
                $arr_extension = ['jpg', 'jpeg', 'png', 'gif'];

                $file_size_mb = $_FILES['image']['size'] / 1024 / 1024;
                //làm tròn theo đơn vị thập phân
                $file_size_mb = round($file_size_mb, 2);

                if (!in_array($extension, $arr_extension)) {
                    $this->error['image'] = 'Cần upload file định dạng ảnh jpg, jpeg, png, gif';
                } elseif ($file_size_mb > 2) {
                    $this->error['image'] = 'File upload không được quá 2MB';
                }
            }

            if (empty($this->error)) {
                $filename = $category['image'];
                //xử lý upload file nếu có
                if ($_FILES['image']['error'] == 0) {
                    $dir_uploads = 'assets/uploads';
                    //xóa file cũ, thêm @ vào trước hàm unlink để tránh báo lỗi khi xóa file ko tồn tại
                    @unlink($dir_uploads . '/' . $filename);
                    if (!file_exists($dir_uploads)) {
                        mkdir($dir_uploads);
                    }
                    //tạo tên file theo 1 chuỗi ngẫu nhiên để tránh upload file trùng lặp
                    $filename = time() . '-category-' . Helper::create_slug($name) . '.' . $extension;
                    move_uploaded_file($_FILES['image']['tmp_name'], $dir_uploads . '/' . $filename);
                }
                $category_model = new Category();
                $category_model->image = $filename;
                $category_model->name = $name;
                $category_model->description = $description;
                $category_model->parent_cat = $parent_cat;
                $is_update = $category_model->update($id);
                if ($is_update) {
                    $_SESSION['success'] = 'Cập nhật thành công';
                } else {
                    $_SESSION['error'] = 'Cập nhật thất bại';
                }
                header('Location: index.php?controller=categoryAdmin');
                exit();
            }
        }

        $this->content = $this->render('views/admin/categories/update.php', [
            'parent_cats' => $parent_cats,
            'category' => $category,
            'title' => $this->title,
        ]);

        require_once 'views/adminLayouts/main.php';
    }

    public function delete()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: index.php?controller=categoryAdmin&action=index');
            exit();
        }
        $id = $_GET['id'];
        $category_model = new Category();
        $category = $category_model->getCategoryById($id);
        if (empty($category)) {
            $_SESSION['error'] = "Không tồn tại danh mục #$id";
            $this->error['error'] = "Không tồn tại danh mục #$id";
        } elseif ($category_model->hasChild($id)) {
            $_SESSION['error'] = "Không thể xóa danh mục này vì đang chứa các danh mục con khác.";
            $$this->error['error'] = "Không thể xóa danh mục này vì đang chứa các danh mục con khác.";
        } elseif ($category_model->hasProduct($id)) {
            $_SESSION['error'] = "Danh mục đã có sản phẩm nên không thể xóa.";
            $this->error['error'] = "Danh mục đã có sản phẩm nên không thể xóa.";
        }

        if (empty($this->error)) {
            $filename = !empty($category['image']) ? $category['image'] : '';
            $is_delete = $category_model->delete($id);
            if ($is_delete) {
                if ($filename != '') {
                    @unlink('assets/uploads/' . $filename);
                }
                $_SESSION['success'] = 'Xóa thành công';
            } else {
                $_SESSION['error'] = 'Xóa thất bại';
            }
        }
        header('Location: index.php?controller=categoryAdmin&action=index');
        exit();
    }

    public function detail()
    {
        $this->title = "Thông tin chi tiết danh mục";
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: index.php?controller=categoryAdmin&action=index');
            exit();
        }
        $id = $_GET['id'];
        $category_model = new Category();
        $category = $category_model->getCategoryById($id);
        if (empty($category)) {
            $_SESSION['error'] = "Không tồn tại danh mục #$id";
            header('Location: index.php?controller=categoryAdmin&action=index');
            exit();
        }
        if (empty($this->error)) {
            $this->content = $this->render('views/admin/categories/detail.php', [
                'category' => $category,
                'title' => $this->title,
            ]);
            require_once 'views/adminLayouts/main.php';
        } else {
            header('Location: index.php?controller=categoryAdmin&action=index');
            exit();
        }
    }
}
