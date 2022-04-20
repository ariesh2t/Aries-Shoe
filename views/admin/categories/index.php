<h2>Tìm kiếm</h2>
<form action="" method="get" class="row align-middle">
    <input type="hidden" name="controller" value="categoryAdmin"/>
    <input type="hidden" name="action" value="index"/>
    <div class="col-lg-4 col-md-12 col-sm-12 mb-2">
        <label class="form-label" for="category">Tên danh mục</label>
        <input id="category" type="text" name="name" value="<?php echo $_GET['name'] ?? '' ?>"
               placeholder="Nhập tên category" class="form-control"/>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12 mb-2">
        <label class="form-label" for="parent_cat">Chọn danh mục cha</label>
        <select id="parent_cat" class="form-select" name="parent_cat">
            <option disabled selected></option>
            <?php if (isset($_GET['parent_cat']) && strcmp($_GET['parent_cat'], '0')): ?>
                <option value="'0'" selected>NULL</option>
            <?php else: ?>
                <option value="'0'">NULL</option>
            <?php endif; ?>
            <?php foreach ($parent_cats as $parent_cat):
                $selected = '';
                if(isset($_GET['parent_cat']) && $_GET['parent_cat'] == $parent_cat['id']) {
                    $selected = 'selected';
                }
                ?>
                <option value="<?php echo $parent_cat['id']; ?>" <?php echo $selected; ?> >
                    <?php echo $parent_cat['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12 text-right text-lg-center pt-4 mb-2">
        <button type="submit" class="btn btn-success"><i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm</button>
        <a href="index.php?controller=categoryAdmin" class="btn btn-warning">Xóa filter</a>
    </div>
</form>

<h2 class="mt-5">Danh sách category</h2>
<a href="index.php?controller=categoryAdmin&action=create" class="btn btn-primary">
    <i class="fa-solid fa-plus"></i> Thêm mới
</a>
<table class="table table-hover table-striped mt-2">
    <tr>
        <th scope="col">ID</th>
        <th scope="col" width="20%">Tên danh mục</th>
        <th scope="col">Hình ảnh</th>
        <th scope="col" width="40%">Mô tả</th>
        <th scope="col" width="12%">Danh mục cha</th>
        <th scope="col">Created at</th>
        <th scope="col"></th>
    </tr>
    <?php if (!empty($categories)): ?>
    <?php foreach ($categories as $category): ?>
        <tr style="height: 60px">
            <th scope="row">
                <?php echo $category['id']; ?>
            </th>
            <td>
                <div class="text-2 text-center"><?php echo $category['name'] ?></div>
            </td>
            <td>
                <?php if (!empty($category['image'])): ?>
                    <img height="50" src="assets/uploads/<?php echo $category['image'] ?>" alt="<?php echo $category['image'] ?>">
                <?php endif; ?>
            </td>
            <td class="align-middle">
                <div class="text-2"><?php echo $category['description'] ?></div>
            </td>
            <td>
                <div class="text-2 text-center">
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
            </td>
            <td>
                <?php echo date('d-m-Y', strtotime($category['created_at'])); ?>
            </td>
            <td>
                <a class="mr-1" href="index.php?controller=categoryAdmin&action=detail&id=<?php echo $category['id'] ?>" title="Chi tiết">
                    <i class="fa fa-eye"></i>
                </a>
                <a class="mr-1" href="index.php?controller=categoryAdmin&action=update&id=<?php echo $category['id'] ?>" title="Sửa">
                    <i class="fa fa-pencil-alt"></i>
                </a>
                <a class="mr-1" href="index.php?controller=categoryAdmin&action=delete&id=<?php echo $category['id'] ?>" title="Xóa"
                   onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
                    <i class="fa fa-trash"></i>
                </a>
            </td>
        </tr>
    <?php endforeach ?>
    <?php else: ?>
        <tr>
            <td colspan="7">Không có bản ghi nào</td>
        </tr>
    <?php endif; ?>
</table>

<?php
    require_once "views/adminLayouts/pagination.php";
?>
