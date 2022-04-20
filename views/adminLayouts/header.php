<?php
$year = '';
$username = '';
$jobs = '';
$avatar = '';
if (isset($_SESSION['admin'])) {
    $fullname = $_SESSION['admin']['fullname'];
    $year = date('Y', strtotime($_SESSION['admin']['created_at']));
}
?>
<header class="main-header position-fixed w-100">
    <!-- Logo -->
    <a href="index.php?controller=homeAdmin" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">Aries</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
            <img src="assets/images/logo/logo-shop.png" alt="" width="40px">
            Aries shoes
        </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <i class="fa fa-bars"></i>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle align-middle" data-toggle="dropdown">
                        <div class="float-start me-1" style="position: relative; width: 30px; height: 40px; overflow: hidden;">
                            <img src="assets/<?php echo isset($_SESSION['admin']['avatar']) ? 'uploads/'.$_SESSION['admin']['avatar'] : 'images/img.png' ?>"
                                 style="position: absolute; height: 56px; top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                 alt="User Image">
                        </div>
                        <span class="hidden-xs"><?php echo $fullname; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="assets/<?php echo isset($_SESSION['admin']['avatar']) ? 'uploads/'.$_SESSION['admin']['avatar'] : 'images/img.png' ?>"
                                 class="img-circle" alt="User Image">
                            <p>
                                ADMIN
                                <small>Thành viên từ năm <?php echo $year; ?></small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="row text-center">
                                <div class="col-6">
                                    <a href="index.php?controller=userAdmin&action=profile&id=<?php echo $_SESSION['admin']['id'] ?>"
                                       class="btn btn-outline-success rounded">Profile</a>
                                </div>
                                <div class="col-6">
                                    <a href="index.php?controller=userAdmin&action=logout" class="btn btn-outline-danger rounded">
                                        Sign out
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar position-fixed">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel row justify-content-center">
            <div class="col-3 position-relative">
                <img src="assets/<?php echo isset($_SESSION['admin']['avatar']) ? 'uploads/'.$_SESSION['admin']['avatar'] : 'images/img.png' ?>" alt="User Image">
            </div>
            <div class="pull-left col-8 text-white hidden-md-sm">
                <div><?php echo $fullname; ?></div>
                <small><i class="fa fa-circle text-success"></i> Online</small>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu mt-3" data-widget="tree">
            <li class="header">Admin of Aries</li>
            <li>
                <a href="index.php">
                    <i class="fa-solid fa-chart-line"></i> <span>Dashboard</span>
                    <span class="pull-right-container">
              <!--<small class="label pull-right bg-green">new</small>-->
                    </span>
                </a>
            </li>

            <li>
                <a href="index.php?controller=categoryAdmin&action=index">
                    <i class="fa-solid fa-table-list"></i> <span>Quản lý danh mục</span>
                    <span class="pull-right-container">
              <!--<small class="label pull-right bg-green">new</small>-->
                    </span>
                </a>
            </li>
            <li>
                <a href="index.php?controller=productAdmin&action=index">
                    <i class="fa-solid fa-boxes-stacked"></i> <span>Quản lý sản phẩm</span>
                    <span class="pull-right-container">
              <!--<small class="label pull-right bg-green">new</small>-->
                    </span>
                </a>
            </li>
            <li>
                <a href="index.php?controller=userAdmin&action=index">
                    <i class="fa-solid fa-users"></i> <span>Quản lý tài khoản</span>
                    <span class="pull-right-container">
              <!--<small class="label pull-right bg-green">new</small>-->
                    </span>
                </a>
            </li>
            <li>
                <a href="index.php?controller=orderAdmin&action=index">
                    <i class="fa-solid fa-cart-flatbed"></i> <span>Quản lý đơn hàng</span>
                    <span class="pull-right-container">
              <!--<small class="label pull-right bg-green">new</small>-->
                    </span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<!-- Breadcrumd Wrapper. Contains breadcrumb -->
<div class="breadcrumb-wrap content-wrap content-wrapper" style="margin-top: 70px">
    <!-- Content Header (Page header) -->
    <section class="content-header" style="height: 50px">
        <ol class="breadcrumb">
            <li class="me-1"><a href="index.php">Home</a></li>
            <?php if(isset($_GET['controller']) && $_GET['controller']!='home') : ?>
                <li class='active me-1'><a href="index.php?controller=<?php echo $_GET['controller']; ?>"><?php echo ucfirst($_GET['controller']) ?></a></li>
            <?php endif; ?>
            <?php if(isset($_GET['action']) && $_GET['action']!='index') : ?>
                <li class='active me-1'><?php echo ucfirst($_GET['action'])?></li>
            <?php endif; ?>
        </ol>
    </section>
</div>

<!-- Messaeg Wrapper. Contains messaege error and success -->
<div class="message-wrap content-wrap content-wrapper">
    <section class="content-header">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($this->error['error'])): ?>
            <div class="alert alert-danger">
                <?php
                echo $this->error['error'];
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>
    </section>
</div>