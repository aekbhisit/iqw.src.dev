<?php @include ("../inc/auth.inc.php"); ?>
<?php
$modules = 'clips';
$modules_name = 'สินค้า';
$module_active = 'clips';
?>
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
<!-- all-pages-include-script -->
<?php include('../all-pages-include-script.php'); ?>
<!-- Custom script for all page -->
<script type="text/javascript" src="../all-pages-script.js"></script>
<!-- Custom script for this page -->
<script type="text/javascript" src="categories-script.js"></script>
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
                 <!-- Breadcrumbs -->
                    <?php 
					$breadcrumbs = array(
						0=>array('name'=>'หน้าหลัก','alt'=>'Home','link'=>'../dashboard/dashboard.php','class'=>''),
						1=>array('name'=>'หมวดหมู่','alt'=>'Categories','link'=>'javascript:void(0)','class'=>'active'),
						2=>array('name'=>$modules_name,'alt'=>$modules_name,'link'=>'index.php','class'=>'')
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
                    <div style="display:block; width:100%; min-height:35px; height:auto; clear:both; padding-left:10px; padding-right:10px;"><a href="categories-form.php?mode=add"><button class="da-button green medium">เพิ่มหมวดหมู่ใหม่</button></a></div>
                      
                    	<div class="grid_4">
                        	<div class="da-panel collapsible">
                            	<div class="da-panel-header">
                                	<span class="da-panel-title">
                                        <img src="../images/icons/black/16/list.png" alt="" />
                                       	 จัดการหมวดหมู่
                                    </span>
                                    
                                </div>
                                <div class="da-panel-content">
                                 <table id="da-ex-datatable-numberpaging" class="da-table">
                                        <thead>
                                            <tr>
                                                <th width="40">ลำดับ</th>
                                                <th>หมวดหมู่</th>
                                                <th width="50">ระดับชั้น</th>
                                                <th width="75">วันที่</th>
                                                <th width="38">ลำดับ</th>
                                                <th width="75">แก้ไข</th> 
                                                <th width="38">#ID</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="da-icon-column">             
                                               		 <a href="#"><img src="../images/icons/color/magnifier.png" /></a>
                                                	<a href="#"><img src="../images/icons/color/pencil.png" /></a>
                                                	<a href="#"><img src="../images/icons/color/cross.png" /></a>
                                                </td>
                                                  <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!--end grid-->
                        
                         <div style="display:block; width:100%; min-height:35px; height:auto; clear:both; padding-left:10px; padding-right:10px;"><button class="da-button blue medium" onclick="setCheckAll();"><label for="checkboxAll"><input name="checkboxAll"  id="checkboxAll" type="checkbox" value="" />เลือกทั้งหมด</label></button>  <button class="da-button red medium" onclick="setDeleteSelectedData();">ลบรายการที่เลือก</button></div>                       
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
