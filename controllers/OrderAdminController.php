<?php
require_once 'controllers/Controller.php';
require_once 'models/Product.php';
require_once 'models/Order.php';
require_once 'models/OrderStatus.php';
require_once 'models/OrderDetail.php';

class OrderAdminController extends Controller
{
    public function index()
    {
        $this->title = "Danh sách đơn hàng";
        $order_model = new Order();
        $order_status_model = new OrderStatus();
        $amount = $order_model->countTotal();
        $current_page = $_GET['page'] ?? 1;
        $limit = 5;
        $total_page = ceil($amount / $limit);
        if ($current_page > $total_page){
            $current_page = $total_page;
        } elseif ($current_page < 1){
            $current_page = 1;
        }
        $start = ($current_page - 1) * $limit >= 0 ? ($current_page - 1) * $limit : 0;
        $orders = $order_model->getAllByPaginate($start, $limit);
        $order_statuses = $order_status_model->getAll();
        $this->content = $this->render('views/admin/orders/index.php', [
            'orders' => $orders,
            'order_statuses' => $order_statuses,
            'total_page' => $total_page,
            'current_page' => $current_page,
            'title' => $this->title,
        ]);

        //gọi layout để nhúng thuộc tính $this->content
        require_once 'views/adminLayouts/main.php';
    }

    public function detail() {
        $this->title = "Chi tiết đơn hàng";
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: index.php?controller=orderAdmin');
            exit();
        }
        $id = $_GET['id'];
        $order_model = new Order();
        $order_detail_model = new OrderDetail();
        $order = $order_model->getOrderById($id);
        if (empty($order)) {
            $_SESSION['error'] = "Không tồn tại đơn hàng $id";
            header('Location: index.php?controller=orderAdmin&action=index');
            exit();
        }
        $order_details = $order_detail_model->getAllProductByOrderId($id);

        $this->content = $this->render('views/admin/orders/detail.php', [
            'order' => $order,
            'order_details' => $order_details,
            'title' => $this->title,
        ]);

        //gọi layout để nhúng thuộc tính $this->content
        require_once 'views/adminLayouts/main.php';
    }

    public function update() {
        $this->title = "Sửa trạng thái đơn hàng";
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: index.php?controller=orderAdmin');
            exit();
        }
        $id = $_GET['id'];
        $order_model = new Order();
        $order_status_model = new OrderStatus();
        $order_detail_model = new OrderDetail();
        $order = $order_model->getOrderById($id);
        if (empty($order)) {
            $_SESSION['error'] = "Không tồn tại đơn hàng $id";
            header('Location: index.php?controller=orderAdmin&action=index');
            exit();
        } elseif ($order['order_status_id'] == 4 || $order['order_status_id'] == 5) {
            $_SESSION['error'] = "Không thể cập nhật trạng thái đơn hàng đã hoàn thành hoặc đã hủy.";
            header('Location: index.php?controller=orderAdmin&action=index');
            exit();
        }
        $order_statuses = $order_status_model->getAll();

        if (isset($_POST['submit'])) {
            $order_status_id = $_POST['order_status_id'];
            if (!empty($order_status_model->getStatus($order_status_id))) {
                if ($order_status_id == 5) {
                    foreach ($order_detail_model->getAllProductByOrderId($id) as $order_product) {
                        $product_model = new Product();
                        $product = $product_model->getById($order_product['product_id']);
                        $amount = $product['amount'] + $order_product['quantity'];
                        $is_update = $product_model->updateAmount($product['id'], $amount);
                        if (!$is_update) {
                            $_SESSION['error'] = "Cập nhật thất bại!";
                            header('Location: index.php?controller=orderAdmin');
                            exit();
                        }
                    }
                }
                $order_model->order_status_id = $order_status_id;
                $order_model->update($id);
                $_SESSION['success'] = "Cập nhật đơn hàng thành công!";
            } else {
                $_SESSION['error'] = "Cập nhật thất bại!";
            }
            header('Location: index.php?controller=orderAdmin');
            exit();
        }

        $this->content = $this->render('views/admin/orders/update.php', [
            'order' => $order,
            'order_statuses' => $order_statuses,
            'title' => $this->title,
        ]);

        //gọi layout để nhúng thuộc tính $this->content
        require_once 'views/adminLayouts/main.php';
    }
}