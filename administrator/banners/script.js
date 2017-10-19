// JavaScript Document
"use strict";
var oTable;
var modules = "banners";
var oTableData;
var chkSortableMsg = true;
$(document).ready(function(e) {
	var d = new Date();
	oTable = $("table#da-ex-datatable-numberpaging").dataTable({
		"sPaginationType": "full_numbers",
       	"bProcessing": true,
        "bServerSide": true,
		"bStateSave": true,
        "sAjaxSource": "../../app/index.php?module="+modules+"&task=getData",
		"fnServerParams": function( aoData ){ aoData.push( { name: "filterCategoryID", value: $('#TableCategoryFilter').val() }); },
		"fnStateSaveParams": function (oSettings, oData) {  
			oTableData = oData;
			if(oData.oSearch.sSearch=='undefined' ){ oData.oSearch.sSearch = ""; }
			var sort_rule =oTableData.aaSorting+'';
			var sort_split = sort_rule.split(',');
			var column = sort_split[0];
			var direction = sort_split[1];
			// alert drag & drop to sort
			if(column=='4'&&direction=='asc'){ 
				sortable(true); 
				if(chkSortableMsg){
					chkSortableMsg = false;
					$.jGrowl("แจ้งให้ทราบ ! <br> ท่านสามารถเรียงลำดับข้อมูลได้โดยการ Drag & Drop ข้อมูลในตาราง", {position: "bottom-right"});
				}
			}else{
				sortable(false);
			}
		},
		"oLanguage": {
           	"sLengthMenu": "แสดง _MENU_ รายการต่อหน้า",
           	"sZeroRecords": "ขออภัยไม่พบข้อมูล",
            "sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
            "sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 รายการ",
            "sInfoFiltered": "(กรองข้อมูลจาก _MAX_ รายการทั้งหมด)",
			"oPaginate": {
       			"sFirst": "หน้าแรก",
				"sLast": "หน้าสุดท้าย",
				"sNext": "ถัดไป",
				"sPrevious": "ก่อนหน้า"
    		},
			"sSearch": "ค้นหา :",
			"sLoadingRecords": "กรุณารอสักครู่ กำลังโหลดข้อมูล<div class='modal_show' onclick='reloadPageNow()'></div>",
			"sProcessing": "กรุณารอสักครู่ กำลังโหลดข้อมูล<div class='modal_show' onclick='reloadPageNow()'></div>"
        }
	});
	LoadCategoryFilter();
	showChangeCategoryDialog();
});// document_ready
function setDeleteSelectedData(){
	var selected = getTableCheckboxChecked();
	if(selected.length<=0){
		$.jGrowl("แจ้งเตือน ! <br> ท่านต้องเลือกรายการที่ต้องการลบอย่างน้อย 1 รายการ", {position: "bottom-right"});
	}else{
		if(confirm('ยืนยันลบข้อมูลที่เลือก !')){
			$.each(selected,function(index,value){
				setDeleteData(value,true,selected.length,index);
			});
		}
	}
}
function setDeleteData(id,confirmed,length,index){
	var d = new Date();
	var url = "../../app/index.php?module="+modules+"&task=setDelete&id="+id+"&d"+d.getTime();
	if(confirmed){
		$.get(url,function(data){
			if(length==(index+1)){ 
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> ลบรายการเว็บเพจสำเร็จ", {position: "bottom-right"});
			}
		});
	}else{
		if(confirm('ยืนยันลบข้อมูลที่เลือก !')){
			$.get(url,function(data){
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> ลบรายการเว็บเพจเสร็จ", {position: "bottom-right"});
			});
		}
	}
}
function setStatus(id,status){
	var d= new Date();
	var url = "../../app/index.php?module="+modules+"&task=setStatus&status="+status+"&id="+id+"&d"+d.getTime();
	$.get(url, function(data){
		reloadTableData(oTable);
		$.jGrowl("แจ้งเตือน ! <br>เปลี่ยนสถานะเว็บเพจสำเร็จ", {position: "bottom-right"});
	});
}
function setEdit(id){
	var url ="form.php?mode=edit&id="+id;
	window.location.replace(url);
}
function setDuplicate(id){
	var d= new Date();
	var url = "../../app/index.php?module="+modules+"&task=duplicate&id="+id+"&d"+d.getTime();
	$.get(url, function(data){
		reloadTableData(oTable);
		$.jGrowl("แจ้งเตือน ! <br>คัดลอกเว็บเพจสำเร็จ", {position: "bottom-right"});
	});
}
function setTranslate(id){
	var url ="form-translate.php?mode=translate&id="+id;
	window.location.replace(url);
}
function setDelete(id){
	if(confirm('ยืนยันลบหมวดหมู่')){
		var d= new Date();
		var url = "../../app/index.php?module="+modules+"&task=setDelete&id="+id+"&d"+d.getTime();
		$.get(url, function(data){
			reloadTableData(oTable);
			$.jGrowl("แจ้งเตือน ! <br> ลบเว็บเพจสำเร็จ", {position: "bottom-right"});
		});
	}
}
function setMove(id,position){
	var d= new Date();
	var url = "../../app/index.php?module="+modules+"&task=setMove&type="+position+"&id="+id+"&d"+d.getTime();
	$.get(url, function(data){
		reloadTableData(oTable);
		$.jGrowl("แจ้งเตือน ! <br> จัดลำดับเว็บเพจสำเร็จ", {position: "bottom-right"});
	});
}
function setCheckAll(){
	var is_checked = $('#checkboxAll').attr('checked');
	if(is_checked=='checked'||is_checked==true){
		$('#da-ex-datatable-numberpaging').find('input.table_checkbox').each(function(){
			$(this).attr('checked','checked');	
		});
		$('#checkboxAll').attr('checked','checked');
	}else{
		$('#da-ex-datatable-numberpaging').find('input.table_checkbox').each(function(){
			$(this).removeAttr('checked');	
		});
		$('#checkboxAll').removeAttr('checked');
	}
}
// category filter
function showChangeCategoryDialog(){
	configFindCategoryDialog();
	$("#da-dialog-find-cat").bind("click", function(event) {
		loadFindCategoryTableData(findModule);
		$("#da-dialog-div-cat").dialog("option", {modal: false}).dialog("open");
		event.preventDefault();
	});	
}
function sortable(status){
	if(status){
		var is_sortable = $('#sortable').hasClass('ui-sortable');
		if(is_sortable){
			if($("#sortable").sortable("option","enable")){
				$("#sortable").sortable("enable");
			}
		}else{
	  		$("#sortable").sortable({
     	 		placeholder: "ui-state-highlight",
	 	 		delay: 300
    		});
		$("#sortable").bind( "sortstop", function(event, ui) {
			var sort_list = 'start';
			var id_list = 'start';
			$.each($("#sortable").sortable("toArray"),function(index,value){
				var sequence_id = '#sequence_'+value;
				sort_list += '-'+$(sequence_id).val();
				id_list += '-'+value;
			});
			var d = new Date();
			var url = "../../app/index.php?module="+modules+"&task=reorderData&sort="+sort_list+"&id="+id_list+"&d"+d.getTime();
			$.get(url, function(data){
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> จัดลำดับสำเร็จ", {position: "bottom-right"});
			});	
		});
		}// disable 
	}else{ //disable sort
		$("#sortable").sortable('disable');
	}
}
function switchDataOrder(id){
	var sort_val = $('#sequence_'+id).val();
	var old_sort_val = $('#sequence_'+id).attr('title');
	if(sort_val!=old_sort_val){
		var d = new Date();
		var url = "../../app/index.php?module="+modules+"&task=switchOrder&id="+id+"&sort="+sort_val+"&d"+d.getTime();
		$.get(url,function(data){
			reloadTableData(oTable);
			$.jGrowl("แจ้งเตือน ! <br> เรียงลำดับเสร็จ", {position: "bottom-right"});
		});	
	}
}
function setReorderAll(){
	var sort_rule = oTableData.aaSorting+'';
	var sort_split = sort_rule.split(',');
	var column = sort_split[0];
	var direction = sort_split[1];
	var d = new Date();
	var url = "../../app/index.php?module="+modules+"&task=setReorderAll&column="+column+"&direction="+direction+"&d"+d.getTime();
	if(confirm('ยีนยันเรียงลำดับข้อมูลใหม่ตามการแสดงผลของตาราง !')){
		 $.get(url, function(data){
			reloadTableData(oTable);
			$.jGrowl("แจ้งเตือน ! <br> เรียงลำดับเสร็จ", {position: "bottom-right"});
		});	
	}
}
function LoadCategoryFilter(){
	var d= new Date();
	var url = "../../app/index.php?module="+modules+"&task=loadCategoriesFilter&d"+d.getTime();
	$.get(url, function(data){
		var filter = '<div style="width:auto; min-width:150px; position:relative; float:right; display: inline-block; padding: 10px; text-align:right;"><label >หมวดหมู่ : <select name="TableCategoryFilter" id="TableCategoryFilter" onchange="setCategoryFilter()">'+data+'</select></label></div>';
    	$('#da-ex-datatable-numberpaging_filter').after(filter);
	});
}
function setCategoryFilter(){
  	oTable.fnFilter();
}
function showChangeCategoryDialog(){
	configChangeCategoryDialog();
	$("#da-dialog-change-cat").bind("click", function(event) {
		var selected = getTableCheckboxChecked();
		if(selected.length<=0){
			$.jGrowl("แจ้งเตือน ! <br> ท่านต้องเลือกรายการที่ต้องการลบอย่างน้อย 1 รายการ", {position: "bottom-right"});
		}else{
			loadChangeCategoryTableData();
			$("#da-dialog-div-category").dialog("option", {modal: false}).dialog("open");
			event.preventDefault();
		}
	});	
}
function configChangeCategoryDialog(){
	$("#da-dialog-div-category").dialog({
		autoOpen: false, 
		title: "เลือกหมวดที่ต้องการ", 
		modal: true, 
		width: "80%",
		height:'500',
		buttons: [{
		text: "ย้ายข้อมูล", 
		click: function() {
			saveChangeCategory();
			$(this).dialog("close");
		}}]
	});	
}
function saveChangeCategory(){
	var category_id = $('#da-ex-datatable-'+modules+'-changecategory').find('input.table_checkbox:checked').val();
	var selected = getTableCheckboxChecked();
	if(selected.length<=0){
		$.jGrowl("แจ้งเตือน ! <br> ท่านต้องเลือกรายการที่ต้องการย้ายอย่างน้อย 1 รายการ", {position: "bottom-right"});
	}else{
		if(confirm('ย้ายข้อมูลไปยังหมวดหมู่ที่เลือก !')){
			$.each(selected,function(index,value){
				setChangeCategory(value,category_id,selected.length,index);
			});
		}
	}
}
function setChangeCategory(id,category_id,length,index){
	var d= new Date();
	var url = "../../app/index.php?module="+modules+"&task=changeCategory&id="+id+"&category_id="+category_id+"&d"+d.getTime();
	$.get(url, function(data){
		if(length==(index+1)){ 
			reloadTableData(oTable);
			$.jGrowl("แจ้งเตือน ! <br> ย้ายหมวดหมู่สำเร็จ", {position: "bottom-right"});
		}
	});	
}
function loadChangeCategoryTableData(){
	var df_table = '<table id="da-ex-datatable-'+modules+'-changecategory" class="da-table"> <thead> <tr> <th width="40">ลำดับ</th> <th>ชื่อ</th><th>ระดับ</th><th>สถานนะ</th><th width="38">#ID</th></tr> </thead> <tbody></tbody></table>';
	$("#da-ex-datatable-category-container").html(df_table);	 
	oTableChange = $("table#da-ex-datatable-"+modules+"-changecategory").dataTable({
		"sPaginationType": "full_numbers",
       	"bProcessing": true,
        "bServerSide": true,
		"bStateSave": true,
        "sAjaxSource": "../../app/index.php?module="+modules+"&task=getCategoriesDataInChangeCategory",
		"oLanguage": {
           	"sLengthMenu": "แสดง _MENU_ รายการต่อหน้า",
           	"sZeroRecords": "ขออภัยไม่พบข้อมูล",
            "sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
            "sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 รายการ",
            "sInfoFiltered": "(กรองข้อมูลจาก _MAX_ รายการทั้งหมด)",
			"oPaginate": {
       			"sFirst": "หน้าแรก",
				"sLast": "หน้าสุดท้าย",
				"sNext": "ถัดไป",
				"sPrevious": "ก่อนหน้า"
    		},
			"sSearch": "ค้นหา :",
			"sLoadingRecords": "กรุณารอสักครู่ กำลังโหลดข้อมูล<div class='modal_show' onclick='reloadPageNow()'></div>",
			"sProcessing": "กรุณารอสักครู่ กำลังโหลดข้อมูล<div class='modal_show' onclick='reloadPageNow()'></div>"
        }
	});	
}