<?php @include ("../inc/auth.inc.php") ; ?>
<?php
$modules = 'menus';
$modules_name = 'จัดการเมนู';
$module_active = 'menus' ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- Viewport metatags -->
<meta name="HandheldFriendly" content="true" />
<meta name="MobileOptimized" content="320" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<!-- iOS webapp metatags -->
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />

<!-- iOS webapp icons -->
<link rel="apple-touch-icon" href="../touch-icon-iphone.png" />
<link rel="apple-touch-icon" sizes="72x72" href="../touch-icon-ipad.png" />
<link rel="apple-touch-icon" sizes="114x114" href="../touch-icon-retina.png" />

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

<!-- DataTables Plugin -->
<script type="text/javascript" src="../plugins/datatables/jquery.dataTables.min.js"></script>

<!-- Core JavaScript Files -->
<script type="text/javascript" src="../js/core/dandelion.core.js"></script>

<!-- Customizer JavaScript File (remove if not needed) -->
<script type="text/javascript" src="../js/core/dandelion.customizer.js"></script>

<script type="text/javascript" src="../plugins/jgrowl/jquery.jgrowl.js"></script>
<link rel="stylesheet" type="text/css" href="../plugins/jgrowl/jquery.jgrowl.css" media="screen" />

<!--Text editor -->
<script type="text/javascript" src="../plugins/elrte/js/elrte.min.js"></script>
<link rel="stylesheet" type="text/css" href="../plugins/elrte/css/elrte.css" />
<!-- elFinder Plugin -->
<script type="text/javascript" src="../plugins/elfinder/js/elfinder.min.js"></script>
<link rel="stylesheet" href="../plugins/elfinder/css/elfinder.css" media="screen" />

<!--form validate-->
<script type="text/javascript" src="../plugins/validate/jquery.validate.min.js"></script>

<!-- PLUpload Plugin -->
<!--<script type="text/javascript" src="plugins/plupload/plupload.js"></script>
<script type="text/javascript" src="plugins/plupload/plupload.flash.js"></script>
<script type="text/javascript" src="plugins/plupload/plupload.html4.js"></script>
<script type="text/javascript" src="plugins/plupload/plupload.html5.js"></script>
<script type="text/javascript" src="plugins/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
<link rel="stylesheet" href="plugins/plupload/jquery.plupload.queue.css" />-->

<!-- Demo JavaScript Files -->
<!--<script type="text/javascript" src="js/demo/demo.files.js"></script>-->
<?php include('../all-pages-include-script.php'); ?>
<!-- Custom script for all page -->
<script type="text/javascript" src="../all-pages-script.js"></script>

<!-- Custom script for this page -->
<script type="text/javascript" src="form-script.js"></script>

<!-- Nestable JS -->
<script type="text/javascript" src="nestable.min.js"></script>
<link rel="stylesheet" type="text/css" href="nestable.css" media="screen" />

<!-- Fontawesome CSS -->
<link rel="stylesheet" type="text/css" href="../css/font-awesome-4.7.0/css/font-awesome.min.css" media="screen" />

<title><?=ucfirst($_SERVER['SERVER_NAME'])?> Admin - Dashboard</title>

<style>
/* The Modal (background) */
.modals {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 99999; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    position: relative;
    background-color: #fefefe;
    margin: auto;
    padding: 0;
    border: 1px solid #888;
    width: 50%;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    -webkit-animation-name: animatetop;
    -webkit-animation-duration: 0.4s;
    animation-name: animatetop;
    animation-duration: 0.4s
}

/* Add Animation */
@-webkit-keyframes animatetop {
    from {top:-300px; opacity:0} 
    to {top:0; opacity:1}
}

@keyframes animatetop {
    from {top:-300px; opacity:0}
    to {top:0; opacity:1}
}

/* The Close Button */
.close {
    color: white;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

.modal-header {
    padding: 2px 16px;
    background-color: white;
    color: black;
}

/*.modal-body {padding: 2px 16px;}*/

.modal-footer {
    padding: 2px 16px;
    background-color: #5cb85c;
    color: white;
}

.nestlist-toolbar{
  font-size: 16px;
  height: 40px;
  display:block;
  float: right;
  margin-top: -32px;
  margin-right: 10px;
}
</style>

</head>

<body class="loading">
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
                	<!-- Breadcrumbs -->
                    <?php 
						$breadcrumbs = array(
						0=>array('name'=>'หน้าหลัก','alt'=>'Home','link'=>'../dashboard/dashboard.php','class'=>false),
						1=>array('name'=>'หมวดหมู่','alt'=>'Categories','link'=>'categories.php','class'=>false),
						2=>array('name'=>$modules_name,'alt'=>$modules_name,'link'=>'index.php','class'=>false),
						3=>array('name'=>'เพิ่ม / แก้ไข','alt'=>'Add / Edit','link'=>'javascript:void(0)','class'=>'active')
					);
					include('../inc/breadcrumbs.php');
					?>
                    <!-- end Breadcrumbs -->
                    
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
                    <div style="display:block; clear:both; margin-left:10px; margin-right:10px; display:none;">
                    <!--start flash messaages-->
                    <div class="da-message error">
                                        This is an error message
                     </div>
                     <div class="da-message success">
                                        This is a success message
                     </div>
                    <div class="da-message info">
                                        This is an info message
                    </div>
                    </div>
                    <!--end flash messaages-->
                    <!--start form-->
                    <form class="da-form" name="form" id="form" enctype="multipart/form-data" onsubmit="setSaveData();return false;">
                    <input name="id"  id="id" type="hidden" value="" />
                    <input name="hprimaryid"  id="hprimaryid" type="hidden" value="" />
                      	<div class="grid_4">
                        	<div class="da-panel">
                            	<div class="da-panel-header">
                                	<span class="da-panel-title">
                                        <img src="../images/icons/black/16/pencil.png" alt="" />
                                        จัดการเมนู
                                    </span>
                                </div>
                                <div class="da-panel-content">
                                    <div id="form-error" class="da-message error" style="display:none;"></div>
                                    	<div class="da-form-inline">
                                            <div class="da-form-row">
                                                <label>ชื่อกลุ่มเมนู <!-- <span class="required">*</span> --></label>
                                                <div class="da-form-item large">
                                                    <!-- <label id="categories_name"></label> -->
                                                	<span class="formNote" >ใส่ชื่อกลุ่มเมนู</span>
                                                    <input type="text" name="categories_name" id="categories_name" value="" />
                                                </div>
                                            </div>
                                            <div class="da-form-row">
                                                <label>รายการเมนู</label>
                                                <div class="da-form-item large">
                                                    <span class="formNote" >เมนูภายในกลุ่มเมนู</span>
                                                    <div class="da-panel">
                                                        <div class="da-panel-header">
                                                            <span class="da-panel-title">
                                                                <img src="../images/icons/black/16/documents_small.png" />
                                                                รายการเมนู (การเปลี่ยนแปลงเมนูจะถูกบันทึกอัตโนมัติ)
                                                            </span>
                                                        </div>
                                                        <div class="da-panel-content" id="widgetInsertMenu">

                                                            <div class="dd" id="nestable_list" style="padding:10px;">
                                                            </div>

                                                            <div class="footer-add-menu" style="float: left;clear: both; margin:10px auto; padding:10px;">
                                                                <button id="addMenus" type="button"><i class="fa fa-plus" aria-hidden="true" ></i>&nbsp;<span style="color:green">เพิ่มเมนูใหม่</span></button>
                                                            </div>

                                                            <input type="hidden" value="" id="savevaljson" name="savevaljson"/>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                   			<div class="da-button-row">
                                            	<input type="reset" value="ยกเลิก" class="da-button gray left">
                                            	<input type="submit" value="บันทึก" class="da-button green">
                                            </div>
                                        </div>
                                    <!--old form end-->
                                </div>
                            </div>
                        </div> <!--grid 4-->
                    
                        </form>
                <!--enf form-->        
                
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
    <div class="modal" onclick="reloadPageNow()"></div>​

    <!-- Modal -->
    <!-- The Modal -->
    <div id="addMenuModal" class="modals">

      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-body">
            <form class="da-form" name="menuAddForm" id="menuAddForm" enctype="multipart/form-data" onsubmit="SaveAddMenuData();return false;">
                <input name="menu_id"  id="menu_id" type="hidden" value="" />
                <input name="cate_id"  id="cate_id" type="hidden" value="" />
                <input name="hID"  id="hID" type="hidden" value="" />
                <div class="da-panel">
                    <div class="da-panel-header">
                        <span class="da-panel-title">
                            <img src="../images/icons/black/16/pencil.png" alt="" />
                            เพิ่ม/แก้ไขเมนู
                        </span>
                    </div>
                    <div class="da-panel-content">
                        <div class="da-form-inline">
                            <div class="da-form-row">
                                <label>ชื่อเมนู <span class="required">*</span></label>
                                <div class="da-form-item large">
                                    <span class="formNote" >ใส่ชื่อเมนู</span>
                                    <input type="text" name="menuAddName" id="menuAddName" value="" />
                                </div>
                            </div> 
                            <div class="da-form-row">
                                <label>รูปแบบลิงค์ <span class="required">*</span></label>
                                <div class="da-form-item small">
                                    <span class="formNote" >รูปแบบการเปิดหน้าต่างลิงค์</span>
                                    <select id="menuAddTarget" name="menuAddTarget">
                                        <option value="2">ค่าเริ่มต้น</option>
                                        <option value="1">เปิดหน้าต่างใหม่</option>
                                    </select>
                                </div>
                            </div> 
                            <div class="da-form-row">
                                <label>ประเภทลิงค์ <span class="required">*</span></label>
                                <div class="da-form-item small">
                                    <span class="formNote" >ประเภทของลิงค์</span>
                                    <select id="menuAddType" name="menuAddType" onchange="setChangeMenuType(this.value);">
                                        <option value="0">ลิงค์กับระบบเว็บเพจ</option>
                                        <option value="1">ลิงก์ภายนอกระบบ</option>
                                    </select>
                                </div>
                            </div> 
                            <div class="da-form-row" id="wpMenuList">
                                <label>เลือกเว็บเพจ <span class="required">*</span></label>
                                <div class="da-form-item small">
                                    <span class="formNote" >เลือกเว็บเพจที่ต้องการลิงค์</span>
                                    <select id="menuPagesList" name="menuPagesList"></select>
                                </div>
                            </div> 
                            <div class="da-form-row" id="exMenuLink" style="display:none;">
                                <label>ลิงค์ภายนอก <span class="required">*</span></label>
                                <div class="da-form-item large">
                                    <span class="formNote" >ลิงค์ภายนอกที่ต้องการลิงค์</span>
                                    <input type="text" name="menuExLink" id="menuExLink" value="" />
                                </div>
                            </div> 
                            <div class="da-form-row">
                                <label>การใข้งาน <span class="required">*</span></label>
                                <div class="da-form-item small">
                                    <span class="formNote" >สถานะการใช้งาน</span>
                                    <select id="menuAddStatus" name="menuAddStatus">
                                        <option value="1">เปิดใช้งาน</option>
                                        <option value="0">ปิดการใข้งาน</option>
                                    </select>
                                </div>
                            </div> 
                            <div class="da-form-row">
                                <label></label>
                                <div class="da-form-item small">
                                    <input type="reset" value="ยกเลิก" class="da-button gray" style="width:20%;" id="menuResetBtn">
                                    <input type="submit" value="บันทึก" class="da-button green left" style="width:20%; margin-right:10px;">
                                </div>
                            </div> 

                        </div>                   
                    </div>
                </div>
            </form>
        </div>
      </div>

    </div>

     <div id="translateMenuModal" class="modals">

      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-body">
            <form class="da-form" name="menuTranslateForm" id="menuTranslateForm" enctype="multipart/form-data" onsubmit="SaveTranslateMenuData();return false;">
                <input name="translate_menu_id"  id="translate_menu_id" type="hidden" value="" />
                <div class="da-panel">
                    <div class="da-panel-header">
                        <span class="da-panel-title">
                            <img src="../images/icons/black/16/pencil.png" alt="" />
                            เพิ่มภาษาเมนู
                        </span>
                    </div>
                    <div class="da-panel-content">
                        <div class="da-form-inline">
                            <div class="da-form-row">
                                <label>ชื่อเมนูภาษาหลัก</label>
                                <div class="da-form-item large">
                                    <span class="formNote" >ชื่อเมนูในภาษาหลัก</span>
                                    <span id="menuMainTitle" style="top: 5px; position: relative;"></span>
                                </div>
                            </div> 
                            <div class="da-form-row">
                                <label>เลือกภาษา <span class="required">*</span></label>
                                <div class="da-form-item small">
                                    <span class="formNote" >เลือกภาษาที่ต้องการแปล</span>
                                    <select id="menuTranslateLang" name="menuTranslateLang" onchange="setChangeLangTranslate(this.value);">
                                    </select>
                                </div>
                            </div> 
                            <div class="da-form-row">
                                <label>ชื่อเมนู <span class="required">*</span></label>
                                <div class="da-form-item large">
                                    <span class="formNote" >ชื่อเมนูในภาษาที่เลือก</span>
                                    <input type="text" name="menuTranslateTitle" id="menuTranslateTitle" value="" />
                                </div>
                            </div> 
                            <div class="da-form-row">
                                <label></label>
                                <div class="da-form-item small">
                                    <input type="reset" value="ยกเลิก" class="da-button gray" style="width:20%;" id="menuTranslateResetBtn">
                                    <input type="submit" value="บันทึก" class="da-button green left" style="width:20%; margin-right:10px;">
                                </div>
                            </div> 

                        </div>                   
                    </div>
                </div>
            </form>
        </div>
      </div>

    </div>

</body>
</html>
