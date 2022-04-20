<?php
require_once 'models/User.php';

class LoginController
{
    public $content;
    public $error = [];
    public $title;

    /**
     * @param $file string Đường dẫn tới file
     * @param array $variables array Danh sách các biến truyền vào file
     * @return false|string
     */
    public function render(string $file, $variables = [])
    {
        extract($variables);
        ob_start();
        require_once $file;

        return ob_get_clean();
    }

    public function login()
    {
        $this->title = "Đăng nhập";
        if (isset($_SESSION['user'])) {
            header('Location: index.php?controller=home&action=index');
            exit();
        } elseif (isset($_SESSION['admin'])) {
            header('Location: index.php?controller=homeAdmin&action=index');
            exit();
        }
        if (isset($_POST['submit'])) {
//            die;
            $username = $_POST['username'];
            $password = $_POST['password'];
            //validate
            if (empty($username)) {
                $this->error['username'] = 'Tên tài khoản không được để trống!';
            } elseif (!preg_match("/^[a-zA-z0-9]*$/", $username) ) {
                $this->error['username'] = "Tên tài khoản không chứa ký tự đặc biệt!";
            }
            if (empty($password)) {
                $this->error['password'] = 'Mật khẩu không được để trống!';
            } elseif (strlen($password) > 20) {
                $this->error['password'] = 'Mật khẩu dài quá 20 ký tự!';
            } elseif (strlen($password) < 8) {
                $this->error['password'] = 'Mật khẩu phải có ít nhất 8 ký tự!';
            }
            $user_model = new User();
            if (empty($this->error)) {
                $user = $user_model->getUser($username);
                if (empty($user)) {
                    $this->error['error'] = "Không tìm thấy tài khoản $username.";
                } elseif ($user['status']==0) {
                    $this->error['error'] = "Tài khoản của bạn đã bị khóa.";
                } else {
                    $is_same_password = password_verify($password, $user['pswd']);
                    if ($is_same_password) {
                        $_SESSION['success'] = 'Đăng nhập thành công';
                        //tạo session user để xác định user nào đang login
                        if ($user['role_id'] == 1) {
                            $_SESSION['admin'] = $user;
                            header("Location: index.php?controller=homeAdmin&action=index");
                        } else {
                            $_SESSION['user'] = $user;
                            header("Location: index.php?controller=home&action=index");
                        }
                        exit();
                    } else {
                        $this->error['error'] = 'Sai tên tài khoản hoặc mật khẩu';
                    }
                }
            }
        }
        $this->content = $this->render('views/homes/login.php');

        require_once 'views/userLayouts/main.php';
    }
}