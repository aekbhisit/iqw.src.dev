    </main>

    <footer>
        <section id="footer-contact" class="footer-contact pt-80 pb-50">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="text-center">Contact Us</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-sm-6 col-12">
                        <!-- home footer quickmenu -->
                        <?=_html($_DATA["footer_quickmenu"]['content'])?>
                        
                        <!-- .home footer quickmenu -->
                        <div class="footer-social-medias mt-80">
                            <!-- home footer Follow Us -->
                            <?=_html($_DATA["footer_followus"]['content'])?>
                            <!-- .home footer Follow Us -->
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <!-- home footer Appointed -->
                        <?=_html($_DATA["footer_appointed"]['content'])?>
                        <!-- .home footer Appointed -->
                    </div>
                    <div class="col-sm-6 col-12">
                        <h3>Leave Message</h3>
                        <form id="footer-contact-form" data-toggle="validator" role="form">
                            <div class="form-group">
                                <input type="text" class="form-control" id="inputName" placeholder="Name" data-error="That is required field" required />
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" id="inputEmail" placeholder="Email" data-error="That email address is invalid" required />
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" id="inputMessage" placeholder="Message" data-error="That is required field" required></textarea>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn has-outline has-outline-5">
                                    <div>Send</div>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <section id="footer-copyright" class="pt-10 pb-10">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">Copyright © 2017 Thai Nippon Rubber Industry Co., Ltd.All rights Reserved.</div>
                    </div>
                </div>
            </div>
        </section>
    </footer>

    <script type="text/javascript" src="https://use.typekit.net/lvt6bcw.js"></script>
    <script type="text/javascript">try{Typekit.load({ async: true });}catch(e){}</script>
    <script type="text/javascript" src="<?=THEME_ROOT_URL?>js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="<?=THEME_ROOT_URL?>js/popper.min.js"></script>
    <script type="text/javascript" src="<?=THEME_ROOT_URL?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?=THEME_ROOT_URL?>js/bootstrap-formhelpers.min.js"></script>
    <script type="text/javascript" src="<?=THEME_ROOT_URL?>js/bootstrap.validator.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.2.0/ekko-lightbox.min.js"></script>
    <script type="text/javascript" src="<?=THEME_ROOT_URL?>owlcarousel/owl.carousel.min.js"></script>
    <script type="text/javascript" src="<?=THEME_ROOT_URL?>js/jquery.ellipsis.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox();
            });
            $('#footer-contact-form').validator();
            $('#home-product .owl-carousel-1').owlCarousel({
                items: 1,
                loop: true,
                margin: 0,
                center: true,
            });
            $('#home-product .owl-carousel-2').owlCarousel({
                items: 4,
                slideBy: 4,
                loop: true,
                margin: 0,
                dots: false,
                nav: true,
                navText : ['<div class="arrow-left has-outline has-outline-5"></div>','<div class="arrow-right has-outline has-outline-5"></div>']
            });

            $('#about-history .owl-carousel').owlCarousel({
                items: 1,
                loop: true,
                margin: 0,
                center: true,
                dotData: true,
                dotsContainer: '#carousel-custom-dots'
            });

            $('.collapse').collapse('hide');
            $('a[data-toggle="collapse"]').on('click', function() {
                $(this).find('i').toggleClass('fa-angle-down fa-angle-up');
            });

            $('#product-main .image-thumbnail').find('.thumbnail').on('click', function() {
                var elm = $(this).attr('data-id');
                $('#product-main .image-view').find('.item:visible').fadeOut('fast', function() {
                    $(elm).fadeIn();
                });
            });

            $('.max-line-1').ellipsis({ responsive: true, lines: 1 });
            $('.max-line-2').ellipsis({ responsive: true, lines: 2 });
            $('.max-line-3').ellipsis({ responsive: true, lines: 3 });
        });
    </script>
</body>
</html>
