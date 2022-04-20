<?php
require_once "models/Category.php";
$category_model = new Category();
?>
<h2>Tìm kiếm</h2>
    <form action="" method="GET" class="row">
        <input type="hidden" name="controller" value="productAdmin"/>
        <input type="hidden" name="action" value="index"/>
        <div class="col-lg-4 col-md-12 col-sm-12 mb-2">
            <label for="name" class="form-label">Tên sản phẩm</label>
            <input type="text" id="name" name="name" value="<?php echo isset($_GET['name']) ? $_GET['name'] : '' ?>"
                   placeholder="Nhập tên sản phẩm" class="form-control"/>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12 mb-2">
            <label for="category_id" class="form-label">Chọn danh mục</label>
            <select id="category_id" name="category_id" class="form-select">
                <option></option>
                <?php foreach ($categories as $category):
                    $selected = '';
                    if (isset($_GET['category_id']) && $category['id'] == $_GET['category_id']) {
                        $selected = 'selected';
                    }
                    ?>
                    <option value="<?php echo $category['id'] ?>" <?php echo $selected; ?>>
                        <?php echo $category['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12 text-right text-lg-center pt-4 mb-2">
            <button type="submit" class="btn btn-success"><i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm</button>
            <a href="index.php?controller=productAdmin" class="btn btn-warning">Xóa filter</a>
        </div>
    </form>


    <h2 class="mt-5">Danh sách sản phẩm</h2>
    <a href="index.php?controller=productAdmin&action=create" class="btn btn-primary">
        <i class="fa fa-plus"></i> Thêm mới
    </a>
    <table class="table table-hover table-striped mt-2">
    <tr>
        <th scope="col">Danh mục</th>
        <th scope="col">Hình ảnh</th>
        <th width="35%">Tên sản phẩm</th>
        <th scope="col">Giá bán</th>
        <th scope="col">Màu</th>
        <th scope="col">Size</th>
        <th scope="col">Số lượng</th>
        <th width="10%">Updated at</th>
        <th scope="col"></th>
    </tr>
<?php if (!empty($products)): ?>
    <?php foreach ($products as $product): ?>
        <tr style="height: 60px" class=<?php echo $product['amount'] <5 ? "'bg-warning'  title='Sắp hết hàng'": '' ?>>
            <td scope="row">
                <div class="text-2 text-center"><?php echo $category_model->getCategoryById($product['category_id'])['name'] ?></div>
            </td>
            <td>
                <?php if (!empty($product['image'])): ?>
                    <img height="50" src="assets/uploads/<?php echo $product['image'] ?>" alt="<?php echo $product['name'] ?>">
                <?php endif; ?>
            </td>
            <td class="align-middle">
                <div class="text-2"><?php echo $product['name'] ?></div>
            </td>
            <td><?php echo number_format($product['price']) ?></td>
            <td >
                <div style="width: 100%; height: 10px; background: <?php echo $product['color'] ?>"></div>
            </td>
            <td class=""><?php echo $product['size'] ?></td>
            <td>
                <?php echo number_format($product['amount']) ?>
            </td>
            <td><?php echo !empty($product['updated_at']) ? date('d-m-Y', strtotime($product['updated_at'])) : '--' ?></td>
            <td>
                <?php
                $url_detail = "index.php?controller=productAdmin&action=detail&id=" . $product['id'];
                $url_update = "index.php?controller=productAdmin&action=update&id=" . $product['id'];
                $url_delete = "index.php?controller=productAdmin&action=delete&id=" . $product['id'];
                ?>
                <a class="mr-1" title="Chi tiết" href="<?php echo $url_detail ?>"><i class="fa fa-eye"></i></a>
                <a class="mr-1" title="Update" href="<?php echo $url_update ?>"><i class="fa fa-pencil-alt"></i></a>
                <a class="mr-1" title="Xóa" href="<?php echo $url_delete ?>" onclick="return confirm('Are you sure delete?')">
                    <i class="fa fa-trash"></i></a>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php else: ?>
    <tr>
        <td colspan="9">Chưa có sản phẩm nào</td>
    </tr>
    </table>
<?php endif; ?>
<?php echo "<p class='float-start' style='line-height: 40px'>Trang ".$current_page."/".$total_page . "</p>"; ?>
<div class="pagination justify-content-center">
    <?php
    $url = $_SERVER['QUERY_STRING'];
    $pos = strpos($url, '&page');
    if ($pos) {
        $url = substr($url, 0, $pos);
    }

    if ($total_page > 1) {
        $prev = $current_page - 1; $next = $current_page + 1;

        echo "<a href='index.php?" . $url . "&page=1" . "' class='btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angles-left'></i></a>";

        if ($prev >= 1) {
            echo "<a href='index.php?" . $url . "&page=$prev" . "' class='btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angle-left'></i></a>";
            echo "<a href='index.php?" . $url . "&page=$prev" . "' class='btn btn-info me-2 rounded-circle'>$prev</a>";
        } else {
            echo "<span class='btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angle-left'></i></span>";
        }

        echo "<a href='index.php?" . $url . "&page=$current_page" . "' class='btn btn-warning me-2 rounded-circle'>$current_page</a>";

        if ($next <= $total_page) {
            echo "<a href='index.php?" . $url . "&page=$next" . "' class='btn btn-info me-2 rounded-circle'>$next</a>";
            echo "<a href='index.php?" . $url . "&page=$next" . "' class='btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angle-right'></i></a>";
        }else {
            echo "<span class='btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angle-right'></i></span>";
        }

        echo "<a href='index.php?" . $url . "&page=$total_page" . "' class='btn btn-info me-2 rounded-circle'><i class='fa-solid fa-angles-right'></i></a>";
    }
    ?>
</div>