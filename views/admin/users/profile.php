<h2 class="mb-4">Profile</h2>
<div class="row justify-content-center">
    <div class="col-lg-5 col-12">
        <div class="img text-center">
            <div>
                <img src="assets/<?php echo isset($user['avatar']) ? 'uploads/'.$user['avatar'] : 'images/img.png' ?>"
                     style="width: 100%; max-width: 300px;" class="mb-5 img-bordered" alt="<?php echo $user['fullname'] ?>">
            </div>
            <div class="hidden-md-sm">
                <a href="index.php?controller=userAdmin&action=updateProfile&id=<?php echo $user['id'] ?>"
                   class="btn btn-outline-info">Edit profile</a>
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-12 text-center text-lg-start">
        <div class="mb-3">
            <div class="text-danger h5">Username</div>
            <p><?php echo $user['username'] ?></p>
        </div>
        <div class="mb-3">
            <div class="text-danger h5">Họ tên</div>
            <p><?php echo !empty($user['fullname']) ? $user['fullname'] : 'Chưa cập nhật họ tên!' ?></p>

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
            <div class="text-danger h5">Created at</div>
            <p><?php echo date('d-m-Y H:i:s', strtotime($user['created_at'])) ?></p>
        </div>
        <div class="mb-3">
            <div class="text-danger h5">Updated at</div>
            <p><?php echo date('d-m-Y H:i:s', strtotime($user['updated_at'])) ?></p>
        </div>
    </div>
    <div class="col-12 text-center">
        <a href="index.php?controller=userAdmin&action=updateProfile&id=<?php echo $user['id'] ?>"
           class="btn btn-outline-info mt-3 mb-5 hidden-lg">Edit profile</a>
    </div>
</div>