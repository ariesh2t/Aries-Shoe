<div class="header">
    <div class="banner">
        <img src="assets/images/banner/banner.png" class="img-fluid" alt="banner">
    </div>
    <div class="mid-header">
        <div class="container">
            <div class="row">
                <div class="logo col-3">
                    <a href="index.php">
                        <img src="assets/images/logo/logo-shop.png" class="img-thumbnail" title="Aries Shoes" alt="logo">
                    </a>
                </div>
                <div class="col-custom col-9">
                    <div class="cart float-end text-center">
                        <div class="mini-cart">
                            <div class="heading-cart">
                                <a href="index.php?controller=cart&action=index">
                                    <i class="fas fa-cart-arrow-down"></i>
                                </a>
                            </div>
                            <?php
                                $cart_total = 0;
                                if (isset($_SESSION['cart'])) {
                                    foreach ($_SESSION['cart'] AS $cart) {
                                        $cart_total += $cart['quantity'];
                                    }
                                }
                            ?>
                            <div class="cart-quantity">
                                <p><?php echo $cart_total; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="account float-end text-center">
                        <div class="account-hover">
                                <span>
                                    <i class="fas fa-user"></i>
                                </span>
                            <div class="wp">
                                <?php if(isset($_SESSION['user'])) : ?>
                                    <a href="index.php?controller=user&action=profile&id=<?php echo $_SESSION['user']['id']?>" class="btns">Tài khoản</a>
                                    <a href="index.php?controller=order&action=history" class="btns">Đơn hàng</a>
                                <?php else: ?>
                                    <a href="index.php?controller=login&action=login" class="btns">Đăng nhập</a>
                                    <a href="index.php?controller=register&action=register" class="btns">Đăng ký</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <a href="#phone" class="support float-end text-center" title="Gọi ngay 0394546187">
                        <p class="hidden-md hidden-sm">Tư vấn bán hàng</p>
                        <span class="bold hidden-md hidden-sm">Gọi ngay 0394546187</span>
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
<nav class="navbar p-0 navbar-expand-lg navbar-light bg-danger">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 300px;">
                <li class="nav-item">
                    <a class="nav-link text-white mb-0 me-1 ms-1 text-uppercase" href="index.php">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white mb-0 me-1 ms-1 text-uppercase" href="index.php?controller=product&action=showAll">Sản phẩm</a>
                </li>
                <li class="nav-item dropdown has-mega">
                    <a class="nav-link text-white mb-0 me-1 ms-1 text-uppercase dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Danh mục
                    </a>
                    <ul class="dropdown-menu hidden-lg" aria-labelledby="navbarScrollingDropdown">
                        <?php foreach ($cat_model->getAllCatParent() as $category) : ?>
                            <li><a class="dropdown-item" href="index.php?controller=category&action=showProductByCatParent&cat_parent=<?php echo $category['id']?>"><?php echo $category['name'] ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="mega-content hidden-md hidden-sm pt-2 pb-3 ps-3">
                        <div class="parent-mega-menu">
                            <ul class="types row justify-content-center">
                                <?php foreach ($cat_model->getAllCatParent(0,5) as $catParent): ?>
                                    <li class="item col">
                                        <h4 class="title-type">
                                            <a href="index.php?controller=category&action=showProductByCatParent&cat_parent=<?php echo $catParent['id']?>">
                                                <span><?php echo $catParent['name']?></span>
                                            </a>
                                        </h4>
                                        <ul class="type">
                                            <?php foreach ($cat_model->getChildByCatParent($catParent['id'],0,8) as $catChild): ?>
                                                <li class="item-type">
                                                    <a href="index.php?controller=category&action=showProductByCatChild&category_id=<?php echo $catChild['id']?>">
                                                        <span><?php echo $catChild['name']?></span>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </li>
                <?php foreach ($cat_model->getBrandOrderByCountProduct(0,2) as $catParent) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link text-white mb-0 me-1 ms-1 text-uppercase dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $cat_model->getCategoryById($catParent['parent_cat'])['name']?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php foreach ($cat_model->getChildByCatParent($catParent['parent_cat'],0,5) as $catChild): ?>
                                <li>
                                    <a class="dropdown-item" href="index.php?controller=category&action=showProductByCatChild&category_id=<?php echo $catChild['id']?>">
                                        <span><?php echo $catChild['name']?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
                <li class="nav-item">
                    <a class="nav-link text-white mb-0 me-1 ms-1 text-uppercase" href="#">Giới thiệu</a>
                </li>
            </ul>
            <form method="get" action="index.php?controller=product&action=showAll" class="d-flex">
                <div class="input-group">
                    <input type="hidden" name="controller" value="product">
                    <input type="hidden" name="action" value="showAll">
                    <input type="text" name="name" class="form-control" placeholder="Tên sản phẩm" style="padding: 5px 10px; margin: 5px 0"
                        value="<?php echo $_GET['name'] ?? '' ?>">
                    <button class="btn btn-primary" type="submit" id="button-addon2" style="padding: 5px 10px; margin: 5px 0"><i class="fa-solid fa-magnifying-glass text-white"></i></button>
                </div>
            </form>
        </div>
    </div>
</nav>