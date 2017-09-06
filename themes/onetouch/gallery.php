<?php 
// echo "<pre>";
// print_r($_DATA) ;
// echo "</pre>";
?>
<?php include('header.php'); ?>
        <article id="intro" style="background-image: url(<?=THEME_ROOT_URL?>images/content/gallery-intro.jpg);">
            <div class="container">
                <div class="row d-table">
                    <div class="col-12 d-table-cell">
                        <h1>
                            <div class="title has-outline text-none">Our Gallery</div>
                            <div class="empty-clear"></div>
                            <div class="tagline has-outline mb-0"><strong>Onetouch</strong> Condom</div>
                        </h1>
                        <div class="scrolldown has-outline has-outline-5"></div>
                    </div>
                </div>
            </div>
        </article>
        <article id="gallery-main" class="pt-70 pb-50">
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
                    <?php $images = $_DATA['gallery_all']['images'] ; 
                    foreach($images as $img){
                    ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <a class="d-block mb-4" data-toggle="lightbox" data-gallery="gallery" href="<?=$img['image']?>">
                            <img class="img-fluid img-thumbnail" src="<?=$img['image']?>" alt="<?=$img['name']?>">
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </article>
<?php include('footer.php'); ?>