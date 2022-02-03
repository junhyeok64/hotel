<?php
    include "../config/hotel_config.php";
    //theme link : https://themewagon.com/themes/free-bootstrap-hotel-template-royal/
?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="image/favicon.png" type="image/png">
        <title>Royal Hotel</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="vendors/linericon/style.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="vendors/bootstrap-datepicker/bootstrap-datetimepicker.min.css">
        <link rel="stylesheet" href="vendors/nice-select/css/nice-select.css">
        <link rel="stylesheet" href="vendors/owl-carousel/owl.carousel.min.css">
        <!-- main css -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/responsive.css">
    </head>
    <body>
        <!--================Header Area =================-->
        <header class="header_area">
            <div class="container">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <a class="navbar-brand logo_h" href="<?=base_url?>"><img src="image/Logo.png" alt=""></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                        <ul class="nav navbar-nav menu_nav ml-auto">
                            <li class="nav-item<?php if($page=="") { echo " active";} ?>">
                                <a class="nav-link" href="<?=base_url?>">Home</a>
                            </li> 
                            <li class="nav-item<?php if($page=="about") { echo " active";} ?>">
                                <a class="nav-link" href="about.php">About us</a>
                            </li>
                            <li class="nav-item<?php if($page=="gallery") { echo " active";} ?>">
                                <a class="nav-link" href="gallery.php">Gallery</a>
                            </li>
                            <li class="nav-item<?php if($page=="reserve") { echo " active";} ?>">
                                <a class="nav-link" href="reserve.php">Reserve</a>
                            </li>
                            <!--<li class="nav-item submenu dropdown<?php if($page=="blog") { echo " active";} ?>">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Blog</a>
                                <ul class="dropdown-menu">
                                    <li class="nav-item"><a class="nav-link" href="blog.html">Blog</a></li>
                                    <li class="nav-item"><a class="nav-link" href="blog-single.html">Blog Details</a></li>
                                </ul>
                            </li> -->
                            <li class="nav-item<?php if($page=="mypage") { echo " active";} ?>">
                                <a class="nav-link" href="mypage.php">Mypage</a>
                            </li>
                            <!--<li class="nav-item<?php if($page=="elements") { echo " active";} ?>">
                                <a class="nav-link" href="elements.php">Elemests</a>
                            </li>-->
                            <li class="nav-item<?php if($page=="contact") { echo " active";} ?>">
                                <a class="nav-link" href="contact.php">Contact</a>
                            </li>
                        </ul>
                    </div> 
                </nav>
            </div>
        </header>
        <!--================Header Area =================-->

        <?php
            if($page != "") {
                switch($page) {
                    case "contact":
                        $show_page = $page." Us";
                    break;
                    default:
                        $show_page = $page;
                    break;
                }
        ?>        
        <!--================Breadcrumb Area =================-->
        <section class="breadcrumb_area">
            <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background=""></div>
            <div class="container">
                <div class="page-cover text-center">
                    <h2 class="page-cover-tittle"><?=ucwords($show_page)?></h2>
                    <ol class="breadcrumb">
                        <li><a href="index.html">Home</a></li>
                        <li class="active"><?=ucwords($show_page)?></li>
                    </ol>
                </div>
            </div>
        </section>
        <!--================Breadcrumb Area =================-->
        <?php } ?>