<?php
require_once 'models/Category.php';
$cat_model = new Category();
?>
<div class="footer">
    <div class="head-footer">
        <div class="container-lg container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="icon">
                        <img src="assets/images/icons/free-transfer.png" alt="icon">
                    </div>
                    <div class="content">
                        <p class="title-content">Miễn phí vận chuyển</p>
                        <span class="desc-content">Miễn phí trong nội thành</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="icon">
                        <img src="assets/images/icons/return-goods.png" alt="icon">
                    </div>
                    <div class="content">
                        <p class="title-content">Đổi trả hàng</p>
                        <span class="desc-content">Đổi trả hàng trong 30 ngày</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="icon">
                        <img src="assets/images/icons/reduce-time.png" alt="icon">
                    </div>
                    <div class="content">
                        <p class="title-content">Tiết kiệm thời gian</p>
                        <span class="desc-content">Mua sắm online dễ hơn</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="icon">
                        <img src="assets/images/icons/consuling.png" alt="icon">
                    </div>
                    <div class="content">
                        <p class="title-content">Tư vấn trực tiếp</p>
                        <span class="desc-content">Đội ngũ tư vấn nhiệt tình</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mid-footer">
        <div class="container">
            <div class="row justify-content-evenly">
                <div class="col-8 col-md-6 col-lg-3">
                    <h4 class="title-bold">Chăm sóc khách hàng</h4>
                    <ul class="dropdown-list">
                        <li>
                            <a href="index.php">Trang chủ</a>
                        </li>
                        <li>
                            <a href="index.php?controller=product&action=showAll">Sản phẩm</a>
                        </li>
                    </ul>
                </div>
                <div class="col-8 col-md-6 col-lg-3">
                    <h4 class="title-bold">Danh mục</h4>
                    <ul class="dropdown-list">
                        <?php foreach ($cat_model->getAllCatParent(0,5) as $catParent): ?>
                            <li>
                                <a href="index.php?controller=category&action=showProductByCatParent&cat_parent=<?php echo $catParent['id']?>">
                                    <?php echo $catParent['name']?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom-footer">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="item-f">
                        <h4 id="phone" class="title-menu">Gọi mua hàng (8h-21h)</h4>
                        <a class="phone" href="tel:0394546187">0394546187</a>
                        <p>Tất cả các ngày trong tuần</p>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="item-f">
                        <h4 class="title-menu">Gọi khiếu nại (8h-20h)</h4>
                        <a class="phone" href="tel:0394546187">0394546187</a>
                        <p>Các ngày trong tuần (Trừ ngày lễ)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>