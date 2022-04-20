<div class="title-main">
    <div class="container a-center">
        <p class="title-page red">Đăng nhập tài khoản</p>
    </div>
</div>
<div class="form">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 form-login">
                <div class="desc mb-4">
                    <span>Nếu đã có tài khoản, đăng nhập tại đây</span>
                </div>
                <form id="form-login" action="" method="post">
                    <fieldset class="form-group mb-3">
                        <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="username" id="username" placeholder="Nhập username của bạn"
                               value="<?php echo isset($_POST['username']) ? htmlentities($_POST['username']) : ''?>" required>
                        <small class="text-danger fst-italic">
                            <?php
                            if (!empty($this->error['username'])) {
                                echo $this->error['username'];
                            }
                            ?> &nbsp;
                        </small>
                    </fieldset>
                    <fieldset class="form-group mb-3 hide-pass">
                        <label class="form-label" for="password">Mật khẩu <span class="text-danger">*</span></label>
                        <input class="form-control" type="password" name="password" id="password"
                               value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''?>" required>
                        <div class="eyes">
                            <i class="fas fa-eye" style="display: block"></i>
                        </div>
                        <?php if (!empty($this->error['password'])): ?>
                            <small class="text-danger fst-italic">
                                <?php
                                echo $this->error['password'];
                                ?>
                            </small>
                        <?php endif; ?>
                    </fieldset>
                    <div class="center">
                        <input class="btns" type="submit" name="submit" id="login" value="Đăng nhập">
                    </div>

                    <div class="register">
                        <span>Bạn chưa có tài khoản? &nbsp; &nbsp;</span>
                        <a href="index.php?controller=register&action=register">Đăng ký ngay</a>
                    </div>

                </form>
            </div>

            <div class="col-lg-6 recover-password">
                <div class="desc">
                    <span>Bạn quên mật khẩu? Nhập username để lấy lại mật khẩu</span>
                </div>
                <form id="form-recover" class="form-recover" action="" method="post">
                    <fieldset class="form-group">
                        <label class="form-label" for="username-recover">Username *</label>
                        <input class="form-control" type="text" name="username-recover" id="username-recover" placeholder="Nhập username của bạn" required>
                    </fieldset>
                    <div class="center">
                        <input class="btns" type="submit" name="recover" id="recover" value="Lấy lại mật khẩu">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>