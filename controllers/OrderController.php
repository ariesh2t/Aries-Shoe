<?php
require_once 'controllers/Controller.php';
require_once 'models/Product.php';
require_once 'models/Order.php';
require_once 'models/OrderStatus.php';
require_once 'models/OrderDetail.php';

class OrderController extends Controller
{
    public function history()
    {
        $user_id = $_SESSION['user']['id'];
        $this->title = "Danh sách đơn hàng";
        $order_model = new Order();
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
        $orders = $order_model->getOrderByUserId($start, $limit, $user_id);
        $this->content = $this->render('views/user/orders/history.php', [
            'orders' => $orders,
            'total_page' => $total_page,
            'current_page' => $current_page,
            'title' => $this->title,
        ]);

        //gọi layout để nhúng thuộc tính $this->content
        require_once 'views/userLayouts/main.php';
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
            header('Location: index.php?controller=order&action=history');
            exit();
        }
        $order_details = $order_detail_model->getAllProductByOrderId($id);

        $this->content = $this->render('views/user/orders/detail.php', [
            'order' => $order,
            'order_details' => $order_details,
            'title' => $this->title,
        ]);

        //gọi layout để nhúng thuộc tính $this->content
        require_once 'views/userLayouts/main.php';
    }

    public function cancelOrder() {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: index.php?controller=orderAdmin');
            exit();
        }
        $id = $_GET['id'];
        $order_model = new Order();
        $order_detail_model = new OrderDetail();
        $order = $order_model->getOrderById($id);
        $status = $order['order_status_id'];
        if (empty($order)) {
            $_SESSION['error'] = "Không tồn tại đơn hàng $id";
            $this->error['error'] = "Không tồn tại đơn hàng $id";
        }elseif ($status == 3) {
            $_SESSION['error'] = "Đơn hàng $id đang trên đường giao đến bạn. Không thể hủy";
            $this->error['error'] = "Đơn hàng $id đang trên đường giao đến bạn. Không thể hủy";
        } elseif ($status == 4 || $status == 5) {
            $_SESSION['error'] = "Không thể hủy đơn hàng đã giao hoặc đã hủy";
            $this->error['error'] = "Không thể hủy đơn hàng đã giao hoặc đã hủy";
        }
        if (empty($this->error)) {
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
            $order_model->order_status_id = 5;
            if ($order_model->update($id)) {
                $_SESSION['success'] = "Hủy đơn hàng thành công!";
            } else {
                $_SESSION['error'] = "Hủy đơn hàng thất bại!";
            }
        }
        header('Location: index.php?controller=order&action=history');
        exit();
    }
}