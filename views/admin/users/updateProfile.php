
<h2 class="mb-4">Cập nhật thông tin cá nhân</h2>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group mb-3">
        <label for="fullname">Họ tên</label>
        <input type="text" name="fullname" id="fullname"
               value="<?php echo isset($_POST['fullname']) ? htmlentities($_POST['fullname']) : $user['fullname'] ?>"
               class="form-control"/>
        <?php if (!empty($this->error['fullname'])): ?>

            <small class="text-danger fst-italic">
                <?php
                echo $this->error['fullname'];
                ?>
            </small>
        <?php endif; ?>
    </div>
    <div class="form-group mb-3">
        <label for="phone">Số điện thoại</label>
        <input type="text" name="phone" id="phone"
               value="<?php echo isset($_POST['phone']) ? htmlentities($_POST['phone']) : htmlentities($user['phone']) ?>"
               class="form-control"/>
        <?php if (!empty($this->error['phone'])): ?>

            <small class="text-danger fst-italic">
                <?php
                echo $this->error['phone'];
                ?>
            </small>
        <?php endif; ?>
    </div>
    <div class="form-group mb-3">
        <label for="address">Địa chỉ</label>
        <input type="text" name="address" id="address"
               value="<?php echo isset($_POST['address']) ? htmlentities($_POST['address']) : $user['address'] ?>"
               class="form-control"/>
    </div>
    <div class="form-group mb-3">
        <label for="avatar">Avatar</label>
        <div class="thumb">
            <img id="imgSrc" src="assets/<?php echo isset($user['avatar']) ? 'uploads/'.$user['avatar'] : 'images/img.png' ?>" />
            <div id="uploadCover" class="thumb-cover">
                <i class="fa fa-plus-square"></i>
                <input type="file" name="avatar" id="imgUpload" accept="image/*" title="Click để thay đổi hình ảnh!">
            </div>
        </div>
        <?php if (!empty($this->error['avatar'])): ?>

            <small class="text-danger fst-italic">
                <?php
                echo $this->error['avatar'];
                ?>
            </small>
        <?php endif; ?>
    </div>
    <div class="form-group mb-3">
        <label for="username">Username</label>
        <input type="text" name="username" id="username"
               value="<?php echo isset($_POST['username']) ? htmlentities($_POST['username']) : htmlentities($user['username']) ?>"
               class="form-control"/>
        <?php if (!empty($this->error['username'])): ?>

            <small class="text-danger fst-italic">
                <?php
                echo $this->error['username'];
                ?>
            </small>
        <?php endif; ?>
    </div>
    <div id="form-pswd">
        <div class="form-group mb-3 position-relative">
            <label for="password">Mật khẩu</label>
            <input type="password" name="password" id="password" value="" class="form-control"/>
            <div class="eyes position-absolute">
                <i class="fas fa-eye" style="display: block"></i>
            </div>
            <?php if (!empty($this->error['password'])): ?>

                <small class="text-danger fst-italic">
                    <?php
                    echo $this->error['password'];
                    ?>
                </small>
            <?php endif; ?>
        </div>
        <div class="form-group mb-3 position-relative">
            <label for="new-pswd">Mật khẩu mới</label>
            <input type="password" name="new-pswd" id="new-pswd" value="" class="form-control"/>
            <div class="new-eyes position-absolute">
                <i class="fas fa-eye" style="display: block"></i>
            </div>
            <?php if (!empty($this->error['new-pswd'])): ?>
                <small class="text-danger fst-italic">
                    <?php
                    echo $this->error['new-pswd'];
                    ?>
                </small>
            <?php endif; ?>
        </div>
        <div class="form-group mb-3 position-relative">
            <label for="password_confirm">Nhập lại mật khẩu</label>
            <input type="password" name="password_confirm" id="password_confirm" class="form-control"/>
            <div class="re-eyes position-absolute">
                <i class="fas fa-eye" style="display: block"></i>
            </div>
            <?php if (!empty($this->error['password_confirm'])): ?>
                <small class="text-danger fst-italic">
                    <?php
                    echo $this->error['password_confirm'];
                    ?>
                </small>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group mb-3 row">
        <div class="text-start col-6">
            <a class="btn btn-danger" id="btn-pswd"><i class="fa-solid fa-key"></i> Đổi mật khẩu</a>
            <button type="submit" name="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
            <button class="btn btn-warning" type="reset">Reset</button>
        </div>
        <div class="text-end col-6">
            <a href="index.php?controller=userAdmin&action=profile&id=<?php echo $user['id'] ?>" class="btn btn-danger"><i class="fa-solid fa-rotate-left"></i> Quay lại</a>
        </div>
    </div>
</form>