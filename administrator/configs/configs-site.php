<?php @include ("../inc/auth.inc.php"); ?>
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
<!-- Chosen Plugin -->
<script type="text/javascript" src="../plugins/chosen/chosen.jquery.js"></script>
<link rel="stylesheet" href="../plugins/chosen/chosen.css" media="screen" />
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
<!--form validate-->
<script type="text/javascript" src="../plugins/validate/jquery.validate.min.js"></script>
<!-- all-pages-include-script -->
<?php include('../all-pages-include-script.php'); ?>
<!-- Custom script for all page -->
<script type="text/javascript" src="../all-pages-script.js"></script>
<!-- Custom script for this page -->
<script type="text/javascript" src="configs-site-script.js"></script>
<title><?=ucfirst($_SERVER['SERVER_NAME'])?> Admin - Dashboard</title>
</head>
<body>
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
						1=>array('name'=>'ตั่งค่าเว็บไซต์','alt'=>'Configs','link'=>'javascript:void(0)','class'=>'active')
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
				  	$module_active = 'configs' ;
                	include('../inc/side_bar.php');
				?>
                <!-- Main Content Wrapper -->
                <div id="da-content-wrap" class="clearfix">
                
                	<!-- Content Area -->
                	<div id="da-content-area">
                        <div class='modal' onclick='reloadPageNow()'></div>
                        <form class="da-form" name="configs_form" id="configs_form" enctype="multipart/form-data" onsubmit="return false;">
                        <input name="configs_id"  id="configs_id" type="hidden" />
                    	<div class="grid_4">
                        	<div class="da-panel collapsible">
                            	<div class="da-panel-header">
                                	<span class="da-panel-title">
                                        <img src="../images/icons/color/blog.png" alt="" />
                                        ตั้งค่าเว็บไซต์
                                    </span>
                                    
                                </div>
                                <div class="da-panel-toolbar top">
                                    <ul style="float:right;">
                                        <li><a href="javascript:void(0);" onclick="setConfigsEdit()" id="configs_show_edit"><img src="../images/icons/color/pencil.png" alt="" />แก้ไข</a></li>
                                        <li><a  href="javascript:void(0);" onclick="setSaveConfigs()" id="configs_show_save" style="display:none;"><img src="../images/icons/color/disk.png" alt="" />บันทึก</a></li>
                                    </ul>
                                </div>      
                                <div class="da-panel-content">
                                    <table class="da-table da-detail-view">
                                        <tbody>
                                        	<tr>
                                            	<th>เปิด / ปิด เว็บไซต์</th>
                                                <td>
                                                    <label style="float:left ; margin-left:10px; margin-right:10px; ">
                                                      <input name="configs_open" type="radio" disabled="disabled" id="configs_open_0" value="1"  />
                                                      เปิด</label>
                                                    <label style="float:left ; margin-left:10px; margin-right:10px; ">
                                                      <input type="radio" name="configs_open" value="0" id="configs_open_1"  disabled="disabled"/>
                                                      ปิด</label></td>
                                            </tr>
                                            <tr>
                                            	<th>ชื่อเว็บไซต์</th>
                                                <td><input name="configs_sitename"  id="configs_sitename" placeholder="ชื่อเว็บไซต์" type="text" disabled="disabled"/></td>
                                            </tr>
	                                    	<tr>
                                            <th>Favicon</th>
                                                <td><input name="configs_favicon_url"  id="configs_favicon_url" placeholder="ใส่ Favicon URL" type="text" disabled="disabled"/></td>
                                            </tr>    
                                             <tr>
                                            <th>Meta Keywords</th>
                                                <td><input name="configs_meta_keywords"  id="configs_meta_keywords" placeholder="ใส่ Meta Keywords" type="text" disabled="disabled"/></td>
                                            </tr>
                                            <tr>
                                            <th>Meta Description</th>
                                                <td><input name="configs_meta_description"  id="configs_meta_description" placeholder="ใส่ Meta Description" type="text" disabled="disabled" /></td>
                                            </tr> 
                                             <tr>
                                            <th>ข้อความเมื่อปิดเว็บไซต์</th>
                                                <td><input name="configs_underconstruction_text"  id="configs_underconstruction_text" placeholder="ใส่ ข้อความเมื่อปิดเว็บไซต์" type="text" disabled="disabled"/></td>
                                            </tr>    
                                             <tr>
                                            <th>URL ภาพพื้นหลัง</th>
                                                <td><input name="configs_background_url"  id="configs_background_url" placeholder="ใส่ URL ภาพพื้นหลัง" type="text" disabled="disabled" /></td>
                                            </tr>    
                                              <tr>
                                            <th>ภาษาที่ใช้</th>
                                                <td><select class="chzn-select"  multiple="multiple" name="configs_languages[]" id="configs_languages" size="10" >
                                                	
                                                </select></td>
                                            </tr>    
                                           </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        </form>                 
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
