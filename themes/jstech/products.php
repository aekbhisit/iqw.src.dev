<?php include 'inc/header.php'; ?>
<?php 
$products = $_DATA['product_all'] ;
?>

<!-- Breadcrumb -->
    <section id="subheader" class="bc03 no-bottom" data-stellar-background-ratio="0.5">
        <div class="overlay">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                            <h1>Products
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
        <div id="content" class="no-bottom">

            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?php foreach($products as $pd){ ?>
                        <div class="col-md-4 wow fadeInUp" data-wow-delay="0">
                            <div class="products-box">
                            	<div class="img-box">
                                    <img src="<?=$pd['image']?>" alt="<?=$pd['name']?>" style="max-height: 240px; min-height: 240px;"> 
                                </div>
                                <div class="text">
                                    <a href="/product_detail-<?=$pd['id']?>-<?=$pd['slug']?>"><h2><?=$pd['name']?></h2></a>
                                    <a href="/product_detail-<?=$pd['id']?>-<?=$pd['slug']?>" class="btn-text">View Details</a>
                                </div>
                            </div>
                        </div>
                        <?php } ?>

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
                            <a href="/contact" class="btn-border-light">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

<?php include 'inc/footer.php'; ?>