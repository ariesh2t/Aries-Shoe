<?php
    require_once 'models/Category.php';
    require_once 'models/Product.php';
    $cat_model = new Category();
    $product_model = new Product();
    $parentCatNavbar = $cat_model->getCatParentForNavbar();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $this->title ?></title><!-- CSS only -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="assets/css/all.min.css"/>
    <link rel="stylesheet" href="assets/css/styleUser.css">
    <!-- Tooltip plugin (zebra) css file -->
    <link rel="stylesheet" href="assets/css/zebra_tooltips.min.css"/>
    <!-- Owl Carousel plugin css file. only used pages -->
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css"/>
</head>
<body>
    <div class="position-fixed top-0 w-100" style="background: #fff; z-index: 1000">
        <?php require_once 'header.php'; ?>
    </div>

    <div class="main">
        <div class="bread-crumb mb-5">
            <div class="container-fluid">
                <div class="navigation">
                    <a href="index.php"><span>Trang chủ</span></a>
                    <span class="red">
                        <?php
                            if (!isset($_GET['category_id'])) {
                                echo isset($_GET['cat_parent']) && !empty($_GET['cat_parent']) ? "<i class='fas fa-angle-right'></i>" . $cat_model->getCategoryById($_GET['cat_parent'])['name'] : '';
                            } elseif (!empty($_GET['category_id'])){
                                $id = $_GET['category_id'];
                                $category = $cat_model->getCategoryById($id);
                                $catParent = $cat_model->getCategoryById($category['parent_cat']);
                                echo "<i class='fas fa-angle-right'></i> <a style='color: #ff2d37' href='index.php?controller=category&action=showProductByCatParent&cat_parent=" . $catParent['id'] . "'>" . $catParent['name'] . "</a>";
                                echo "<i class='fas fa-angle-right'></i>" . $category['name'];
                            }
                        ?>
                    </span>
                    <span class="red">
                        <?php echo isset($_GET['product_id']) && !empty($_GET['product_id']) ? "<i class='fas fa-angle-right'></i>" . $product_model->getById($_GET['product_id'])['name'] : '' ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="position-relative">
            <span class="ajax-message"></span>
            <span class="ajax-error"></span>
        </div>
        <div class="container-fluid">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($this->error['error'])): ?>
                <div class="alert alert-danger">
                    <?php
                    echo $this->error['error'];
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <?php echo $this->content; ?>
    </div>

<?php require_once 'footer.php'; ?><!-- JavaScript Bundle with Popper -->
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/scriptUser.js"></script>
<script src="assets/js/zebra_tooltips.min.js"></script>

</body>

</html>