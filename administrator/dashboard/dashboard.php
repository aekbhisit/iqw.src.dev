<?php @include ("../inc/auth.inc.php") ; ?>
<?php include ("../inc/gettext.inc.php") ; ?>
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
<link rel="stylesheet" type="text/css" href="../css/reset.css" media="screen" />
<!--  Fluid Grid System -->
<link rel="stylesheet" type="text/css" href="../css/fluid.css" media="screen" />
<!-- Theme Stylesheet -->
<link rel="stylesheet" type="text/css" href="../css/dandelion.theme.css" media="screen" />
<!--  Main Stylesheet -->
<link rel="stylesheet" type="text/css" href="../css/dandelion.css" media="screen" />
<!-- Demo Stylesheet -->
<link rel="stylesheet" type="text/css" href="../css/demo.css" media="screen" />

<!-- jQuery JavaScript File -->
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>

<!-- jQuery-UI JavaScript Files -->
<script type="text/javascript" src="../jui/js/jquery-ui-1.8.20.min.js"></script>
<script type="text/javascript" src="../jui/js/jquery.ui.timepicker.min.js"></script>
<script type="text/javascript" src="../jui/js/jquery.ui.touch-punch.min.js"></script>
<link rel="stylesheet" type="text/css" href="../jui/css/jquery.ui.all.css" media="screen" />

<!-- Plugin Files -->

<!-- FileInput Plugin -->
<script type="text/javascript" src="../js/jquery.fileinput.js"></script>
<!-- Placeholder Plugin -->
<script type="text/javascript" src="../js/jquery.placeholder.js"></script>
<!-- Mousewheel Plugin -->
<script type="text/javascript" src="../js/jquery.mousewheel.min.js"></script>
<!-- Scrollbar Plugin -->
<script type="text/javascript" src="../js/jquery.tinyscrollbar.min.js"></script>
<!-- Tooltips Plugin -->
<script type="text/javascript" src="../plugins/tipsy/jquery.tipsy-min.js"></script>
<link rel="stylesheet" href="../plugins/tipsy/tipsy.css" />

<!-- Validation Plugin -->
<script type="text/javascript" src="../plugins/validate/jquery.validate.js"></script>

<!-- Statistic Plugin JavaScript Files (requires metadata and excanvas for IE) -->
<script type="text/javascript" src="../js/jquery.metadata.js"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="js/excanvas.js"></script>
<![endif]-->
<script type="text/javascript" src="../js/core/plugins/dandelion.circularstat.min.js"></script>

<!-- Wizard Plugin -->
<script type="text/javascript" src="../js/core/plugins/dandelion.wizard.min.js"></script>

<!-- Fullcalendar Plugin -->
<script type="text/javascript" src="../plugins/fullcalendar/fullcalendar.min.js"></script>
<script type="text/javascript" src="../plugins/fullcalendar/gcal.js"></script>
<link rel="stylesheet" href="../plugins/fullcalendar/fullcalendar.css" media="screen" />
<link rel="stylesheet" href="../plugins/fullcalendar/fullcalendar.print.css" media="print" />

<!-- Load Google Chart Plugin -->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	// Load the Visualization API and the piechart package.
	google.load('visualization', '1.0', {'packages':['corechart']});
</script>
<!-- Debounced resize script for preventing to many window.resize events
      Recommended for Google Charts to perform optimally when resizing -->
<script type="text/javascript" src="../js/jquery.debouncedresize.js"></script>

<!-- Core JavaScript Files -->
<script type="text/javascript" src="../js/core/dandelion.core.js"></script>

<!-- Customizer JavaScript File (remove if not needed) -->
<script type="text/javascript" src="../js/core/dandelion.customizer.js"></script>

<!--growl-->
<script type="text/javascript" src="../plugins/jgrowl/jquery.jgrowl.js"></script>
<link rel="stylesheet" type="text/css" href="../plugins/jgrowl/jquery.jgrowl.css" media="screen" />


<!-- Custom script for all page -->
<script type="text/javascript" src="../all-pages-script.js"></script>

<!-- Custom script for this page -->
<script type="text/javascript" src="dashboard-script.js"></script>
<style>
h1, h2{
	padding-left:10px;
}
</style>

<title><?=ucfirst($_SERVER['SERVER_NAME'])?> Admin - Dashboard</title>

</head>

<body>
	<!-- Main Wrapper. Set this to 'fixed' for fixed layout and 'fluid' for fluid layout' -->
	<div id="da-wrapper" class="fluid">
    
        <!-- Header -->
        <div id="da-header">
        <!--header top-->
        <?php include('../inc/header_top.php');?>
        <!--end header top-->
         <!--noti end--> 
            <div id="da-header-bottom">
                <!-- Container -->
                <div class="da-container clearfix">
                <!--search box-->
           			<?php 
					include('../inc/search_top.php');
					?>
                	<!--end search box-->
                     <?php 
					$breadcrumbs = array(
						0=>array('name'=>'หน้าหลัก','alt'=>'Homt','link'=>'../dashboard/dashboard.php','class'=>'active'),
					);
					include('../inc/breadcrumbs.php');
					?>
                </div>
            </div>
        </div>
    
        <!-- Content -->
        <div id="da-content">
            <!-- Container -->
            <div class="da-container clearfix">
	            <!-- Sidebar Separator do not remove -->
                <div id="da-sidebar-separator"></div>
                <!-- Sidebar -->
                <?php
                	include('../inc/side_bar.php');
				?>
                <!-- Main Content Wrapper -->
                <div id="da-content-wrap" class="clearfix">
                
                	<!-- Content Area -->
                	<div id="da-content-area">
                    	<div class="grid_4" >
                            <ul class="da-circular-stat-wrap" id="showCircularStat">
                                <li class="da-circular-stat {fillColor: '#a6d037', value: 10, maxValue: 100, label: 'Today'}"></li>
                                <li class="da-circular-stat {fillColor: '#ea799b', value: 20, maxValue: 100, label: 'This Week'}"></li>
                                <li class="da-circular-stat {fillColor: '#fab241', value: 50, maxValue: 100, label: 'This Month'}"></li>
                                <li class="da-circular-stat {fillColor: '#61a5e4', value: 90, maxValue: 100, label: 'Total'}"></li>
                            </ul>
                        	<div class="da-panel-widget">
                                <h1  style=" padding-left:10px; padding-top:10px;"><?=T_("จำนวนผู้เข้าชมย้อยหลัง")?></h1>
                                <div id="da-ex-gchart-line" style="height:225px;"></div>
                            </div>
                        </div>
                        
                        <!-- <div class="grid_1" >
                        	<div class="da-panel-widget">
                                <h1 style=" padding-left:10px; padding-top:10px;"><?=T_("สรุป")?></h1>
                                <ul class="da-summary-stat">
                                	<li id="pages_sumary">
                                    	<a href="#">
                                            <span class="da-summary-icon" style="background-color:#e15656;">
                                                <img src="../images/icons/white/32/computer_imac.png" alt="" />
                                            </span>
                                            <span class="da-summary-text">
                                                <span class="value " id="show_pages_count">10</span>
                                                <span class="label">Pages</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li id="blogs_sumary">
                                    	<a href="#">
                                            <span class="da-summary-icon" style="background-color:#a6d037;">
                                                <img src="../images/icons/white/32/single_document.png" alt="" />
                                            </span>
                                            <span class="da-summary-text">
                                                <span class="value" id="show_blogs_count">10</span>
                                                <span class="label">Blogs</span>
                                            </span>
                                        </a>
                                    </li>
                                      <li id="news_sumary">
                                    	<a href="#">
                                            <span class="da-summary-icon" style="background-color:#a6d037;">
                                                <img src="../images/icons/white/32/digg_3.png" alt="" />
                                            </span>
                                            <span class="da-summary-text">
                                                <span class="value" id="show_news_count">10</span>
                                                <span class="label">News</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li id="products_sumary">
                                    	<a href="#">
                                            <span class="da-summary-icon" style="background-color:#ea799b;">
                                                <img src="../images/icons/white/32/scan_label.png" alt="" />
                                            </span>
                                            <span class="da-summary-text">
                                                <span class="value" id="show_products_mainproduct_count">100</span>                                        
                                                <span class="label">Products</span>
	                                        </span>
                                        </a>
                                    </li>
                                    <li id="contacts_sumary">
                                        <a href="#">
                                            <span class="da-summary-icon" style="background-color:#fab241;">
                                                <img src="../images/icons/white/32/mail.png" alt="" />
                                            </span>
                                            <span class="da-summary-text">
                                                <span class="value " id="show_contacts_count">50</span>
                                                <span class="label">Contacts</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li id="user_sumary">
                                    	<a href="#">
                                            <span class="da-summary-icon" style="background-color:#656565;">
                                                <img src="../images/icons/white/32/single_user.png" alt="" />
                                            </span>
                                            <span class="da-summary-text">
                                                <span class="value" id="show_users_count">20</span>
                                                <span class="label">User</span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div> -->
                        
                        <div class="clear"></div>
                         
                    	<div class="grid_2" >
                        	<div class="da-panel">
                            	<div class="da-panel-header">
                                	<span class="da-panel-title">
                                        <img src="../images/icons/color/comment.png" alt="" />
                                       <?=T_("คู่มือการใช้งาน")?>
                                    </span>
                                    
                                </div>
                                <div class="da-panel-content with-padding">
                              		<div style="padding:20px;">
                                    <ul>
                                        <li><a href="../../manual/1.pdf" target="_blank">1. ปุ่มจัดการเบื้องต้น</a></li>
                                        <li><a href="../../manual/2.pdf" target="_blank">2. แก้ไขเนื้อหาหน้าแรก</a></li>
                                        <li><a href="../../manual/3.pdf" target="_blank">3. จัดการข่าวสาร</a></li>
                                        <li><a href="../../manual/4.pdf" target="_blank">4. จัดการ Company Profile</a></li>
                                        <li><a href="../../manual/5.pdf" target="_blank">5. จัดการเนื้อหา Phetcharat Care</a></li>
                                        <li><a href="../../manual/6.pdf" target="_blank">6. ดูรายละเอียดการติดต่อ</a></li>
                                        <li><a href="../../manual/7.pdf" target="_blank">7. แก้ไขโครงการเดิม</a></li>
                                        <li><a href="../../manual/8.pdf" target="_blank">8. เพิ่มโครงการใหม่</a></li>
                                        <li><a href="../../manual/9.pdf" target="_blank">9. จัดการไฟล์</a></li>
                                        
                                        <!-- <li><a href="../../manual/10.pdf" target="_blank">สมาชิก</a></li> -->
                                    </ul> 
                                    </div>
                                </div>
                            </div>
                        </div>
                         	<div class="grid_2">
                        	<div class="da-panel">
                            	<div class="da-panel-header">
                                	<span class="da-panel-title">
                                        <img src="../images/icons/color/direction.png" alt="" />
                                        แนะนำหรือแจ้งปัญหาการใช้งาน
                                    </span>
                                    
                                </div>
                                <div class="da-panel-widget">
                                	<div>
                                    <form class="da-form" id="client_msg" name="client_msg" onsubmit="sentMailSupport() ; return false;">
                                      <div class="da-form-row">
                                                <label>เลือกประเภท</label>
                                                <div class="da-form-item large">
                                                <select name="client_msg_type" id="client_msg_type">
                                                  <option value="0">แจ้งปัญหา</option>
                                                  <option value="1">แนะนำ</option>
                                                </select>
                                                </div>
                                        </div>
                                    	<div class="da-form-inline">
                                            <div class="da-form-row">
                                                <label>เรื่อง</label>
                                                <div class="da-form-item large">
                                                	<span class="formNote">ใส่เรื่องที่ต้องการแจ้ง</span>
                                                    <input  type="text" name="client_msg_title" id="client_msg_title" />
                                                </div>
                                            </div>
                                            <div class="da-form-row">
                                                <label>ข้อความ</label>
                                                <div class="da-form-item large">
                                                	<span class="formNote">ใส่ข้อความที่ต้องการส่ง</span>
                                                    <textarea rows="auto" cols="auto" id="client_msg_text" name="client_msg_text"></textarea>
                                                </div>
                                            </div>
                                                <div class="da-button-row large">
                                        	       <input type="submit" value="ส่งข้อความ" class="da-button blue" />
                                                </div>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
        <!-- Footer -->
        <div id="da-footer">
        	<div class="da-container clearfix">
            	   <p>Copyright <?=date('Y')?>. <?=ucfirst($_SERVER['SERVER_NAME'])?> All Rights Reserved.
            </div>
        </div>
        
    </div>
    
</body>
</html>
