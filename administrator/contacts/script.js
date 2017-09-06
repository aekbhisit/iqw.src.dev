// JavaScript Document
var oTable ;
var modules = "contacts";
(function($) {
	$(document).ready(function(e) {
		var d = new Date();
		oTable = $("table#da-ex-datatable-numberpaging").dataTable({
			"sPaginationType": "full_numbers",
       		 "bProcessing": true,
        	"bServerSide": true,
			"bStateSave": true,
        	"sAjaxSource": "../../app/index.php?module="+modules+"&task=getContactsData",
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
					  "sLoadingRecords": "กรุณารอสักครู่ กำลังโหลดข้อมูล <div class='modal_show' onclick='reloadPageNow()'></div>",
					  "sProcessing": "กรุณารอสักครู่ กำลังโหลดข้อมูล <div class='modal_show' onclick='reloadPageNow()'></div>" 
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
					setDeleteData(value,true) ;
				})	;
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> ลบรายการติดต่อรสำเร็จ", {position: "bottom-right"});
			}
		}
}

function setDeleteData(id,confirmed){
	var d = new Date() ;
	var  url = "../../app/index.php?module="+modules+"&task=setDelete&id="+id+"&d"+d.getTime() ;
	if(confirmed){
		$.get(url,function(data){
		});
	}else{
		if(confirm('ยืนยันลบข้อมูลที่เลือก !')){
			$.get(url,function(data){
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> ลบรายการติดต่อเสร็จ", {position: "bottom-right"});
			});
		}
	}
}

function setContactsStatus(id,status){
	var d= new Date();
	var url = "../../app/index.php?module="+modules+"&task=setContactsStatus&status="+status+"&id="+id+"&d"+d.getTime() ;
	$.get(url, function(data){
		reloadTableData(oTable);
		$.jGrowl("แจ้งเตือน ! <br>เปลี่ยนสถานะติดต่อสำเร็จ", {position: "bottom-right"});
	});
}

function setContactsEdit(id){
	var url ="contacts-form.php?mode=edit&id="+id;
	window.location.replace(url); 
}

function setContactsDelete(id){
	if(confirm('ยืนยันลบหมวดหมู่')){
		var d= new Date();
		var url = "../../app/index.php?module="+modules+"&task=setContactsDelete&id="+id+"&d"+d.getTime() ;
		$.get(url, function(data){
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> ลบติดต่อสำเร็จ", {position: "bottom-right"});
		});
	}
}

function setContactsMove(id,position){
		var d= new Date();
		var url = "../../app/index.php?module="+modules+"&task=setContactsMove&type="+position+"&id="+id+"&d"+d.getTime() ;
		$.get(url, function(data){
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> จัดลำดับติดต่อสำเร็จ", {position: "bottom-right"});
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
