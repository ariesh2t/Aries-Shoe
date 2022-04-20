<div class="title-main">
    <div class="container a-center">
        <p class="title-page red">Đăng ký tài khoản</p>
    </div>
</div>

<div class="form">
    <div class="container">
        <form action="" method="post" id="form-register">
            <div class="row">
                <div class="col-lg-6 col-md-12 form-register">
                    <div class="desc mb-4">
                        <span>Nếu chưa có tài khoản, vui lòng đăng ký tại đây</span>
                    </div>
                    <div>
                        <label class="form-label" for="fullname">Họ tên <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="fullname" id="fullname"
                               value="<?php echo isset($_POST['fullname']) ? $_POST['fullname'] : '' ?>" required>
                        <small class="text-danger fst-italic">
                            <?php
                            if (!empty($this->error['fullname'])) {
                                echo $this->error['fullname'];
                            }
                            ?> &nbsp;
                        </small>
                    </div>

                    <div>
                        <label class="form-label" for="phone">Số điện thoại <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="phone" id="phone"
                               value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : '' ?>" required>
                        <small class="text-danger fst-italic">
                            <?php
                            if (!empty($this->error['phone'])) {
                                echo $this->error['phone'];
                            }
                            ?> &nbsp;
                        </small>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 form-register mt-5">
                    <div>
                        <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="username" id="username"
                               value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>" required>
                        <small class="text-danger fst-italic">
                            <?php
                            if (!empty($this->error['username'])) {
                                echo $this->error['username'];
                            }
                            ?> &nbsp;
                        </small>
                    </div>
                    <div class="hide-pass eye">
                        <label class="form-label" for="password">Mật khẩu <span class="text-danger">*</span></label>
                        <input class="form-control" type="password" name="password" id="password"
                               value="<?php echo isset($_POST['password']) ? $_POST['password'] : '' ?>" required>
                        <div class="eyes">
                            <i class="fas fa-eye eye-pass"></i>
                        </div>
                        <small class="text-danger fst-italic">
                            <?php
                            if (!empty($this->error['password'])) {
                                echo $this->error['password'];
                            }
                            ?> &nbsp;
                        </small>
                    </div>
                    <div class="hide-pass eye">
                        <label class="form-label" for="re-password">Nhập lại mật khẩu <span class="text-danger">*</span></label>
                        <input class="form-control" type="password" name="re-password" id="re-password" value="<?php echo isset($_POST['re-password']) ? $_POST['re-password'] : '' ?>" required>
                        <div class="re-eyes">
                            <i class="fas fa-eye eye-pass"></i>
                        </div>
                        <small class="text-danger fst-italic">
                            <?php
                            if (!empty($this->error['re-password'])) {
                                echo $this->error['re-password'];
                            }
                            ?> &nbsp;
                        </small>
                    </div>
                </div>
            </div>
            <div class="center">
                <input class="btns" type="submit" name="submit" id="login" value="Đăng ký">
            </div>

            <div class="login">
                <span class="me-4">Bạn đã có tài khoản?</span>
                <a href="index.php?controller=login&action=login">Đăng nhập ngay</a>
            </div>

        </form>
    </div>

</div>
