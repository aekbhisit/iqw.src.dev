// JavaScript Document
var modules = "addon";
(function($) {
	$(document).ready(function(e) {
		formInit();
		//selectImages();
		$(document).find(".datetimepicker").each(function(){
			$(this).datetimepicker();
		});
		$.jGrowl("แจ้งเตือน ! <br> โหลดข้อมูลเสร็จแล้วพร้อมแก้ไข", {position: "bottom-right"});
	});// document_ready
}) (jQuery);
function gotoManagePage(){
	//var url = 'index.php'; 
	//window.location.replace(url);
	window.location.reload(true);
}
function formInit(){
	var d = new Date();
	var request = window.location.search.replace('?','');
	var url = "../../app/index.php?module="+modules+"&task=formInit&"+request+"&d"+d.getTime();
	$.getJSON(url,function(data){ 
		if(typeof data=='object' && data!=null){
			$('#all_project').val(data.all_project);
			$('#ongoing_roof').val(data.ongoing_roof);
			$('#ongoing_ground').val(data.ongoing_ground);
		}
	});
}
function setSaveData(){
	var d = new Date();	
	var url = "../../app/index.php?module="+modules+"&task=saveData&d"+d.getTime();
	$.ajax({
		type: 'POST', 
		url: url, 
		enctype: 'multipart/form-data', 
		data: $('#form').serialize(),
		beforeSend: function() {
		},
		success: function(data){
			gotoManagePage()
		}
	});
}