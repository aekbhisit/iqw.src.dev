// JavaScript Document
var oTable ;
(function($) {
	$(document).ready(function(e) {
		var d = new Date();
		formInit() ;
		$.jGrowl("แจ้งเตือน ! <br> โหลดข้อมูลเสร็จแล้วพร้อมแก้ไข", {position: "bottom-right"});
	});// document_ready
	
}) (jQuery);

function formInit(){
	var d = new Date();
	var request = window.location.search.replace('?','') ;
	var url = "../../app/index.php?module=configs&task=configsSiteFormInit&"+request+"&d"+d.getTime() ;
	$.getJSON(url,function(data){ 
			if(typeof data=='object' && data!=null ){
				$('#configs_id').val(data.id);
				if(parseInt(data.opensite)==1){
						$('#configs_open_0').attr('checked','checked');
						$('#configs_open_1').removeAttr('checked');
				}else{
						$('#configs_open_1').attr('checked','checked');
						$('#configs_open_0').removeAttr('checked');
				}
			
				$('#configs_sitename').val(data.sitename);
				$('#configs_favicon_url').val(data.favicon_url);
				$('#configs_meta_keywords').val(data.meta_keywords);
				$('#configs_meta_description').val(data.meta_description);
				$('#configs_underconstruction_text').val(data.underconstruction_text);
				$('#configs_background_url').val(data.background_url);
				
				$.each(data.languages, function(i,v){
					if(v.status==1){
						var option  =  '<option value="'+v.code+'" selected="selected">'+v.language+'</option>';
					}else{
						var option  =  '<option value="'+v.code+'">'+v.language+'</option>';
					}
					$('#configs_languages').append(option)
				});
				
				if($.fn.chosen){
					$(".chzn-select").chosen();
				}
			}
	});
}

function setConfigsEdit(id){
	/*
	$('#configs_form').find('select').each(function(){
		$(this).removeAttr('disabled');
		//$(".chzn-select").chosen();
	});
	*/
	$('#configs_form').find('input').each(function(){
		$(this).removeAttr('disabled');
		$('#configs_show_edit').fadeOut('fast');
		$('#configs_show_save').fadeIn('fast');
	});
}

function setSaveConfigs(){
	var d = new Date();	
	var url = "../../app/index.php?module=configs&task=saveConfigsSite&d"+d.getTime() ;
	$.ajax({
		  type: 'POST', 
		  url: url, 
		  enctype: 'multipart/form-data', 
		  data: $('#configs_form').serialize(),
		  beforeSend: function() {
				$('#configs_form').validate({ 
					rules: {
					configs_opensite: {
						required: true
					},
					configs_sitename: {
						required: true
					},
					configs_meta_keywords: {
						required: true
					},
					configs_meta_description: {
						required: true
					},
					configs_favicon_url: {
						required: true
					},
					configs_underconstruction_text: {
						required: true
					},
					configs_background_url: {
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
			 window.location.reload(true) ;
		 }
	});
}