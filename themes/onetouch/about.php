<?php 
// echo "<pre>";
// print_r($_DATA) ;
// echo "</pre>";
?>
<?php include('header.php'); ?>
        <article id="intro" style="background-image: url(<?=THEME_ROOT_URL?>images/content/about-intro.jpg);">
            <div class="container">
                <div class="row d-table">
                    <div class="col-12 d-table-cell">
                        <h1>
                            <div class="title has-outline text-none">Know About</div>
                            <div class="empty-clear"></div>
                            <div class="tagline has-outline mb-0"><strong>Onetouch</strong> Condom</div>
                        </h1>
                        <div class="scrolldown has-outline has-outline-5"></div>
                    </div>
                </div>
            </div>
        </article>
        <!-- page about -->
        <?=_html($_DATA['about_know_about']['content'])?>
    <!--     <article class="pt-70 pb-30">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h1>
                            <div class="tagline text-none">Know About</div>
                            <div class="empty-clear"></div>
                            <div class="title has-outline">Onetouch</div> 
                        </h1>
                        <h2>Lorem Ipsum is simply</h2>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it.</p>
                        <div class="separator"></div>
                        <p class="color-soft">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it.</p>
                    </div>
                    <div class="col-md-6">
                        <div class="d-lg-table w-100 h-100">
                            <div class="d-lg-table-cell text-center align-middle">
                                <img src="<?=THEME_ROOT_URL?>images/content/home-product-003.jpg" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article> -->
        <!-- <?=_html($_DATA['about_know_about']['content'])?> -->
       <!--  <article class="pt-70 pb-30">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-lg-table w-100 h-100">
                            <div class="d-lg-table-cell text-center align-middle">
                                <img src="<?=THEME_ROOT_URL?>images/content/logo.png" />
                                <img src="<?=THEME_ROOT_URL?>images/content/thai-nippon-rubber-logo.png" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h2>Lorem Ipsum is simply</h2>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it.</p>
                        <div class="separator"></div>
                        <p class="color-soft">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s when an unknown printer took a galley of type and scrambled it.</p>
                    </div>
                </div>
            </div>
        </article> -->
        <!-- .page about -->
        <!-- page category history -->
        <article id="about-history" class="has-fill-top pb-70">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1>
                            <div class="tagline">Onetouch</div>
                            <div class="empty-clear"></div>
                            <div class="title has-outline"><strong>History</strong> Timeline</div> 
                        </h1>
                        <div class="owl-carousel owl-theme mx-auto">
                            <!-- page category history loop each -->
                            <?php $about_history = $_DATA['about_history'] ; 
                            foreach($about_history as $ah){
                            ?>
                            <div class="item" data-dot="<?=$ah['name']?>">
                                <?=_html($ah['content'])?>
                            </div>
                            <?php } ?>
                            <!-- .page category history loop each -->
                        </div>
                        <div id="carousel-custom-dots" class="mx-auto">
                            <!-- page category history loop dot -->
                            <?php $about_history = $_DATA['about_history'] ; 
                            foreach($about_history as $ah){
                            ?>
                             <div class="owl-dot">
                                <span class="dot"></span>
                                <span class="txt"><?=$ah['name']?></span>
                            </div>
                            <?php } ?>
                            <!-- .page category history loop dot -->
                        </div>
                    </div>
                </div>
            </div>
        </article>
        <!-- .page category history -->
        

<?php include('footer.php'); ?>