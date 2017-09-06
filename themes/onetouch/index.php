<?php 
// echo "<pre>";
// print_r($_DATA) ;
// echo "</pre>";

$_head_banner = $_DATA["home_banner"] ;

?>
<?php include('header.php'); ?>
        <article id="intro" style="background-image: url(<?=$_head_banner['image']?>);">
            <div class="container">
                <div class="row d-table">
                    <div class="col-12 d-table-cell">
                        <h1>
                            <div class="title has-outline"><?=$_head_banner['name']?></div>
                            <div class="empty-clear"></div>
                            <div class="tagline has-outline text-none mb-0"><?=str_replace(array('[',']'),array('<strong>','</strong>'),$_head_banner['slug'])?></div>
                        </h1>
                        <div class="scrolldown has-outline has-outline-5"></div>
                    </div>
                </div>
            </div>
        </article>
        <article id="home-about" class="pt-70 pb-30">
            <div class="container">
                <div class="row">
                    <!-- home home_know_about -->
                   	<?=_html($_DATA['home_know_about']['content']) ; ?>
                    <!-- .home home_know_about -->
                </div>
            </div>
        </article>
        <article id="home-product" class="has-fill-top has-fill-bottom">
            <div class="container">
                <div class="row">
                    <?php 
					$show_product = $_DATA['home_show_product'][0] ;

                    ?>
                    <div class="col-md-6">
                        <div class="d-lg-table w-100 h-100">
                            <div class="d-lg-table-cell text-center align-middle">
                                <div class="owl-carousel owl-carousel-1 owl-theme mx-auto">
                                    <!-- home product image  -->
                                    <?php if(!empty($show_product['image'])){?>
                                    <div class="item">
                                        <img src="<?=$show_product['image']?>" />
                                    </div>
                                    <?php } ?>
                                     <?php if(!empty($show_product['image1'])){?>
                                    <div class="item">
                                        <img src="<?=$show_product['image1']?>" />
                                    </div>
                                    <?php } ?>
                                     <?php if(!empty($show_product['image2'])){?>
                                    <div class="item">
                                        <img src="<?=$show_product['image2']?>" />
                                    </div>
                                    <?php } ?>
                                     <?php if(!empty($show_product['image3'])){?>
                                    <div class="item">
                                        <img src="<?=$show_product['image3']?>" />
                                    </div>
                                    <?php } ?>
                                     <?php if(!empty($show_product['image4'])){?>
                                    <div class="item">
                                        <img src="<?=$show_product['image4']?>" />
                                    </div>
                                    <?php } ?>
                                    <!-- .home product image  -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- home product detail  -->
                        <?=_html($show_product['content'])?>
                        <!-- .home product detail  -->
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="owl-carousel owl-carousel-2 owl-theme mx-auto">
                            <?php $all_products = $_DATA['home_all_product'] ;
                            foreach($all_products as $p){
                            ?>
                            <div class="item">
                            	<a href="/product-<?=$p['id']?>-<?=$p['name']?>" target="_blank"  >
                                <img src="<?=$p['image']?>" alt="<?=$p['name']?>" />
                            	</a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </article>
        <article id="home-news" class="pt-0 pb-70">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-right">
                        <h1 class="text-left">
                            <div class="tagline mb-0">Onetouch</div>
                            <div class="empty-clear"></div>
                            <div class="title">News & Events</div> 
                        </h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation">
                                <a href="#news" aria-controls="news" role="tab" data-toggle="tab" class="active">News</a>
                            </li>
                            <li role="presentation">
                                <a href="#events" aria-controls="events" role="tab" data-toggle="tab">Events</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="news">
                                <ul class="news-events-list">
                                	<?php $home_news = $_DATA['home_news'] ;
		                            foreach($home_news as $n){
                                        $show_data = showDate($n['cdate'],'DD MMM YY','en') ;
		                            ?>
                                    <li class="list-item">
                                        <div class="row">
                                            <div class="col-md-5 item-image">
                                                <img class="img-fluid img-thumbnail" src="<?=$n['image']?>" alt="<?=$n['name']?>">
                                            </div>
                                            <div class="col-md-7 item-detail">
                                                <h3 class="max-line-1"><?=$n['name']?></h3>
                                                <div class="item-info">
                                                    <span><i class="fa fa-calendar" aria-hidden="true"></i> <?=$show_data['D']?> <?=$show_data['M']?> <?=$show_data['Y']?></span>
                                                    <span><i class="fa fa-eye" aria-hidden="true"></i> <?=$n['stat']?> views</span>
                                                </div><?=_html(['content'])?>
                                             </div>
                                        </div>
                                    </li>
                                    <?php } ?>
                                </ul>
                                <nav class="pagination">
                                    <a href="#" class="active">1</a>
                                    <a href="#">2</a>
                                    <a href="#">3</a>
                                    <a href="#">4</a>
                                </nav>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="events">
                             	event detail
                               <!--  <ul class="news-events-list">
                                	<?php $home_events = $_DATA['home_events'] ;
		                            foreach($home_events as $n){
		                            	$show_date = showDate($n['cdate'],'DD MMM YY','en');
		                            ?>
                                    <li class="list-item">
                                        <div class="row">
                                            <div class="col-md-5 item-image">
                                                <img class="img-fluid img-thumbnail" src="<?=$n['image']?>" alt="<?=$n['name']?>">
                                            </div>
                                            <div class="col-md-7 item-detail">
                                                <h3 class="max-line-1"><?=$n['name']?></h3>
                                                <div class="item-info">
                                                    <span><i class="fa fa-calendar" aria-hidden="true"></i> <?=$show_date['D']?> <?=$show_date['M']?> <?=$show_date['Y']?></span>
                                                    <span><i class="fa fa-eye" aria-hidden="true"></i> <?=$n['stat']?> views</span>
                                                </div><?=_html(['name'])?></div>
                                            </div>
                                        </div>
                                    </li>
                                    <?php } ?>
                                </ul>
                                <nav class="pagination">
                                    <a href="#" class="active">1</a>
                                    <a href="#">2</a>
                                    <a href="#">3</a>
                                    <a href="#">4</a>
                                </nav> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation">
                                <a href="#facebook" aria-controls="facebook" role="tab" data-toggle="tab" class="active">Facebook</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="facebook">
                                <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fonetouchthailand%2F&tabs=timeline&width=500&height=436&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=false&appId" width="500" height="436" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
        <article id="home-gallery" class="has-fill-footer pb-50">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1>
                            <div class="tagline mb-0">Onetouch</div>
                            <div class="empty-clear"></div>
                            <div class="title text-none">Our Gallery</div> 
                        </h1>
                    </div>
                </div>
                <div class="row">
                	<?php $home_gallery = $_DATA['home_gallery'] ;
		                foreach($home_gallery['images'] as $g){
		            ?>
                    <div class="col-lg-3 col-md-4 col-xs-6">
                        <a class="d-block mb-4" data-toggle="lightbox" data-gallery="gallery" href="<?=$g['image']?>">
                            <img class="img-fluid img-thumbnail" src="<?=$g['image']?>" alt="<?=$g['title']?>">
                        </a>
                    </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <a href="/gallery" class="read-more has-outline has-outline-5 mt-30">More +</a>
                    </div>
                </div>
            </div>
        </article>
<?php include('footer.php'); ?>