// JavaScript Document
"use strict";
var oTable;
var modules = "pages";
(function($) {
	$(document).ready(function(e) {
		var d = new Date();
		oTable = $("table#da-ex-datatable-numberpaging").dataTable({
			"sPaginationType": "full_numbers",
       		 "bProcessing": true,
        	"bServerSide": true,
			"bStateSave": true,
        	"sAjaxSource": "../../app/index.php?module="+modules+"&task=getCategoriesData",
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
	});// document_ready
}) (jQuery);
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
	var d = new Date() ;
	var url = "../../app/index.php?module="+modules+"&task=setCategoryDelete&id="+id+"&d"+d.getTime();
	if(confirmed){
		$.get(url,function(data){
			if(length==(index+1)){ 
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> ลบรายการหมวดหมู่เสร็จ", {position: "bottom-right"});
			}
		});
	}else{
		if(confirm('ยืนยันลบข้อมูลที่เลือก !')){
			$.get(url,function(data){
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> ลบรายการหมวดหมู่เสร็จ", {position: "bottom-right"});
			});
		}
	}
}

function setCategoryStatus(id,status){
	var d= new Date();
	var url = "../../app/index.php?module="+modules+"&task=setCategoryStatus&status="+status+"&id="+id+"&d"+d.getTime() ;
	$.get(url, function(data){
		reloadTableData(oTable);
		$.jGrowl("แจ้งเตือน ! <br>เปลี่ยนสถานะหมวดหมู่สำเร็จ", {position: "bottom-right"});
	});
}

function setCategoryEdit(id){
	var url ="categories-form.php?mode=edit&id="+id;
	window.location.replace(url);
}

function setCategoryDuplicate(id){
	var d= new Date();
	var url = "../../app/index.php?module="+modules+"&task=duplicateCategory&id="+id+"&d"+d.getTime() ;
	$.get(url, function(data){
		reloadTableData(oTable);
		$.jGrowl("แจ้งเตือน ! <br>คัดลอกหมวดหมู่สำเร็จ", {position: "bottom-right"});
	});
}

function setCategoryTranslate(id){
	var url ="categories-form-translate.php?mode=translate&id="+id;
	window.location.replace(url);
}


function setCategoryDelete(id){
	if(confirm('ยืนยันลบหมวดหมู่')){
		var d= new Date();
		var url = "../../app/index.php?module="+modules+"&task=setCategoryDelete&id="+id+"&d"+d.getTime() ;
		$.get(url, function(data){
			if(data=='haschild'||data=='hasitem'){
				if(data=='haschild'){
					alert('ไม่สามารถลบหมวดหมู่นี้ได้เนื่องจากมีหมวดหมู่ย่อยอยู่ !');
					$.jGrowl("แจ้งเตือน ! <br> ต้องลบหมวดหมู่ย่อยและหน้าเพจภายในหมวดหมู่นี้ก่อน", {position: "bottom-right"});
				}
				if(data=='hasitem'){
					alert('ไม่สามารถลบหมวดหมู่นี้ได้เนื่องจากมีเว็บเพจอยู่ !');
					$.jGrowl("แจ้งเตือน ! <br> ต้องลบเว็บเพจภายในหมวดหมู่นี้ก่อน", {position: "bottom-right"});
				}
			}else{
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> ลบหมวดหมู่สำเร็จ", {position: "bottom-right"});
			}
		});
	}
}


function setCategoryMove(id,position){
		var d= new Date();
		var url = "../../app/index.php?module="+modules+"&task=setCategoryMove&position="+position+"&id="+id+"&d"+d.getTime() ;
		$.get(url, function(data){
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> จัดลำดับหมวดหมู่สำเร็จ", {position: "bottom-right"});
		});
}

function setCheckAll(){
	var is_checked =$('#checkboxAll').attr('checked');
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