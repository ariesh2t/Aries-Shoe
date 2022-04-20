<h2 class="mb-4">Chi tiết user</h2>
<div class="row justify-content-center">
    <div class="col-lg-5 col-12">
        <div class="img text-center">
            <div>
                <img src="assets/<?php echo isset($user['avatar']) ? 'uploads/'.$user['avatar'] : 'images/img.png' ?>"
                     style="width: 100%; max-width: 300px;" class="mb-5 img-bordered" alt="<?php echo $user['fullname'] ?>">
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-12 text-center text-lg-left">
        <div class="mb-3">
            <div class="text-danger h5">ID</div>
            <p><?php echo $user['id'] ?></p>
        </div>
        <div class="mb-3">
            <div class="text-danger h5">Username</div>
            <p><?php echo $user['username'] ?></p>
        </div>
        <div class="mb-3">
            <div class="text-danger h5">Họ tên</div>
            <p><?php echo $user['fullname'] ?></p>

        </div>
        <div class="mb-3">
            <div class="text-danger h5">Số điện thoại</div>
            <p><?php echo !empty($user['phone']) ? $user['phone'] : 'Chưa cập nhật số điện thoại!' ?></p>
        </div>
        <div class="mb-3">
            <div class="text-danger h5">Địa chỉ</div>
            <p><?php echo !empty($user['address']) ? $user['address'] : 'Chưa cập nhật địa chỉ!' ?></p>
        </div>
        <div class="mb-3">
            <div class="text-danger h5">Role</div>
            <p><?php echo $user['role_id'] == 1 ? 'admin' : 'user' ?></p>
        </div>
        <div class="mb-3">
            <div class="text-danger h5">Trạng thái</div>
            <p><?php echo $user['status'] == 0 ? 'Disabled' : 'Active' ?></p>
        </div>
        <div class="mb-3">
            <div class="text-danger h5">Created at</div>
            <p><?php echo date('d-m-Y H:i:s', strtotime($user['created_at'])) ?></p>
        </div>
        <div class="mb-3">
            <div class="text-danger h5">Updated at</div>
            <p><?php echo date('d-m-Y H:i:s', strtotime($user['updated_at'])) ?></p>
        </div>
    </div>
</div>
<div class="row">
    <div class=" col-6">
        <?php if ($user['id'] != $_SESSION['user']['id']) : ?>
            <a href="index.php?controller=userAdmin&action=updateStatus&id=<?php echo $user['id'] ?>"
               class="btn btn-warning text-right">
                <i class="fa fa-pencil-alt"></i> Sửa trạng thái
            </a>
        <?php endif; ?>
    </div>
    <div class="text-end col-6">
        <a href="index.php?controller=userAdmin&action=index" class="btn btn-danger text-right"><i class="fa-solid fa-rotate-left"></i> Quay lại</a>
    </div>
</div>