<?php
require_once 'controllers/Controller.php';
require_once 'models/User.php';
require_once 'models/Role.php';
class UserAdminController extends Controller {
    public function index()
    {
        $this->title = "Danh sách tài khoản";
        $user_model = new User();
        $role_model = new Role();
        $amount = $user_model->getTotal();

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

        $users = $user_model->getAll($start, $limit);
        $roles = $role_model->getAll();
        $this->content = $this->render('views/admin/users/index.php', [
            'users' => $users,
            'roles' => $roles,
            'total_page' => $total_page,
            'current_page' => $current_page,
            'title' => $this->title,
        ]);

        //gọi layout để nhúng thuộc tính $this->content
        require_once 'views/adminLayouts/main.php';
    }

    public function create() {
        $this->title = "Tạo tài khoản admin";
        $user_model = new User();
        if (isset($_POST['submit'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];
            $count_user = $user_model->getUserByUsername($username);
            //xử lý validate
            if (empty($username)) {
                $this->error['username'] = 'Username không được để trống';
            } elseif (strlen($username) > 255) {
                $this->error['username'] = "Username quá dài (>255 ký tự)";
            } elseif (!preg_match("/^[a-zA-z0-9]*$/", $username) ) {
                $this->error['username'] = "Tên tài khoản không chứa ký tự đặc biệt!";
            } elseif ($count_user>0) {
                $this->error['username'] = 'Username này đã tồn tại trong CSDL';
            }
            if (empty($password)) {
                $this->error['password'] = "Chưa nhập mật khẩu.";
            } elseif (strlen($password) > 20) {
                $this->error['password'] = 'Mật khẩu dài quá 20 ký tự!';
            } elseif (strlen($password) < 8) {
                $this->error['password'] = 'Mật khẩu phải có ít nhất 8 ký tự!';
            }
            if ($password != $password_confirm) {
                $this->error['password_confirm'] = 'Mật khẩu xác nhận không khớp.';
            }

            //xủ lý lưu dữ liệu khi biến error rỗng
            if (empty($this->error)) {
                $user_model->username = $username;
                //lưu password dưới dạng mã hóa, hiện tại sử dụng cơ chế md5
                $user_model->pswd = password_hash($password, PASSWORD_BCRYPT);
                $is_insert = $user_model->insertAdmin();
                if ($is_insert) {
                    $_SESSION['success'] = 'Tạo tài khoản admin mới thành công.';
                } else {
                    $_SESSION['error'] = 'Tạo tài khoản admin thất bại.';
                }
                header('Location: index.php?controller=userAdmin');
                exit();
            }
        }

        $this->content = $this->render('views/admin/users/create.php', [
            'title' => $this->title,
        ]);

        require_once 'views/adminLayouts/main.php';
    }

    public function updateStatus() {
        $this->title = "Cập nhật trạng thái";
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header("Location: index.php?controller=userAdmin");
            exit();
        }
        $id = $_GET['id'];
        $user_model = new User();
        $user = $user_model->getById($id);
        if ($id == $_SESSION['admin']['id']) {
            $_SESSION['error'] = "Không thể tự cập nhật trạng thái của mình.";
            header('Location: index.php?controller=userAdmin');
            exit();
        } else {
            if (isset($_POST['submit'])) {
                $status = $_POST['status'];

                if ($status != 'true' && $status != 'false') {
                    $this->error['status'] = "Không có trạng thái này.";
                }

                if (empty($this->error)) {
                    $user_model->status = $status == 'true' ? 1 : 0;
                    $user_model->updated_at = date('Y-m-d H:i:s');

                    $is_update = $user_model->updateStatus($id);

                    if ($is_update) {
                        $_SESSION['success'] = "Cập nhật trạng thái user #$id thành công";
                    } else {
                        $_SESSION['error'] = "Cập nhật trạng thái user #$id thất bại";
                    }
                    header('Location: index.php?controller=userAdmin');
                    exit();
                }
            }
        }
        $this->content = $this->render('views/admin/users/updateStatus.php', [
            'user' => $user,
            'title' => $this->title,
        ]);

        require_once 'views/adminLayouts/main.php';
    }

    public function delete() {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: index.php?controller=userAdmin');
            exit();
        }

        $id = $_GET['id'];
        $user_model = new User();
        $is_delete = $user_model->delete($id);
        if ($is_delete) {
            $_SESSION['success'] = 'Xóa dữ liệu thành công';
        } else {
            $_SESSION['error'] = 'Xóa dữ liệu thất bại';
        }
        header('Location: index.php?controller=userAdmin');
        exit();
    }

    public function detail() {
        $this->title = "Thông tin tài khoản";
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header("Location: index.php?controller=userAdmin");
            exit();
        }
        $id = $_GET['id'];
        $user_model = new User();
        $user = $user_model->getById($id);
        if (empty($user)) {
            $_SESSION['error']= "Không tồn tại user #$id";
            header("Location: index.php?controller=userAdmin");
            exit();
        }

        $this->content = $this->render('views/admin/users/detail.php', [
            'user' => $user,
            'title' => $this->title,
        ]);

        require_once 'views/adminLayouts/main.php';
    }

    public function logout() {

//        session_destroy();
        $_SESSION = [];
        session_destroy();
//        unset($_SESSION['user']);
        $_SESSION['success'] = 'Đăng xuất thành công';
        header('Location: index.php?controller=login&action=login');
        exit();
    }

    public function profile() {
        $this->title = "Thông tin cá nhân";
        if (!isset($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] != $_SESSION['admin']['id']) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header("Location: index.php?controller=homeAdmin");
            exit();
        }
        $id = $_GET['id'];
        $user_model = new User();
        $user = $user_model->getById($id);
        $this->content = $this->render('views/admin/users/profile.php', [
            'user' => $user,
            'title' => $this->title,
        ]);

        require_once 'views/adminLayouts/main.php';
    }

    public function updateProfile() {
        $this->title = "Cập nhật thông tin cá nhân";
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header("Location: index.php?controller=homeAdmin");
            exit();
        }
        $id = $_GET['id'];
        if ($id != $_SESSION['admin']['id']) {
            $_SESSION['error'] = 'Không thể chỉnh sửa thông tin cá nhân của user khác.';
            header("Location: index.php?controller=homeAdmin");
            exit();
        }
        $user_model = new User();
        $user = $user_model->getById($id);

        if (isset($_POST['submit'])) {
            $fullname = htmlspecialchars($_POST['fullname']);
            $address = htmlspecialchars($_POST['address']);
            $phone = $_POST['phone'];
            $username = htmlspecialchars(trim($_POST['username']));
            $count_user = $user_model->getUserByUsername($username, $id);

            //xử lý validate
            if (empty($username)) {
                $this->error['username'] = "Username không được để trống.";
            } elseif ($count_user) {
                $this->error['username'] = 'Username này đã tồn tại trong CSDL';
            } elseif (strlen($username) > 255) {
                $this->error['username'] = "Username quá dài (>255 ký tự)";
            } elseif (!preg_match("/^[a-zA-z0-9]*$/", $username) ) {
                $this->error['username'] = "Username không chứa ký tự đặc biệt!";
            }
            if (!empty($fullname) && (strlen($fullname) > 255 || strlen($fullname) < 5)) {
                $this->error['fullname'] = "Tên phải lớn hơn 5 ký tự và nhỏ hơn 255 ký tự.";
            }
            if (!empty($phone) && !preg_match('/(0[3|5|7|8|9])+([0-9]{8})\b/', $phone)) {
                $this->error['phone'] = "Nhập sai số điện thoại Việt Nam.";
            }
            if (empty($_POST['password']) && !empty($_POST['new-pswd'])) {
                $this->error['password'] = "Chưa nhập mật khẩu.";
            } elseif (!empty($_POST['password']) && !password_verify($_POST['password'], $user['pswd'])) {
                $this->error['password'] = "Sai mật khẩu.";
            } elseif (strlen($_POST['new-pswd']) > 20) {
                $this->error['new-pswd'] = 'Mật khẩu dài quá 20 ký tự!';
            } elseif (!empty($_POST['new-pswd']) && strlen($_POST['new-pswd']) < 8) {
                $this->error['new-pswd'] = 'Mật khẩu phải có ít nhất 8 ký tự!';
            } elseif ($_POST['new-pswd'] != $_POST['password_confirm']) {
                $this->error['password_confirm'] = "Mật khẩu không khớp.";
            } elseif (!empty($_POST['password']) && empty($_POST['new-pswd'])) {
                $this->error['new-pswd'] = "Mật khẩu mới không được để trống.";
            }
            if ($_FILES['avatar']['error'] == 0) {
                $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $extension = strtolower($extension);
                $allow_extensions = ['png', 'jpg', 'jpeg', 'gif'];
                $file_size_mb = $_FILES['avatar']['size'] / 1024 / 1024;
                $file_size_mb = round($file_size_mb, 2);
                if (!in_array($extension, $allow_extensions)) {
                    $this->error['avatar'] = 'Phải upload avatar dạng ảnh';
                } else if ($file_size_mb > 2) {
                    $this->error['avatar'] = 'File upload không được lớn hơn 2Mb';
                }
            }

            //xủ lý lưu dữ liệu khi biến error rỗng
            if (empty($this->error)) {
                $filename = $user['avatar'];
                //xử lý upload ảnh nếu có
                if ($_FILES['avatar']['error'] == 0) {
                    $dir_uploads = 'assets/uploads';
                    //xóa file ảnh đã update trc đó
                    @unlink($dir_uploads . '/' . $filename);
                    if (!file_exists($dir_uploads)) {
                        mkdir($dir_uploads);
                    }

                    $filename = time() . '-user-' . $this->create_slug($fullname) . '.' . $extension;
                    move_uploaded_file($_FILES['avatar']['tmp_name'], $dir_uploads . '/' . $filename);
                }
                //lưu password dưới dạng mã hóa, hiện tại sử dụng cơ chế md5
                $user_model->fullname = $fullname;
                $user_model->username = $username;
                $user_model->phone = $phone;
                $user_model->address = $address;
                $user_model->avatar = $filename;
                $user_model->pswd = !empty($_POST['password']) ? password_hash($_POST['new-pswd'], PASSWORD_BCRYPT) : $user['pswd'];
                $user_model->updated_at = date('Y-m-d H:i:s');
                $is_update = $user_model->update($id);
                if ($is_update) {
                    $_SESSION['admin'] = $user_model->getById($id);
                    $_SESSION['success'] = 'Update profile thành công';
                } else {
                    $_SESSION['error'] = 'Update profile thất bại';
                }
                header("Location: index.php?controller=userAdmin&action=profile&id=$id");
                exit();
            }
        }

        $this->content = $this->render("views/admin/users/updateProfile.php", [
            'user' => $user,
            'title' => $this->title,
        ]);

        require_once 'views/adminLayouts/main.php';
    }

    public function create_slug($string): string
    {
        $search = array(
            '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
            '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
            '#(ì|í|ị|ỉ|ĩ)#',
            '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
            '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
            '#(ỳ|ý|ỵ|ỷ|ỹ)#',
            '#(đ)#',
            '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
            '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
            '#(Ì|Í|Ị|Ỉ|Ĩ)#',
            '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
            '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
            '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
            '#(Đ)#',
            "/[^a-zA-Z0-9\-\_]/",
        );
        $replace = array(
            'a',
            'e',
            'i',
            'o',
            'u',
            'y',
            'd',
            'A',
            'E',
            'I',
            'O',
            'U',
            'Y',
            'D',
            '-',
        );
        $string = preg_replace($search, $replace, $string);
        $string = preg_replace('/(-)+/', '-', $string);
        $string = strtolower($string);
        return $string;
    }
}