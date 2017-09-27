// JavaScript Document
var modules = "menus";
(function($) {
	$(document).ready(function(e) {
		formInit() ;
		modalMenusInit();
		$.jGrowl("แจ้งเตือน ! <br> โหลดข้อมูลเสร็จแล้วพร้อมแก้ไข", {position: "bottom-right"});
	});// document_ready
}) (jQuery);

function gotoManagePage(){
	var url = 'index.php'; 
	window.location.replace(url);
}

function nestableOnChangeInit(category_id){
	var updateOutput = function(e){
		// console.log(e);
		// console.log(e.delegateTarget.id);
		var id = e.delegateTarget.id;
		var widget = $('#'+id).parent('#widgetInsertMenu');
		var json = widget.find('input[name="savevaljson"]');
		var list = $('div#'+id+'.dd');
		
		if(window.JSON){
			json.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));

			var tree = encodeURI(json.val());
			//console.log(tree);
			var d = new Date();
			var url = "../../app/index.php?module="+modules+"&task=setUpdateMenuSort&category_id="+category_id+"&d"+d.getTime() ;
			$.post(url,{'tree':tree},function(data){
			});
			
		}else{
			$('#savevaljson'+category_id).output.val('JSON browser support required for this demo.');
		}
	};
	$('#nestable_list').nestable({
		group: 0,
		maxDepth: 4
	}).on('change', updateOutput);
}

function modalMenusInit(){

	var modal = document.getElementById('addMenuModal');

	$('#addMenus').click(function(){
		$('#addMenuModal').css('display','block');
		$('#menuAddName').val("");
		$('#menuExLink').val("");
		$('#wpMenuList').fadeIn(1);
		$('#exMenuLink').fadeOut(1);
		getPagesAllList();
	});

	$('#closeModal').click(function(){
		$('#addMenuModal').css('display','none');
	});

	$('#menuResetBtn').click(function(){
		$('#addMenuModal').css('display','none');
	});

	$(window).click(function(event){
		if (event.target == modal) {
			$('#addMenuModal').css('display','none');
		}
	});

	var modalTranslate = document.getElementById('translateMenuModal');

  //   $('#addMenus').click(function(){
  //   	$('#addMenuModal').css('display','block');
  //   	$('#menuAddName').val("");
  //   	$('#menuExLink').val("");
  //   	$('#wpMenuList').fadeIn(1);
		// $('#exMenuLink').fadeOut(1);
  //   	getPagesAllList();
  //   });

  $('#menuTranslateResetBtn').click(function(){
  	$('#translateMenuModal').css('display','none');
  });

  $(window).click(function(event){
  	if (event.target == modalTranslate) {
  		$('#translateMenuModal').css('display','none');
  	}
  });
}

function initGetList(category_id){
	var d = new Date();	
	var url = "../../app/index.php?module="+modules+"&task=getAllCategoryNestList&category_id="+category_id+"&d"+d.getTime() ;
	var updateOutput = '';
	$.get(url,function(data){
		// $('#nestable_list_'+category_id).html('');
		// $('#nestable_list_'+category_id).append(data);
		$('#nestable_list').html('');
		$('#nestable_list').append(data);
		
	});
}

function formInit(){
	var d = new Date();
	var request = window.location.search.replace('?','') ;
	var url = "../../app/index.php?module="+modules+"&task=formInit&"+request+"&d"+d.getTime() ;
	$.getJSON(url,function(data){ 
		if(typeof data=='object' && data!=null){
			$('#id').val(data.cate_id);
			$('#hprimaryid').val(data.cate_id);
			$('#cate_id').val(data.cate_id);
			$('#savevaljson').val(data.cate_id);
			$('#categories_name').val(data.title);
			// $('#categories_name').html(data.title);
			nestableOnChangeInit(data.cate_id);
			initGetList(data.cate_id);
		}else{
			gotoManagePage();
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
	var url = "../../app/index.php?module="+modules+"&task=saveCategoryMenu&d"+d.getTime() ;
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
					categories_name: {
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

function SaveAddMenuData(){

	var result = true;

	if($('#menuAddName').val() == ""){
		$('#menuAddName').css("background-color","rgb(255, 191, 191)");
		result = false;
	}else{
		$('#menuAddName').css("background-color","#fcfcfc");
	}

	if(result == true){

		var formData = $('#menuAddForm');

		var d = new Date();	
		var url = "../../app/index.php?module="+modules+"&task=setSaveMenuData&d"+d.getTime() ;
		$.ajax({
			type: 'POST', 
			url: url, 
			enctype: 'multipart/form-data', 
			data: formData.serialize(),
			success: function(data){
				switch(data){
					case '0':
					$.jGrowl("เพิ่มเมนูสำเร็จ ! <br> เมนูใหม่ถูกเพิ่มแล้ว", {position: "bottom-right"});
					initGetList($('#cate_id').val());
					$('#menuAddName').val("");
					$('#menuAddType').val(0);
					$('#addMenuModal').css('display','none');

					// noty({force: true, layout: 'bottomRight', text:$.i18n._('menus_i_insert_data_completed'),  type: 'success',  timeout: 700, modal: true, callback: {
					// 	afterClose: function() { 
					// 		// $(event).parent('div').parent('div').parent('div').find('div.dd').html("");
					// 		initGetList($('#cate_id').val());
					// 		// loadaddmenubtn(event);
					// 		// gototoprow(event);
					// 		// $(event).remove();
					// 	}
					// }});
					break; 
					case '1':
					$.jGrowl("อัพเดทเมนูสำเร็จ ! <br> เมนูใหม่ถูกอัพเดทแล้ว", {position: "bottom-right"});
					initGetList($('#cate_id').val());
					$('#menuAddName').val("");
					$('#menuAddType').val(0);
					$('#addMenuModal').css('display','none');
					// noty({force: true, layout: 'bottomRight',  text:$.i18n._('menus_i_update_data_completed'),  type: 'success',  timeout: 700, modal: true, callback: {
					// 	afterClose: function() { 
					// 		$(event).parent('div').parent('div').parent('div').find('div.dd').html("");
					// 		initGetList($(event).find('input#group_form').val());
					// 		loadaddmenubtn(event);
					// 		gototoprow(event);
					// 		$(event).remove();
					// 	}
					// }});
					break; 
					default:
					$.jGrowl("ผิดพลาด ! <br> ไม่สามารถแก้ไขข้อมูลได้ กรุณาลองใหม่", {position: "bottom-right"});
					break;
				}
			}
		});
		return false;
	}else{
		return false;
	}
}

function setUpdateMenusStatus(id,status,ui){
	var d = new Date();	
	var url = "../../app/index.php?module="+modules+"&task=setUpdateMenusStatus&id="+id+"&status="+status+"&d"+d.getTime() ;
	$(ui).blur();
	$.get(url,function(data){
		switch(data){
			case '0':
			if(status==0){
				$('#btn_status_on_'+id).hide();	
				$('#btn_status_off_'+id).fadeIn(1);	
			}else{
				$('#btn_status_off_'+id).hide();	
				$('#btn_status_on_'+id).fadeIn(1);	
			}
			break; 
			default:
			$.jGrowl("ผิดพลาด ! <br> ไม่สามารถแก้ไขข้อมูลได้ กรุณาลองใหม่", {position: "bottom-right"});
			break;
		}
	});
}

function getPagesAllList(pages_id){
	var d = new Date();	
	var url = "../../app/index.php?module="+modules+"&task=getAllPagesList&pages_id="+pages_id+"&d"+d.getTime() ;
	$.get(url,function(data){
		$('#menuPagesList').html(data);
	});
}

function setChangeMenuType(type){
	switch(type){
		case 1 :
		case '1' :
		$('#wpMenuList').fadeOut(1);
		$('#exMenuLink').fadeIn(1);
		break;

		default :
		$('#wpMenuList').fadeIn(1);
		$('#exMenuLink').fadeOut(1);
		break;
	}
}

function setEditMenu(menu_id){
	var d = new Date();	
	var url = "../../app/index.php?module="+modules+"&task=initMenu&id="+menu_id+"&d"+d.getTime() ;
	$.getJSON(url,function(data){ 
		if(typeof data=='object' && data!=null){
			$('#menu_id').val(data.menu_id);
			$('#menuAddName').val(data.title);
			$('#menuAddType').val(data.type);
			$('#menuAddTarget').val(data.target);
			$('#hID').val(data.parent_id);
			getPagesAllList(data.module_content_id);
			$('#menuExLink').val(data.link);
			$('#menuAddStatus').val(data.status);
			$('#addMenuModal').css('display','block');
			setChangeMenuType(data.type);
		}
	});
}

function setDeleteMenu(id,event){
	var category_id = $('#cate_id').val();
	var menu_name = $('#menu_title_'+id).html();
	if(category_id!=0 && category_id!=null){
		$(event).blur();
		if (confirm("ยืนยันการลบเมนู "+menu_name+' ?') == true) {
			var d = new Date();	
			var url = "../../app/index.php?module="+modules+"&task=setDeleteMenus&id="+id+"&d"+d.getTime() ;
			
			$.get(url,function(data){
				switch(data){
					case '0':
					$.jGrowl("ลบเมนูสำเร็จ ! <br> เมนูถูกลบแล้ว", {position: "bottom-right"});
					initGetList(category_id);
					break; 
					case '1':
					$.jGrowl("ผิดพลาด ! <br> ไม่สามารถลบเมนูนี้ได้ เนื้องจากมีเมนูย่อยอยู่่", {position: "bottom-right"});
					break;
					default:
					$.jGrowl("ผิดพลาด ! <br> ไม่สามารถลบข้อมูลได้ กรุณาลองใหม่", {position: "bottom-right"});
					break;
				}
			});
		}
	}else{
		$.jGrowl("ผิดพลาด ! <br> ไม่สามารถแก้ไขข้อมูลได้ กรุณาลองใหม่", {position: "bottom-right"});
	}
}

function setDuplicateMenu(id,event){
	var category_id = $('#cate_id').val();
	if(id!=0 && id!=null){
		var d = new Date();	
		var url = "../../app/index.php?module="+modules+"&task=setDuplicateMenu&id="+id+"&category_id="+category_id+"&d"+d.getTime() ;
		$(event).blur();
		$.get(url,function(data){
			switch(data){
				case '0':
				$.jGrowl("คัดลอกเมนูสำเร็จ ! <br> เมนูถูกคัดลอกแล้ว", {position: "bottom-right"});
				initGetList(category_id);
				break; 
				// case '999':  // insert complete
				// 	_system_core_setMainBootbox_NoTADD($.i18n._('menus_i_sorry_package_limit_update'));
				// break; 
				default:
				$.jGrowl("ผิดพลาด ! <br> ไม่สามารถคัดลอกข้อมูลได้ กรุณาลองใหม่", {position: "bottom-right"});
				break;
			}
		});
	}else{
		noty({force: true,  layout:'bottomRight', text:$.i18n._('menus_i_not_duplicate_category'),  type: 'error', timeout: 1000});
	}
}


function setTranslateMenu(id,event){
	var d = new Date();	
	var url = "../../app/index.php?module="+modules+"&task=getlanguage&id="+id+"&d"+d.getTime();
	$.getJSON(url,function(data){
		$('#translateMenuModal').css('display','block');
		$('#translate_menu_id').val(id);
		$('#menuMainTitle').html(data.main_title);
		$('#menuTranslateLang').html(data.result);
		$('#menuTranslateTitle').html(data.result);
	});
}

function SaveTranslateMenuData(){

	var result = true;

	if($('#menuTranslateLang').val() == ""){
		$('#menuTranslateLang').css("background-color","rgb(255, 191, 191)");
		result = false;
	}else{
		$('#menuTranslateLang').css("background-color","#fcfcfc");
	}

	if($('#menuTranslateTitle').val() == ""){
		$('#menuTranslateTitle').css("background-color","rgb(255, 191, 191)");
		result = false;
	}else{
		$('#menuTranslateTitle').css("background-color","#fcfcfc");
	}

	if(result == true){

		var formData = $('#menuTranslateForm');

		var d = new Date();	
		var url = "../../app/index.php?module="+modules+"&task=setSaveTranslateMenuData&d"+d.getTime() ;
		$.ajax({
			type: 'POST', 
			url: url, 
			enctype: 'multipart/form-data', 
			data: formData.serialize(),
			success: function(data){
				switch(data){
					case '0':
					$.jGrowl("แปลภาษาสำเร็จ ! <br> เมนูถูกได้ถูกแปลภาษาแล้ว", {position: "bottom-right"});
					initGetList($('#hprimaryid').val());
					$('#menuTranslateTitle').val("");
					$('#menuTranslateLang').val("");
					$('#translateMenuModal').css('display','none');

					// noty({force: true, layout: 'bottomRight', text:$.i18n._('menus_i_insert_data_completed'),  type: 'success',  timeout: 700, modal: true, callback: {
					// 	afterClose: function() { 
					// 		// $(event).parent('div').parent('div').parent('div').find('div.dd').html("");
					// 		initGetList($('#cate_id').val());
					// 		// loadaddmenubtn(event);
					// 		// gototoprow(event);
					// 		// $(event).remove();
					// 	}
					// }});
					break; 
					case '1':
					$.jGrowl("อัพเดทภาษาเมนูสำเร็จ ! <br> เมนูถูกอัพเดทภาษาแล้ว", {position: "bottom-right"});
					initGetList($('#hprimaryid').val());
					$('#menuTranslateTitle').val("");
					$('#menuTranslateLang').val("");
					$('#translateMenuModal').css('display','none');
					// noty({force: true, layout: 'bottomRight',  text:$.i18n._('menus_i_update_data_completed'),  type: 'success',  timeout: 700, modal: true, callback: {
					// 	afterClose: function() { 
					// 		$(event).parent('div').parent('div').parent('div').find('div.dd').html("");
					// 		initGetList($(event).find('input#group_form').val());
					// 		loadaddmenubtn(event);
					// 		gototoprow(event);
					// 		$(event).remove();
					// 	}
					// }});
					break; 
					default:
					$.jGrowl("ผิดพลาด ! <br> ไม่สามารถแก้ไขข้อมูลได้ กรุณาลองใหม่", {position: "bottom-right"});
					break;
				}
			}
		});
		return false;
	}else{
		return false;
	}

}

function setChangeLangTranslate(lang){
	var id = $('#translate_menu_id').val();
	var d = new Date();	
	var url = "../../app/index.php?module="+modules+"&task=setChangeLangTranslate&id="+id+"&lang="+lang+"&d"+d.getTime();
	$.getJSON(url,function(data){
		if(lang != ""){
			$('#menuTranslateTitle').val(data.translate_title);
		}else{
			$('#menuTranslateTitle').val("");
		}
		
	});
}