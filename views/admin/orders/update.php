<h2 class="mb-4">Chỉnh sửa trạng thái đơn hàng #<?php echo $order['id']?></h2>
<form action="" method="post">
    <input type="hidden" name="id" value="<?php echo $order['id']?>">
    <div class="mb-3">
        <label for="order_status_id">Trạng thái đơn hàng</label>
        <select name="order_status_id" id="order_status_id" class="form-select">
            <?php foreach ($order_statuses as $order_status) :
                $selected = ($order_status['id'] == $order['order_status_id']) ? 'selected' : '';
                ?>
                <option value="<?php echo $order_status['id'] ?>" <?php echo $selected?>><?php echo ucfirst($order_status['status']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group mb-3 row">
        <div class="text-left col-6">
            <button type="submit" name="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
        </div>
        <div class="text-end col-6">
            <a href="index.php?controller=orderAdmin&action=index" class="btn btn-danger text-right"><i class="fa-solid fa-rotate-left"></i> Quay lại</a>
        </div>
    </div>
</form>