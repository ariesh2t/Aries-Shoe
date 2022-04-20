<?php
    require_once 'models/Product.php';
    require_once 'models/Category.php';
    $product_model = new Product();
    $category_model = new Category();
?>
<h2 class="mb-4">Chi tiết đơn hàng #<?php echo $order['id']?></h2>
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
                <th scope="col">Thông tin</th>
                <th scope="col">Danh mục</th>
                <th width="10%" scope="col">Số lượng</th>
                <th width="15%">Giá</th>
            </tr>
            <?php foreach ($order_details as $order_detail) :
                $product = $product_model->getById($order_detail['product_id']);
                ?>
                <tr style="height: 60px;">
                    <td class="align-middle">
                        <div class="text-2 text-center"><?php echo $product['name']; ?></div>
                    </td>
                    <td>
                        <div class="d-inline-block" style="width: 10px; height: 10px; background: <?php echo $product['color'] ?>"></div> |
                        Size: <?php echo $product['size']; ?>
                    </td>
                    <td class="align-middle">
                        <div class="text-2 text-center"><?php echo $category_model->getCategoryById($product['category_id'])['name']; ?></div>
                    </td>
                    <td><?php echo $order_detail['quantity']; ?></td>
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
        <p><?php echo $order['note'] ?></p>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <a href="index.php?controller=orderAdmin&action=update&id=<?php echo $order['id']; ?>" class="btn btn-warning"><i class="fa fa-pencil-alt"></i> Sửa đơn hàng</a>
    </div>
    <div class="text-end col-6">
        <a href="index.php?controller=orderAdmin&action=index" class="btn btn-danger text-right"><i class="fa-solid fa-rotate-left"></i> Quay lại</a>
    </div>
</div>