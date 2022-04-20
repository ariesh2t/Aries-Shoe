<?php
    $order_status_model = new OrderStatus();
?>
<h2>Tìm kiếm</h2>
<form action="" method="get" class="row align-middle">
    <input type="hidden" name="controller" value="orderAdmin"/>
    <input type="hidden" name="action" value="index"/>
    <div class="col-lg-4 col-md-12 col-sm-12 mb-2">
        <label class="mb-2 form-label" for="order_status_id">Trạng thái</label>
        <select id="order_status_id" class="form-select" name="order_status_id">
            <option selected></option>
            <?php foreach ($order_statuses as $order_status):
                $selected = '';
                if(isset($_GET['order_status_id']) && $_GET['order_status_id'] == $order_status['id']) {
                    $selected = 'selected';
                }
                ?>
                <option value="<?php echo $order_status['id']; ?>" <?php echo $selected; ?> ><?php echo ucfirst($order_status['status']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12 mb-2">

    </div>
    <div class="col-lg-4 col-md-12 col-sm-12 text-right text-lg-center pt-4 mb-2">
        <button type="submit" class="btn btn-success"><i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm</button>
        <a href="index.php?controller=orderAdmin" class="btn btn-warning">Xóa filter</a>
    </div>
</form>

<h2 class="mt-5">Danh sách đơn hàng</h2>
<table class="table table-hover table-striped mt-2">
    <tr>
        <th scope="col">Tên người nhận</th>
        <th scope="col">Số điện thoại</th>
        <th width="25%">Địa chỉ</th>
        <th scope="col">Ghi chú</th>
        <th scope="col">Status</th>
        <th scope="col">Tổng tiền</th>
        <th scope="col">Created at</th>
        <th scope="col"></th>
    </tr>
    <?php if (!empty($orders)): ?>
    <?php foreach ($orders as $order):
        $status = $order_status_model->getStatus($order['order_status_id'])['status'];
    ?>
        <tr style="height: 60px">
            <td class="align-middle">
                <div class="text-2"><?php echo htmlentities($order['fullname']) ?></div>
            </td>
            <td><?php echo $order['phone'] ?></td>
            <td class="align-middle">
                <div class="text-2"><?php echo $order['address'] ?></div>
            </td>
            <td class="align-middle">
                <div class="text-2"><?php echo htmlentities($order['note']) ?></div>
            </td>
            <td>
                <?php if ($status == 'waiting') : ?>
                    <p class="badge bg-warning"><?php echo ucfirst($status)?></p>
                <?php elseif ($status == 'cancelled') : ?>
                    <p class="badge bg-danger"><?php echo ucfirst($status)?></p>
                <?php elseif ($status == 'delivered') : ?>
                    <p class="badge bg-success"><?php echo ucfirst($status)?></p>
                <?php else: ?>
                    <p class="badge bg-primary"><?php echo ucfirst($status)?></p>
                <?php endif; ?>
            </td>
            <td><?php echo number_format($order['total_price'])?></td>
            <td><?php echo date('d-m-Y', strtotime($order['created_at'])) ?></td>
            <td>
                <?php
                    $url_detail = "index.php?controller=orderAdmin&action=detail&id=" . $order['id'];
                    $url_updateStatus = "index.php?controller=orderAdmin&action=update&id=" . $order['id'];
                ?>
                <a class="mr-1" title="Chi tiết" href="<?php echo $url_detail ?>"><i class="fa fa-eye"></i></a>
                <a class="mr-1" title="Update" href="<?php echo $url_updateStatus ?>"><i class="fa fa-pencil-alt"></i></a>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="9">Chưa có đơn hàng nào!</td>
        </tr>
    <?php endif; ?>
</table>
<?php
require_once "views/adminLayouts/pagination.php";
?>

