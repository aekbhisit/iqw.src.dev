// JavaScript Document
"use strict";
var modules = "products";
(function($) {
	$(document).ready(function(e) {
		formInit();
		//selectImages();
	/*	$(document).find(".datetimepicker").each(function(){
			$(this).datetimepicker();
			});*/
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
			$('#id').val(data.id);
			loadNowCategories(data.category_id);
			$('#meta_key').val(data.meta_key);
			$('#meta_description').val(data.meta_description);
			$('#name').val(data.name);
			$('#slug').val(data.slug);
			$('#content').html(data.content);
			if(data.image!=''){
				$('#image').val(data.image);
				$('#show_image').attr('src',data.image);
				$('#show_image').fadeIn('fast');
			}
			if(data.image1!=''){
				$('#image1').val(data.image1);
				$('#show_image1').attr('src',data.image1);
				$('#show_image1').fadeIn('fast');
			}
			if(data.image2!=''){
				$('#image2').val(data.image2);
				$('#show_image2').attr('src',data.image2);
				$('#show_image2').fadeIn('fast');
			}
			if(data.image3!=''){
				$('#image3').val(data.image3);
				$('#show_image3').attr('src',data.image3);
				$('#show_image3').fadeIn('fast');
			}
			if(data.image4!=''){
				$('#image4').val(data.image4);
				$('#show_image4').attr('src',data.image4);
				$('#show_image4').fadeIn('fast');
			}
			$('#status').find('option:[value="'+data.status+'"]').attr('selected','selected');
		}else{
			loadNowCategories(0);
		}
	});
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
function setSaveData(){
	var d = new Date();	
	var url = "../../app/index.php?module="+modules+"&task=saveData&d"+d.getTime();
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
			// alert(data);  
			gotoManagePage()
		}
	});
}
function selectImages(){
	var input1 = $('#image');
    $(input1).bind('click',function () {
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
    var input2 = $('#image1');
    $(input2).bind('click',function () {
		if($(document).has('#finder').length<=0){
			$('#image1').after('<div id="finder"></div>');
		}
		$('#finder').elfinder({
         	url : '../../files/php/connector.php',
        	closeOnEditorCallback: false,
        	getFileCallback: function(url) {
				$(input).val(url);
     	   }
		});
    });	

    var input3 = $('#image2');
    $(input3).bind('click',function () {
		if($(document).has('#finder').length<=0){
			$('#image2').after('<div id="finder"></div>');
		}
		$('#finder').elfinder({
         	url : '../../files/php/connector.php',
        	closeOnEditorCallback: false,
        	getFileCallback: function(url) {
				$(input).val(url);
     	   }
		});
    });	

    var input4 = $('#image3');
    $(input4).bind('click',function () {
		if($(document).has('#finder').length<=0){
			$('#image3').after('<div id="finder"></div>');
		}
		$('#finder').elfinder({
         	url : '../../files/php/connector.php',
        	closeOnEditorCallback: false,
        	getFileCallback: function(url) {
				$(input).val(url);
     	   }
		});
    });	
    var input5 = $('#image4');
    $(input5).bind('click',function () {
		if($(document).has('#finder').length<=0){
			$('#image4').after('<div id="finder"></div>');
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