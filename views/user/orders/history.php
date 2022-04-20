<?php
    require_once 'models/OrderDetail.php';
    require_once 'models/OrderStatus.php';
    $order_detail_model = new OrderDetail();
    $order_status_model = new OrderStatus();
?>
<div class="container p-5 shadow-lg">
    <h4 class="mb-4">Danh sách đơn hàng</h4>
    <table class="table table-striped table-hover text-center">
        <thead class="bold">
            <tr>
                <td>Mã đơn hàng</td>
                <td>Số lượng sản phẩm</td>
                <td>Tổng tiền</td>
                <td>Ngày tạo</td>
                <td>Trạng thái</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order) : ?>
            <tr>
                <td class="align-middle"><?php echo $order['id'];?></td>
                <td class="align-middle">
                    <?php
                        $dem = 0;
                        foreach ($order_detail_model->getAllProductByOrderId($order['id']) as $item) {
                            $dem ++;
                        }
                        echo $dem;
                    ?>
                </td>
                <td>
                    <?php echo number_format($order['total_price']); ?>
                </td>
                <td class="align-middle">
                    <?php echo date('d-m-Y', strtotime($order['created_at'])); ?>
                </td>
                <td class="align-middle">
                    <?php $status = $order_status_model->getStatus($order['order_status_id'])['status'];
                        if ($status == 'waiting') : ?>
                        <span class="badge bg-warning"><?php echo ucfirst($status)?></span>
                    <?php elseif ($status == 'cancelled') : ?>
                        <span class="badge bg-danger"><?php echo ucfirst($status)?></span>
                    <?php elseif ($status == 'delivered') : ?>
                        <span class="badge bg-success"><?php echo ucfirst($status)?></span>
                    <?php else: ?>
                        <span class="badge bg-primary"><?php echo ucfirst($status)?></span>
                    <?php endif; ?>
                </td>
                <td class="align-middle">
                    <?php
                    $url_detail = "index.php?controller=order&action=detail&id=" . $order['id'];
                    $url_updateStatus = "index.php?controller=order&action=update&id=" . $order['id'];
                    ?>
                    <a class="mr-1" title="Chi tiết" href="<?php echo $url_detail ?>"><i class="fa fa-eye"></i></a>
                    <?php if($status == 'waiting' || $status == 'preparing') : ?>
                        <a title="Hủy đơn hàng" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')" href="index.php?controller=order&action=cancelOrder&id=<?php echo $order['id']?>"><i class="fa-solid fa-ban"></i></a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
