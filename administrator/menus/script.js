// JavaScript Document
var oTable ;
var modules = "menus";
(function($) {
	$(document).ready(function(e) {
		var d = new Date();
		oTable = $("table#da-ex-datatable-numberpaging").dataTable({
			"sPaginationType": "full_numbers",
       		 "bProcessing": true,
        	"bServerSide": true,
			"bStateSave": true,
        	"sAjaxSource": "../../app/index.php?module="+modules+"&task=getData",
			"oLanguage": {
           		 	"sLengthMenu": "แสดง _MENU_ รายการต่อหน้า",
           		 	"sZeroRecords": "ขออภัยไม่พบข้อมูล",
            		"sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
            		"sInfoEmpty": "แสดง 0 ถึิง 0 จาก 0 รายการ",
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
	var selected = getTableCheckboxChecked() ;
	if(selected.length<=0){
			$.jGrowl("แจ้งเตือน ! <br> ท่านต้องเลือกรายการที่ต้องการลบอย่างน้อย 1 รายการ", {position: "bottom-right"});
		}else{
			if(confirm('ยืนยันลบข้อมูลที่เลือก !')){
				 $.each(selected,function(index,value){
					setDeleteData(value,true,selected.length,index) ;
				})	;
			}
		}
		
}

function setDeleteData(id,confirmed,length,index){
	var d = new Date() ;
	var  url = "../../app/index.php?module="+modules+"&task=setDelete&id="+id+"&d"+d.getTime() ;
	if(confirmed){
		$.get(url,function(data){
			if(length==(index+1)){ 
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> ลบรายที่เลือกสำเร็จ", {position: "bottom-right"});
			}
		});
	}else{
		if(confirm('ยืนยันลบข้อมูลที่เลือก !')){
			$.get(url,function(data){
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> ลบรายที่เลือกสำเร็จ", {position: "bottom-right"});
			});
		}
	}
}

function setStatus(id,status){
	var d= new Date();
	var url = "../../app/index.php?module="+modules+"&task=setStatus&status="+status+"&id="+id+"&d"+d.getTime() ;
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
	var url = "../../app/index.php?module="+modules+"&task=duplicate&id="+id+"&d"+d.getTime() ;
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
		var url = "../../app/index.php?module="+modules+"&task=setDelete&id="+id+"&d"+d.getTime() ;
		$.get(url, function(data){
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> ลบเว็บเพจสำเร็จ", {position: "bottom-right"});
			
		});
	}
}

function setMove(id,position){
		var d= new Date();
		var url = "../../app/index.php?module="+modules+"&task=setMove&type="+position+"&id="+id+"&d"+d.getTime() ;
		$.get(url, function(data){
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> จัดลำดับเว็บเพจสำเร็จ", {position: "bottom-right"});
		});
}
