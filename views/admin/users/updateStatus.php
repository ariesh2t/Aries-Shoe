<h2 class="mb-4">Cập nhật trạng thái</h2>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group mb-3">
        <label for="username">Username</label>
        <input type="text" id="username" value="<?php echo $user['username'] ?>" disabled class="form-control"/>
    </div>
    <div class="form-group mb-3">
        <label for="avatar">Avatar</label>
        <div>
            <img id="mg-thumbnail" width="200" src="assets/<?php echo !empty($user['avatar']) ? 'uploads/'.$user['avatar'] : 'images/img.png' ?>" />
        </div>
    </div>
    <div class="form-group mb-3">
        <label for="fullname">Họ tên</label>
        <input type="text" id="fullname" disabled value="<?php echo $user['fullname'] ?>" class="form-control"/>
    </div>
    <div class="form-group mb-3">
        <label for="phone">Số điện thoại</label>
        <input type="number" id="phone" value="<?php echo $user['phone'] ?>" disabled class="form-control"/>
    </div>
    <div class="form-group mb-3">
        <label for="address">Địa chỉ</label>
        <input type="text" id="address" value="<?php echo $user['address'] ?>" disabled class="form-control"/>
    </div>
    <div class="form-group mb-3">
        <label for="status">Trạng thái</label>
        <select name="status" class="form-control" id="status">
            <?php
            if ($user['status'] == 0) {
                $selected_disabled = 'selected';
                $selected_active = '';
            } else {
                $selected_active = 'selected';
                $selected_disabled = '';
            }
            if (isset($_POST['status'])) {
                switch ($_POST['status']) {
                    case 'false':
                        $selected_disabled = 'selected';
                        $selected_active = '';
                        break;
                    case 'true':
                        $selected_active = 'selected';
                        $selected_disabled = '';
                        break;
                }
            }
            ?>
            <option value="false" <?php echo $selected_disabled; ?>>Disabled</option>
            <option value="true" <?php echo $selected_active ?>>Active</option>
        </select>
        <?php if (!empty($this->error['status'])): ?>
            <small class="text-danger fst-italic">
                <?php
                echo $this->error['status'];
                ?>
            </small>
        <?php endif; ?>
    </div>
    <div class="form-group mb-3 row">
        <div class="text-left col-6">
            <button type="submit" name="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
            <input type="reset" class="btn btn-warning" name="submit" value="Reset"/>
        </div>
        <div class="text-end col-6">
            <a href="index.php?controller=userAdmin&action=index" class="btn btn-danger text-right"><i class="fa-solid fa-rotate-left"></i> Quay lại</a>
        </div>
    </div>
</form>