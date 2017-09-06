<?php @include ("../inc/auth.inc.php") ; ?>
<?php
$modules = 'finds';
$modules_name = 'finds';
$module_active = 'finds' ;
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

<!-- Custom script for all page -->
<script type="text/javascript" src="../all-pages-script.js"></script>

<!-- Custom script for this page -->
<script type="text/javascript" src="form-script.js"></script>


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
                    <form class="da-form" name="form" id="form" enctype="multipart/form-data" onsubmit="setSaveData();return false;">
                    <input name="id"  id="id" type="hidden" value="" />
                     
                      <!--start main content form-->
                      	<div class="grid_4">
                        	<div class="da-panel">
                            	<div class="da-panel-header">
                                	<span class="da-panel-title">
                                        <img src="../images/icons/black/16/pencil.png" alt="" />
                                        เพิ่ม Query Rule
                                    </span>
                                    
                                </div>
                                <div class="da-panel-content">
                                <div id="form-error" class="da-message error" style="display:none;"></div>
                                    	<div class="da-form-inline">
                                         	 <div class="da-form-row">
                                                <label>ระบบ<span class="required">*</span></label>
                                                <div class="da-form-item large">
                                                	<span class="formNote"  >เลือกระบบ (ถ้ามี)</span>
                                                    <select id="moduleList_id" name="moduleList_id" disabled="disabled" ></select>
                                                </div>
                                            </div>
                                            <div class="da-form-row">
                                                <label>ชื่อ <span class="required">*</span></label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >ใส่ชื่อ Rule</span>
                                                    <input type="text" name="name" id="name" value="" />
                                                </div>
                                            </div>
                                             <div class="da-form-row">
                                                <label>Task </label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >ค่า Task ของโมดูล (task ในไฟล์ controller) </span>
                                                      <select name="findTaskType" id="findTaskType" onchange="findTaskTypeFilter(this.value)" >
                                                    	<option value="">--Select Find Type--</option> 
                                                    	<option value="find">Find</option>
                                                         <option value="custom">Custom</option> 
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="da-form-row" id="findTask" style="display:none">
                                                <label>Task </label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >ค่า Task ของโมดูล (task ในไฟล์ controller) </span>
                                                    <input type="text" name="task" id="task" value="" />
                                                </div>
                                            </div>
                                            <div class="da-form-row" id="findTypeContainer" style="display:none">
                                                <label>Find Type </label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >Findind type </span>
                                                    <select name="findType" id="findType" onchange="findTypeFilter(this.value)" >
                                                    	<option value="">--Select Find Type--</option>
                                                    	<option value="one">One</option>
                                                         <option value="in_category">In Category</option> 
                                                        <option value="all">All in Module</option>
                                                        <option value="category_one">List a category</option>
                                                        <option value="category_all">List all categories</option>
                                                    </select>
                                                </div>
                                            </div>
                                              <div class="da-form-row"  id="findParamContainer" style="display:none">
                                                <label>Find Parameter </label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >Findind Parameter </span>
                                                    <select name="findParameter" id="findParameter" onchange="findParmFilter(value)">
                                                    	<option value="">--Select Find Parameter--</option>
                                                    	<option value="custom">Custom</option>
                                                         <option value="param1">URL Parameter 1</option> 
                                                         <option value="param1+">URL Parameter 1+</option> 
                                                         <option value="param2">URL Parameter 2</option> 
                                                         <option value="param2+">URL Parameter 2+</option> 
                                                         <option value="param3">URL Parameter 3</option> 
                                                         <option value="param3+">URL Parameter 3+</option>
                                                          <option value="param4">URL Parameter 4</option> 
                                                         <option value="param4+">URL Parameter 4+</option> 
                                                          <option value="param5">URL Parameter 5</option> 
                                                         <option value="param5+">URL Parameter 5+</option> 
                                                          <option value="param6">URL Parameter 6</option> 
                                                         <option value="param6+">URL Parameter 6+</option> 
                                                          <option value="param7">URL Parameter 7</option> 
                                                         <option value="param7+">URL Parameter 7+</option> 
                                                          <option value="param8">URL Parameter 8</option> 
                                                         <option value="param8+">URL Parameter 8+</option> 
                                                          <option value="param9">URL Parameter 9</option> 
                                                         <option value="param9+">URL Parameter 9+</option> 
                                                         <option value="param10">URL Parameter 10</option> 
                                                         <option value="param10+">URL Parameter 10+</option> 
                                                    </select>
                                                </div>
                                            </div>
                                             <div class="da-form-row"  id="findOneContainer" style="display:none">
                                                <label>Find One </label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >Findind type </span>
                                                    	<button id="da-dialog-find-one" class="da-button blue">เลือกข้อมูล</button>
                                                        <div id="da-dialog-div-one" style="display:none;">
                                                        	   <div class="da-panel-content" id="da-ex-datatable-numberpaging-container">
                                                                 <table id="da-ex-datatable-numberpaging" class="da-table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="40">ลำดับ</th>
                                                                                <th>ชื่อ</th>
                                                                                <th>หมวดหมู่</th>
                                                                                <th>สถานนะ</th>
                                                                                <th width="38">#ID</th> 
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                  	  </div>
                                                       <div  id="showFindOneSelectedContainer" style="display:none;">
                                                      <p>
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tbody id="findOneSelected">
                                                        </tbody>
                                                       </table>
                                                        </div>
                                                </div>
                                            </div>
                                              <div class="da-form-row"  id="findInCategoryContainer" style="display:none">
                                                <label>Find in category </label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >Findind in category </span>
                                                    <button id="da-dialog-find-cat" class="da-button blue">เลือกข้อหมวดหมู่</button>
                                                        <div id="da-dialog-div-cat" style="display:none;">
                                                        	   <div class="da-panel-content" id="da-ex-datatable-numberpaging-cat-container">
                                                                    <table id="da-ex-datatable-numberpaging-cat" class="da-table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th width="40">ลำดับ</th>
                                                                                <th>ชื่อ</th>
                                                                                <th>ระดับ</th>
                                                                                <th>สถานะ</th>
                                                                                <th width="38">#ID</th> 
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                         
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                  	  </div>
                                                      <div id="showFindCategorySelectedContainer" style="display:none;">
                                                      <p>
                                                     <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tbody id="findCategorySelected">
                                                            </tbody>
                                                        </table>
                                                        </div>
                                                </div>
                                            </div>
                                             <div class="da-form-row"  id="findIsSlugContainer" style="display:none">
                                                <label>Is Slug </label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >Is Slug </span>
                                                    <select name="isSlug" id="isSlug" >
                                                    	<option value="1">Slug</option>
                                                    	<option value="0">ID</option>
                                                    </select>
                                                </div>
                                            </div>
                                              <div class="da-form-row"  id="findPublicStatusContainer" style="display:none">
                                                <label>Public Status </label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >Public Status </span>
                                                    <select name="findStatus" id="findStatus" >
                                                    	<option value="1">Public</option>
                                                    	<option value="0">Unpublic</option>
                                                    </select>
                                                </div>
                                            </div>
                                              <div class="da-form-row"  id="findSearchParamContainer" style="display:none">
                                                <label>Search Parameter </label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >Search Parameter </span>
                                                    <select name="searchParameter" id="searchParameter" onchange="findSearchParamFilter(this.value)" >
                                                    	<option value="">--Select Search Parameter--</option>
                                                    	<option value="custom">Custom Keyword</option>
                                                         <option value="param1">URL Parameter 1</option> 
                                                         <option value="param1+">URL Parameter 1+</option> 
                                                         <option value="param2">URL Parameter 2</option> 
                                                         <option value="param2+">URL Parameter 2+</option> 
                                                         <option value="param3">URL Parameter 3</option> 
                                                         <option value="param3+">URL Parameter 3+</option>
                                                          <option value="param4">URL Parameter 4</option> 
                                                         <option value="param4+">URL Parameter 4+</option> 
                                                          <option value="param5">URL Parameter 5</option> 
                                                         <option value="param5+">URL Parameter 5+</option> 
                                                          <option value="param6">URL Parameter 6</option> 
                                                         <option value="param6+">URL Parameter 6+</option> 
                                                          <option value="param7">URL Parameter 7</option> 
                                                         <option value="param7+">URL Parameter 7+</option> 
                                                          <option value="param8">URL Parameter 8</option> 
                                                         <option value="param8+">URL Parameter 8+</option> 
                                                          <option value="param9">URL Parameter 9</option> 
                                                         <option value="param9+">URL Parameter 9+</option> 
                                                         <option value="param10">URL Parameter 10</option> 
                                                         <option value="param10+">URL Parameter 10+</option> 
                                                    </select>
                                                </div>
                                            </div>
                                             <div class="da-form-row" id="findSearchContainer" style="display:none">
                                                <label>Custom Searchkey </label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >Custom Searchkey "about us" </span>
                                                    <input type="text" name="search_key" id="search_key" value="" />
                                                </div>
                                            </div>
                                            <div class="da-form-row" id="findFilterContainer" style="display:none">
                                                <label>Custom SQL Filter </label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >Custom Filter in where  { Ex: tabel.field=value } </span>
                                                    <input type="text" name="filter_key" id="filter_key" value="" />
                                                </div>
                                            </div>
                                            <div class="da-form-row"  id="findQueryOrderContainer" style="display:none">
                                                <label>SQL Query Order </label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >SQL Query Order {Ex: table.field asc, table.field desc} </span>
                                                    <input type="text" name="find_order" id="find_order" value="" />
                                                </div>
                                            </div>
                                             <div class="da-form-row"  id="findPaginateContainer" style="display:none">
                                                <label>Paginate Parameter </label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >Paginate Parameter </span>
                                                    <select name="pageParameter" id="pageParameter"  onchange="findPaginateParamFilter(this.value)">
                                                    	<option value="0">No Paginate</option>
                                                         <option value="param1">URL Parameter 1</option> 
                                                         <option value="param1+">URL Parameter 1+</option> 
                                                         <option value="param2">URL Parameter 2</option> 
                                                         <option value="param2+">URL Parameter 2+</option> 
                                                         <option value="param3">URL Parameter 3</option> 
                                                         <option value="param3+">URL Parameter 3+</option>
                                                          <option value="param4">URL Parameter 4</option> 
                                                         <option value="param4+">URL Parameter 4+</option> 
                                                          <option value="param5">URL Parameter 5</option> 
                                                         <option value="param5+">URL Parameter 5+</option> 
                                                          <option value="param6">URL Parameter 6</option> 
                                                         <option value="param6+">URL Parameter 6+</option> 
                                                          <option value="param7">URL Parameter 7</option> 
                                                         <option value="param7+">URL Parameter 7+</option> 
                                                          <option value="param8">URL Parameter 8</option> 
                                                         <option value="param8+">URL Parameter 8+</option> 
                                                          <option value="param9">URL Parameter 9</option> 
                                                         <option value="param9+">URL Parameter 9+</option> 
                                                         <option value="param10">URL Parameter 10</option> 
                                                         <option value="param10+">URL Parameter 10+</option> 
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="da-form-row"  id="findPaginateLengthContainer" style="display:none">
                                                <label>Paginate Length</label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >Paginate Length</span>
                                                    <input type="text" name="paginateLength" id="paginateLength" value="10" />
                                                </div>
                                            </div>
                                             <div class="da-form-row"  id="findSeparateContainer" style="display:none">
                                                <label>Separate</label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >Separate Status </span>
                                                    <select name="findSeparate" id="findSeparate" >
                                                    	<option value="0">Not Separate</option>
                                                    	<option value="1">Separate</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="da-form-row"  id="findCountContainer" style="display:none">
                                                <label>Count All</label>
                                                <div class="da-form-item large">
                                                	<span class="formNote" >Count All</span>
                                                    <select name="findCount" id="findCount" >
                                                  		<option value="1">Count</option>
                                                    	<option value="0">Not Count</option>
                                                    </select>
                                                </div>
                                            </div>
                                      		<div class="da-form-row">
                                                <label>เปิดใช้งาน<span class="required">*</span></label>
                                                <div class="da-form-item small">
                                                	<span class="formNote">เลือกสถานการเปิดปิดเว็บเพจ</span>
                                                    <select id="status" name="status" >
                                                    <option value="1">เปิดใช้งาน</option>
                                                    <option value="0">ปิดใช้งาน</option>
                                                    </select>
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
</body>
</html>
