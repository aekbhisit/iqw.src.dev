// JavaScript Document
var oTable ;
(function($) {
	$(document).ready(function(e) {
		var d = new Date();
		oTable = $("table#da-ex-datatable-numberpaging").dataTable({
			"sPaginationType": "full_numbers",
       		 "bProcessing": true,
        	"bServerSide": true,
			"bStateSave": true,
        	"sAjaxSource": "../../app/index.php?module=statistics&task=getStatsData",
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

function setDeleteAllStatData(id){
	if(confirm('ยืนยันลบข้อมูล')){
		var d= new Date();
		var url = "../../app/index.php?module=statistics&task=setDeleteAllStatData&d"+d.getTime() ;
		$.get(url, function(data){
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> ลบข้อมูลสถิติสำเร็จ", {position: "bottom-right"});
			
		});
	}
}

