<?php
    require_once 'models/Product.php';
    $product_model = new Product();
?>
<div class="center-main">
    <div class="container">
        <div class="title-main center">
            <h3 class="text-start text-lg-center"><?php echo $category['name'] ?></h3>
        </div>
        <div class="section">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <?php if(!empty($catChilds)) : ?>
                        <?php foreach ($catChilds as $catChild) : ?>
                        <?php
                            $products = $product_model->getAllByCatChild($catChild['id'], 0, 8);
                            if (!empty($products)) :
                        ?>
                        <a class="h5 text-danger" href="index.php?controller=category&action=showProductByCatChild&category_id=<?php echo $catChild['id'] ?>">
                            <?php echo $catChild['name'] ?>
                        </a>
                        <hr class="bg-danger">
                        <div class="row mb-5 justify-content-start difference-products">
                            <?php foreach ($products as $product) :
                                $min_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['min'];
                                $max_price = $product_model->getMinMaxPrice($product['name'], $product['category_id'])['max'];
                                $image = $product_model->getFirstImage($product['name'], $product['category_id'])['image'];
                                $urlDetail = "index.php?controller=product&action=detail&name={$product['name']}&category_id={$product['category_id']}"
                                ?>
                                <div class="col-lg-3 col-6 mb-4">
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
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <div>
                        Chưa có sản phẩm nào!
                    </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-3">
                    <div class="text-center h4 text-danger">Danh mục</div>
                    <div class="p-2" style="height: 300px; overflow: auto; border: 1px solid #416cef">
                        <table>
                            <?php foreach ($catParents as $catParent) : ?>
                                <tr>
                                    <td class="p-1">
                                        <div class="img-catp">
                                            <img style="height: 50px" src="<?php echo "assets/uploads/" . $catParent['image'] ?>" alt="">
                                        </div>
                                    </td>
                                    <td class="px-1">
                                        <a class="text-dark" href="index.php?controller=category&action=showProductByCatParent&cat_parent=<?php echo $catParent['id'] ?>">
                                            <?php echo $catParent['name'] ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
