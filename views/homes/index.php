<?php
    require_once 'models/Category.php';
    require_once 'models/Product.php';
    $product_model = new Product();
    $amount = $product_model->countTotalBestSelling();
    $cat_model = new Category();
?>

<div class="center-main">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
        </div>
        <div class="carousel-inner overflow-hidden d-flex text-center align-middle">
            <div class="carousel-item active">
                <img src="assets/images/banner/slide1.png" alt="">
            </div>
            <div class="carousel-item">
                <img src="assets/images/banner/slide2.jpg" alt="">
            </div>
            <div class="carousel-item">
                <img src="assets/images/banner/slide3.jpg" alt="">
            </div>
            <div class="carousel-item">
                <img src="assets/images/banner/slide4.png" alt="">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden text-danger">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden text-danger">Next</span>
        </button>
    </div>
    <div class="section1">
        <div class="container">
            <div class="row">
                <?php foreach ($cat_model->getBrandOrderByCountProduct(0,1) as $catParent) :
                    $category = $cat_model->getCategoryById($catParent['parent_cat']);
                    ?>
                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 item-prop">
                        <div class="wrap-banner d-flex justify-content-center align-middle">
                            <a href="index.php?controller=category&action=showProductByCatParent&cat_parent=<?php echo $category['id']?>">
                                <img src="assets/uploads/<?php echo $category['image']?>" alt="category">
                                <div class="wrap-title">
                                    <h2><?php echo $category['name']?></h2>
                                    <p><?php echo $catParent['totalQtt']?> sản phẩm</p>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php foreach ($cat_model->getBrandOrderByCountProduct(1,4) as $catParent) :
                    $category = $cat_model->getCategoryById($catParent['parent_cat']);
                    ?>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 item-prop">
                        <div class="wrap-banner">
                            <a href="index.php?controller=category&action=showProductByCatParent&cat_parent=<?php echo $category['id']?>">
                                <img src="assets/uploads/<?php echo $category['image']?>" alt="category">
                                <div class="wrap-title">
                                    <h2><?php echo $category['name']?></h2>
                                    <p><?php echo $catParent['totalQtt']?> sản phẩm</p>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php if($product_model->getBestSelling(0, 6)) : ?>
        <div class="section2">
        <div class="container">
            <div class="title-section">
                <h2 class="text-uppercase">
                    Sản phẩm bán chạy
                </h2>
            </div>
            <div id="carouselExampleControls" class="carousel slide px-2 hidden-md hidden-sm" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row" style="flex-wrap: nowrap">
                            <?php foreach ($product_model->getBestSelling(0, 6) as $product) :
                                $min_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['min'];
                                $max_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['max'];
                                $urlDetail = "index.php?controller=product&action=detail&name={$product['name']}&category_id={$product['category_id']}";
                                $image = $product_model->getFirstImage($product['name'], $product['category_id'])['image']; ?>
                                <div class="col-2">
                                    <div class="item-product px-2">
                                        <div class="img-product d-flex align-items-center justify-content-center">
                                            <a href="<?php echo $urlDetail?>">
                                                <img src="assets/uploads/<?php echo $image; ?>" alt="">
                                            </a>
                                        </div>
                                        <div class="name-product mt-2">
                                            <a href="<?php echo $urlDetail?>">
                                                <span class="text-2"><?php echo $product['name']; ?></span>
                                            </a>
                                        </div>
                                        <div class="text-danger text-start">
                                            <?php
                                            echo number_format($min_price) . "<sup>đ</sup>";
                                            echo $min_price != $max_price ? " - " . number_format($max_price) . "<sup>đ</sup>" : '';
                                            ?>
                                        </div>
                                        <div class="detail-product text-end mb-1">
                                            <a class="btn-show-detail" href="<?php echo $urlDetail?>">Chi tiết</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php $amount_per_slide = ceil($amount/6); ?>
                    <?php for ($i = 1; $i < $amount_per_slide; $i++) : ?>
                        <div class="carousel-item">
                            <div class="row" style="flex-wrap: nowrap">
                                <?php foreach ($product_model->getBestSelling($i*6, 6) as $product) :
                                    $min_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['min'];
                                    $max_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['max'];
                                    $urlDetail = "index.php?controller=product&action=detail&name={$product['name']}&category_id={$product['category_id']}";
                                    $image = $product_model->getFirstImage($product['name'], $product['category_id'])['image'];
                                ?>
                                    <div class="col-2">
                                        <div class="item-product px-2">
                                            <div class="img-product d-flex align-items-center justify-content-center">
                                                <a href="<?php echo $urlDetail?>">
                                                    <img src="assets/uploads/<?php echo $image; ?>" alt="">
                                                </a>
                                            </div>
                                            <div class="name-product mt-2">
                                                <a href="<?php echo $urlDetail?>">
                                                    <span class="text-2"><?php echo $product['name']; ?></span>
                                                </a>
                                            </div>
                                            <div class="text-danger text-start">
                                                <?php
                                                echo number_format($min_price) . "<sup>đ</sup>";
                                                echo $min_price != $max_price ? " - " . number_format($max_price) . "<sup>đ</sup>" : '';
                                                ?>
                                            </div>
                                            <div class="detail-product text-end mb-1">
                                                <a class="btn-show-detail" href="<?php echo $urlDetail?>">Chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
            <div id="carouselExampleControls" class="carousel slide px-2 hidden-lg hidden-sm" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row" style="flex-wrap: nowrap">
                            <?php foreach ($product_model->getBestSelling(0, 3) as $product) :
                                $min_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['min'];
                                $max_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['max'];
                                $urlDetail = "index.php?controller=product&action=detail&name={$product['name']}&category_id={$product['category_id']}";
                                $image = $product_model->getFirstImage($product['name'], $product['category_id'])['image']; ?>
                                <div class="col-4">
                                    <div class="item-product px-2">
                                        <div class="img-product d-flex align-items-center justify-content-center">
                                            <a href="<?php echo $urlDetail?>">
                                                <img src="assets/uploads/<?php echo $image; ?>" alt="">
                                            </a>
                                        </div>
                                        <div class="name-product mt-2">
                                            <a href="<?php echo $urlDetail?>">
                                                <span class="text-2"><?php echo $product['name']; ?></span>
                                            </a>
                                        </div>
                                        <div class="text-danger text-start">
                                            <?php
                                            echo number_format($min_price) . "<sup>đ</sup>";
                                            echo $min_price != $max_price ? " - " . number_format($max_price) . "<sup>đ</sup>" : '';
                                            ?>
                                        </div>
                                        <div class="detail-product text-end mb-1">
                                            <a class="btn-show-detail" href="<?php echo $urlDetail?>">Chi tiết</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php $amount_per_slide = ceil($amount/3); ?>
                    <?php for ($i = 1; $i < $amount_per_slide; $i++) : ?>
                        <div class="carousel-item">
                            <div class="row" style="flex-wrap: nowrap">
                                <?php foreach ($product_model->getBestSelling($i*3, 3) as $product) :
                                    $min_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['min'];
                                    $max_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['max'];
                                    $urlDetail = "index.php?controller=product&action=detail&name={$product['name']}&category_id={$product['category_id']}";
                                    $image = $product_model->getFirstImage($product['name'], $product['category_id'])['image'];
                                ?>
                                    <div class="col-4">
                                        <div class="item-product px-2">
                                            <div class="img-product d-flex align-items-center justify-content-center">
                                                <a href="<?php echo $urlDetail?>">
                                                    <img src="assets/uploads/<?php echo $image; ?>" alt="">
                                                </a>
                                            </div>
                                            <div class="name-product mt-2">
                                                <a href="<?php echo $urlDetail?>">
                                                    <span class="text-2"><?php echo $product['name']; ?></span>
                                                </a>
                                            </div>
                                            <div class="text-danger text-start">
                                                <?php
                                                echo number_format($min_price) . "<sup>đ</sup>";
                                                echo $min_price != $max_price ? " - " . number_format($max_price) . "<sup>đ</sup>" : '';
                                                ?>
                                            </div>
                                            <div class="detail-product text-end mb-1">
                                                <a class="btn-show-detail" href="<?php echo $urlDetail?>">Chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
            <div id="carouselExampleControls" class="carousel slide px-2 hidden-lg hidden-md" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row" style="flex-wrap: nowrap">
                            <?php foreach ($product_model->getBestSelling(0, 2) as $product) :
                                $min_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['min'];
                                $max_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['max'];
                                $urlDetail = "index.php?controller=product&action=detail&name={$product['name']}&category_id={$product['category_id']}";
                                $image = $product_model->getFirstImage($product['name'], $product['category_id'])['image']; ?>
                                <div class="col-6">
                                    <div class="item-product px-2">
                                        <div class="img-product d-flex align-items-center justify-content-center">
                                            <a href="<?php echo $urlDetail?>">
                                                <img src="assets/uploads/<?php echo $image; ?>" alt="">
                                            </a>
                                        </div>
                                        <div class="name-product mt-2">
                                            <a href="<?php echo $urlDetail?>">
                                                <span class="text-2"><?php echo $product['name']; ?></span>
                                            </a>
                                        </div>
                                        <div class="text-danger text-start">
                                            <?php
                                            echo number_format($min_price) . "<sup>đ</sup>";
                                            echo $min_price != $max_price ? " - " . number_format($max_price) . "<sup>đ</sup>" : '';
                                            ?>
                                        </div>
                                        <div class="detail-product text-end mb-1">
                                            <a class="btn-show-detail" href="<?php echo $urlDetail?>">Chi tiết</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php $amount_per_slide = ceil($amount/2); ?>
                    <?php for ($i = 1; $i < $amount_per_slide; $i++) : ?>
                        <div class="carousel-item">
                            <div class="row" style="flex-wrap: nowrap">
                                <?php foreach ($product_model->getBestSelling($i*2, 2) as $product) :
                                    $min_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['min'];
                                    $max_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['max'];
                                    $urlDetail = "index.php?controller=product&action=detail&name={$product['name']}&category_id={$product['category_id']}";
                                    $image = $product_model->getFirstImage($product['name'], $product['category_id'])['image']; ?>
                                    <div class="col-6">
                                        <div class="item-product px-2">
                                            <div class="img-product d-flex align-items-center justify-content-center">
                                                <a href="<?php echo $urlDetail?>">
                                                    <img src="assets/uploads/<?php echo $image ?>" alt="">
                                                </a>
                                            </div>
                                            <div class="name-product mt-2">
                                                <a href="<?php echo $urlDetail?>">
                                                    <span class="text-2"><?php echo $product['name']; ?></span>
                                                </a>
                                            </div>
                                            <div class="text-danger text-start">
                                                <?php
                                                echo number_format($min_price) . "<sup>đ</sup>";
                                                echo $min_price != $max_price ? " - " . number_format($max_price) . "<sup>đ</sup>" : '';
                                                ?>
                                            </div>
                                            <div class="detail-product text-end mb-1">
                                                <a class="btn-show-detail" href="<?php echo $urlDetail?>">Chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="section3">
        <div class="container">
            <div class="wrap-active-sneaker">
                <?php foreach ($cat_model->getBrandOrderByCountProduct(0,1) as $catParent) :
                    $category = $cat_model->getCategoryById($catParent['parent_cat']);
                    $similar_products = $product_model->getAllByCatParent($category['id'], 0, 5);
                    ?>
                    <div class="wrap">
                        <div class="row">
                            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <div class="position-relative justify-content-center align-middle">
                                    <img src="assets/uploads/<?php echo $category['image']?>" alt="">
                                </div>
                            </div>
                            <div class="content-active-sneaker col-lg-7 col-md-7 col-sm-12 col-xs-12">
                                <div class="title-section">
                                    <h2>
                                        <a href="index.php?controller=category&action=showProductByCatParent&cat_parent=<?php echo $category['id']?>">
                                            <?php echo $category['name']?>
                                        </a>
                                    </h2>
                                </div>
                                <div class="content-section">
                                    <p>
                                        <?php echo $category['description']?>
                                    </p>
                                </div>
                                <div class="show-all">
                                    <a href="index.php?controller=category&action=showProductByCatParent&cat_parent=<?php echo $category['id']?>">
                                        <span>Xem tất cả</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row px-2 mt-3">
                        <?php foreach ($similar_products as $similar_product) :
                            $min_price = $product_model->getMinMaxPrice($similar_product['name'], $similar_product['category_id'])['min'];
                            $max_price = $product_model->getMinMaxPrice($similar_product['name'], $similar_product['category_id'])['max'];
                            $image = $product_model->getFirstImage($similar_product['name'], $similar_product['category_id'])['image'];
                            $urlDetail = "index.php?controller=product&action=detail&name={$similar_product['name']}&category_id={$similar_product['category_id']}"
                            ?>
                            <div class="col-lg-2 col-6 mb-4">
                                <div class="item-product">
                                    <div class="img-product d-flex align-items-center justify-content-center">
                                        <a href="<?php echo $urlDetail; ?>">
                                            <img src="assets/uploads/<?php echo $image; ?>" alt="">
                                        </a>
                                    </div>
                                    <div class="name-product mt-2">
                                        <a href="<?php echo $urlDetail; ?>">
                                            <span class="text-2"><?php echo $similar_product['name']; ?></span>
                                        </a>
                                    </div>
                                    <div class="text-danger">
                                        <?php
                                        echo number_format($min_price) . "<sup>đ</sup>";
                                        echo $min_price != $max_price ? " - " . number_format($max_price) . "<sup>đ</sup>" : '';
                                        ?>
                                    </div>
                                    <div class="detail-product text-end mb-1">
                                        <a class="btn-show-detail" href="<?php echo $urlDetail; ?>">Chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>