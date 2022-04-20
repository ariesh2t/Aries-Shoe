<?php
    require_once 'models/Category.php';
    require_once 'models/Product.php';
    $category_model = new Category();
    $product_model = new Product();
?>
<div class="timeline-items container">
    <h2 class="my-5">Giỏ hàng của bạn</h2>
    <?php if (!empty($_SESSION['cart'])): ?>
    <form action="" method="post">
        <div id="listcart">
            <table class="table align-middle" style="border: #fff" id="cart">
                <tbody>
                <tr>
                    <th></th>
                    <th width="40%">Tên sản phẩm</th>
                    <th class="text-center" width="12%">Số lượng</th>
                    <th class="text-center">Giá</th>
                    <th class="text-center">Thành tiền</th>
                    <th></th>
                </tr>

                <?php
                // Khai báo tổng giá trị đơn hàng
                $total_cart = 0;
                foreach ($_SESSION['cart'] AS $product_id => $cart):
                    $product = $product_model->getById($product_id);
                    ?>
                    <tr>
                        <td class="text-center">
                            <img class="product-avatar img-responsive"
                                 src="assets/uploads/<?php echo $cart['image'] ?>"
                                 width="80">
                        </td>
                        <td>
                            <div class="content-product text-2">
                                <a href="index.php?controller=product&action=detail&name=<?php echo $cart['name'];?>&category_id=<?php echo $cart['category_id'];?>"
                                   class="content-product-a">
                                    <?php echo $cart['name']; ?>
                                </a>
                            </div>
                            <div class="d-inline-block" style="width: 10px; height: 10px; background: <?php echo $cart['color'];?>"></div>
                            <div class="fs-7 d-inline-block">Size: <?php echo $cart['size'];?></div> |
                            <div class="fs-7 d-inline-block">Danh mục: <?php echo $category_model->getCategoryById($cart['category_id'])['name']; ?></div>
                        </td>
                        <td>
                            <div class="input-group mb-3">
                                <input type="number" class="product-amount form-control p-2" min="0" max="<?php echo $product['amount']?>" id="quantity_<?php echo $product_id; ?>"
                                       name="<?php echo $product_id; ?>" value="<?php echo $cart['quantity']; ?>">
                                <button class="btn-update btn btn-outline-primary" data-id="<?php echo $product_id; ?>" type="button">Cập nhật</button>
                            </div>
                        </td>
                        <td class="text-center">
                            <?php echo number_format($cart['price']) ?>
                        </td>
                        <td class="text-center">
                            <?php
                            $total_item = $cart['price'] * $cart['quantity'];
                            // Cộng dồn để lấy ra tổng giá trị đơn hàng
                            $total_cart += $total_item;
                            echo number_format($total_item);
                            ?>
                        </td>
                        <td>
                            <a href="" class="content-product-a text-danger" data-id="<?php echo $product_id; ?>" data-quantity="<?php echo $cart['quantity']; ?>">
                                Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <tr>
                    <td colspan="6" class="text-end">
                        Tổng giá trị đơn hàng:
                        <span class="product-price">
             <?php echo number_format($total_cart); ?> vnđ
            </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="text-end">
                        <a href="index.php?controller=cart&action=payment" class="btn btn-success p-2">Đến trang thanh toán</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </form>
    <?php else:?>
    <div>
        <p>Bạn chưa có sản phẩm nào trong giỏ</p>
        <a href="index.php?controller=product&action=showAll" class="btn btn-success">Xem danh sách sản phẩm</a>
    </div>
    <?php endif; ?>
</div>
<!--Timeline items end -->