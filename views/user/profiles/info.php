<div class="container px-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-8 col-sm-8">
            <!-- Profile picture card-->
            <div class="card mb-4 mb-xl-0">
                <div class="card-header h5">Avatar</div>
                <div class="card-body text-center">
                    <div class="d-flex flex-column align-items-center text-center py-2">
                        <?php if (empty($user['avatar'])) : ?>
                            <img style="max-width: 200px" src="assets/images/img.png" alt="">
                        <?php else: ?>
                            <img style="max-width: 200px" src="assets/uploads/<?php echo $user['avatar']; ?>" alt="">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-8 col-sm-8">
            <div class="card mb-4">
                <div class="card-header h5">Thông tin cá nhân</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="h5 text-danger">Họ tên</div>
                            <div><?php echo $user['fullname']; ?></div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h5 text-danger">Số điện thoại</div>
                            <div><?php echo $user['phone']; ?></div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="h5 text-danger">Địa chỉ</div>
                            <div><?php echo !empty($user['address']) ? $user['address'] : "<small class='fst-italic'>Chưa có thông tin địa chỉ</small>"; ?></div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h5 text-danger">Username</div>
                            <div><?php echo $user['username']; ?></div>
                        </div>
                        <div class="col-12">
                            <a href="index.php?controller=user&action=edit&id=<?php echo $user['id']?>" class="btn btn-outline-primary mt-2">Chỉnh sửa thông tin cá nhân</a>
                            <a href="index.php?controller=user&action=logout" class="btn btn-outline-danger mt-2">Đăng xuất</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>