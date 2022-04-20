<h2 class="mb-4">Tạo tài khoản admin</h2>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group mb-3">
        <label for="username">Username <span class="text-danger">*</span></label>
        <input type="text" name="username" id="username"
               value="<?php echo isset($_POST['username']) ? htmlentities($_POST['username']) : '' ?>" class="form-control"/>
        <?php if (!empty($this->error['username'])): ?>
            <small class="text-danger fst-italic">
                <?php
                echo $this->error['username'];
                ?>
            </small>
        <?php endif; ?>
    </div>
    <div class="form-group mb-3 position-relative">
        <label for="password">Mật khẩu <span class="text-danger">*</span></label>
        <input type="password" name="password" id="password"
               value="<?php echo isset($_POST['password']) ? htmlentities($_POST['password']) : '' ?>" class="form-control"/>
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
        <label for="password_confirm">Xác nhận mật khẩu <span class="text-danger">*</span></label>
        <input type="password" name="password_confirm" id="password_confirm"
               value="<?php echo isset($_POST['password_confirm']) ? htmlentities($_POST['password_confirm']) : '' ?>" class="form-control"/>
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
    <div class="form-group mb-3 row">
        <div class="text-start col-6">
            <button type="submit" name="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
            <input type="reset" class="btn btn-warning" name="submit" value="Reset"/>
        </div>
        <div class="text-end col-6">
            <a href="index.php?controller=userAdmin&action=index" class="btn btn-danger text-right"><i class="fa-solid fa-rotate-left"></i> Quay lại</a>
        </div>
    </div>
</form>