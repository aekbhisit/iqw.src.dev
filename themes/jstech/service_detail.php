<?php include 'inc/header.php'; ?>
<?php 
$service = $_DATA['service_detail'] ;
$service_cat_name = $service_menu[$service['category_id']]['name'] ;
?>
<!-- Breadcrumb -->
    <section id="subheader" class="no-bottom" data-stellar-background-ratio="0.5">
        <div class="overlay">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                            <h1><?=$service_cat_name?>
                                <span>Service</span>
                            </h1>
                        <div class="small-border wow flipInY" data-wow-delay=".8s" data-wow-duration=".8s"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="clearfix"></div>

<!-- Content -->
    <div id="content" class="no-padding">
        
        <!-- section begin -->
        <section class="services-detail">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <img src="<?=$service['image']?>" alt="">
                    </div>

                    <div class="col-md-6 wow fadeInRight" data-wow-delay=".5s" data-wow-duration=".8s">

                        <h2><?=$service['name']?></h2>
                        <div class="small-border left wow flipInY" data-wow-delay=".8s" data-wow-duration=".8s"></div>
                        <?=_html($service['content'])?>

                    </div>

                </div>
            </div>

            <div class="clearfix"></div>

        </section>
        <!-- section close -->

        <div class="call-to-action text-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-9">
                        <!-- page หน้าแรก contact bar -->
                        <?php echo _html($_DATA['home_contactbar']['content']) ;?>
                        <!-- .page หน้าแรก contact bar -->
                    </div>

                    <div class="col-md-3">
                        <a href="/contact" class="btn-border-light">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php include 'inc/footer.php'; ?>