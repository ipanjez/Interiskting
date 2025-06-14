<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v2.0.0
* @link https://coreui.io
* Copyright (c) 2018 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
  <meta name="author" content="Łukasz Holeczek">
  <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">  <title>Integrated Risk Document & Monitoring</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- disabled.. embedded on direct page
  <link href="<?= base_url('assets/vendors/fullcalendar/fullcalendar.css'); ?>" rel="stylesheet">
  -->
  <link href="<?php echo base_url('assets/plugins/alertify/css/alertify.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/plugins/alertify/css/themes/bootstrap.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/plugins/jquery-nestable/jquery.nestable.css'); ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendors/bootstrap-datepicker-gijgo/css/gijgo.min.css'); ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendors/bootstrap-select/css/bootstrap-select.css'); ?>" rel="stylesheet">
  <!-- Icons-->
  <link href="<?php echo base_url(); ?>assets/vendors/@coreui/icons/css/coreui-icons.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/vendors/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- Font Awesome 6 for modern icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="<?php echo base_url(); ?>assets/vendors/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
  <!-- Main styles for this application-->
  <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/vendors/pace-progress/css/pace.min.css" rel="stylesheet">
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
  <?php
  $user = $this->ion_auth->user()->row();
  ?>
  <header class="app-header navbar">
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">
      <img class="navbar-brand-full" src="<?php echo base_url(); ?>assets/img/brand/logo.svg" width="89" height="25" alt="CoreUI Logo">
      <img class="navbar-brand-minimized" src="<?php echo base_url(); ?>assets/img/brand/sygnet.svg" width="30" height="30" alt="CoreUI Logo">
    </a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
      <span class="navbar-toggler-icon"></span>
    </button>

    <ul class="nav navbar-nav ml-auto">
      <li class="nav-item d-md-down-none">
        <a class="nav-link" href="#">
          <i class="icon-bell"></i>
          <span class="badge badge-pill badge-danger">5</span>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
          <img class="img-avatar" src="<?php echo base_url(); ?>assets/img/avatars/user2-160x160.jpg" alt="user image">
        </a>
        <div class="dropdown-menu dropdown-menu-right">
          <div class="dropdown-header text-center">
            <strong>Account</strong>
          </div>
          <a class="dropdown-item" href="<?php echo base_url(); ?>profile/edit_user/<?= $user->id; ?>">
            <i class="fa fa-user"></i> Profile</a>
          <a class="dropdown-item" href="<?php echo base_url(); ?>auth/logout">
            <i class="fa fa-lock"></i> Logout</a>
        </div>
      </li>
      <li>Hai, <b><?php echo $user->first_name; ?></b></li>
    </ul>
    <button class="navbar-toggler aside-menu-toggler d-md-down-none" type="button" data-toggle="aside-menu-lg-show">
      <span class="navbar-toggler-icon"></span>
    </button>
    <button class="navbar-toggler aside-menu-toggler d-lg-none" type="button" data-toggle="aside-menu-show">
      <span class="navbar-toggler-icon"></span>
    </button>
  </header>
  <div class="app-body">
    <div class="sidebar">
      <nav class="sidebar-nav">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="<?php echo site_url('grafik'); ?>">
              <i class="nav-icon icon-speedometer"></i> Dashboard
              <span class="badge badge-primary">NEW</span>
            </a>
          </li>
          <!--list -->
          <li class="sidebar-menu list" id="menuSub">
            <?php $menus = $this->layout->get_menu() ?>
            <?php if (is_array($menus)) :
              foreach ($menus as $menu) : ?>
                <!-- MAIN NAVIGATIOn MASTER -->
          <li class="nav-title"><?php echo $menu['label'] ?></li>
          <?php if (is_array($menu['children'])) : ?>
            <?php foreach ($menu['children'] as $menu2) : ?>
              <!-- jadwal, menu -->
              <li <?php echo $menu2['attr'] != '' ? ' id="' . $menu2['attr'] . '" ' : '' ?> <?php echo is_array($menu2['children']) ? ' class="treeview" ' : '' ?>> <?php if (is_array($menu2['children'])) : ?>
                  <!-- datasheet, utilities, help -->
              <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="<?php echo $menu2['link'] != '#' ? base_url($menu2['link']) : '#' ?>">
                  <i class="nav-icon icon-badge"></i> <?php echo $menu2['label'] ?></a>

                <ul class="nav-dropdown-items">
                  <!-- sub datasheet, utilities, help -->

                  <?php foreach ($menu2['children'] as $menu3) : ?>
                    <li class="nav-item">
                      <?php if (is_array($menu3['children'])) : ?>
                        <a class="nav-link" href="<?php echo $menu3['link'] != '#' ? base_url($menu3['link']) : '#' ?>">
                          <i class="nav-icon icon-shield"></i> <?php echo $menu3['label'] ?></a>
                    </li>
                </ul>
                <ul class="nav-dropdown-items">
                  <!-- sub OF Pabrik 1 -->
                  <?php foreach ($menu3['children'] as $menu4) : ?>
                    <li class="nav-item">
                      <a class="nav-link" href="<?php echo $menu4['link'] != '#' ? base_url($menu4['link']) : '#' ?>">
                        <i class="nav-icon icon-puzzle"></i> <?php echo $menu4['label'] ?>
                      </a>
                    </li>
                  <?php endforeach ?>
                </ul>
              </li>

            <?php else : ?>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo $menu3['link'] != '#' ? base_url($menu3['link']) : '#' ?>">
                  <i class="nav-icon icon-pie-chart"></i><?php echo $menu3['label'] ?></a>
              </li>
            <?php endif ?>
            </li>
          <?php endforeach ?>
        </ul>
      <?php else : ?>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $menu2['link'] != '#' ? base_url($menu2['link']) : '#' ?>">
            <i class="nav-icon icon-puzzle"></i><?php echo $menu2['label'] ?></a>
        </li>
      <?php endif ?>
      </li>
    <?php endforeach ?>
  <?php endif ?>
<?php endforeach ?>
<?php endif ?>
</li>

</ul>
      </nav>
      <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>

    <!-- Your content will be here-->
    <?php $this->load->view($page); ?>