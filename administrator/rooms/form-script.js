// JavaScript Document
var modules = "rooms";
(function($) {
	$(document).ready(function(e) {
		formInit() ;
		//selectImagesAll();
		selectImages();
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
				$('#javascript').val(data.javascript);
				$('#css').val(data.css);
				$('#meta_key').val(data.meta_key);
				$('#meta_description').val(data.meta_description);
				$('#name').val(data.name);
				$('#slug').val(data.slug);
				$('#concept').val(data.concept);
				$('#description').val(data.description);
				$('#content').elrte('val', data.content);
				$('#price').val(data.price);
				$('#discount').val(data.discount);
				$('#currency').val(data.currency);
				if(data.image!=''){
					$('#image').val(data.image);
					$('#show_image').attr('src',data.image);
					$('#show_image').fadeIn('fast');
				}
				if(data.gallery!=''){
					var img_json = $.parseJSON(data.gallery);
					showImageInit(img_json) ;
				}
				if(data.facility!=''){
					var facility = $.parseJSON(data.facility);
					$.each(facility,function(i,v){
						var fac_id = 'input[name="facility[]"][value="'+v+'"]' ;
						$(fac_id).attr('checked','checked');
					});
				}
				$('#status').find('option:[value="'+data.status+'"]').attr('selected','selected');
			}else{
				loadNowCategories(0) ;
			}
	});
}

function showImageInit(img_json){
	var galleries_img = '';
	var galleries_detail = '';
	$.each(img_json,function(k,img){
					galleries_img += img.src+','
					galleries_detail += img.id+'::'+img.src+'::'+img.title+'::'+img.description+'||'  ;
					var img_name_id =  '#'+img.id+'_image_name';
					var img_description_id =  '#'+img.id+'_image_description';
					$(img_name_id).val(img.title);
					$(img_description_id).val(img.description);
		}); 
		$('#galleries_images').val(galleries_img);
		$('#galleries_all_detail').val(galleries_detail);
		addGalleryImage();
}

function addGalleryImage(){
	if($('#galleries_images').val()!=''){
		var images = $('#galleries_images').val().split(',') ;
		$.each(images,function(i,url){
			if(url!=''){
					var id = $('#galleries_images_cnt').val();
					var img  = ' <li id="'+id+'"> ';
					  img  +=  '<a href="'+url+'" rel="prettyPhoto[pp1]"><img src="'+url+'" alt="'+url+'" /> </a>';
					  img  +=  '	<span class="da-gallery-hover">';
					  img  +=  '	<ul>';
					  img  +=  '	<li class="da-gallery-update" id="a-gallery-update-'+id+'"><a href="javascript:void(0)" onclick="editImage('+id+')">แก้ไข</a></li>';
					  img  +=  '	<li class="da-gallery-delete"><a href="javascript:void(0)" onclick="deleteImage('+id+')">ลบ</a></li>';
					  img  +=  '	</ul>';
					  img  +=  '	</span>';
					   img  +=  '	</li>	';
					   img+=imgDescriptionFrom(id);
					  $('#images-list').append(img);
					  $('#galleries_images_cnt').val(parseInt(id)+1) ;
					  addImageDetail(id) ;
					  prettyGallery() ;
					  sortAble();
			}
		});
	}
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
	 var result = $('#images-list').sortable('toArray');
	 $('#galleries_sort').val(result);
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
					},
					rackrate: {
						required: true
					},
					promotion_until: {
						required: true
					},
					promotion_rate: {
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
			 //alert(data);  
			 gotoManagePage()
		 }
	});
}

function selectImagesAll(){
	if($(document).has('.elfinder-browse').length>0){
		$(document).find('.elfinder-browse').each(function(){
			 $(this).bind('click',function(){
				 var input = $(this) ;
				 opts = { 
				 url : '../../files/connectors/php/connector.php',
				 commandsOptions: {
					getfile: {
					  oncomplete: 'destroy' // destroy elFinder after file selection
					}
				  },
				 editorCallback : function(url) { alert(url);input.val(url) },
				 dialog : { title : 'Files Management'}
   			 };
				 if($(document).has('#finder').length<=0){
					$(this).append('<div id="finder"></div>');
				}
				$('#finder').elfinder(opts);
			});
		}) ;
	}
}

function selectImages(){
	 var input = $('#galleries_images');
	var f =  $('#myelfinder').elfinder({
         url : '../../files/php/connector.php',
        closeOnEditorCallback: false,
        getFileCallback: function(url) {
          var indexof = strpos ($('#galleries_images').val(), url, 0) ;
						if(indexof<0){
							if(input.val()!=''){
								input.val( input.val()+','+url)  ;
							}else{
								input.val(url)  ;
							}
							addGalleryImageOne(url) ;
						}
        }
  });
	
}

function imgDescriptionFrom(id){
	 var form = '<div id="da-ex-dialog-form-div-'+id+'" class="no-padding da-form" >';
			form += '<div class="da-form-inline"> ';
             form += '<div class="da-form-row">';
             form += '<label>ชื่อรูปภาพ</label> ';
             form += '<div class="da-form-item large"> ';
             form += '<input type="text" name="'+id+'_image_name" id="'+id+'_image_name" /> ';
             form += '</div> ';
             form += '</div> ';
             form += '<div class="da-form-row"> ';
             form += '<label>คำอธิบาย</label> ';
             form += '<div class="da-form-item large"> ';
             form += '<textarea rows="2" cols="auto" name="'+id+'_image_description" id="'+id+'_image_description"></textarea>';
             form += '</div> ';
             form += '</div></div> ';
		  return form ;
}

function strpos (haystack, needle, offset) {
  var i = (haystack+'').indexOf(needle, (offset || 0));
  return i === -1 ? -1 : i;
}

function addGalleryImageOne(url){
		var id = $('#galleries_images_cnt').val();
		var img  = ' <li id="'+id+'"> ';
		  img  +=  '<a href="'+url+'" rel="prettyPhoto[pp1]"><img src="'+url+'" alt="'+url+'" /> </a>';
		  img  +=  '	<span class="da-gallery-hover">';
		  img  +=  '	<ul>';
		  img  +=  '	<li class="da-gallery-update" id="a-gallery-update-'+id+'"><a href="javascript:void(0)" onclick="editImage('+id+')">แก้ไข</a></li>';
		  img  +=  '	<li class="da-gallery-delete"><a href="javascript:void(0)" onclick="deleteImage('+id+')">ลบ</a></li>';
		  img  +=  '	</ul>';
		  img  +=  '	</span>';
		   img  +=  '	</li>	';
		   img+=imgDescriptionFrom(id);
		  $('#images-list').append(img);
		  $('#galleries_images_cnt').val(parseInt(id)+1) ;
		  addImageDetail(id) ;
		  prettyGallery() ;
		  sortAble();
}

function deleteImage(id){
	var img_src = $('#'+id).find('a img').attr('src');
	var img_name =$('#galleries_images').val().replace(img_src,'');
	var img_new_name = img_name.replace(',,',',');
	$('#galleries_images').val(img_new_name) ;
	$('#images-list').find('#'+id).fadeOut('slow',function(){
			$(this).remove() ;
	}) ;
}

function prettyGallery(){
	$(".da-gallery.prettyPhoto ul li a[rel^='prettyPhoto']").prettyPhoto();
}

function addImageDetail(id){
	var dialog_id = "#da-ex-dialog-form-div-"+id
 	$(dialog_id).dialog({
			autoOpen: false, 
			title: "UI Dialog Modal Form", 
			modal: true, 
			width: "500", 
			buttons: [{
					text: "ตกลง", 
					click: function() {
						var title_id = '#'+id+'_image_name' ;
						var description_id = '#'+id+'_image_name' ;
						var detail  =  $('#galleries_all_detail').val()+id+'::'+$('#'+id).find('a img').attr('src')+'::'+$(title_id).val()+'::'+$(description_id).val()+'||';
						$('#galleries_all_detail').val(detail) ;
						$('#'+id).find('a img').attr('alt',$(title_id).val())
						$( this ).dialog( "close" );
					}}]
		});	
}

function editImage(id){
		var edit_bt = '#da-gallery-update-'+id;
		var div_id = '#da-ex-dialog-form-div-'+id;
	//		$(edit_bt).bind("click", function(event) {
			$(div_id).dialog("option", {modal: true}).dialog("open");
			event.preventDefault();
	//	});	
}

function sortAble(){
	$( "#images-list" ).sortable({
			placeholder: "ui-state-highlight"
		});
	$( "#images-list" ).sortable({
			cancel: "#0"
		});
	$( "#images-list" ).disableSelection();
	$( "#images-list" ).bind('sortstop',function(){
		var result = $('#images-list').sortable('toArray');
		$('#galleries_sort').val(result) ;
	});
}