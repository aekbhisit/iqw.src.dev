<?php @include ("../inc/auth.inc.php") ; ?>
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

<!-- FileInput Plugin -->
<script type="text/javascript" src="../js/jquery.fileinput.js"></script>

<!-- Plugin Files -->
<!-- elFinder Plugin -->
<script type="text/javascript" src="../plugins/elfinder/js/elfinder.min.js"></script>
<link rel="stylesheet" href="../plugins/elfinder/css/elfinder.css" media="screen" />

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

<!-- PLUpload Plugin -->
<script type="text/javascript" src="../plugins/plupload/plupload.js"></script>
<script type="text/javascript" src="../plugins/plupload/plupload.flash.js"></script>
<script type="text/javascript" src="../plugins/plupload/plupload.html4.js"></script>
<script type="text/javascript" src="../plugins/plupload/plupload.html5.js"></script>
<script type="text/javascript" src="../plugins/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>
<link rel="stylesheet" href="../plugins/plupload/jquery.plupload.queue.css" />

<!-- Custom script for all page -->
<script type="text/javascript" src="../all-pages-script.js"  charset="utf-8"></script>
<script type="text/javascript" >
(function($) {
	$(document).ready(function(e) {	
		$("#da-ex-plupload").pluploadQueue({
			// General settings
			runtimes : 'flash,html5,html4', 
			url : null, 
			max_file_size : '100mb',
			max_file_count: 20, // user can add no more then 20 files at a time
			chunk_size : '1mb',
			url : '../../files/upload_module.php',
    		flash_swf_url : '../plugins/plupload/plupload.flash.swf',
			unique_names : false,
			multiple_queues : true
		});
		
  var input = $('#modules') ;
    $(input).bind('click',function () {
		if($(document).has('#finder').length<=0){
			$('#modules').after('<div id="finder"></div>');
		}
		 $('#finder').elfinder({
         	url : '../../files/php/connector_module.php',
        	closeOnEditorCallback: false,
        	getFileCallback: function(url) {
				 $(input).val(url) ;
     	   }
		});
    });	
	});
}) (jQuery);
</script>
<title>iQuickweb Admin - Dashboard</title>

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
                	    <?php 
					$breadcrumbs = array(
						0=>array('name'=>'หน้าหลัก','alt'=>'Homt','link'=>'../dashboard/dashboard.php','class'=>''),
						1=>array('name'=>'จัดการไฟล์ Install','alt'=>'Blogs','link'=>'javascript:void(0)','class'=>'active')
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
				  	$module_active = 'files' ;
                	include('../inc/side_bar.php');
				?>
                <!-- Main Content Wrapper -->
                <div id="da-content-wrap" class="clearfix">
                	<!-- Content Area -->
                	<div id="da-content-area">
                             <!--start main seo form-->
                          <div class="grid_4">
                            <div class="da-panel collapsible collapsed">
                                <div class="da-panel-header">
                                    <span class="da-panel-title">
                                        <img src="../images/icons/black/16/upload.png" />
                                        อัปโหลดไฟล์ .zip ขนาดใหญ่สูงสุดไม่เกิน 100MB  สำหรับติดตั้ง
                                    </span>
                                </div>
                                <div class="da-panel-content">
                                 		<div class="da-form-row">
                                                	<div id="da-ex-plupload"></div>
                                      </div>
                                </div>
                            </div>
                                  <form class="da-form" name="form" id="form" enctype="multipart/form-data" onsubmit="setSaveData();return false;">
                            <div class="da-panel collapsible">
                                <div class="da-panel-header">
                                    <span class="da-panel-title">
                                        <img src="../images/icons/black/16/upload.png" />
                                        เลือกไฟล์โมดูล
                                    </span>
                                </div>
                                <div class="da-panel-content">
                                        <div class="da-form-row">
                                                <label>โมดูล</label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >เลือกไฟล์ โมดูล .zip (ถ้ามี)</span>
                                                     <input type="text" name="modules" id="modules" value="" class="elfinder-browse" />
                                                     <img src="" id="show_image" style="display:none; max-width:150px; max-height:150px; padding:10px; margin-top:20px; border:#CCC 1px solid; border-radius: 5px;" />
                                                </div>
                                         </div>
                                </div>
                            </div>
                            </form>
                        </div>
                      <!--start main content form-->
                            <!--end upload file -->
                            
                        </div>
                                       
                    </div>
                </div>
            </div> 
        </div>
        
        <!-- Footer -->
        <div id="da-footer">
        	<div class="da-container clearfix">
            	<p>Copyright 2012. iQuickweb All Rights Reserved.
            </div>
        </div>
        
    </div>
   
</body>
</html>
