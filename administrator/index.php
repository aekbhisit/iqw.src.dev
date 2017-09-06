<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- Viewport metatags -->
<meta name="HandheldFriendly" content="true" />
<meta name="MobileOptimized" content="320" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<!-- iOS webapp metatags -->
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />

<!-- iOS webapp icons -->
<link rel="apple-touch-icon" href="touch-icon-iphone.png" />
<link rel="apple-touch-icon" sizes="72x72" href="touch-icon-ipad.png" />
<link rel="apple-touch-icon" sizes="114x114" href="touch-icon-retina.png" />

<!-- CSS Reset -->
<link rel="stylesheet" type="text/css" href="css/reset.css" media="screen" />
<!--  Fluid Grid System -->
<link rel="stylesheet" type="text/css" href="css/fluid.css" media="screen" />
<!-- Login Stylesheet -->
<link rel="stylesheet" type="text/css" href="css/login.css" media="screen" />

<!-- Required JavaScript Files -->
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery.placeholder.js"></script>
<script type="text/javascript" src="plugins/validate/jquery.validate.min.js"></script>

<!-- Core JavaScript Files -->
<script type="text/javascript" src="js/core/dandelion.login.js"></script>
<script type="text/javascript">
function setAdminLogin(){
		jQuery.extend(jQuery.validator.messages, {
				required: "ต้องใส่ข้อมูล."
		});
		var d = new Date();	
		var url = "../app/index.php?module=users&task=setAdminLogin&d"+d.getTime() ;
			$.ajax({
				  type: 'POST', 
				  url: url, 
				  enctype: 'multipart/form-data', 
				  data: $('#users_login_form').serialize(),
				  beforeSend: function() {
							 $('#users_login_form').validate({ 
								rules: {
								username: {
									required: true
								},
								password: {
									required: true
								}
							}, 
							invalidHandler: function(form, validator) {
								var errors = validator.numberOfInvalids();
								if (errors) {
									var message = errors == 1
									? 'ผิดพลาด ต้องใส่ข้อมูลให้ครบ'
									: 'ผิดพลาด ต้องใส่ข้อมูลให้ครบ';
									//$("#form-error").html(message).show();
								} else {
									//$("#form-error").hide();
								}
							}
							 });
							return $('#users_login_form').valid();
					  },
				  success: function(data){
					var result = $.parseJSON(data);
					if(result.loginResult=='login_match'){
					  	var login_redirect =  'dashboard/dashboard.php';
						window.location.replace(login_redirect);
					}else{
						if(result.loginResult=='password_notmatch'){
							var msg  = 'รหัสผ่านไม่ถูกต้อง';
							$('#login_result').html(msg);
						}
						if(result.loginResult=='username_notmatch'){
							var msg  = 'ชื่อผู้ใช้ไม่ถูกต้อง';
							$('#login_result').html(msg);
						}
					}
				 }
			});
		
	}
</script>
<title><?=ucfirst($_SERVER['SERVER_NAME'])?> Admin - Login</title>
</head>

<body>
<div id="da-login">
	<div id="da-login-box-wrapper">
    	<div id="da-login-top-shadow">
        </div>
    	<div id="da-login-box">
        	<div id="da-login-box-header">
            	<h1>Login</h1><div id="login_result" style="float:left; font-size:12px; width:100%; text-align:center; color:#900;"></div>
            </div>
            <div id="da-login-box-content">
            
            	<form id="users_login_form"  name="users_login_form" method="post" onsubmit="setAdminLogin() ;return false ;">
                	<div id="da-login-input-wrapper">
                    	<div class="da-login-input">
	                        <input type="text" name="username" id="da-login-username" placeholder="Username" />
                        </div>
                    	<div class="da-login-input">
	                        <input type="password" name="password" id="da-login-password" placeholder="Password" />
                        </div>
                    </div>
                    <div id="da-login-button">
                    	<input type="submit" value="Login"" id="da-login-submit" />
                    </div>
                </form>
            </div>
            <div id="da-login-box-footer">
            	<!--<a href="#">forgot your password?</a>-->
                <div id="da-login-tape"></div>
            </div>
        </div>
    	<div id="da-login-bottom-shadow">
        </div>
    </div>
</div>
    
</body>
</html>
