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
  <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
  <title>Integrated Risk Document & Monitoring</title>

  <!-- full calender -->
  <link href="<?= base_url(); ?>assets/vendors/fullcalendar/fullcalendar.2.6.1.css" rel="stylesheet">

  <link href="<?php echo base_url(); ?>assets/vendors/fullcalendar/bootstrapValidator.min.css" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>assets/vendors/fullcalendar/bootstrap-colorpicker.min.css" rel="stylesheet" />
  <!-- Custom css  -->
  <link href="<?php echo base_url(); ?>assets/vendors/fullcalendar/custom.css" rel="stylesheet" />

  <link href="<?php echo base_url('assets/plugins/alertify/css/alertify.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/plugins/alertify/css/themes/bootstrap.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/plugins/jquery-nestable/jquery.nestable.css'); ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendors/bootstrap-datepicker-gijgo/css/gijgo.min.css'); ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendors/bootstrap-select/css/bootstrap-select.css'); ?>" rel="stylesheet">

  <!-- Icons-->
  <link href="<?php echo base_url(); ?>assets/vendors/@coreui/icons/css/coreui-icons.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/vendors/flag-icon-css/css/flag-icon.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/vendors/simple-line-icons/css/simple-line-icons.css" rel="stylesheet">
  <!-- Main styles for this application-->
  <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/vendors/pace-progress/css/pace.min.css" rel="stylesheet">
</head>
<style>
  .colorpicker,
  .colorpicker * {
    z-index: 9999;
  }
</style>

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
          <!-- monitor pelaporan -->
          <li class="sidebar-menu list" id="menuSub">
            <?php $menus = $this->layout->get_menu() ?>
            <?php if (is_array($menus)) :
              foreach ($menus as $menu) : ?>
                <!-- MAIN NAVIGATIOM MASTER -->
          <li class="nav-title"><?php echo $menu['label'] ?></li>
          <?php if (is_array($menu['children'])) : ?>
            <?php foreach ($menu['children'] as $menu2) : ?>
              <!-- kepatuhan -->
              <li <?php echo $menu2['attr'] != '' ? ' id="' . $menu2['attr'] . '" ' : '' ?> <?php echo is_array($menu2['children']) ? ' class="treeview" ' : '' ?>> <?php if (is_array($menu2['children'])) : ?>
                  <!-- monitor pelaporan -->
              <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="<?php echo $menu2['link'] != '#' ? base_url($menu2['link']) : '#' ?>">
                  <i class="nav-icon icon-badge"></i> <?php echo $menu2['label'] ?></a>
                <ul class="nav-dropdown-items">
                  <!-- sub monitor pelaporan // monitor bulanan triwulan dll -->
                  <?php foreach ($menu2['children'] as $menu3) : ?>
                    <li class="nav-item">
                      <?php if (is_array($menu3['children'])) : ?>
                        <a class="nav-link" href="<?php echo $menu3['link'] != '#' ? base_url($menu3['link']) : '#' ?>">
                          <i class="nav-icon icon-puzzle"></i> <?php echo $menu3['label'] ?></a>
                    </li>
                </ul>
              </li>
              <ul class="treeview-menu">
                <?php foreach ($menu3['children'] as $menu4) : ?>
                  <li <?php echo $menu4['attr'] != '' ? ' id="' . $menu4['attr'] . '" ' : '' ?>>
                    <a href="<?php echo $menu4['link'] != '#' ? base_url($menu4['link']) : '#' ?>" class="name">m
                      <i class="<?php echo $menu4['icon'] ?>"></i> <span><?php echo $menu4['label'] ?></span>
                    </a>
                  </li>
                <?php endforeach ?>
              </ul>
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