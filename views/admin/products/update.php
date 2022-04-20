<h2 class="mb-4">Cập nhật thông tin sản phẩm</h2>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group mb-3">
        <label class="form-label" for="category_id">Chọn danh mục</label>
        <select name="category_id" class="form-control" id="category_id">
            <?php foreach ($categories as $category):
                $selected = '';
                if ($product['category_id'] == $category['id']) {
                    $selected = 'selected';
                }
                if (isset($_POST['category_id']) && $category['id'] == $_POST['category_id']) {
                    $selected = 'selected';
                }
                ?>
                <option value="<?php echo $category['id'] ?>" <?php echo $selected; ?>>
                    <?php echo $category['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($this->error['category'])): ?>
            <small class="text-danger fst-italic">
                <?php
                echo $this->error['category'];
                ?>
            </small>
        <?php endif; ?>
    </div>
    <div class="form-group mb-3">
        <label class="form-label" for="name">Nhập tên sản phẩm</label>
        <input type="text" name="name" value="<?php echo $_POST['name'] ?? $product['name'] ?>"
               class="form-control" id="name"/>
        <?php if (!empty($this->error['name'])): ?>
            <small class="text-danger fst-italic">
                <?php
                echo $this->error['name'];
                ?>
            </small>
        <?php endif; ?>
    </div>
    <div class="form-group mb-3">
        <label class="form-label" for="image">Ảnh sản phẩm</label>
        <div class="thumb">
            <img id="imgSrc" src="assets/uploads/<?php echo $product['image'] ?>" />
            <div id="uploadCover" class="thumb-cover">
                <i class="fa fa-plus-square"></i>
                <input type="file" name="image" id="imgUpload" accept="image/*" title="Click để thay đổi hình ảnh!">
            </div>
            <?php if (!empty($this->error['image'])): ?>
                <small class="text-danger fst-italic">
                    <?php
                    echo $this->error['image'];
                    ?>
                </small>
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group mb-3">
        <label class="form-label" for="color">Màu sắc</label>
        <input type="color" name="color" value="<?php echo $_POST['color'] ?? $product['color'] ?>"
               class="form-control" id="color"/>
    </div>
    <div class="form-group mb-3">
        <label class="form-label" for="size">Size</label>
        <input type="number" name="size" value="<?php echo $_POST['size'] ?? $product['size'] ?>"
               class="form-control" id="size"/>
        <?php if (!empty($this->error['size'])): ?>
            <small class="text-danger fst-italic">
                <?php
                echo $this->error['size'];
                ?>
            </small>
        <?php endif; ?>
    </div>
    <div class="form-group mb-3">
        <label class="form-label" for="cost">Giá nhập</label>
        <input type="number" name="cost" value="<?php echo $_POST['cost'] ?? $product['cost'] ?>"
               class="form-control" id="cost"/>
        <?php if (!empty($this->error['cost'])): ?>
            <small class="text-danger fst-italic">
                <?php
                echo $this->error['cost'];
                ?>
            </small>
        <?php endif; ?>
    </div>
    <div class="form-group mb-3">
        <label class="form-label" for="price">Giá bán</label>
        <input type="number" name="price" value="<?php echo $_POST['price'] ?? $product['price'] ?>"
               class="form-control" id="price"/>
        <?php if (!empty($this->error['price'])): ?>
            <small class="text-danger fst-italic">
                <?php
                echo $this->error['price'];
                ?>
            </small>
        <?php endif; ?>
    </div>
    <div class="form-group mb-3">
        <label class="form-label" for="amount">Số lượng</label>
        <input type="number" name="amount" value="<?php echo $_POST['amount'] ?? $product['amount'] ?>"
               class="form-control" id="amount"/>
        <?php if (!empty($this->error['amount'])): ?>
            <small class="text-danger fst-italic">
                <?php
                echo $this->error['amount'];
                ?>
            </small>
        <?php endif; ?>
    </div>
    <div class="form-group mb-3">
        <label class="form-label" for="description">Mô tả sản phẩm</label>
        <textarea name="description" id="description"
                  class="form-control"><?php echo isset($_POST['description']) ? htmlentities($_POST['description']) : htmlentities($product['description']) ?></textarea>
    </div>
    <div class="form-group mb-3 row">
        <div class="text-start col-6">
            <button type="submit" name="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
            <input type="reset" class="btn btn-warning" name="submit" value="Reset"/>
        </div>
        <div class="text-end col-6">
            <a href="index.php?controller=productAdmin&action=index" class="btn btn-danger text-right"><i class="fa-solid fa-rotate-left"></i> Quay lại</a>
        </div>
    </div>
</form>