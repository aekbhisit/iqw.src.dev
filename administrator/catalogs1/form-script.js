// JavaScript Document
var modules = "catalogs";
(function($) {
	$(document).ready(function(e) {
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
        cssfiles : ['plugins/elrte/css/elrte-inner.css'],
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

function show_maps_attr(id){
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
						 saveChangeCategory();
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
						zoom: 5 ,
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
