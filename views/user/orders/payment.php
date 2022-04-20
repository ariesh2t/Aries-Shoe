<?php
    require_once 'models/Category.php';
    require_once 'models/Product.php';
    $category_model = new Category();
    $product_model = new Product();
?>
<div class="container">
    <div class="mb-5">
        <h4 class="mb-4 text-danger">Thông tin đơn hàng</h4>
        <div class="row justify-content-center">
            <div id="listcart" class="col-10">
                <table class="table table-striped table-borderless align-middle" id="cart">
                    <thead>
                        <tr class="text-danger">
                            <th width="15%"></th>
                            <th width="40%">Tên sản phẩm</th>
                            <th class="text-center" width="12%">Số lượng</th>
                            <th class="text-center">Giá</th>
                            <th class="text-center">Thành tiền</th>
                        </tr>
                    </thead>

                    <tbody>
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
                                    <?php echo $cart['name']; ?>
                                </div>
                                <div class="d-inline-block" style="width: 10px; height: 10px; background: <?php echo $cart['color'];?>"></div>
                                <div class="fs-7 d-inline-block">Size: <?php echo $cart['size'];?></div> |
                                <div class="fs-7 d-inline-block">Danh mục: <?php echo $category_model->getCategoryById($cart['category_id'])['name']; ?></div>
                            </td>
                            <td class="text-center">
                                <?php echo number_format($cart['quantity']); ?>
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
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5" class="text-end">
                            Tổng giá trị đơn hàng:
                            <span class="product-price">
                                <?php echo number_format($total_cart); ?> vnđ
                            </span>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="user-info">
        <h4 class="mb-4 text-danger">Thông tin người đặt hàng</h4>
        <form action="" method="post">
            <div class="row justify-content-around">
                <div class="col-lg-5">
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo $_SESSION['user']['fullname']?>" required>
                        <?php if (!empty($this->error['fullname'])): ?>
                            <small class="text-danger fst-italic">
                                <?php
                                echo $this->error['fullname'];
                                ?>
                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $_SESSION['user']['phone']?>" required>
                        <?php if (!empty($this->error['phone'])): ?>
                            <small class="text-danger fst-italic">
                                <?php
                                echo $this->error['phone'];
                                ?>
                            </small>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ nhận hàng</label>
                        <input type="text" class="form-control" id="address" name="address"
                               value="<?php echo !empty($_SESSION['user']['address']) ? $_SESSION['user']['address'] : ''; ?>" required>
                        <?php if (!empty($this->error['address'])): ?>
                            <small class="text-danger fst-italic">
                                <?php
                                echo $this->error['address'];
                                ?>
                            </small>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="note" class="form-label">Ghi chú</label>
                        <textarea name="note" id="note" class="form-control" rows="10"></textarea>
                    </div>
                </div>
                <div class="col-11 text-end">
                    <button type="submit" name="submit" class="btn btn-danger">Đặt hàng</button>
                </div>
            </div>
        </form>
    </div>
</div>