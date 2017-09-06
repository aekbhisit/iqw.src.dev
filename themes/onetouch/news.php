<?php 
// echo "<pre>";
// print_r($_DATA) ;
// echo "</pre>";
?>
<?php include('header.php'); ?>
        <article id="intro" style="background-image: url(<?=THEME_ROOT_URL?>images/content/news-intro.jpg);">
            <div class="container">
                <div class="row d-table">
                    <div class="col-12 d-table-cell">
                        <h1>
                            <div class="title has-outline text-none">News & Events</div>
                            <div class="empty-clear"></div>
                            <div class="tagline has-outline mb-0"><strong>Onetouch</strong> Condom</div>
                        </h1>
                        <div class="scrolldown has-outline has-outline-5"></div>
                    </div>
                </div>
            </div>
        </article>
        <article id="news-section" class="pt-70 pb-50">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1>
                            <div class="tagline has-outline"><strong>Onetouch</strong> News</div>
                        </h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-columns">
                            <?php $news_all = $_DATA['news_all'] ;
                            foreach($news_all as $na){
                                $show_data = showDate($na['cdate'],'DD MMM YY','en') ;
                            ?>
                            <div class="card mb-30">
                                <img class="card-img-top" src="<?=$na['image']?>" alt="<?=$na['name']?>">
                                <div class="card-body">
                                    <h2 class="card-title"><?=$na['name']?></h2>
                                    <div class="card-info">
                                        <span><i class="fa fa-calendar" aria-hidden="true"></i> <?=$show_data['D']?> <?=$show_data['M']?> <?=$show_data['Y']?></span>
                                        <span><i class="fa fa-eye" aria-hidden="true"></i> <?=$na['stat']?> views</span>
                                    </div>
                                    <p class="card-text"><?=substr(strip_tags(_html($na['content'])),0,100)?></p>
                                    <a href="/news" class="btn read-more has-outline has-outline-5 mt-20 mb-30">See More +</a>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <nav aria-label="Page navigation example" class="mx-auto mt-30">
                        <ul class="pagination pagination-lg justify-content-center">
                            <!-- <li class="page-item"><a class="page-link" href="#">Previous</a></li> -->
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">...</a></li>
                            <li class="page-item"><a class="page-link" href="#">10</a></li>
                            <!-- <li class="page-item"><a class="page-link" href="#">Next</a></li> -->
                        </ul>
                    </nav>
                </div>
            </div>
        </article>
        <article id="event-section" class="pt-70 pb-50">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1>
                            <div class="tagline has-outline"><strong>Onetouch</strong> Events</div>
                        </h1>
                    </div>
                </div>
                <div class="row mt-20">
                    <?php $event_all = $_DATA['event_all'] ;
                    foreach($event_all as $ea){
                        $show_data = showDate($ea['cdate'],'DD MMM YY','en') ;
                    ?>
                    <div class="col-lg-4 col-sm-6 col-12 event-item-box">
                        <a class="event-item d-block" href="#" style="background-image: url(<?=$ea['image']?>);">
                            <div class="event-content-box d-block">
                                <div class="event-view">
                                    <div>
                                        <i class="fa fa-eye"></i>
                                        <p>View</p>
                                    </div>
                                </div>
                                <div class="event-description">
                                    <div class="event-title max-line-1"><?=$ea['name']?></div>
                                    <div class="max-line-2">
                                        <?=substr(strip_tags(_html($ea['content'])),0,100)?>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <h2 class="mt-30 mb-40 max-line-1"><a href="#"><?=$ea['name']?></a></h2>
                    </div>
                    <?php } ?>
                   
                </div>
            </div>
            <div class="row">
                    <nav aria-label="Page navigation example" class="mx-auto mt-30">
                        <ul class="pagination pagination-lg justify-content-center">
                            <!-- <li class="page-item"><a class="page-link" href="#">Previous</a></li> -->
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">...</a></li>
                            <li class="page-item"><a class="page-link" href="#">10</a></li>
                            <!-- <li class="page-item"><a class="page-link" href="#">Next</a></li> -->
                        </ul>
                    </nav>
                </div>
        </article>
<?php include('footer.php'); ?>