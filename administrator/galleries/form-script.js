// JavaScript Document
"use strict";
var modules = "galleries";
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
		if(typeof data=='object' && data!=null ){
			$('#id').val(data.id);
			$('#meta_key').val(data.meta_key);
			$('#meta_description').val(data.meta_description);
			loadNowCategories(data.category_id);
			$('#name').val(data.name);
			$('#slug').val(data.slug);
			$('#content').val(data.content);
			if(data.cover!=''){
				$('#cover').val(data.cover);
				$('#show-cover').attr('src',data.cover);
				$('#show-cover').fadeIn('fast');
			}
			if(data.images_list!=''){
				showImageInit(data.images_list);
			}
			$('#galleries_status').find('option:[value="'+data.status+'"]').attr('selected','selected');
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

function showImageInit(img_json){
	if(typeof img_json === 'object' && img_json !== null) {
		var img_length = img_json.length;
		$.each(img_json,function(i,img){
			var img_length = $('#countImageIndex').val();
			var img_item = '<li id="'+img_length+'"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
			img_item += '<tr>';
			img_item += ' <td width="120" height="120" align="center" valign="middle"><a href="'+img.image+'" rel="prettyPhoto"><img src="'+img.image+'"  style="width:100px; height:100px; margin-top:5px; border:#ccc 1px solid; border-radius:10px;"/></a></td>';
			img_item += '<td>';
			img_item += "<input name=\"images["+img_length+"][id]\"  id=\"images["+img_length+"][id]\" type=\"hidden\"  value=\""+img.id+"\" />";
			img_item +="<input name=\"images["+img_length+"][src]\"  id=\"images["+img_length+"][src]\" type=\"hidden\" value=\""+img.image+"\" />";
			img_item += "<label  style=\"height:20px; font-size:90%; padding:0px;\">ชื่อภาพ</label><input name=\"images["+img_length+"][title]\"  id=\"images["+img_length+"][title]\" type=\"text\" style=\"margin-bottom:5px;\" value=\""+img.title+"\" />";
			img_item += "<label  style=\"height:20px; font-size:90%; padding:0px\">คำอธิบายภาพ</label><input name=\"images["+img_length+"][description]\"  id=\"images["+img_length+"][description]\" type=\"text\" value=\""+img.description+"\"  />";
			img_item += '</td>';
			img_item += '<td style="width:50px; padding-left:20px; padding-top:20px;" align="center" valign="middle">';
			img_item += '<a href="javascript:void(0)" onclick="deleteGalleryImage('+img_length+','+img.id+');"><input type="button" value="ลบ" class="da-button red left"></a>';
			img_item += "<br/><input name=\"images["+img_length+"][order]\"  id=\"images["+img_length+"][order]\" type=\"text\" class=\"showImageOrder\" value=\""+img.sequence+"\" style=\"width:40px;\" />";
			img_item += '</td>';
			img_item += '</tr>';
			img_item += '</table>';
			img_item += '</li>';
			$('ul#displayImagesList').append(img_item);
			$('#countImageIndex').val(parseInt($('#countImageIndex').val())+1);
		});
		prettyGallery();
		sortAble();
	}
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
		if($(document).has('#finder').length<=0){
			$('#displayImagesList').after('<div id="finder"></div>');
		}
		  dialog = $('<div id="finder" />').dialogelfinder({
		  url : '../../files/php/connector.php',
		  commandsOptions: {
		  getfile: {
			  	multiple : true,
				oncomplete: 'destroy' 
			}
			},
				getFileCallback: function(file){
						addGalleryImage(file)
				} // pass callback to file manager
	});
}


function addGalleryImage(files){
		$.each(files,function(i,url){
			var img_length = $('#countImageIndex').val();
			var img_item = '<li id="'+img_length+'"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
                    img_item += '<tr>';
                    img_item += ' <td width="120" height="120" align="center" valign="middle"><a href="'+url+'" rel="prettyPhoto"><img src="'+url+'"  style="width:100px; height:100px; margin-top:5px; border:#ccc 1px solid; border-radius:10px;"/></a></td>';
                    img_item += '<td>';
				    img_item += "<input name=\"images["+img_length+"][id]\"  id=\"images["+img_length+"][id]\" type=\"hidden\" />";
                    img_item +="<input name=\"images["+img_length+"][src]\"  id=\"images["+img_length+"][src]\" type=\"hidden\" value=\""+url+"\" />";
                    img_item += "<label  style=\"height:20px; font-size:90%; padding:0px;\">ชื่อภาพ</label><input name=\"images["+img_length+"][title]\"  id=\"images["+img_length+"][title]\" type=\"text\" style=\"margin-bottom:5px;\" />";
                    img_item += "<label  style=\"height:20px; font-size:90%; padding:0px\">คำอธิบายภาพ</label><input name=\"images["+img_length+"][description]\"  id=\"images["+img_length+"][description]\" type=\"text\" />";
                    img_item += '</td>';
                    img_item += '<td style="width:50px; padding-left:20px; padding-top:20px;" align="center" valign="middle">';
                    img_item += '<a href="javascript:void(0)" onclick="deleteGalleryImage('+img_length+','+img_length+');"><input type="button" value="ลบ" class="da-button red left"></a>';
					 img_item += "<br/><input name=\"images["+img_length+"][order]\"  id=\"images["+img_length+"][order]\" type=\"text\" class=\"showImageOrder\" value=\""+img_length+"\" style=\"width:40px;\" />";
                    img_item += '</td>';
                    img_item += '</tr>';
                    img_item += '</table>';
                    img_item += '</li>';
				
					 $('ul#displayImagesList').append(img_item);
					$('#countImageIndex').val(parseInt($('#countImageIndex').val())+1) ;
		});
		 prettyGallery() ;
		 sortAble();
}

function deleteGalleryImage(id,img_id){
	if(confirm('ยืนยันลบภาพนี้')){
		$('#displayImagesList').find('li#'+id).remove() ;
		$('#deletedImageList').val($('#deletedImageList').val()+','+img_id);
	}
}

function prettyGallery(){
	 $("a[rel^='prettyPhoto']").prettyPhoto();
}

function sortAble(){
	$( "#displayImagesList" ).sortable({
			placeholder: "ui-state-highlight"
		});
	$( "#displayImagesList" ).bind('sortstop',function(){
		var result = $('#displayImagesList').sortable('toArray');
		$.each(result,function(i,v){
			$('ul#displayImagesList').find('li#'+v).each(function(index, element) {
                	$(this).find('input.showImageOrder').val(i);
            });
		});
	});
}

function showSelectImageDialog(){	
	selectImages();
}