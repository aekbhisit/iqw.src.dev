// JavaScript Document
var modules = "orders";
(function($) {
	$(document).ready(function(e) {
		loadLanguage();
		$('#categories_language').bind('change',function(){
			var lang = $('#categories_language').val();
			categoryFormTranslateInit(lang) ;
		});
		selectImages() ;
		$.jGrowl("แจ้งเตือน ! <br> โหลดข้อมูลเสร็จแล้วพร้อมแก้ไข", {position: "bottom-right"});
	});// document_ready
}) (jQuery);

function gotoManagePage(){
	var url = 'categories.php'; 
	window.location.replace(url);
}

function categoryFormTranslateInit(lang){
	var d = new Date();
	var request = window.location.search.replace('?','') ;
	var url = "../../app/index.php?module="+modules+"&task=categoryFormTranslateInit&language="+lang+"&"+request+"&d"+d.getTime() ;
	$.getJSON(url,function(data){ 
			//alert($.params(data));
			if(typeof data=='object' && data!=null){
				$('#categories_id').val(data.category_id);
				 loadNowCategories(data.parent_id) ;
				$('#now_translate').val(data.translate_from);
				$('#categories_name').val(data.name);
				$('#categories_description').html(data.description);
				if(data.image!=''){
					$('#categories_server_images').val(data.image);
					$('#show_categories_image').attr('src',data.image);
					$('#show_categories_image').fadeIn('fast');
				}
			}
	});
}

function loadNowCategories(selected){
	var d = new Date();
	var url = "../../app/index.php?module="+modules+"&task=loadCategories&d"+d.getTime() ;
	$.getJSON(url,function(data){
		//alert(data);
		var ul_list = '<br><ul style="list-style:none;">';
		var options_list = "";
		$.each(data,function(index,value){
			var indent = '';
			for(i=0;i<value.level-1;i++){
				indent += '-';
			}
			if(value.level>0){
				ul_list += '<li>'+indent+' '+value.name+'</li>' ;
			}
			if(value.level>0||value.id==0){
				if(value.id==selected){
					options_list += '<option value="'+value.id+'" selected="selected">'+indent+' '+value.name+'</option>' ;
				}else{
					options_list += '<option value="'+value.id+'" >'+indent+' '+value.name+'</option>' ;
				}
			}
		});
		ul_list += '</ul>';
		$('#show-categories').html(ul_list) ;
	//	$('#categories_parent').html(options_list) ;
	//	$('#categories_parent').removeAttr('disabled') ;
	});
}

function setSaveCategoriesTranslate(){
	var d = new Date();	
	var url = "../../app/index.php?module="+modules+"&task=saveCategoryTranslate&d"+d.getTime() ;
	$.ajax({
		  type: 'POST', 
		  url: url, 
		  enctype: 'multipart/form-data', 
		  data: $('#categories_form').serialize(),
		  beforeSend: function() {
				$('#categories_form').validate({ 
					rules: {
					 categories_language: {
						required: true
					},
					categories_name: {
						required: true
					},
					categories_slug: {
						required: true
					}
				}, 
				invalidHandler: function(form, validator) {
					var errors = validator.numberOfInvalids();
					if (errors) {
						var message = errors == 1
						? 'ผิดพลาด ต้องใส่ข้อมูลให้ครบ'
						: 'ผิดพลาด ต้องใส่ข้อมูลให้ครบ';
						$("#category-form-error").html(message).show();
					} else {
						$("#form-error").hide();
					}
				}
				 });
				return $('#categories_form').valid();
			  },
		  success: function(data){
			  //alert(data);
			 gotoManagePage()
		 }
	});
}

function selectImages(){
		 var input = $('#categories_server_images'),
  		  opts = { 
       		 url : '../../files/connectors/php/connector.php',
       		 editorCallback : function(url) { input.val(url) },
        	closeOnEditorCallback : true,
        	dialog : { title : 'Files Management'}
   		 };
    $(input).bind('click',function () {
		if($(document).has('#finder').length<=0){
			$('#categories_server_images').after('<div id="finder"></div>');
		}
        $('#finder').elfinder(opts);
    });	
}

function loadLanguage(){
	var d = new Date();
	var url = "../../app/index.php?module="+modules+"&task=loadLanguages&d"+d.getTime() ;
	$.getJSON(url,function(data){
		var ul_list = '<br><ul style="list-style:none;">';
		var options_list = "<option >--เลือกภาษา--</option>";
		$.each(data,function(index,value){
			options_list += '<option value="'+value.code+'" >'+value.language+'</option>' ;
		});
		$('#categories_language').html(options_list) ;
		$('#categories_language').removeAttr('disabled') ;
	});
}
