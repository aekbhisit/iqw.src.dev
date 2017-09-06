// JavaScript Document
var modules = "catalogs";
var editor_class = "";
(function($) {
	$(document).ready(function(e) {
	//	alert('script ready');
		addAttribute() ;
		formInit() ;
		//selectImagesAll();
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
			console.log(data);
			if(typeof data=='object' && data!=null){
				$('#id').val(data.id);
				 loadNowCategories(data.category_id) ;
				 loadFormAttr(data.id);
				$('#javascript').val(data.javascript);
				$('#css').val(data.css);
				$('#meta_key').val(data.meta_key);
				$('#meta_description').val(data.meta_description);
				$('#name').val(data.name);
				$('#slug').val(data.slug);
				$('#status').find('option:[value="'+data.status+'"]').attr('selected','selected');
			}else{
				loadNowCategories(0) ;
				loadFormAttr(0);
			}
	});
}

function loadFormAttr(catalog_id){

	var d = new Date();
	var category_id = $('#categories').val();
	if(category_id==0){
		var url = "../../app/index.php?module="+modules+"&task=getAttrField&category_id="+category_id+"&catalog_id=0&d"+d.getTime() ;
		$.get(url,function(data){
			// console.log(data) ;
			$('#rowCatalogsSlug').after(data);
			attrTextEditor();
			attrInputBrowserFile();
		});
	}else{
		var url = "../../app/index.php?module="+modules+"&task=getAttrField&category_id="+category_id+"&catalog_id="+catalog_id+"&d"+d.getTime() ;
		$.get(url,function(data){
			$('#rowCatalogsSlug').after(data);
			attrTextEditor();
			attrInputBrowserFile();
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
		// $('#categories').bind('change',function(){
		// 	loadFormAttr($('#id').val()) ;
		// });
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

function addAttribute(label,require,note,attr){
	if(require){
		var requ = '<span class="required">*</span>';
	}else{
		var requ = '';
	}
 	var field = '<div class="da-form-row"><label>'+label+''+requ+'</label><div class="da-form-item small"><span class="formNote">'+note+'</span>'+attr+'</div></div>' ;
	$('#attribute-zone').before(field);
}

function attrTextEditor(){
	var dialog ;
	 var opts = {
        cssClass : 'el-rte',
        toolbar  : 'maxi',
        cssfiles : [
        	'plugins/elrte/css/elrte-inner.css',
        	'../../themes/phet_th/vendor/bootstrap/css/bootstrap.min.css',
			'../../themes/phet_th/vendor/fontawesome/css/font-awesome.min.css',
			'../../themes/phet_th/vendor/animateit/animate.min.css',
			'../../themes/phet_th/vendor/owlcarousel/owl.carousel.css',
			'../../themes/phet_th/vendor/magnific-popup/magnific-popup.css',
			'../../themes/phet_th/css/theme-base.css',
			'../../themes/phet_th/css/theme-elements.css',
			'../../themes/phet_th/css/responsive.css',
			'../../themes/phet_th/css/color-variations/red.css',
			'../../themes/phet_th/css/style.css'
        	],
		fmAllow: true,
		fmOpen: function(callback) {
		//	if (!dialog) {
			  // create new elFinder
			  dialog = $('<div if="finder" />').dialogelfinder({
				url: FILE_ROOT+'files/php/connector.php',
				commandsOptions: {
				  getfile: {
					oncomplete : 'destroy' // close/hide elFinder
				  }
				},
				getFileCallback: callback // pass callback to file manager
			  });
		  }	
	 }
	 $(document).find('.attrs').each(function(i,u){
		if($(this).has('.elrte').length>=1){
			$(this).find('.elrte').each(function(){
				$(this).elrte(opts);
				// console.log(183);
				// console.log( $(this).find("iframe").attr('style') ); //.addClass('grand') ;
			});
		}
	});
}

function attrInputBrowserFile(){
	 $(document).find('.attrs').each(function(){
		$(this).find('.elfinder-browse').each(function(){
		 $(this).bind('click',function () {
					var input = $(this) ;
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
	});
}

function show_maps_attr(id,ele){
	var maps_container = '#maps_container_'+id ;
	var map_container_panel = '#map_container_panel_'+id ;
	$(maps_container).dialog({
			autoOpen: false, 
			title: "เลือกแผนที่", 
			modal: true, 
			width: "80%",
			height:'500',
			buttons: [{
					text: "ตกลง", 
					click: function() {
						var maps_lat_long = $('#maps_latitude_'+id).val()+','+$('#maps_longitude_'+id).val() ;
						$(ele).val(maps_lat_long) ;
						var myMap  = $('#google_maps_container_'+id);
						$(myMap).setmap('destroy');
						$( this ).dialog( "close" );
					}}]
		});	
	setMaps(id);
    $(maps_container).dialog("option", {modal: false}).dialog("open");
	event.preventDefault();	
}

function setMaps(id){
				var myMap  = $('#google_maps_container_'+id);
				myMap.setmap({
					components: true,
					map: {
						zoom: 12 ,
						type:'roadmap'
					},
					marker: {
						current: false,
						position: {
							latitude: $('#maps_latitude_'+id).val(),
							longitude: $('#maps_longitude_'+id).val()
						}
					},
					drop: function ( latitude, longitude, components ) {
						$('#maps_latitude_'+id).val(latitude);
						$('#maps_longitude_'+id).val(longitude);
					}
				});
				
				$('#maps_search_button_'+id).bind('click', function () {
					$('#google_maps_container_'+id).setmap('setAddress', $('#maps_search_'+id).val(), function ( latitude, longitude, fullAddress ) {
						$('#maps_latitude_'+id).val(latitude);
						$('#maps_longitude_'+id).val(longitude);
						$('#maps_search_'+id).val(fullAddress);
					});
				});
} 
// gallery script
function showImageInit(attr_id,img_json){
	img_length = img_json.length ;
	$.each(img_json,function(i,img){
			var img_length = $('#countImageIndex_'+attr_id).val();
			var show_length = parseInt(img_length)+1;
			
			var show_image_name_arr =  url.split('/') ;
			var show_image_name = show_image_name_arr[show_image_name_arr.length-1] ;
			
			var img_item = '<li id="'+attr_id+'_'+img_length+'" style="list-style:none"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
                    img_item += '<tr>';
                    img_item += ' <td width="120" height="120" align="center" valign="middle"><a href="'+url+'" rel="prettyPhoto"><img src="'+url+'"  style="width:100px; height:100px; margin-top:5px; border:#ccc 1px solid; border-radius:10px;"/></a></td>';
                    img_item += '<td>';
				    img_item += "<input name=\"attrValue["+attr_id+"]["+img_length+"][id]\"  id=\"attrValue["+attr_id+"]["+img_length+"][id]\" type=\"hidden\" />";
                    img_item += "<input name=\"attrValue["+attr_id+"]["+img_length+"][src]\"  id=\"attrValue"+attr_id+"]["+img_length+"][src]\" type=\"hidden\" value=\""+url+"\" />";
                    img_item += "<label  style=\"height:20px; font-size:90%; padding:0px;\">ชื่อภาพ ("+show_image_name+")</label><input name=\"attrValue["+attr_id+"]["+img_length+"][title]\"  id=\"attrValue["+attr_id+"]["+img_length+"][title]\" type=\"text\" style=\"margin-bottom:5px;\" />";
                    img_item += "<label  style=\"height:20px; font-size:90%; padding:0px\">คำอธิบายภาพ</label><input name=\"attrValue["+attr_id+"]["+img_length+"][description]\"  id=\"attrValue["+attr_id+"]["+img_length+"][description]\" type=\"text\" />";
                    img_item += '</td>';
                    img_item += '<td style="width:50px; padding-left:20px; padding-top:20px;" align="center" valign="middle">';
                    img_item += '<a href="javascript:void(0)" onclick="deleteGalleryImage('+attr_id+','+img_length+','+img_length+');"><input type="button" value="ลบ" class="da-button red left"></a>';
					 img_item += "<br/><input name=\"attrValue["+attr_id+"]["+img_length+"][order]\"  id=\"attrValue["+attr_id+"]["+img_length+"][order]\" type=\"text\" class=\"showImageOrder\" value=\""+show_length+"\" style=\"width:40px; margin-top:5px;\"  />";
                    img_item += '</td>';
                    img_item += '</tr>';
                    img_item += '</table>';
                    img_item += '</li>';
				
					 $('ul#displayImagesList').append(img_item);
					$('#countImageIndex').val(parseInt($('#countImageIndex').val())+1) ;
		});
		 prettyGallery() ;
		 sortAble(attr_id);
}
function selectImages(attr_id){
		if($(document).has('#finder_'+attr_id).length<=0){
			$('#displayImagesList_'+attr_id).after('<div id="finder"></div>');
		}
		  dialog = $('<div id="finder_"'+attr_id+' />').dialogelfinder({
		  url : '../../files/php/connector.php',
		  commandsOptions: {
		  getfile: {
			  	multiple : true,
				oncomplete: 'destroy' 
			}
			},
				getFileCallback: function(file){
						addGalleryImage(file,attr_id)
				} // pass callback to file manager
	});
}


function addGalleryImage(files,attr_id){
		$.each(files,function(i,url){
			var img_length = $('#countImageIndex_'+attr_id).val();
			var show_length  = parseInt(img_length)+1;
			var show_image_name_arr =  url.split('/') ;
			var show_image_name = show_image_name_arr[show_image_name_arr.length-1] ;
			var img_item = '<li id="'+attr_id+'_'+img_length+'" style="list-style:none"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
                    img_item += '<tr>';
                    img_item += ' <td width="120" height="120" align="center" valign="middle"><a href="'+url+'" rel="prettyPhoto"><img src="'+url+'"  style="width:100px; height:100px; margin-top:5px; border:#ccc 1px solid; border-radius:10px;"/></a></td>';
                    img_item += '<td>';
                    img_item += "<input name=\"attrValue["+attr_id+"]["+img_length+"][src]\"  id=\"attrValue["+attr_id+"]["+img_length+"][src]\" type=\"hidden\" value=\""+url+"\" />";
                    img_item += "<label  style=\"height:20px; font-size:90%; padding:0px; width:100%\">ชื่อภาพ ("+show_image_name+")</label><input name=\"attrValue["+attr_id+"]["+img_length+"][title]\"  id=\"attrValue["+attr_id+"]["+img_length+"][title]\" type=\"text\" style=\"margin-bottom:5px;\" />";
                    img_item += "<label  style=\"height:20px; font-size:90%; padding:0px\">คำอธิบายภาพ</label><input name=\"attrValue["+attr_id+"]["+img_length+"][description]\"  id=\"attrValue["+attr_id+"]["+img_length+"][description]\" type=\"text\" />";
                    img_item += '</td>';
                    img_item += '<td style="width:50px; padding-left:20px; padding-top:20px;" align="center" valign="middle">';
                    img_item += '<a href="javascript:void(0)" onclick="deleteGalleryImage('+attr_id+','+img_length+','+img_length+');"><input type="button" value="ลบ" class="da-button red left"></a>';
					 img_item += "<br/><input name=\"attrValue["+attr_id+"]["+img_length+"][order]\"  id=\"attrValue["+attr_id+"]["+img_length+"][order]\" type=\"text\" class=\"showImageOrder\" value=\""+show_length+"\" style=\"width:40px; margin-top:5px;\"  />";
                    img_item += '</td>';
                    img_item += '</tr>';
                    img_item += '</table>';
                    img_item += '</li>';
				
					 $('ul#displayImagesList_'+attr_id).append(img_item);
					$('#countImageIndex_'+attr_id).val(parseInt($('#countImageIndex_'+attr_id).val())+1) ;
		});
		 prettyGallery() ;
		 sortAble(attr_id);
}

function deleteGalleryImage(attr_id,id,img_id){
	if(confirm('ยืนยันลบภาพนี้')){
		$('#displayImagesList_'+attr_id).find('li#'+attr_id+'_'+id).remove() ;
		$('#deletedImageList_'+attr_id).val($('#deletedImageList_'+attr_id).val()+','+img_id);
	}
}

function prettyGallery(){
	 $("a[rel^='prettyPhoto']").prettyPhoto();
}

function sortAble(attr_id,chk){
if(chk==1){
	$('#attr_gallery_sort_'+attr_id).hide();
}
 var chk_gallery = $(document).has("#displayImagesList_"+attr_id ).length ;
	if(chk_gallery>=1){
	$( "#displayImagesList_"+attr_id ).sortable({
			placeholder: "ui-state-highlight"
		});
	$( "#displayImagesList_"+attr_id ).bind('sortstop',function(){
		var result = $('#displayImagesList_'+attr_id).sortable('toArray');
		$.each(result,function(i,v){
			$('ul#displayImagesList_'+attr_id).find('li#'+v).each(function(index, element) {
                	$(this).find('input.showImageOrder').val(i);
            });
		});
	});
	}//if
}

function showSelectImageDialog(attr_id){	
	selectImages(attr_id);
}

function setShowFormGroupSet(group,set,type){
	if(type=='show'){
		var next = set+1 ;
		$('.da-form-row.attrs.group.'+group+'[data-set="'+next+'"]').removeClass('hide') ;
	}else{
		if(set>1){
			$('.da-form-row.attrs.group.'+group+'[data-set="'+set+'"]').addClass('hide') ;
		}
	}
	
}