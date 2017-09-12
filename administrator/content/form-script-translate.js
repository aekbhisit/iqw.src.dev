// JavaScript Document
var modules = "htmlzone";
(function($) {
	$(document).ready(function(e) {
		formInit();
		$('#translate_language').bind('change',function(){
			var lang = $('#translate_language').val();
			formInitDataTranslate();
		});
		$(document).find(".datetimepicker").each(function(){
			$(this).datetimepicker();
		});
		loadLanguage();
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
	var url = "../../app/index.php?module="+modules+"&task=getAllInputInBlock&"+request+"&d"+d.getTime();
	$.getJSON(url,function(data){ 
		if(typeof data=='object' && data!=null){
			createBlock(data);
		}
	});
}
function formInitDataTranslate() {
	var d = new Date();
	var request = window.location.search.replace('?','');
	var url = "../../app/index.php?module="+modules+"&task=getAllInputInBlock_lang&language="+$('#translate_language').val()+"&"+request+"&d"+d.getTime();
	$.getJSON(url,function(data){ 
		if(typeof data=='object' && data!=null){
			console.log(data);
			//createBlock(data);
		}
	});
}
function inputBrowserFileTest(){
	$(document).find('.elfinder-browse').each(function(){
		$(this).bind('click',function () {
			var input = $(this);
			// create new elFinder
			dialog = $('<div id="finder" />').dialogelfinder({
				url : '../../files/php/connector.php',
				commandsOptions: {
					getfile: {
						oncomplete: 'destroy' 
					}
				},
				getFileCallback: function(file){
					$(input).val(file);
				} // pass callback to file manager
			});
		});
	});
}
function createBlock(data) {
	var html = '';
	html = '';
	$.each(data,function(k,v){
		html += checkBlock(v);
	});
	$('div.blockhtmlcontent').html(html);
	inputBrowserFileTest();
	initFormTextEditor();
}
function checkBlock(data) {
	var html = '';
	switch (parseInt(data.type)) {
		// text
		case 1:
			html += '<div class="da-form-row">';
			html += '<label>'+data.name+' <span class="required">*</span></label>';
			html += '<div class="da-form-item large">';
			html += '<span class="formNote">'+data.description+'</span>';
			html += '<input type="hidden" name="block['+data.zone_input_id+'][zone_data_id]" id="image" value="'+data.zone_data_id+'" />';
			html += '<input type="text" name="block['+data.zone_input_id+'][text]" id="name'+data.zone_input_id+'" value="" />';
			html += '</div>';
			html += '</div>';
		break;
		// textarea
		case 2:
			html += '<div class="da-form-row">';
			html += '<label>'+data.name+' <span class="required">*</span></label>';
			html += '<div class="da-form-item large">';
			html += '<span class="formNote">'+data.description+'</span>';
			html += '<input type="hidden" name="block['+data.zone_input_id+'][zone_data_id]" id="image" value="'+data.zone_data_id+'" />';
			html += '<textarea class="texteditor" name="block['+data.zone_input_id+'][text]" id="description'+data.zone_input_id+'"></textarea>';
			html += '</div>';
			html += '</div>';
		break;
		// image
		case 3:
			html += '<div class="da-form-row">';
			html += '<label>'+data.name+' </label>';
			html += '<div class="da-form-item large">';
			html += '<span class="formNote">'+data.description+'</span>';
			html += '<div id="finder"></div>';
			html += '<input type="hidden" name="block['+data.zone_input_id+'][zone_data_id]" id="image" value="'+data.zone_data_id+'" />';
			html += '<input type="text" name="block['+data.zone_input_id+'][text]" id="image" value="" class="elfinder-browse" />';
			html += '<img src="" id="show_image" style="display:none; max-width:150px; max-height:150px; padding:10px; margin-top:20px; border:#CCC 1px solid; border-radius: 5px;" />';
			html += '</div>';
			html += '</div>';
		break;
	}
	return html;
}
function setSaveTranslate(){
	var d = new Date();	
	var url = "../../app/index.php?module="+modules+"&task=saveTranslateContent&d"+d.getTime();
	$('#form').find('.elrte').each(function(){
		$(this).elrte('updateSource');
	});
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
function selectImages(){
	var input = $('#image');
	var f =  $('#myelfinder').elfinder({
		url : '../../files/php/connector.php',
        closeOnEditorCallback: false,
        getFileCallback: function(url) {
         	input.val(url);	
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