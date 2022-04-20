<?php
class Controller
{
    public function __construct()
    {
        if (isset($_GET['controller']) && strpos($_GET['controller'], 'Admin') != false) {
            if (!isset($_SESSION['admin'])) {
                $_SESSION['error'] = "Bạn cần đăng nhập";
                header('Location: index.php?controller=login&action=login');
                exit();
            }
        }
        if (isset($_SESSION['admin']) && strpos($_GET['controller'], 'Admin') == false) {
            header('Location: index.php?controller=homeAdmin&action=index');
            exit();
        }

        if (isset($_GET['controller']) && ($_GET['controller'] == 'cart' || $_GET['controller'] == 'order' || $_GET['controller'] == 'user')) {
            if (!isset($_SESSION['user'])) {
                $_SESSION['error'] = "Bạn cần đăng nhập";
                header('Location: index.php?controller=login&action=login');
                exit();
            }
        }
    }

    public $content;
    public $error=[];
    public $title;

    /**
     * @param $file string Đường dẫn tới file
     * @param array $variables array Danh sách các biến truyền vào file
     * @return false|string
     */
    public function render(string $file, $variables = []) {
        extract($variables);
        ob_start();
        require_once $file;

        return ob_get_clean();
    }
}
