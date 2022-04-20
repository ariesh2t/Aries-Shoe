
<h2 class="mb-4">Chi tiết sản phẩm #<?php echo $product['id']; ?></h2>

<div class="row mb-3 justify-content-center">
    <div class="col-lg-2 col-md-8 col-sm-8 text-lg-right h5 text-danger">Tên sản phẩm</div>
    <div class="col-lg-7 col-md-8 col-sm-8  mb-2 align-bottom"><?php echo $product['name']; ?></div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-lg-2 col-md-8 col-sm-8 text-lg-right h5 text-danger">Hình ảnh</div>
    <div class="col-lg-7 col-md-8 col-sm-8  mb-2 align-bottom">
        <img src="<?php echo "assets/uploads/" . $product['image']; ?>" alt="" class="w-50 img-thumbnail">
    </div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-lg-2 col-md-8 col-sm-8 text-lg-right h5 text-danger">Màu</div>
    <div class="col-lg-7 col-md-8 col-sm-8  mb-2 align-bottom">
        <div style="width: 100px; height: 30px; background: <?php echo $product['color']; ?>"></div>
        <div><?php echo $product['color']; ?></div>
    </div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-lg-2 col-md-8 col-sm-8 text-lg-right h5 text-danger">Size</div>
    <div class="col-lg-7 col-md-8 col-sm-8  mb-2 align-bottom"><?php echo $product['size']; ?></div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-lg-2 col-md-8 col-sm-8 text-lg-right h5 text-danger">Giá bán</div>
    <div class="col-lg-7 col-md-8 col-sm-8  mb-2 align-bottom"><?php echo number_format($product['price']); ?></div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-lg-2 col-md-8 col-sm-8 text-lg-right h5 text-danger">Mô tả</div>
    <div class="col-lg-7 col-md-8 col-sm-8  mb-2 align-bottom"><?php echo $product['description']; ?></div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-lg-2 col-md-8 col-sm-8 text-lg-right h5 text-danger">Danh mục</div>
    <div class="col-lg-7 col-md-8 col-sm-8  mb-2 align-bottom">
        <?php echo $product['category_name'] ?>
    </div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-lg-2 col-md-8 col-sm-8 text-lg-right h5 text-danger">Created at</div>
    <div class="col-lg-7 mb-2 col-md-8 col-sm-8  mb-2 align-bottom">
        <?php echo date('d-m-Y H:i:s', strtotime($product['created_at'])); ?>
    </div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-lg-2 col-md-8 col-sm-8 text-lg-right h5 text-danger">Updated at</div>
    <div class="col-lg-7 mb-2 col-md-8 col-sm-8  mb-2 align-bottom">
        <?php echo date('d-m-Y H:i:s', strtotime($product['updated_at'])); ?>
    </div>
</div>
<div class="row">
    <div class=" col-6">
        <a href="index.php?controller=productAdmin&action=update&id=<?php echo $product['id'] ?>" class="btn btn-warning text-right">
            <i class="fa fa-pencil-alt"></i> Sửa thông tin
        </a>
    </div>
    <div class="text-end col-6">
        <a href="index.php?controller=productAdmin&action=index" class="btn btn-danger text-right">
            <i class="fa-solid fa-rotate-left"></i> Quay lại
        </a>
    </div>
</div>
