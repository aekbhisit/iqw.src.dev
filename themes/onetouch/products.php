<?php 
// echo "<pre>";
// print_r($_DATA) ;
// echo "</pre>";
?>
<?php include('header.php'); ?>
        <article id="intro" style="background-image: url(<?=THEME_ROOT_URL?>images/gallery/pexels-photo-341858.jpg);">
            <div class="container">
                <div class="row d-table">
                    <div class="col-12 d-table-cell">
                        <h1>
                            <div class="title text-none has-outline">All products</div>
                            <div class="empty-clear"></div>
                            <div class="tagline has-outline mb-0"><strong>Onetouch</strong> Condom</div>
                        </h1>
                        <div class="scrolldown has-outline has-outline-5"></div>
                    </div>
                </div>
            </div>
        </article>
        <article id="product-1" class="product-type pt-70 pb-30">
            <div class="container pb-30">
                <div class="row">
                    <div class="col-md-6">
                        <!-- product category condom -->
                        <?php $cat_condom =  $_DATA['product_cat_condom']; ?>
                        <h1>
                            <div class="tagline text-none"><?=$cat_condom['name']?></div>
                            <div class="empty-clear"></div>
                            <div class="title has-outline"><?=str_replace(array('[',']'),array('<span class="text-light">','</span>'),$cat_condom['slug'])?></div> 
                        </h1>
                        <?=_html($cat_condom['description'])?>
                        <!-- .product category condom -->
                    </div>
                    <div class="col-md-6">
                        <div class="d-lg-table w-100 h-100">
                            <div class="d-lg-table-cell text-center align-middle">
                                <img src="<?=$cat_condom['image']?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
        <section id="collapseOne" class="collapse pt-40" role="tabpanel" aria-labelledby="headingOne">
            <div class="container">
                <div class="row">
                    <!-- loop product in category condom -->
                     <?php $product_condom =  $_DATA['products_condom']; 
                     foreach($product_condom as $pc){
                     ?>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="product-item-box has-outline has-outline-5">
                            <div class="product-label">
                                <span>New</span>
                            </div>
                            <a href="/product-<?=$pc['id']?>-<?=$pc['slug']?>">
                                <img src="<?=$pc['image']?>" alt="<?=$pc['name']?>" />
                                <h2><?=$pc['name']?></h2>
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- .loop product in category condom -->
                </div>
            </div>
        </section>
        <article id="product-2" class="product-type pt-70 pb-30">
            <div class="container">
                <div class="row">
                    <?php $cat_gel =  $_DATA['products_cateogry_gel']; ?>
                    <div class="col-md-6">
                        <div class="d-lg-table w-100 h-100">
                            <div class="d-lg-table-cell text-center align-middle">
                                <img src="<?=$cat_gel['image']?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- product category gel -->
                        
                        <h1>
                            <div class="tagline text-none"><?=$cat_gel['name']?></div>
                            <div class="empty-clear"></div>
                            <div class="title has-outline"><?=str_replace(array('[',']'),array('<span class="text-light">','</span>'),$cat_gel['slug'])?></div> 
                        </h1>
                        <?=_html($cat_gel['description'])?>
                        <!-- .product category gel -->
                    </div>
                </div>
            </div>
        </article>
        <section id="collapseTwo" class="collapse pt-40" role="tabpanel" aria-labelledby="headingTwo">
            <div class="container">
                <div class="row">
                    <!-- loop product in category condom -->
                     <?php $product_gel =  $_DATA['products_condom']; 
                     foreach($product_gel as $pg){
                     ?>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="product-item-box has-outline has-outline-5">
                            <div class="product-label">
                                <span>New</span>
                            </div>
                            <a href="product-detail.php">
                                <img src="<?=$pg['image']?>" alt="<?=$pg['name']?>" />
                                <h2><?=$pg['name']?></h2>
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- .loop product in category condom -->
                </div>
            </div>
        </section>
        <article id="product-buy" class="pt-70 pb-80">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="separator"></div>
                        <h1>
                            <div class="tagline mb-0">Onetouch</div>
                            <div class="empty-clear"></div>
                            <div class="title text-none">Where to Get?</div> 
                        </h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <!-- page Onetouch here to Get? category detail -->
                       <?=_html($_DATA['product_wheretoget']['description'])?>
                         <!-- .page Onetouch here to Get? category detail -->
                    </div>
                    <div class="col-md-8">
                        <div id="accordion" role="tablist" aria-multiselectable="false">
                            <!-- page loop incategory Onetouch here to Get?  -->
                            <?php $products_wheretoget_all = $_DATA['products_wheretoget_all'] ; 
                            foreach($products_wheretoget_all as $wtg){
                            ?>
                            <?=_html($wtg['content'])?>
                            <?php } ?>
                            <!-- .page loop incategory Onetouch here to Get?  -->
                        </div>
                    </div>
                </div>
            </div>
        </article>
<?php include('footer.php'); ?>