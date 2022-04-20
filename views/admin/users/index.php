<h2>Tìm kiếm</h2>
<form action="" method="get" class="row align-middle">
    <input type="hidden" name="controller" value="userAdmin"/>
    <input type="hidden" name="action" value="index"/>
    <div class="col-lg-4 col-md-12 col-sm-12 mb-2">
        <label class="form-label">Họ tên</label>
        <input type="text" name="fullname" value="<?php echo $_GET['fullname'] ?? '' ?>"
               placeholder="Nguyễn Văn A" class="form-control"/>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12 mb-2">
        <label class="mb-2 form-label">Roles</label>
        <select id="role_id" class="form-select" name="role">
            <option selected></option>
            <?php foreach ($roles as $role):
                $selected = '';
                if(isset($_GET['role']) && $_GET['role'] == $role['id']) {
                    $selected = 'selected';
                }
            ?>
            <option value="<?php echo $role['id']; ?>" <?php echo $selected; ?> ><?php echo $role['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12 text-right text-lg-center pt-4 mb-2">
        <button type="submit" class="btn btn-success"><i class="fa-solid fa-magnifying-glass"></i> Tìm kiếm</button>
        <a href="index.php?controller=userAdmin" class="btn btn-warning">Xóa filter</a>
    </div>
</form>

<h2 class="mt-5">Danh sách user</h2>
<a href="index.php?controller=userAdmin&action=create" class="btn btn-primary">
    <i class="fa fa-plus"></i> Tạo tài khoản admin
</a>
<table class="table table-hover table-striped mt-2">
    <tr>
        <th scope="col">ID</th>
        <th scope="col">Username</th>
        <th scope="col">Avatar</th>
        <th width="20%">Họ tên</th>
        <th scope="col">Số điện thoại</th>
        <th width="25%">Địa chỉ</th>
        <th scope="col">Role</th>
        <th scope="col">Status</th>
        <th scope="col">Created at</th>
        <th scope="col"></th>
    </tr>
    <?php if (!empty($users)): ?>
        <?php foreach ($users as $user): ?>
            <tr style="height: 60px">
                <td><?php echo $user['id'] ?></td>
                <td><?php echo $user['username'] ?></td>
                <td>
                   <img id="mg-thumbnail" height="50" src="assets/<?php echo !empty($user['avatar']) ? 'uploads/'.$user['avatar'] : 'images/img.png' ?>" />
                </td>
                <td class="align-middle">
                    <div class="text-2"><?php echo $user['fullname'] ?></div>
                </td>
                <td><?php echo $user['phone'] ?></td>
                <td class="align-middle">
                    <div class="text-2"><?php echo $user['address'] ?></div>
                </td>
                <td><?php echo $user['role_id'] == 1 ? 'Admin' : 'User' ?></td>
                <td>
                    <?php if ($user['status']) : ?>
                        <i class="fa-solid fa-lock-open"></i>
                    <?php else: ?>
                        <i class="fa-solid fa-lock"></i>
                    <?php endif; ?>
                </td>
                <td><?php echo date('d-m-Y', strtotime($user['created_at'])) ?></td>
                <td>
                    <?php
                        $url_detail = "index.php?controller=userAdmin&action=detail&id=" . $user['id'];
                        $url_update = "index.php?controller=userAdmin&action=updateStatus&id=" . $user['id'];
                    ?>
                    <a class="mr-1" title="Chi tiết" href="<?php echo $url_detail ?>"><i class="fa fa-eye"></i></a>
                    <a class="mr-1" title="Update" href="<?php echo $url_update ?>"><i class="fa fa-pencil-alt"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="9">Không có user!</td>
        </tr>
    <?php endif; ?>
</table>

<?php
require_once "views/adminLayouts/pagination.php";
?>