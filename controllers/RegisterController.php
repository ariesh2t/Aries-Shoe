<?php
require_once 'controllers/Controller.php';
require_once 'models/User.php';

class RegisterController extends Controller
{
    public function register() {
        $this->title = "Đăng ký tài khoản";
        $user_model = new User();
        $count_user = $user_model->getUserByUsername($username);
        if (isset($_POST['submit'])) {
            $fullname = $_POST['fullname'];
            $phone = $_POST['phone'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $password_confirm = $_POST['re-password'];
            //xử lý validate
            if (empty($fullname)) {
                $this->error['fullname'] = 'Họ tên không được để trống.';
            } elseif ((strlen($fullname) > 255 || strlen($fullname) < 5)) {
                $this->error['fullname']  = "Họ tên phải lớn hơn 5 ký tự và nhỏ hơn 255 ký tự.";
            }
            if (empty($phone)) {
                $this->error['phone']  = "Chưa nhập số điện thoại.";
            } elseif (!preg_match('/(0[3|5|7|8|9])+([0-9]{8})\b/', $phone)) {
                $this->error['phone'] = "Nhập sai số điện thoại Việt Nam.";
            }
            if (empty($username)) {
                $this->error['username'] = 'Username không được để trống';
            } elseif (strlen($username) > 255) {
                $this->error['username'] = "Username quá dài (>255 ký tự)";
            } elseif (!preg_match("/^[a-zA-z0-9]*$/", $username)) {
                $this->error['username'] = "Username không chứa ký tự đặc biệt!";
            } elseif ($count_user > 0) {
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
                $this->error['re-password'] = 'Mật khẩu xác nhận không khớp.';
            } elseif (empty($password_confirm)) {
                $this->error['re-password'] = "Chưa nhập mật khẩu.";
            }
//
            //xủ lý lưu dữ liệu khi biến error rỗng
            if (empty($this->error)) {
                $user_model->username = $username;
                $user_model->fullname = $fullname;
                $user_model->phone = $phone;
                //lưu password dưới dạng mã hóa, hiện tại sử dụng cơ chế md5
                $user_model->pswd = password_hash($password, PASSWORD_BCRYPT);
                $is_insert = $user_model->createUser();
                if ($is_insert) {
                    $_SESSION['success'] = 'Đăng ký tài khoản thành công.';
                } else {
                    $_SESSION['error'] = 'Đăng ký tài khoản thất bại.';
                }
                header('Location: index.php?controller=login&action=login');
                exit();
            }
        }

        $this->content = $this->render('views/user/register.php', [
            'title' => $this->title,
        ]);

        require_once 'views/userLayouts/main.php';
    }
}