<div class="container">
    <?php
    require_once 'models/Product.php';
    require_once 'models/Category.php';
    require_once 'models/OrderStatus.php';
    $product_model = new Product();
    $category_model = new Category();
    $order_status_model = new OrderStatus();
    $status = $order_status_model->getStatus($order['order_status_id'])['status'];
    ?>
    <h2 class="mb-4">Chi tiết đơn hàng #<?php echo $order['id']?></h2>
    <div class="mb-4">
        Trạng thái đơn hàng:
        <?php if ($status == 'waiting') : ?>
            <span class="badge bg-warning"><?php echo ucfirst($status)?></span>
        <?php elseif ($status == 'cancelled') : ?>
            <span class="badge bg-danger"><?php echo ucfirst($status)?></span>
        <?php elseif ($status == 'delivered') : ?>
            <span class="badge bg-success"><?php echo ucfirst($status)?></span>
        <?php else: ?>
            <span class="badge bg-primary"><?php echo ucfirst($status)?></span>
        <?php endif; ?>
    </div>
    <div class="row justify-content-center">
        <div class="col-5 mb-3">
            <div class="text-danger h5">Tên người nhận hàng</div>
            <p><?php echo $order['fullname'] ?></p>
        </div>
        <div class="col-5 mb-3">
            <div class="text-danger h5">Số điện thoại</div>
            <p><?php echo $order['phone'] ?></p>
        </div>
        <div class="col-10 mb-3">
            <div class="text-danger h5">Địa chỉ nhận hàng</div>
            <p><?php echo $order['address'] ?></p>
        </div>
        <div class="col-10">
            <div class="text-danger h5">Danh sách sản phẩm</div>
            <table class="table table-hover table-striped mt-2">
                <tr>
                    <th scope="col" width="40%">Sản phẩm</th>
                    <th width="15%" scope="col">Thông tin</th>
                    <th width="15%" scope="col">Danh mục</th>
                    <th width="10%" scope="col">Số lượng</th>
                    <th width="15%">Giá</th>
                </tr>
                <?php foreach ($order_details as $order_detail) :
                    $product = $product_model->getById($order_detail['product_id']);
                    ?>
                    <tr style="height: 60px;">
                        <td>
                            <div class="text-2 text-center">
                                <a href="index.php?controller=product&action=detail&name=<?php echo $product['name']; ?>&category_id=<?php echo $product['category_id']?>">
                                    <?php echo $product['name']; ?>
                                </a>
                            </div>
                        </td>
                        <td>
                            <div class="d-inline-block" style="width: 10px; height: 10px; background: <?php echo $product['color'] ?>"></div> |
                            Size: <?php echo $product['size']; ?>
                        </td>
                        <td>
                            <div class="text-2 text-center align-middle"><?php echo $category_model->getCategoryById($product['category_id'])['name']; ?></div>
                        </td>
                        <td><?php echo number_format($order_detail['quantity']); ?></td>
                        <td><?php echo number_format($order_detail['price']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="mb-3 col-10">
            <div class="text-danger h5">Tổng giá trị đơn hàng</div>
            <p><?php echo number_format($order['total_price']) ?><sup>đ</sup></p>
        </div>
        <div class="col-10 mb-3">
            <div class="text-danger h5">Ghi chú</div>
            <p><?php echo empty($order['note']) ? 'Không có' : $order['note'] ?></p>
        </div>
    </div>
    <div class="row">
        <div class="text-end col-11">
            <?php if($status == 'waiting' || $status == 'preparing') : ?>
            <a onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')" href="index.php?controller=order&action=cancelOrder&id=<?php echo $order['id']?>" class="btn btn-warning">Hủy đơn hàng</a>
            <?php endif; ?>
            <a href="index.php?controller=order&action=history" class="btn btn-danger text-right"><i class="fa-solid fa-rotate-left"></i> Quay lại</a>
        </div>
    </div>
</div>