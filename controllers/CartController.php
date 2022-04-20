<?php
require_once 'controllers/Controller.php';
require_once 'models/Product.php';
require_once 'models/Order.php';
require_once 'models/OrderDetail.php';

class CartController extends Controller
{
    public function add()
    {
        if (isset($_SESSION['user'])) {
            $name = htmlspecialchars($_GET['name']);
            $category_id = $_GET['category_id'];
            $color = $_GET['color'];
            $size = $_GET['size'];
            $quantity = round($_GET['quantity']);
            $product_model = new Product();
            $product = $product_model->checkExists($name, $category_id, $color, $size);
            $product_id = $product['id'];
            if (empty($product)) {
                echo "Trong kho đang hết hàng!";
            } elseif ($quantity > $product['amount']) {
                echo "Số lượng sản phẩm trong kho chỉ còn {$product['amount']}";
            } else {
                $product_cart = [
                    'name' => $product['name'],
                    'category_id' => $product['category_id'],
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'color' => $product['color'],
                    'size' => $product['size'],
                    'quantity' => $quantity,
                ];

                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'][$product_id] = $product_cart;
                } else {
                    // Nếu như sp đã tồn tại trong giỏ, thì update quantity
                    if (array_key_exists($product_id, $_SESSION['cart'])) {
                        $total_qtt = $_SESSION['cart'][$product_id]['quantity'] + $quantity;
                        if ($total_qtt > $product['amount']) {
                            echo "Số lượng sản phẩm trong kho chỉ còn {$product['amount']}, giỏ hàng của bạn đã có {$_SESSION['cart'][$product_id]['quantity']} đôi";
                        } else {
                            $_SESSION['cart'][$product_id]['quantity'] = $total_qtt;
                        }
                    } else {
                        $_SESSION['cart'][$product_id] = $product_cart;
                    }
                }
            }
        } else {
            echo "Bạn chưa đăng nhập!";
        }
    }

    public function index() {
        $this->content = $this->render('views/user/orders/cart.php');
        require_once "views/userLayouts/main.php";
    }

    public function delete()
    {
        $product_id = $_POST['product_id'];
        if (array_key_exists($product_id, $_SESSION['cart'])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    public function update()
    {
        if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
            $product_id = $_POST['product_id'];
            $quantity = round($_POST['quantity']);
            $cart = $_SESSION['cart'];
            $product_model = new Product();
            $product = $product_model->getById($product_id);

            if (array_key_exists($product_id, $cart)) {
                if ($quantity > 0) {
                    if ($quantity > $product['amount']) {
                        echo "Số lượng sản phẩm trong kho chỉ còn {$product['amount']}";
                    } else {
                        $current_qtt = $cart[$product_id]['quantity'];
                        $cart[$product_id] = array(
                            'name' => $cart[$product_id]['name'],
                            'category_id' => $cart[$product_id]['category_id'],
                            'price' => $cart[$product_id]['price'],
                            'image' => $cart[$product_id]['image'],
                            'color' => $cart[$product_id]['color'],
                            'size' => $cart[$product_id]['size'],
                            'quantity' => $quantity,
                        );

                        $_SESSION['cart'] = $cart;
                    }
                } else {
                    unset($_SESSION['cart'][$product_id]);
                }
            }
        }
        echo $current_qtt;
    }

    public function payment() {
        $this->title = "Đặt hàng";
        $order_model = new Order();
        $order_detail_model = new OrderDetail();
        if (isset($_POST['submit'])) {
            $fullname = htmlspecialchars($_POST['fullname']);
            $phone = htmlspecialchars($_POST['phone']);
            $address = htmlspecialchars($_POST['address']);
            $note = htmlspecialchars($_POST['note']);
            $total_price = 0;

            foreach ($_SESSION['cart'] AS $cart){
                $total_price += $cart['price'] * $cart['quantity'];
            }

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
            if (empty($address)) {
                $this->error['address'] = 'Chưa nhập địa chỉ.';
            }

            if (empty($this->error)) {
                $order_model->user_id = $_SESSION['user']['id'];
                $order_model->fullname = $fullname;
                $order_model->phone = $phone;
                $order_model->address = $address;
                $order_model->note = $note;
                $order_model->total_price = $total_price;

                $is_insert_order = $order_model->insert();
                if ($is_insert_order) {
                    $order_id = $order_model->getLastOrderId();
                    $_bool = true;
                    foreach ($_SESSION['cart'] AS $product_id => $cart) {
                        $order_detail_model->product_id = $product_id;
                        $order_detail_model->order_id = $order_id;
                        $order_detail_model->quantity = $cart['quantity'];
                        $order_detail_model->price = $cart['price'];
                        $is_insert_order_detail = $order_detail_model->insert();
                        if (!$is_insert_order_detail) {
                            $_bool = false;
                            break;
                        }
                    }
                    if ($_bool) {
                        unset($_SESSION['cart']);
                        $_SESSION['success'] = 'Đặt hàng thành công';
                        header('Location: index.php?controller=home');
                        exit();
                    }
                } else {
                    $_SESSION['error'] = 'Đặt hàng thất bại';
                }
            }
        }

        $this->content = $this->render('views/user/orders/payment.php');
        require_once "views/userLayouts/main.php";
    }
}