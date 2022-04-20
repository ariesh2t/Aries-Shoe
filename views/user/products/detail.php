<?php
require_once "models/Category.php";
    $product_model = new Product();
    $category_model = new Category();
    $catChild = $category_model->getCategoryById($product['category_id']);
    $catParent = $category_model->getCategoryById($catChild['parent_cat']);
    $similar_products = $product_model->getAllByCatParent($catParent['id'], 0, 8);

    $min_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['min'];
    $max_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['max'];
?>
<div class="mid-main">
    <div class="container">
        <div class="row p-5 shadow-lg justify-content-center">
            <div class="col-lg-4 col-md-4 col-sm-12 img">
                <div class="img-product-big mb-3">
                    <img class="img-thumbnail" id="src" src="assets/uploads/<?php echo $product_model->getFirstImage($product['name'], $product['category_id'])['image'] ?>" alt="default">
                </div>
                <div class="list-img row justify-content-around">
                    <?php foreach ($images as $image) : ?>
                        <div class="img-item col-2">
                            <img class="src img-thumbnail" src="assets/uploads/<?php echo $image['image'] ?>" alt="default">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="col-lg-7 col-md-7 col-sm-12">
                <div class="h3">
                    <?php echo $product['name'] ?>
                </div>
                <div id="price" class="my-3 p-3 h3 text-danger" style="background: rgba(238,238,238,0.53)">
                    <?php
                    echo number_format($min_price) . "<sup>đ</sup>";
                    echo $min_price != $max_price ? " - " . number_format($max_price) . "<sup>đ</sup>" : '';
                    ?>
                </div>
                <form id="add-cart">
                    <input type="hidden" id="name" name="name" value="<?php echo $product['name'] ?>">
                    <input type="hidden" id="category_id" name="category_id" value="<?php echo $product['category_id'] ?>">
                    <table class="w-100">
                        <tr>
                            <td width="20%" class="p-3">
                                <label for="category">Danh mục</label>
                            </td>
                            <td>
                                <?php echo $category_model->getCategoryById($product['category_id'])['name'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-3">
                                <label for="category">Chọn size</label>
                            </td>
                            <td>
                                <?php foreach ($sizes as $size) : ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input sizes" type="radio" name="sizes" id="size-<?php echo $size['size']; ?>" value="<?php echo $size['size']; ?>">
                                        <label class="form-check-label" for="size-<?php echo $size['size'] ?>"><?php echo $size['size']; ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="p-3">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Hướng dẫn chọn size
                                </button>
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Bảng quy đổi size giày</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <h5 class="modal-title" id="exampleModalLabel">Size giày cho trẻ</h5>
                                                <img src="assets/images/banner/size-giay-tre.jpg" alt="">
                                                <h5 class="modal-title mt-5" id="exampleModalLabel">Size giày người lớn</h5>
                                                <img src="assets/images/banner/Bang-do-size-chan.jpg" alt="">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-3">
                                <label for="category">Màu sắc</label>
                            </td>
                            <td>
                                <?php foreach ($colors as $color) : ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input colors" type="radio" name="colors" id="color-<?php echo $color['color']; ?>" value="<?php echo $color['color']; ?>">
                                        <label class="form-check-label" for="color-<?php echo $color['color'] ?>"
                                               style="border: 1px solid; width: 20px; height: 20px; background: <?php echo $color['color'] ?>">
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-3">
                                <label for="category">Số lượng</label>
                            </td>
                            <td>
                                <div class="quanlity-product">
                                    <div class="buttons_added">
                                        <input class="minus is-form" type="button" value="-">
                                        <input id="quantity" aria-label="quantity" class="input-qty" max="<?php echo $product_model->getMaxAmount($product['name'], $product['category_id'])['maxAmount']?>" min="1" name="" type="number" value="1">
                                        <input class="plus is-form" type="button" value="+">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-end">
                                <span id="add-to-cart" class="btn btn-danger"><i class="fa-solid fa-cart-plus text-white"></i> Thêm vào giỏ</span>
                                <!-- Button trigger modal -->
                                <button type="button" id="btn-login" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Bạn chưa đăng nhập</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="index.php?controller=login&action=login" class="btn btn-primary">Đăng nhập</a>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>

        <div class="row p-5 shadow-lg justify-content-center mt-4">
            <div class="text-start h4 p-3 mb-4" style="background: rgba(238,238,238,0.53)">
                Mô tả sản phẩm
            </div>
            <div class="product-desc" style="line-height: 2rem">
                <?php echo $product['description']; ?>
            </div>
        </div>

        <div class="row p-5 shadow-lg justify-content-center mt-4">
            <div class="h3 text-center text-danger py-4">
                Sản phẩm cùng loại
            </div>
            <div class="row justify-content-center difference-products">
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

        </div>
    </div>
</div>