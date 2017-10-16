// JavaScript Document
"use strict";
var modules = "pages";
$(document).ready(function(e) {
	initFormTextEditor();
	$('#translate_language').bind('change',function(){
		var lang = $('#translate_language').val();
		formInit(lang);
	});
	loadLanguage();
	$.jGrowl("แจ้งเตือน ! <br> โหลดข้อมูลเสร็จแล้วพร้อมแก้ไข",{position:"bottom-right"});
});// document_ready
function gotoManagePage(){
	var url = 'index.php'; 
	window.location.replace(url);
}
function formInit(){
	var d = new Date();
	var request = window.location.search.replace('?','');
	if($('#translate_language').val()!='0'){
		var url = "../../app/index.php?module="+modules+"&task=formTranslateInit&language="+$('#translate_language').val()+'&'+request+"&d"+d.getTime();
		$.getJSON(url,function(data){ 
			if(typeof data=='object' && data!=null){
				$('#id').val(data.page_id);
				$('#now_translate').val(data.translate_from);
				$('#meta_key').val(data.meta_key);
				$('#meta_description').val(data.meta_description);
				$('#name').val(data.name);
				$('#description').val(data.description);
				$('#content').val(data.content);
				if(data.image!=''){
					$('#image').val(data.image);
					$('#show_image').attr('src',data.image);
					$('#show_image').fadeIn('fast');
				}
				tinyMCE.activeEditor.setContent(data.content);
			}
		});
	} // if
}
function loadNowCategories(selected){
	var d = new Date();
	var url = "../../app/index.php?module="+modules+"&task=loadCategories&d"+d.getTime();
	$.getJSON(url,function(data){
		var options_list = "";
		$.each(data,function(index,value){
			var indent = '';
			for(var i=0;i<value.level-1;i++){
				indent += '-';
			}
			if(value.level>0||value.id==0){
				if(value.id==selected){
					options_list += '<option value="'+value.id+'" selected="selected">'+indent+' '+value.name+'</option>';
				}else{
					options_list += '<option value="'+value.id+'" >'+indent+' '+value.name+'</option>';
				}
			}
		});
		$('#categories').html(options_list);
		$('#categories').removeAttr('disabled');
	});
}
function setSaveTranslate(){
	var d = new Date();	
	var url = "../../app/index.php?module="+modules+"&task=saveTranslate&d"+d.getTime();
	tinyMCE.triggerSave();
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
function loadLanguage(){
	var d = new Date();
	var url = "../../app/index.php?module="+modules+"&task=loadLanguages&d"+d.getTime();
	$.getJSON(url,function(data){
		var ul_list = '<br><ul style="list-style:none;">';
		var options_list = "<option value='0'>--เลือกภาษา--</option>";
		$.each(data,function(index,value){
			options_list += '<option value="'+value.code+'" >'+value.language+'</option>';
		});
		$('#translate_language').html(options_list);
		$('#translate_language').removeAttr('disabled');
	});
}