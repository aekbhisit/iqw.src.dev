    </main>

    <footer>
        <section id="footer-contact" class="pt-80 pb-50">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="text-center">Contact Us</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-sm-6 col-12">
                        <!-- home footer quickmenu -->
                        <h3>Quick Menu</h3>
                        <nav class="footer-links">
                            <ul class="navbar-nav">
                                <li class="nav-item active"><a href="index.php">Home</a></li>
                                <li class="nav-item active"><a href="about.php">About</a></li>
                                <li class="nav-item active"><a href="products.php">Products</a></li>
                                <li class="nav-item active"><a href="news.php">News & Events</a></li>
                                <li class="nav-item active"><a href="contact.php">Contact</a></li>
                            </ul>
                        </nav>
                        <!-- .home footer quickmenu -->
                        <div class="footer-social-medias mt-80">
                            <!-- home footer Follow Us -->
                            Follow Us
                            <a href="#" rel="nofollow" target="_blank"><i class="fa fa-facebook"></i></a>
                            <a href="#" rel="nofollow" target="_blank"><i class="fa fa-twitter"></i></a>
                            <a href="#" rel="nofollow" target="_blank"><i class="fa fa-instagram"></i></a>
                            <!-- .home footer Follow Us -->
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <!-- home footer Appointed -->
                        <h3>Appointed Distributor</h3>
                        <p>
                            C.P. Consumer Product Co., Ltd.<br />
                            52 Moo15, Ramitra Rd. (km 13)<br />
                            Minburi, Bangkok 10510 Thailand.
                        </p>
                        <div class="mt-20">
                            <div class="custom-icon custom-icon-phone">(662) 917-9740</div>
                            <div class="custom-icon custom-icon-fax">(662) 917-9729</div>
                            <div class="custom-icon custom-icon-email">thanat@cpconsumer.com</div>
                        </div>
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
    <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/popper.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-formhelpers.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.validator.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.2.0/ekko-lightbox.min.js"></script>
    <script type="text/javascript" src="owlcarousel/owl.carousel.min.js"></script>
    <script type="text/javascript" src="js/jquery.ellipsis.min.js"></script>
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
