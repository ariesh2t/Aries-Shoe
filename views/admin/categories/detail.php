<h2 class="mb-4">Chi tiết danh mục</h2>

<div class="row mb-3 justify-content-center">
    <div class="col-lg-2 col-md-8 col-sm-8 h5 text-lg-right text-danger">ID</div>
    <div class="col-lg-7 col-md-8 col-sm-8  mb-2 align-bottom"><?php echo $category['id']; ?></div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-lg-2 col-md-8 col-sm-8 text-lg-right h5 text-danger">Hình ảnh</div>
    <div class="col-lg-7 col-md-8 col-sm-8  mb-2 align-bottom">
        <img src="<?php echo "assets/uploads/" . $category['image']; ?>" alt="" class="w-50 img-thumbnail">
    </div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-lg-2 col-md-8 col-sm-8 text-lg-right h5 text-danger">Tên danh mục</div>
    <div class="col-lg-7 col-md-8 col-sm-8  mb-2 align-bottom"><?php echo $category['name']; ?></div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-lg-2 col-md-8 col-sm-8 text-lg-right h5 text-danger">Mô tả</div>
    <div class="col-lg-7 col-md-8 col-sm-8  mb-2 align-bottom"><?php echo $category['description']; ?></div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-lg-2 col-md-8 col-sm-8 text-lg-right h5 text-danger">Danh mục cha</div>
    <div class="col-lg-7 col-md-8 col-sm-8  mb-2 align-bottom">
        <?php
        $cat_model = new Category();
        if ($category['parent_cat'] != 0) {
            $cat_parent = $cat_model->getCategoryById($category['parent_cat'])['name'];
        } else {
            $cat_parent = "NULL";
        }
        echo $cat_parent;
        ?>
    </div>
</div>
<div class="row mb-3 justify-content-center">
    <div class="col-lg-2 col-md-8 col-sm-8 text-lg-right h5 text-danger">Created at</div>
    <div class="col-lg-7 mb-2 col-md-8 col-sm-8  mb-2 align-bottom">
        <?php echo date('d-m-Y H:i:s', strtotime($category['created_at'])); ?>
    </div>
</div>
<div class="row">
    <div class=" col-6">
        <a href="index.php?controller=categoryAdmin&action=update&id=<?php echo $category['id'] ?>" class="btn btn-warning text-right">
            <i class="fa fa-pencil-alt"></i> Sửa thông tin
        </a>
    </div>
    <div class="text-end col-6">
        <a href="index.php?controller=categoryAdmin&action=index" class="btn btn-danger text-right"><i class="fa-solid fa-rotate-left"></i> Quay lại</a>
    </div>
</div>
