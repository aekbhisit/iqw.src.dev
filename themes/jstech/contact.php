<?php include 'inc/header.php'; ?>

<!-- Breadcrumb -->
    <section id="subheader" class="bc01 no-bottom" data-stellar-background-ratio="0.5">
        <div class="overlay">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1>Contact Us
							<span>Get In Touch With Us</span>
                        </h1>
                        <div class="small-border wow flipInY" data-wow-delay=".8s" data-wow-duration=".8s"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="clearfix"></div>

<!-- Map -->
    <!-- <div id="map"></div> -->
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d8864.855609462584!2d101.1860798903532!3d12.698376136356082!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3102fc74e58f52f7%3A0xbbd466d9c7554590!2z4Lia4Lij4Li04Lip4Lix4LiXIOC5gOC4iOC5gOC4reC4qiDguYDguJfguIQg4LiI4Liz4LiB4Lix4LiU!5e0!3m2!1sen!2sth!4v1499968270798" width="100%" height="480" frameborder="0" style="border:0" allowfullscreen></iframe>

<!-- Content -->
    <div id="content">
        <div class="container">
            <div class="row no-gutter">
                <div class="col-md-6">
                    <!-- pages ติดต่อเรา -->
                    <?php echo _html($_DATA['contact_us']['content']) ; ?>
                    <!-- pages ติดต่อเรา -->

                    <div class="divider-single"></div>

                    <div class="social-icons contact">
                        <a href="https://www.facebook.com/JS-TECH-CO-LTD-305799672827384/"><i class="fa fa-facebook"></i></a>
                        <a href=""><i class="fa fa-twitter"></i></a>
                        <a href="https://www.youtube.com/watch?v=V2O0Ga9tahg"><i class="fa fa-youtube"></i></a>
                        <a href=""><i class="fa fa-instagram"></i></a>
                    </div>

         
                </div>

                <div class="col-md-6">
                    <h2 class="wow fadeInUp" data-wow-delay=".5s" data-wow-duration=".8s">Contact Us</h2>
                    <div class="small-border left wow flipInY" data-wow-delay=".8s" data-wow-duration=".8s"></div>
                    <div id="contact-form-wrapper">
                        <div class="contact_form_holder">
                            <form id="contact" name="contact" class="form-horizontal" method="post" onsubmit="sendRegister(); return false;">
                            	<div class="row">
                            		<div class="col-md-6">
                                		<input type="text" class="form-control" name="name" id="name" placeholder="Your name" />
                            		</div>
                            		<div class="col-md-6">
		                                <!-- <div id="error_email" class="error">Please check your email</div> -->
		                                <input type="text" class="form-control" name="email" id="email" placeholder="Your email" />
                            		</div>
                            	<div class="row">
                            	</div>
                            		<div class="col-md-6">
                                		<input type="text" class="form-control" name="companyname" id="companyname" placeholder="Your company name" />
                            		</div>
                            		<div class="col-md-6">
                                		<input type="text" class="form-control" name="tel" id="tel" placeholder="Your telephone" />
                            		</div>
                            	</div>

                            	<div class="row">
                            		<div class="col-md-12">
		                                <!-- <div id="error_message" class="error">Please check your message</div> -->
		                                <textarea cols="10" rows="10" name="message" id="message" class="form-control" placeholder="Your message"></textarea>

		                                <!-- <div id="mail_success" class="success">Thank you. Your message has been sent.</div> -->
		                                <div id="mail_failed" class="error" style="display: none;">Error, email not sent</div>

		                                <p id="btnsubmit">
		                                    <input type="submit" value="Send" class="btn btn-custom" />
		                                </p>
                            		</div>
                            	</div>

                            </form>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>



<?php include 'inc/footer.php'; ?>
<script src="<?=THEME_ROOT_URL?>js/jquery.validate.min.js"></script>
<script type="text/javascript" charset="utf-8" >

        function sendRegister(){ //form-reqruitment-error
            var d = new Date(); 
            var url = "/app/index.php?module=contacts&task=sentContact&d"+d.getTime() ;
            // alert(url);
            $('#contact').show();
            $.ajax({
                  type: 'POST', 
                  url: url, 
                  enctype: 'multipart/form-data', 
                  data: $('#contact').serialize(),
                  beforeSend: function() {
                        $('#contact').validate({ 
                            rules: {
                            name: {
                                required: true
                            },
                            companyname:{
                                required: true
                            },
                            message:{
                                required: true
                            },
                            tel:{
                                required: true,
                                minlength:10,
                                digits: true
                            },
                            email:{
                                required: true,
                                email: true
                            }
                        }, 
                        invalidHandler: function(form, validator) {
                            var errors = validator.numberOfInvalids();
                            // if (errors) {
                            //     var message = errors == 1
                            //     ? 'ผิดพลาด ต้องใส่ข้อมูลให้ครบและถูกต้อง'
                            //     : 'ผิดพลาด ต้องใส่ข้อมูลให้ครบและถูกต้อง';
                            //     $(".error").html(message).show();
                            //     // $('#serviceReqruit').hide();
                            // } else {
                            //     $("#contact").hide();
                            // }
                        }
                         });
                        return $('#contact').valid();
                      },
                  success: function(data){
                    // alert(data);  
                    if(data==1){ 
                        if(confirm("ส่งอีเมล์สำเร็จ")){
                            window.location.reload(true);
                        }
                    }else{
                        alert('ขออภัยไม่สามารถส่งอีเมล์ได้ในขณะนี้ กรุณาลองใหม่อีกครั้ง');
                    }

                 }
            });
        }

       
        if($.fn.validate) {
            jQuery.extend(jQuery.validator.messages, {
                required: 'ต้องใส่ข้อมูลนี้',
                email: 'ต้องเป็นอีเมล์',
                digits: 'ต้องเป็นตัวเลข',
                minlength: jQuery.validator.format('ต้องมี 10 ตัว'),
            
            });
        }
  
    </script>
