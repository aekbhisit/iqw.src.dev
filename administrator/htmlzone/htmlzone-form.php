<?php @include ("../inc/auth.inc.php"); ?>
<?php
$modules = 'htmlzone';
$modules_name = 'htmlzone';
$module_active = 'htmlzone';
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
<!-- all-pages-include-script -->
<?php include('../all-pages-include-script.php'); ?>
<!-- Custom script for all page -->
<script type="text/javascript" src="../all-pages-script.js"></script>
<!-- Custom script for this page -->
<script type="text/javascript" src="htmlzone-form-script.js"></script>
<title><?=ucfirst($_SERVER['SERVER_NAME'])?> Admin - Dashboard</title>
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
                        <form class="da-form" name="form" id="form" enctype="multipart/form-data" onsubmit="setSaveData(); return false;">
                            <input name="id" id="id" type="hidden" value="" />
                            <!--start main content form-->
                          	<div class="grid_12">
                            	<div class="da-panel">
                                	<div class="da-panel-header">
                                    	<span class="da-panel-title" style="margin-right:0px;">
                                            <img src="../images/icons/black/16/pencil.png" alt="" />
                                            Create input 
                                            <!-- <input onclick="setAddList();" type="button" value="+ Add Input" class="da-button blue" style="float:right;" /> -->
                                        </span>
                                    </div>
                                    <div class="da-panel-content da-form-container">
                                        <div id="form-error" class="da-message error" style="display:none;"></div>
                                        <div class="da-form-row">
                                            <label>หมวดหมู่หลัก<span class="required">*</span></label>
                                            <div class="da-form-item large">
                                                <span class="formNote">เลือกหมวดหมู่หลัก (ถ้ามี)</span>
                                                <select id="zone_id" name="zone_id" disabled="disabled"></select>
                                            </div>
                                        </div>
                                        <div class="da-form-row">
                                            <label>Lable <span class="required">*</span></label>
                                            <div class="da-form-item large">
                                                <span class="formNote">Lable</span>
                                                <input type="text" name="name" id="name" value="" />
                                            </div>
                                        </div>
                                        <div class="da-form-row">
                                            <label>Description <span class="required">*</span></label>
                                            <div class="da-form-item large">
                                                <span class="formNote">Description</span>
                                                <input type="text" name="description" id="description" value="" />
                                            </div>
                                        </div>
                                        <div class="da-form-row">
                                            <label>ประเภท input<span class="required">*</span></label>
                                            <div class="da-form-item large">
                                                <span class="formNote">เลือกประเภท input</span>
                                                <select id="type" name="type">
                                                    <option value="1">Text</option>
                                                    <option value="2">Textarea</option>
                                                    <option value="3">Image</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="da-form-row">
                                            <label>เปิดใช้งาน <span class="required">*</span></label>
                                            <div class="da-form-item small">
                                                <span class="formNote">เลือกสถานการเปิดปิดบล็อก</span>
                                                <select id="status" name="status" >
                                                    <option value="1">เปิดใช้งาน</option>
                                                    <option value="0">ปิดใช้งาน</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="da-form-inline">
                                       		<div class="da-button-row">
                                            	<input type="reset" value="ยกเลิก" class="da-button gray left">
                                            	<input type="submit" value="บันทึก" class="da-button green">
                                            </div>
                                        </div>
                                        <!--old form end-->
                                    </div>
                                </div>
                            </div> 
                            <!--grid 4-->
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
</body>
</html>