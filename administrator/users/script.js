// JavaScript Document
var oTable ;
var modules = "users";
(function($) {
	$(document).ready(function(e) {
		var d = new Date();
		oTable = $("table#da-ex-datatable-numberpaging").dataTable({
			"sPaginationType": "full_numbers",
       		 "bProcessing": true,
        	"bServerSide": true,
			"bStateSave": true,
        	"sAjaxSource": "../../app/index.php?module="+modules+"&task=getUsersData",
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
				$.jGrowl("แจ้งเตือน ! <br> ลบรายการข่าวสารสำเร็จ", {position: "bottom-right"});
			}
		}
}

function setDeleteData(id,confirmed){
	var d = new Date() ;
	var  url = "../../app/index.php?module="+modules+"&task=setUsersDelete&id="+id+"&d"+d.getTime() ;
	if(confirmed){
		$.get(url,function(data){
		});
	}else{
		if(confirm('ยืนยันลบข้อมูลที่เลือก !')){
			$.get(url,function(data){
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> ลบรายการข่าวสารเสร็จ", {position: "bottom-right"});
			});
		}
	}
}

function setUsersStatus(id,status){
	var d= new Date();
	var url = "../../app/index.php?module="+modules+"&task=setUsersStatus&status="+status+"&id="+id+"&d"+d.getTime() ;
	$.get(url, function(data){
		reloadTableData(oTable);
		$.jGrowl("แจ้งเตือน ! <br>เปลี่ยนสถานะสมาชิกสำเร็จ", {position: "bottom-right"});
	});
}

function setUsersEdit(id){
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

function setUsersAddress(id){
	var url ="address-form.php?user_id="+id;
	window.location.replace(url);
}

function setUsersDelete(id){
	if(confirm('ยืนยันลบหมวดหมู่')){
		var d= new Date();
		var url = "../../app/index.php?module="+modules+"&task=setUsersDelete&id="+id+"&d"+d.getTime() ;
		$.get(url, function(data){
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> ลบสมาชิกสำเร็จ", {position: "bottom-right"});
		});
	}
}

function setUsersMove(id,position){
		var d= new Date();
		var url = "../../app/index.php?module="+modules+"&task=setUsersMove&type="+position+"&id="+id+"&d"+d.getTime() ;
		$.get(url, function(data){
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> จัดลำดับข่าวสารสำเร็จ", {position: "bottom-right"});
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