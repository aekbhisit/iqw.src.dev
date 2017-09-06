<?php include 'inc/header.php'; ?>
<?php 
$news = $_DATA['news_detail'] ;
?>
<!-- Breadcrumb -->
    <section id="subheader" class="no-bottom" data-stellar-background-ratio="0.5">
        <div class="overlay">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                            <h1>News
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
    <div id="content">

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2><?=$news['name']?></h2>
                    <div class="small-border left wow flipInY" data-wow-delay=".8s" data-wow-duration=".8s"></div>
                    <p><?=_html($news['content'])?></p>
                    <div class="h30"></div>
                    <div id="gallery" class="gallery full-gallery ex-gallery zoom-gallery">
                        <?php 
                        $images = $news['images'] ; 
                        foreach($images as $img) {
                        ?>
                        <div class="item illustration">
                            <div class="picframe">
                                <a href="<?=$img['image']?>">
                                    <img src="<?=$img['image']?>" alt="<?=$img['title']?>" />
                                </a>
                            </div>
                        </div>
                        <?php } ?>
                       
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
    </div>

<?php include 'inc/footer.php'; ?>