<?php 
// echo '<pre>' ;
// print_r($_DATA) ;
// echo '</pre>';
// make service menu list
$service_cat = $_DATA['services_cat'] ;
$service_menu = array();
foreach($service_cat as $sc){
    $service_menu[] = array(
        "id"=>$sc['id'],
        "slug"=>$sc['slug'],
        "name"=>$sc['name']
    );
}

// make product menu list
$product_cat = $_DATA['products_cat'] ;
$product_menu = array();
foreach($product_cat as $pc){
    $product_menu[] = array(
        "id"=>$pc['id'],
        "slug"=>$pc['slug'],
        "name"=>$pc['name']
    );
}


?>
<?php include 'inc/header.php'; ?>

<!-- Slider -->
<?php include 'inc/slide.php'; ?>
<!-- .Slider -->

    <div class="clearfix"></div>

<!-- Content -->
    <div id="content" class="no-padding">

        <div class="pdtb60">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="text-center">
                            <!-- page หน้าแรก บริษัท เจเอส เทค จำกัด -->
                            <?php echo _html($_DATA['home_jstech']['content']) ;?>
                            <!-- .page หน้าแรก บริษัท เจเอส เทค จำกัด -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box-container">
            <div id="bg-service-1" class="box-one-third light-text">
                <div class="inner wow" data-wow-delay="0s">
                    <!-- page หน้าแรก about us -->
                    <?php echo _html($_DATA['home_aboutus']['content']) ;?>
                    <!-- .page หน้าแรก about us -->
                </div>
            </div>

            <div id="bg-service-2" class="box-one-third light-text">
                <div class="inner">
                    <!-- page หน้าแรก Services -->
                    <?php echo _html($_DATA['home_ourservices']['content']) ;?>
                    <!-- .page หน้าแรก Services -->
                </div>
            </div>

            <div id="bg-service-3" class="box-one-third light-text">
                <div class="inner">
                    <!-- page หน้าแรก Products -->
                    <?php echo _html($_DATA['home_products']['content']) ;?>
                    <!-- .page หน้าแรก Products -->
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <section id="section-features">
            <div class="container">
                    <!-- page หน้าแรก cert -->
                    <?php echo _html($_DATA['home_cert']['content']) ;?>
                    <!-- .page หน้าแรก cert -->
            </div>
        </section>

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