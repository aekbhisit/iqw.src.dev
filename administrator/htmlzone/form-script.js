// JavaScript Document
var modules = "htmlzone";
(function($) {
	$(document).ready(function(e) {
		formInit();
		$.jGrowl("แจ้งเตือน ! <br> โหลดข้อมูลเสร็จแล้วพร้อมแก้ไข", {position: "bottom-right"});
	});// document_ready
}) (jQuery);
function gotoManagePage(){
	var url = 'index.php'; 
	window.location.replace(url);
}
function formInit(){
	var d = new Date();
	var request = window.location.search.replace('?','');
	var url = "../../app/index.php?module="+modules+"&task=formInit&"+request+"&d"+d.getTime();
	$.getJSON(url,function(data){ 
		if(typeof data=='object' && data!=null){
			$('#id').val(data.zone_id);
			$('#name').val(data.name);
			$('#zonenumber').val(data.zone);
			$('#description').val(data.description);
			// $('#content').val(data.content);
			// $('#url').val(data.url);
			$('#status').find('option:[value="'+data.status+'"]').attr('selected','selected');
			// if(data.image!=''){
			// 	$('#image').val(data.image);
			// 	$('#show_image').attr('src',getPreImageURL()+data.image);
			// 	$('#show_image').fadeIn('fast');
			// }
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
			$('#form').validate({ 
				rules: {
					name: {
						required: true
					},
					zonenumber: {
						required: true
					},
					description: {
						required: true
					}
				}, 
				invalidHandler: function(form, validator) {
					var errors = validator.numberOfInvalids();
					if (errors) {
						var message = errors == 1
						? 'ผิดพลาด ต้องใส่ข้อมูลให้ครบ'
						: 'ผิดพลาด ต้องใส่ข้อมูลให้ครบ';
						$("#form-error").html(message).show();
					} else {
						$("#form-error").hide();
					}
				}
			});
			return $('#form').valid();
		},
		success: function(data){  
			gotoManagePage();
		}
	});
}
function selectImages(){
	var input = $('#image');
    $(input).bind('click',function () {
		if($(document).has('#finder').length<=0){
			$('#image').after('<div id="finder"></div>');
		}
		$('#finder').elfinder({
         	url : '../../files/php/connector.php',
        	closeOnEditorCallback: false,
        	getFileCallback: function(url) {
				$(input).val(url);
     	   	}
		});
    });	
}