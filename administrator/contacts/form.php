<?php @include ("../inc/auth.inc.php"); ?>
<?php
$modules = 'contacts';
$modules_name = 'contacts';
$module_active = 'contacts';
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
<!-- Custom script for all page -->
<script type="text/javascript" src="../all-pages-script.js"></script>
<!-- Custom script for this news -->
<script type="text/javascript" src="form-script.js"></script>
<title><?=ucfirst($_SERVER['SERVER_NAME'])?> Admin - Dashboard</title>
</head>
<body class="loading">
    <!-- Main Wrapper. Set this to 'fixed' for fixed layout and 'fluid' for fluid layout' -->
    <div id="da-wrapper" class="fluid">
        <!-- Header -->
        <div id="da-header">
            <?php include('../inc/header_top.php');?>
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
                        0=>array('name'=>'หน้าหลัก','alt'=>'Homt','link'=>'../dashboard/dashboard.php','class'=>''),
                        1=>array('name'=>'หมวดหมู่ที่ติดต่อ','alt'=>'Categories','link'=>'categories.php','class'=>''),
                        2=>array('name'=>'ข้อมูลการติดต่อ','alt'=>'Contacts','link'=>'index.php','class'=>''),
                        3=>array('name'=>'เพิ่มแก้ไข','alt'=>'Add / Edit','link'=>'javascript:void(0)','class'=>'active')
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
                    <form class="da-form" name="contacts_form" id="contacts_form" enctype="multipart/form-data" onsubmit="setSaveContacts(); return false;">
                        <input name="from_email" id="from_email" type="hidden" value="" />   
                        <input name="replyto_email" id="replyto_email" type="hidden" value="" />
                        <input name="contacts_id" id="contacts_id" type="hidden" value="" />
                        <div class="grid_2">
                            <div class="da-panel">
                                <div class="da-panel-header">
                                    <span class="da-panel-title">
                                        <img src="../images/icons/black/16/pencil.png" alt="" />
                                        ข้อความที่ได้รับ
                                    </span>
                                </div>
                                <div class="da-panel-content">
                                <div id="form-error" class="da-message error" style="display:none;"></div>
                                    <div class="da-form-inline">
                                        <div class="da-form-row">
                                            <label>จาก</label>
                                            <div class="da-form-item large" id="contacts_from">
                                            จาก
                                            </div>
                                        </div>
                                        <div class="da-form-row">
                                            <label>เรื่อง</label>
                                            <div class="da-form-item large" id="contacts_subject">
                                            เรื่อง
                                            </div>
                                        </div>
                                        <div class="da-form-row">
                                            <label>ข้อความ</label>
                                            <div class="da-form-item large">
                                                <div id="contacts_messages" name="contacts_messages" style="overflow-x: hidden; overflow-y: hidden; height:250px; border:none; box-shadow:none; " readonly="readonly">ข้อความ</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--old form end-->
                                </div>
                            </div>
                        </div> 
                        <!--grid 2-->
                        <div class="grid_2">
                            <div class="da-panel">
                                <div class="da-panel-header">
                                    <span class="da-panel-title">
                                        <img src="../images/icons/black/16/pencil.png" alt="" />
                                        ตอบกลับ
                                    </span>
                                </div>
                                <div class="da-panel-content">
                                    <div id="form-error" class="da-message error" style="display:none;"></div>
                                    <div class="da-form-inline">
                                        <div class="da-form-row">
                                            <label>ตอบกลับ</label>
                                            <div class="da-form-item large">
                                                <input name="reply_subject" id="reply_subject" value="" />
                                            </div>
                                        </div>
                                        <div class="da-form-row">
                                            <label>ข้อความ<span class="required">*</span></label>
                                            <div class="da-form-item large">
                                               <textarea id="reply_messages" name="reply_messages" style="overflow-x: hidden; overflow-y: hidden; height:250px; "></textarea>
                                            </div>
                                            <div id="myelfinder"></div>
                                        </div>
                                        <div class="da-button-row">
                                            <input type="reset" value="ยกเลิก" class="da-button gray left" />
                                            <input type="submit" value="บันทึก" class="da-button green" />
                                        </div>
                                    </div>
                                    <!--old form end-->
                                </div>
                            </div>
                        </div>
                        <!--grid 2-->
                        <!--enf form-->
                        <div class="grid_4" id="show_replyed">
                            <div class="da-panel collapsible">
                                <div class="da-panel-header">
                                    <span class="da-panel-title">
                                        <img src="../images/icons/black/16/pencil.png" alt="" />
                                        ข้อความที่เคยตอบกลับ
                                    </span>
                                </div>
                                <div class="da-panel-content">
                                    <div id="form-error" class="da-message error" style="display:none;"></div>
                                    <div class="da-form-inline">
                                        <div class="da-form-row">
                                            <label>เรื่อง</label>
                                            <div class="da-form-item large" id="replied_subject">
                                            เรื่อง
                                            </div>
                                        </div>
                                        <div class="da-form-row">
                                            <label>ข้อความ</label>
                                            <div class="da-form-item large">
                                                <span class="formNote"></span>
                                                <textarea id="replied_messages" name="replied_messages" style="overflow-x: hidden; overflow-y: hidden; height:250px; border:none; box-shadow:none; " readonly="readonly">ข้อความ</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!--old form end-->
                                </div>
                            </div>
                        </div> 
                        <!--grid 4-->
                    </form>
                    <!-- </div> -->
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
    <div class="modal" onclick="reloadPageNow();"></div>​
</body>
</html>