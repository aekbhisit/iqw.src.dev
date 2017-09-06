// JavaScript Document
var modules = "newsletters";
(function($) {
	$(document).ready(function(e) {
		formInit() ;
		//selectImages();
		$(document).find(".datetimepicker").each(function(){
			$(this).datetimepicker();
			});
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
				$('#meta_key').val(data.meta_key);
				$('#meta_description').val(data.meta_description);
				$('#name').val(data.name);
				$('#slug').val(data.slug);
			//	$('#page_content').html(data.content);
				$('#content').elrte('val', data.content);
				if(data.image!=''){
					$('#image').val(data.image);
					$('#show_image').attr('src',data.image);
					$('#show_image').fadeIn('fast');
				}
				$('#start').val(data.start);
				$('#end').val(data.end);
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
	$('#form').find('.elrte').each(function(){
		$(this).elrte('updateSource');
	});
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
					image: {
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
			// alert(data);  
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
