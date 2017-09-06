<?php 
$slides = $_DATA['slide'] ;
// 
?>
<div id="slider">
        <div class="fullwidthbanner-container">
            <div id="revolution-slider">
                <ul>
                    <?php 
                        $slide1 = $slides[0] ;
                        $text1 = explode("<br>",$slides[0]['content']);
                    ?>
                    <li data-transition="fade" data-slotamount="7" data-masterspeed="2500" data-delay="5000">
                        <img src="<?=$slide1['image']?>" alt="">

                        <div class="tp-caption"
                            data-x="20"
                            data-y="15"
                            data-speed="800"
                            data-start="1000"
                            data-easing="easeInOutCubic"
                            data-endspeed="300">
                            <img src="<?=THEME_ROOT_URL?>img/logo_jstech.jpg" alt=""">
                        </div>

                        <div class="tp-caption sfr custom-font-1"
                            data-x="20"
                            data-y="180"
                            data-speed="800"
                            data-start="800"
                            data-easing="easeInOutCubic">
                            <?=$text1[0]?>
                            <?=_html(str_replace("|","<br>",$text1[0]))?>  
                        </div>

                        <div class="tp-caption sfr custom-font-1"
                            data-x="20"
                            data-y="235"
                            data-speed="800"
                            data-start="1000"
                            data-easing="easeInOutCubic">
                            <?=_html(str_replace("|","<br>",$text1[1]))?> 
                        </div>

                        <div class="tp-caption sfr custom-font-1"
                            data-x="20"
                            data-y="290"
                            data-speed="800"
                            data-start="1200"
                            data-easing="easeInOutCubic">
                           <?=_html(str_replace("|","<br>",$text1[2]))?> 
                        </div>

                        <div class="tp-caption sfb custom-font-2"
                            data-x="20"
                            data-y="345"
                            data-speed="800"
                            data-start="1400"
                            data-easing="easeInOutCubic">
                            <?=_html(str_replace("|","<br>",$text1[3]))?> 
                        </div>
                    </li>
                    <?php 
                        $slide2 = $slides[1] ;
                        $text2 = explode("<br>",($slides[1]['content']));
                    ?>
                    <li data-transition="fade" data-slotamount="7" data-masterspeed="2500" data-delay="5000">
                        <img src="<?=$slide2['image']?>" alt="">

                        <div class="tp-caption h-line lft"
                            data-x="center"
                            data-y="170"
                            data-speed="800"
                            data-start="1000"
                            data-easing="easeInOutCubic"
                            data-endspeed="300">
                        </div>

                        <div class="tp-caption lft custom-font"
                            data-x="center"
                            data-y="220"
                            data-speed="800"
                            data-start="800"
                            data-easing="easeInOutCubic">
                            <?=_html(str_replace("|","<br>",$text2[0]))?> 
                        </div>

                        <div class="tp-caption sfb custom-font-1"
                            data-x="center"
                            data-y="270"
                            data-speed="800"
                            data-start="1400"
                            data-easing="easeInOutCubic">
                            <?=_html(str_replace("|","<br>",$text2[1]))?> 
                        </div>

                        <div class="tp-caption sfb custom-font-2 text-center"
                            data-x="center"
                            data-y="330"
                            data-speed="800"
                            data-start="1600"
                            data-easing="easeInOutCubic">
                            <?=_html(str_replace("|","<br>",$text2[2]))?> 
                        </div>
                    </li>
                    
                    <?php 
                        $slide3 = $slides[2] ;
                        $text3 = explode("<br>",($slides[2]['content']));
                    ?>
                    <li data-transition="fade" data-slotamount="7" data-masterspeed="2500" data-delay="5000">
                        <img src="<?=$slide2['image']?>" alt="">

                        <div class="tp-caption h-line lft"
                            data-x="20"
                            data-y="130"
                            data-speed="800"
                            data-start="1000"
                            data-easing="easeInOutCubic"
                            data-endspeed="300">
                        </div>

                        <div class="tp-caption sfr custom-font-1"
                            data-x="20"
                            data-y="180"
                            data-speed="800"
                            data-start="800"
                            data-easing="easeInOutCubic">
                            <?=str_replace("|","<br>",$text3[0])?> 
                        </div>

                        <div class="tp-caption sfr custom-font-1"
                            data-x="20"
                            data-y="235"
                            data-speed="800"
                            data-start="1000"
                            data-easing="easeInOutCubic">
                            <?=str_replace("|","<br>",$text3[1])?> 
                        </div>

                        <div class="tp-caption sfr custom-font-1"
                            data-x="20"
                            data-y="290"
                            data-speed="800"
                            data-start="1200"
                            data-easing="easeInOutCubic">
                            <?=str_replace("|","<br>",$text3[3])?> 
                        </div>

                        <div class="tp-caption sfb custom-font-2"
                            data-x="20"
                            data-y="345"
                            data-speed="800"
                            data-start="1400"
                            data-easing="easeInOutCubic">
                            <?=str_replace("|","<br>",$text3[4])?> 
                        </div>
                    </li>
                    
                    <?php 
                        $slide4 = $slides[3] ;
                        $text4 = explode("<br>",($slides[3]['content']));
                    ?>
                    <li data-transition="fade" data-slotamount="7" data-masterspeed="2500" data-delay="5000">
                        <img src="<?=THEME_ROOT_URL?>img/bg_slide04.jpg" alt="">

                        <div class="tp-caption h-line lft"
                            data-x="center"
                            data-y="170"
                            data-speed="800"
                            data-start="1000"
                            data-easing="easeInOutCubic"
                            data-endspeed="300">
                        </div>

                        <div class="tp-caption lft custom-font"
                            data-x="center"
                            data-y="220"
                            data-speed="800"
                            data-start="800"
                            data-easing="easeInOutCubic">
                            <?=str_replace("|","<br>",$text4[0])?> 
                        </div>

                        <div class="tp-caption sfb custom-font-1"
                            data-x="center"
                            data-y="270"
                            data-speed="800"
                            data-start="1400"
                            data-easing="easeInOutCubic">
                            <?=str_replace("|","<br>",$text4[1])?>
                        </div>

                        <div class="tp-caption sfb custom-font-2 text-center"
                            data-x="center"
                            data-y="330"
                            data-speed="800"
                            data-start="1600"
                            data-easing="easeInOutCubic">
                            <?=str_replace("|","<br>",$text4[2])?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>