<?php include 'inc/header.php'; ?>
<?php 
$news = $_DATA['news'] ;
?>
<!-- Breadcrumb -->
    <section id="subheader" class="bc04 no-bottom" data-stellar-background-ratio="0.5">
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
    <div id="content" class="no-bottom">

        <div class="container">
            <?php foreach($news as $n){?>
            <div class="news-list">
                <div class="row">
                    <div class="col-md-4">
                        <div class="thumb-news">
                        <a href="/news_detail-<?=$n['id']?>-<?=$n['slug']?>"><img src="<?=$n['cover']?>" alt="<?=$n['name']?>"></a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <a href="/news_detail-<?=$n['id']?>-<?=$n['slug']?>"><h2><?=$n['name']?></h2></a>
                        <div class="small-border left wow flipInY" data-wow-delay=".8s" data-wow-duration=".8s"></div>
                        <p><?=iconv_substr(strip_tags(_html($n['content'])),0,200,'utf-8')?></p>
                        <a href="/news_detail-<?=$n['id']?>-<?=$n['slug']?>" class="btn-text">View More</a>
                    </div>
                </div>
            </div>
            <?php } ?>
           

        </div>

        <div class="clearfix"></div>

        <div class="h60"></div>

        <div class="call-to-action text-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-9">
                        <h2>Contact us now to get quote for all your needs.</h2>
                    </div>

                    <div class="col-md-3">
                        <a href="contact.php" class="btn-border-light">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

<?php include 'inc/footer.php'; ?>