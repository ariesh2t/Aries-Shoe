<?php
$product_model = new Product();
?>
<div class="center-main">
    <div class="container">
        <div class="title-main center">
            <h3 class="text-start text-lg-center"><?php echo $category['name']; ?></h3>
        </div>
        <div class="section">
            <div class="row">
                <div class="side-bar col-lg-3 hidden-md-sm">
                    <form action="" method="get">
                        <input type="hidden" name="controller" value="category">
                        <input type="hidden" name="action" value="showProductByCatChild">
                        <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                        <fieldset class="mt-3">
                            <legend>Mức giá</legend>
                            <div class="input-group mt-2">
                                <input type="number" class="form-control" name="min-price"
                                       value="<?php echo $_GET['min-price'] ?? ''; ?>" placeholder="Min" aria-label="Min">
                                <span class="input-group-text">-</span>
                                <input type="number" class="form-control" name="max-price"
                                       value="<?php echo $_GET['max-price'] ?? ''; ?>" placeholder="Max" aria-label="Max">
                            </div>
                        </fieldset>
                        <fieldset class="mt-3">
                            <legend>Size</legend>
                            <?php foreach ($pr_sizes as $pr_size) :
                                $checked = '';
                                if (isset($_GET['pr_sizes'])) {
                                    $checked = (in_array($pr_size['size'], $_GET['pr_sizes'])) ? 'checked' : '';
                                }
                                ?>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" name="pr_sizes[]" type="checkbox" value="<?php echo $pr_size['size']; ?>"
                                        <?php echo $checked; ?> id="size_<?php echo $pr_size['size']; ?>">
                                    <label class="form-check-label" for="size_<?php echo $pr_size['size']; ?>"><?php echo $pr_size['size']; ?></label>
                                </div>
                            <?php endforeach; ?>
                        </fieldset>
                        <div class="search text-end mt-5">
                            <input class="btn btn-primary" style="padding: 0.4em" type="submit" value="Tìm kiếm">
                            <a class="btn btn-warning" href="index.php?controller=category&action=showProductByCatChild&category_id=<?php echo $category['id']; ?>">Hủy lọc</a>
                        </div>
                    </form>
                </div>
                <!-- Button trigger modal -->
                <div class="text-end hidden-lg">
                    <button type="button" id="model_search" class="btn btn-primary w-25" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Tìm kiếm
                    </button>
                </div>
                <!-- Modal -->
                <div class="modal fade hidden-lg" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable" style="height: 80vh">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tìm kiếm</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="get" class="row">
                                    <input type="hidden" name="controller" value="category">
                                    <input type="hidden" name="action" value="showProductByCatChild">
                                    <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>">
                                    <fieldset class="col-12 mt-3">
                                        <legend>Mức giá</legend>
                                        <div class="input-group mt-2">
                                            <input type="number" class="form-control" name="min-price"
                                                   value="<?php echo $_GET['min-price'] ?? ''; ?>" placeholder="Min" aria-label="Min">
                                            <span class="input-group-text">-</span>
                                            <input type="number" class="form-control" name="max-price"
                                                   value="<?php echo $_GET['max-price'] ?? ''; ?>" placeholder="Max" aria-label="Max">
                                        </div>
                                    </fieldset>
                                    <fieldset class="col-12 mt-3">
                                        <legend>Size</legend>
                                        <?php foreach ($pr_sizes as $pr_size) :
                                            $checked = '';
                                            if (isset($_GET['pr_sizes'])) {
                                                $checked = (in_array($pr_size['size'], $_GET['pr_sizes'])) ? 'checked' : '';
                                            }
                                            ?>
                                            <div class="form-check form-check-inline mt-2">
                                                <input class="form-check-input" name="pr_sizes[]" type="checkbox" value="<?php echo $pr_size['size']; ?>"
                                                    <?php echo $checked; ?> id="size_<?php echo $pr_size['size']; ?>">
                                                <label class="form-check-label" for="size_<?php echo $pr_size['size']; ?>"><?php echo $pr_size['size']; ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </fieldset>
                                    <div class="search text-end modal-footer">
                                        <input class="btn btn-primary" style="padding: 0.4em" type="submit" value="Tìm kiếm">
                                        <a class="btn btn-warning" href="index.php?controller=category&action=showProductByCatChild&category_id=<?php echo $category['id']; ?>">Hủy lọc</a>
                                        <a class="btn btn-danger" data-bs-dismiss="modal">Đóng</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="main-content col-lg-9 col-md-12 col-sm-12">
                    <?php if($total_page>0) : ?>
                    <div class="row justify-content-center difference-products">
                        <?php foreach ($products as $product) :
                            $min_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['min'];
                            $max_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['max'];
                            $image = $product_model->getFirstImage($product['name'], $product['category_id'])['image'];
                            $urlDetail = "index.php?controller=product&action=detail&name={$product['name']}&category_id={$product['category_id']}"
                            ?>
                            <div class="col-lg-3 col-md-5 col-sm-10 mb-4">
                                <div class="item-product">
                                    <div class="img-product d-flex align-items-center justify-content-center">
                                        <a href="<?php echo $urlDetail; ?>">
                                            <img src="assets/uploads/<?php echo $image; ?>" alt="">
                                        </a>
                                    </div>
                                    <div class="name-product mt-2">
                                        <a href="<?php echo $urlDetail; ?>">
                                            <span class="text-2"><?php echo $product['name']; ?></span>
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
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="pagination float-end">
        <?php
        $url = $_SERVER['QUERY_STRING'];
        $pos = strpos($url, '&page');
        if ($pos) {
            $url = substr($url, 0, $pos);
        }

        if ($total_page > 1) {
            $prev = $current_page - 1; $next = $current_page + 1;

            echo "<a href='index.php?" . $url . "&page=1" . "' class='wh-35 btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angles-left'></i></a>";

            if ($prev >= 1) {
                echo "<a href='index.php?" . $url . "&page=$prev" . "' class='wh-35 btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angle-left'></i></a>";
                echo "<a href='index.php?" . $url . "&page=$prev" . "' class='wh-35 btn btn-info me-2 rounded-circle'>$prev</a>";
            } else {
                echo "<span class='wh-35 btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angle-left'></i></span>";
            }

            echo "<a href='index.php?" . $url . "&page=$current_page" . "' class='wh-35 btn btn-warning me-2 rounded-circle'>$current_page</a>";

            if ($next <= $total_page) {
                echo "<a href='index.php?" . $url . "&page=$next" . "' class='wh-35 btn btn-info me-2 rounded-circle'>$next</a>";
                echo "<a href='index.php?" . $url . "&page=$next" . "' class='wh-35 btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angle-right'></i></a>";
            }else {
                echo "<span class='wh-35 btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angle-right'></i></span>";
            }

            echo "<a href='index.php?" . $url . "&page=$total_page" . "' class='wh-35 btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angles-right'></i></a>";
        }
        ?>
    </div>
    <?php echo "<p class='float-end me-5' style='line-height: 40px'>Trang ".$current_page."/".$total_page . "</p>"; ?>
</div>
<?php else: ?>
<div>Hiện chưa có sản phẩm!</div>
<?php endif; ?>
