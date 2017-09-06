<?php 

$service_cat = $_DATA['services_cat'] ;
$service_menu = array();
foreach($service_cat as $sc){
    $service_menu[$sc['id']] = array(
        "id"=>$sc['id'],
        "slug"=>$sc['slug'],
        "name"=>$sc['name']
    );
}

// make product menu list
$product_cat = $_DATA['product_cat'] ;
$product_menu = array();
foreach($product_cat as $pc){
    $product_menu[$pc['id']] = array(
        "id"=>$pc['id'],
        "slug"=>$pc['slug'],
        "name"=>$pc['name']
    );
}

// print_r($product_menu) ;

?>
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="utf-8">
    <title><?=$_DATA['configs']['sitename']?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?=$_DATA['configs']['meta_description']?>">
    <meta name="keyword" content="<?=$_DATA['configs']['meta_keywords']?>">

    <!-- Favicons -->
    <link rel="shortcut icon" href="<?=THEME_ROOT_URL?>img/favicon.ico">

    <!-- LOAD CSS FILES -->
    <link href="<?=THEME_ROOT_URL?>css/main.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div id="preloader"></div>
    <div id="wrapper">
        <header>
            <div class="container">
                <span id="menu-btn"></span>
                <div class="row">
                    <div class="col-md-2">

                        <div id="logo">
                            <div class="inner">
                                <a href="index.php">
                                    <img src="<?=THEME_ROOT_URL?>img/logo_jstech.jpg" alt="" class="logo-1">
                                    <img src="<?=THEME_ROOT_URL?>img/logo_jstech.jpg" alt="" class="logo-2">
                                </a>

                            </div>
                        </div>

                    </div>

                    <div class="col-md-10">

                        <nav id="mainmenu-container">
                            <ul id="mainmenu">
                                <li class="<?=($_PARAM[0]=='')?'active':''?>"><a href="/">Home</a></li>
                                <li class="<?=($_PARAM[0]=='aboutus')?'active':''?>"><a href="/aboutus">About Us</a></li>
                                <li class="<?=($_PARAM[0]=='services'||$_PARAM[0]=='service'||$_PARAM[0]=='service_detail')?'active':''?>"><a href="/services">Services</a>
                                    <ul>
                                    <?php foreach($service_menu as $sm) { ?>
                                    <li><a href="/service-<?=$sm['id']?>-<?=$sm['slug']?>"><?=$sm['name']?></a></li>
                                    <?php } ?>
                                        
                                    </ul>
                                </li>
                                <li class="<?=($_PARAM[0]=='products'||$_PARAM[0]=='product'||$_PARAM[0]=='product_detail')?'active':''?>"><a href="/products">Products</a>
                                    <ul>
                                    <?php foreach($product_menu as $pm) { ?>
                                    <li><a href="/product-<?=$pm['id']?>-<?=$pm['slug']?>"><?=$pm['name']?></a></li>
                                    <?php } ?>
                                    </ul>
                                </li>
                                <li class="<?=($_PARAM[0]=='news'||$_PARAM[0]=='news_detail')?'active':''?>"><a href="/news">News</a></li>
                                <li class="<?=($_PARAM[0]=='contact')?'active':''?>"><a href="/contact">Contact Us</a></li>
                            </ul>
                        </nav>

                        <div class="social">
                            <a href="https://www.facebook.com/JS-TECH-CO-LTD-305799672827384/"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="https://www.youtube.com/watch?v=V2O0Ga9tahg"><i class="fa fa-youtube"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                        </div>

                    </div>
                </div>
            </div>
        </header>
