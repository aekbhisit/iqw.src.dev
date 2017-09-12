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
	var url = "../../app/index.php?module="+modules+"&task=getAllInputInBlock&"+request+"&d"+d.getTime();
	$.getJSON(url,function(data){ 
		if(typeof data=='object' && data!=null){
			createBlock(data);
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
			html += '<input type="text" name="block['+data.zone_input_id+'][text]" id="name" value="'+data.params+'" />';
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
			html += '<textarea class="texteditor" name="block['+data.zone_input_id+'][text]" id="description">'+data.params+'</textarea>';
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
			html += '<input type="text" name="block['+data.zone_input_id+'][text]" id="image" value="'+data.params+'" class="elfinder-browse" />';
			html += '<img src="" id="show_image" style="display:none; max-width:150px; max-height:150px; padding:10px; margin-top:20px; border:#CCC 1px solid; border-radius: 5px;" />';
			html += '</div>';
			html += '</div>';
		break;
	}
	return html;
}
function setSaveData(){
	var d = new Date();	
	var url = "../../app/index.php?module="+modules+"&task=saveDataHTML&d"+d.getTime();
	tinyMCE.triggerSave();
	$.ajax({
		type: 'POST', 
		url: url, 
		enctype: 'multipart/form-data', 
		data: $('#form').serialize(),
		beforeSend: function() {

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