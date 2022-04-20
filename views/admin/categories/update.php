<?php if (empty($category)): ?>
    <h2 class="mb-4">Không tồn tại danh mục</h2>
<?php else: ?>
    <h2 class="mb-4">Chỉnh sửa danh mục #<?php echo $category['id'] ?></h2>
    <form method="post" action="" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label class="form-label" for="image">Ảnh danh mục</label>
            <div class="thumb">
                <img id="imgSrc" src="assets/uploads/<?php echo $category['image'] ?>" />
                <div id="uploadCover" class="thumb-cover">
                    <i class="fa fa-plus-square"></i>
                    <input type="file" name="image" id="imgUpload" accept="image/*" title="Click để thay đổi hình ảnh!">
                </div>
            </div>
            <?php if (!empty($this->error['image'])): ?>
                <small class="text-danger fst-italic">
                    <?php
                    echo $this->error['image'];
                    ?>
                </small>
            <?php endif; ?>
        </div>
        <div class="form-group mb-3 ">
            <label class="form-label" for="name">Tên danh mục <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" value="<?php echo $_POST['name'] ?? $category['name']; ?>"
                   class="form-control" required/>
            <?php if (!empty($this->error['name'])): ?>
                <small class="text-danger fst-italic">
                    <?php
                    echo $this->error['name'];
                    ?>
                </small>
            <?php endif; ?>
        </div>

        <div class="form-group mb-3">
            <label class="form-label" for="description">Mô tả</label>
            <textarea class="form-control" id="description" name="description">
                <?php echo isset($_POST['description']) ? htmlentities($_POST['description']) : htmlentities($category['description']); ?>
            </textarea>
        </div>

        <div class="form-group mb-3">
            <label class="form-label" for="parent_cat">Danh mục cha <span class="text-danger">*</span></label>
            <select id="parent_cat" class="form-select" name="parent_cat">
                <?php if ($category['parent_cat'] == 0): ?>
                    <option value="0" selected>NULL</option>
                <?php else: ?>
                    <option value="0">NULL</option>
                <?php endif; ?>
                <?php foreach ($parent_cats as $parent_cat):
                    $selected = ($category['parent_cat'] == $parent_cat['id']) ? 'selected' : '';
                    ?>
                    <option value="<?php echo $parent_cat['id']; ?>" <?php echo $selected; ?> ><?php echo $parent_cat['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($this->error['cat_parent'])): ?>
                <small class="text-danger fst-italic">
                    <?php
                    echo $this->error['cat_parent'];
                    ?>
                </small>
            <?php endif; ?>
        </div>

        <div class="form-group mb-3 row">
            <div class="text-start col-6">
                <button type="submit" name="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Lưu</button>
                <input type="reset" class="btn btn-warning" name="reset" value="Reset"/>
            </div>
            <div class="text-end col-6">
                <a href="index.php?controller=categoryAdmin&action=index" class="btn btn-danger text-right"><i class="fa-solid fa-rotate-left"></i> Quay lại</a>
            </div>
        </div>

    </form>
<?php endif; ?>
