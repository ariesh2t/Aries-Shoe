<?php
require_once 'controllers/Controller.php';
require_once 'models/Product.php';
require_once 'models/Category.php';
require_once 'helpers/Helper.php';

class ProductAdminController extends Controller
{
    public function index()
    {
        $this->title = "Danh sách sản phẩm";
        $product_model = new Product();
        $amount = $product_model->countTotal();
        $category_model = new Category();
        $categories = $category_model->getAllCatChild();

        // Phân trang
        $current_page = $_GET['page'] ?? 1;
        $limit = 5;
        $total_page = ceil($amount / $limit);
        if ($current_page > $total_page){
            $current_page = $total_page;
        } elseif ($current_page < 1){
            $current_page = 1;
        }
        $start = ($current_page - 1) * $limit >= 0 ? ($current_page - 1) * $limit : 0;
        $products = $product_model->getAllByPaginate($start, $limit);
        $this->content = $this->render('views/admin/products/index.php', [
            //truyền biến $categories ra vew
            'products' => $products,
            'categories' => $categories,
            'total_page' => $total_page,
            'current_page' => $current_page,
            'title' => $this->title,
        ]);

        //gọi layout để nhúng thuộc tính $this->content
        require_once 'views/adminLayouts/main.php';
    }

    public function create()
    {
        $this->title = "Thêm sản phẩm mới";
        $category_model = new Category();
        //xử lý submit form
        if (isset($_POST['submit'])) {
            $category_id = $_POST['category_id'];
            $name = htmlspecialchars(trim($_POST['name']));
            $color = $_POST['color'];
            $size = $_POST['size'];
            $cost = $_POST['cost'];
            $price = $_POST['price'];
            $amount = $_POST['amount'];
            $description = $_POST['description'];

            $product_model = new Product();
            $product = $product_model->checkExists($name, $category_id, $color, $size);
            //xử lý validate
            if (!empty($product)) {
                $this->error['error'] = 'Sản phẩm đã có trong hệ thống.';
            }
            if (empty($category_model->getCategoryById($category_id))) {
                $this->error['category'] = 'Danh mục không tồn tại.';
            } elseif ($category_model->isParent($category_id)) {
                $this->error['category'] = 'Đây không phải danh mục con.';
            }
            if (empty($name)) {
                $this->error['name'] = 'Chưa nhập tên sản phẩm.';
            } elseif (strlen($name) > 255) {
                $this->error['name'] = 'Tên sản phẩm quá dài (>255 ký tự)';
            }
            if (empty($size)) {
                $this->error['size'] = "Size không được để trống.";
            } elseif (!is_numeric($size) || $size < 0) {
                $this->error['size'] = "Size phải là số lớn hơn 0.";
            }
            if (empty($price)) {
                $this->error['price'] = "Giá bán không được để trống.";
            } elseif (!is_numeric($price) || $price < 0) {
                $this->error['price'] = "Giá bán phải là số lớn hơn 0.";
            }
            if (empty($cost)) {
                $this->error['cost'] = "Giá nhập không được để trống.";
            } elseif (!is_numeric($cost) || $cost < 0) {
                $this->error['cost'] = "Giá nhập phải là số lớn hơn 0.";
            }
            if (empty($amount)) {
                $this->error['amount'] = "Số lượng nhập không được để trống.";
            } elseif (!is_numeric($amount) || $amount < 0) {
                $this->error['amount'] = "Số lượng nhập phải là số lớn hơn 0.";
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

            //nếu ko có lỗi thì tiến hành save dữ liệu
            if (empty($this->error)) {
                $filename = '';
                //xử lý upload file nếu có
                if ($_FILES['image']['error'] == 0) {
                    $dir_uploads = 'assets/uploads';
                    if (!file_exists($dir_uploads)) {
                        mkdir($dir_uploads);
                    }
                    //tạo tên file theo 1 chuỗi ngẫu nhiên để tránh upload file trùng lặp
                    $filename = time() . '-product-' . Helper::create_slug($name) . '.' . $extension;
                    move_uploaded_file($_FILES['image']['tmp_name'], $dir_uploads . '/' . $filename);
                }

                //save dữ liệu vào bảng products
                $product_model->category_id = $category_id;
                $product_model->name = $name;
                $product_model->image = $filename;
                $product_model->cost = $cost;
                $product_model->price = $price;
                $product_model->color = $color;
                $product_model->size = $size;
                $product_model->amount = $amount;
                $product_model->description = $description;
                $is_insert = $product_model->insert();
                if ($is_insert) {
                    $_SESSION['success'] = 'Thêm mới sản phẩm thành công';
                } else {
                    $_SESSION['error'] = 'Thêm mới sản phẩm thất bại';
                }
                header('Location: index.php?controller=productAdmin');
                exit();
            }
        }
        //lấy danh sách category đang có trên hệ thống để phục vụ cho search
        $categories = $category_model->getAllCatChild();

        $this->content = $this->render('views/admin/products/create.php', [
            'categories' => $categories,
            'title' => $this->title,
        ]);
        require_once 'views/adminLayouts/main.php';
    }

    public function detail()
    {
        $this->title = "Xem thông tin sản phẩm";
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: index.php?controller=productAdmin');
            exit();
        }

        $id = $_GET['id'];
        $product_model = new Product();
        $product = $product_model->getById($id);
        if (empty($product)) {
            $_SESSION['error'] = "Không tồn tại sản phẩm #$id";
            header('Location: index.php?controller=productAdmin');
            exit();
        }

        $this->content = $this->render('views/admin/products/detail.php', [
            'product' => $product,
            'title' => $this->title,
        ]);
        require_once 'views/adminLayouts/main.php';
    }

    public function update()
    {
        $this->title = "Chỉnh sửa chi tiết sản phẩm";
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: index.php?controller=productAdmin');
            exit();
        }

        $id = $_GET['id'];
        $product_model = new Product();
        $category_model = new Category();
        $product = $product_model->getById($id);
        if (empty($product)) {
            $_SESSION['error'] = "Không tồn tại sản phẩm #$id";
            header('Location: index.php?controller=productAdmin');
            exit();
        }
        //xử lý submit form
        if (isset($_POST['submit'])) {
            $category_id = $_POST['category_id'];
            $name = htmlspecialchars(trim($_POST['name']));
            $color = $_POST['color'];
            $size = $_POST['size'];
            $cost = $_POST['cost'];
            $price = $_POST['price'];
            $amount = $_POST['amount'];
            $description = $_POST['description'];

            $productCheck = $product_model->checkExists($name, $category_id, $color, $size, $id);

            //xử lý validate
            if (empty($category_model->getCategoryById($category_id))) {
                $this->error['category'] = 'Danh mục không tồn tại.';
            } elseif ($category_model->isParent($category_id)) {
                $this->error['category'] = 'Đây không phải danh mục con.';
            }
            if (!empty($productCheck)) {
                $this->error['error'] = 'Sản phẩm đã có trong hệ thống.';
            }
            if (empty($name)) {
                $this->error['name'] = 'Chưa nhập tên sản phẩm.';
            } elseif (strlen($name) > 255) {
                $this->error['name'] = 'Tên sản phẩm quá dài (>255 ký tự)';
            }
            if (empty($size)) {
                $this->error['size'] = "Size không được để trống.";
            } elseif (!is_numeric($size) || $size < 0) {
                $this->error['size'] = "Size phải là số lớn hơn 0.";
            }
            if (empty($price)) {
                $this->error['price'] = "Giá bán không được để trống.";
            } elseif (!is_numeric($price) || $price < 0) {
                $this->error['price'] = "Giá bán phải là số lớn hơn 0.";
            }
            if (empty($cost)) {
                $this->error['cost'] = "Giá nhập không được để trống.";
            } elseif (!is_numeric($cost) || $cost < 0) {
                $this->error['cost'] = "Giá nhập phải là số lớn hơn 0.";
            }
            if (empty($amount)) {
                $this->error['amount'] = "Số lượng nhập không được để trống.";
            } elseif (!is_numeric($amount) || $amount < 0) {
                $this->error['amount'] = "Số lượng nhập phải là số lớn hơn 0.";
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
                $filename = $product['image'];
                //xử lý upload file nếu có
                if ($_FILES['image']['error'] == 0) {
                    $dir_uploads = 'assets/uploads';
                    @unlink($dir_uploads . '/' . $filename);
                    if (!file_exists($dir_uploads)) {
                        mkdir($dir_uploads);
                    }
                    //tạo tên file theo 1 chuỗi ngẫu nhiên để tránh upload file trùng lặp
                    $filename = time() . '-product-' . Helper::create_slug($name) . '.' . $extension;
                    move_uploaded_file($_FILES['image']['tmp_name'], $dir_uploads . '/' . $filename);
                }
                //save dữ liệu vào bảng products
                $product_model->category_id = $category_id;
                $product_model->name = $name;
                $product_model->image = $filename;
                $product_model->color = $color;
                $product_model->size = $size;
                $product_model->cost = $cost;
                $product_model->price = $price;
                $product_model->amount = $amount;
                $product_model->description = $description;
                $product_model->updated_at = date('Y-m-d H:i:s');

                $is_update = $product_model->update($id);
                if ($is_update) {
                    $_SESSION['success'] = 'Update dữ liệu thành công';
                } else {
                    $_SESSION['error'] = 'Update dữ liệu thất bại';
                }
                header('Location: index.php?controller=productAdmin');
                exit();
            }
        }
        $categories = $category_model->getAllCatChild();

        $this->content = $this->render('views/admin/products/update.php', [
            'categories' => $categories,
            'product' => $product,
            'title' => $this->title,
        ]);
        require_once 'views/adminLayouts/main.php';
    }

    public function delete()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: index.php?controller=productAdmin');
            exit();
        }

        $id = $_GET['id'];
        $product_model = new Product();
        $product = $product_model->getById($id);
        if (empty($product)) {
            $_SESSION['error'] = "Không tồn tại sản phẩm #$id";
            $this->error['error'] = "Không tồn tại sản phẩm #$id";
        }
        if ($product_model->checkOrder($product['id']) > 0) {
            $_SESSION['error'] = "Sản phẩm này đã có người đặt hàng. Không thể xóa";
            $this->error['error'] = "Sản phẩm này đã có người đặt hàng. Không thể xóa";
        }
        if (empty($this->error)) {
            $filename = !empty($product['image']) ? $product['image'] : '';
            $is_delete = $product_model->delete($id);
            if ($is_delete) {
                if ($filename != '') {
                    @unlink('assets/uploads/' . $filename);
                }
                $_SESSION['success'] = 'Xóa thành công';
            } else {
                $_SESSION['error'] = 'Xóa thất bại';
            }
        }
        header('Location: index.php?controller=productAdmin');
        exit();
    }
}
