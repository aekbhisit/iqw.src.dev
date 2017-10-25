// JavaScript Document
"use strict";
var oTable;
(function($) {
	$(document).ready(function(e) {
		var d = new Date();
		formInit();
		$.jGrowl("แจ้งเตือน ! <br> โหลดข้อมูลเสร็จแล้วพร้อมแก้ไข", {position: "bottom-right"});
	});// document_ready
}) (jQuery);
function formInit(){
	var d = new Date();
	var request = window.location.search.replace('?','');
	var url = "../../app/index.php?module=configs&task=configsEmailFormInit&"+request+"&d"+d.getTime();
	$.getJSON(url,function(data){ 
		if(typeof data=='object' && data!=null ){
			$('#configs_id').val(data.id);
			if(parseInt(data.smtp)==1){
				$('#configs_smtp_0').attr('checked','checked');
				$('#configs_smtp_1').removeAttr('checked');
			}else{
				$('#configs_smtp_1').attr('checked','checked');
				$('#configs_smtp_0').removeAttr('checked');
			}
			$('#configs_smtp_secure').val(data.smtp_secure);
			$('#configs_smtp_server').val(data.smtp_server);
			$('#configs_smtp_port').val(data.smtp_port);
			$('#configs_smtp_user').val(data.smtp_user);
			$('#configs_smtp_password').val(data.smtp_password);
		}
	});
}
function setConfigsEdit(id){
	$('#configs_form').find('input').each(function(){
		$(this).removeAttr('disabled');
		$('#configs_show_edit').fadeOut('fast');
		$('#configs_show_save').fadeIn('fast');
	});
}
function setSaveConfigs(){
	var d = new Date();
	var url = "../../app/index.php?module=configs&task=saveConfigsEmail&d"+d.getTime();
	$.ajax({
		type: 'POST',
		url: url,
		enctype: 'multipart/form-data',
		data: $('#configs_form').serialize(),
		beforeSend: function() {
			$('#configs_form').validate({ 
				rules: {
					configs_smtp: {
						required: true
					},
					configs_secure: {
						required: true
					},
					configs_server: {
						required: true
					},
					configs_port: {
						required: true
					},
					configs_user: {
						required: true
					},
					configs_password: {
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
			return $('#configs_form').valid();
		},
		success: function(data){
			window.location.reload(true);
		}
	});
}