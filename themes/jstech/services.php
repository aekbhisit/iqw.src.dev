<?php 
$services = $_DATA['services_cat'] ;
?>
<?php include 'inc/header.php'; ?>

<!-- Breadcrumb -->
    <section id="subheader" class="bc02 no-bottom" data-stellar-background-ratio="0.5">
        <div class="overlay">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                            <h1>Services
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
                        <div class="box-container services-content">
                            <?php 
                                $cnt = 0 ;
                                foreach($services as $sv){
                                    $cnt++;
                            ?>
                            <div class="col-md-6 wow fadeInUp" data-wow-delay="0">
                                <div class="box-with-icon-left">
                                    <i class="fa fa-cube icon-big"></i>
                                    <div class="text">
                                        <img src="<?=$sv['image']?>" alt="<?=$sv['name']?>">
                                        <h2><?=$sv['name']?></h2>
                                        <p><?=_html($sv['description'])?></p>
                                        <div class="divider-single"></div>
                                        <a href="/service-<?=$sv['id']?>-<?=$sv['slug']?>" class="btn-text">View Details</a>
                                    </div>
                                </div>
                            </div>

                            <?php
                                if($cnt>0&&($cnt%2)==0){
                                    echo ' <div class="divider-double"></div>' ;
                                }
                                } 
                            ?>
                            <div class="clearfix"></div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="h60"></div>

            <div class="call-to-action text-light">
                <div class="container">
                    <div class="row">
                        <div class="col-md-9">
                            <h2>
                            <!-- page หน้าแรก contact bar -->
                            <?php echo _html($_DATA['home_contactbar']['content']) ;?>
                            <!-- .page หน้าแรก contact bar -->
                            </h2>
                        </div>

                        <div class="col-md-3">
                            <a href="/contact" class="btn-border-light">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

<?php include 'inc/footer.php'; ?>