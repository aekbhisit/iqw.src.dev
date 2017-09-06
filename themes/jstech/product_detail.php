<?php include 'inc/header.php'; ?>
<?php 
$product = $_DATA['product_detail'] ;
$product_cat_name = $product_menu[$product['category_id']]['name'] ;
list($top_right,$pd_detail) = explode("<!-- pagebreak -->",_html($product['content']),2)  ;

?>

<!-- Breadcrumb -->
    <section id="subheader" class="no-bottom" data-stellar-background-ratio="0.5">
        <div class="overlay">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                            <h1><?=$product_cat_name?>
                                <span>We Are Professional</span>
                            </h1>
                        <div class="small-border wow flipInY" data-wow-delay=".8s" data-wow-duration=".8s"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="clearfix"></div>

<!-- Content -->
        <div id="content" class="no-bottom products-detail">

            <div class="container">
                <div class="row">
                    <div class="col-md-12">

                            <div class="col-md-6 wow fadeInUp" data-wow-delay="0">
                                <img src="<?=$product['image']?>" alt="<?=$product['name']?>">
                                <?php if(!empty($product['image1'])){ ?>
                                <img src="<?=$product['image1']?>" alt="<?=$product['name']?>">
                                <?php } ?>
                                <?php if(!empty($product['image2'])){ ?>
                                <img src="<?=$product['image2']?>" alt="<?=$product['name']?>">
                                <?php } ?>
                                <?php if(!empty($product['image3'])){ ?>
                                <img src="<?=$product['image3']?>" alt="<?=$product['name']?>">
                                <?php } ?>
                                <?php if(!empty($product['image4'])){ ?>
                                <img src="<?=$product['image4']?>" alt="<?=$product['name']?>">
                                <?php } ?>
                             
                            </div>
                            <div class="col-md-6 detail">
                                <h2><?=$product['name']?></h2>
                                <div class="small-border left wow flipInY" data-wow-delay=".8s" data-wow-duration=".8s"></div>
                                <p>
                                   <?=$top_right?>
                                </p>
                                </div>
                            </div>
                            <div class="col-md-12 wow fadeInUp text-center" data-wow-delay="0">
                                <?=$pd_detail?>
                            </div>
                    </div>
                </div>
            </div>

            <div class="h60"></div>

            <div class="call-to-action text-light">
                <div class="container">
                    <div class="row">
                        <div class="col-md-9">
                            <h2><!-- page หน้าแรก contact bar -->
                            <?php echo _html($_DATA['home_contactbar']['content']) ;?>
                            <!-- .page หน้าแรก contact bar --></h2>
                        </div>

                        <div class="col-md-3">
                            <a href="contact.php" class="btn-border-light">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

<?php include 'inc/footer.php'; ?>