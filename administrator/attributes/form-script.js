// JavaScript Document
var modules = "attributes";
(function($) {
	$(document).ready(function(e) {
		formInit() ;
		//selectImages();
		$.jGrowl("แจ้งเตือน ! <br> โหลดข้อมูลเสร็จแล้วพร้อมแก้ไข", {position: "bottom-right"});
	});// document_ready
}) (jQuery);

function gotoManagePage(){
	var url = 'index.php'; 
	window.location.replace(url);
}

function formInit(){
	var d = new Date();
	var request = window.location.search.replace('?','') ;
	var url = "../../app/index.php?module="+modules+"&task=formInit&"+request+"&d"+d.getTime() ;
	$.getJSON(url,function(data){ 
			if(typeof data=='object' && data!=null){
				$('#id').val(data.id);
				 loadNowCategories(data.category_id) ;
				$('#attr_name').val(data.attr_name);
				$('#attr_label').val(data.attr_label);
				$('#attr_type').val(data.attr_type);
				$('#attr_value').val(data.attr_value);
				$('#attr_style').val(data.attr_style);
				$('#attr_class').val(data.attr_class);
				$('#attr_placeholder').val(data.attr_placeholder);
				$('#attr').val(data.attr);
				$('#note').val(data.note);
				$('#status').find('option:[value="'+data.status+'"]').attr('selected','selected');
			}else{
				loadNowCategories(0) ;
			}
	});
}

function loadNowCategories(selected){
	var d = new Date();
	var url = "../../app/index.php?module="+modules+"&task=loadCategories&d"+d.getTime() ;
	$.getJSON(url,function(data){
		var options_list = "";
		$.each(data,function(index,value){
			var indent = '';
			for(i=0;i<value.level-1;i++){
				indent += '-';
			}
			if(value.level>0||value.id==0){
				if(value.id==selected){
					options_list += '<option value="'+value.id+'" selected="selected">'+indent+' '+value.name+'</option>' ;
				}else{
					options_list += '<option value="'+value.id+'" >'+indent+' '+value.name+'</option>' ;
				}
			}
		});
		$('#categories').html(options_list) ;
		$('#categories').removeAttr('disabled') ;
	});
}

function setSaveData(){
	var d = new Date();	
	var url = "../../app/index.php?module="+modules+"&task=saveData&d"+d.getTime() ;
	$.ajax({
		  type: 'POST', 
		  url: url, 
		  enctype: 'multipart/form-data', 
		  data: $('#form').serialize(),
		  beforeSend: function() {
				$('#form').validate({ 
					rules: {
					attr_name: {
						required: true
					}, 
					attr_label: {
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
			 gotoManagePage()
		 }
	});
}

function selectImages(){
	 var input = $('#image') ;
    $(input).bind('click',function () {
		if($(document).has('#finder').length<=0){
			$('#image').after('<div id="finder"></div>');
		}
		 $('#finder').elfinder({
         	url : '../../files/php/connector.php',
        	closeOnEditorCallback: false,
        	getFileCallback: function(url) {
				 $(input).val(url) ;
     	   }
		});
    });	
}
