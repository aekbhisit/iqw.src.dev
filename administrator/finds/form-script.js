// JavaScript Document
var modules = "finds";
var oTable;
(function($) {
	$(document).ready(function(e) {
		formInit();
		$.jGrowl("แจ้งเตือน ! <br> โหลดข้อมูลเสร็จแล้วพร้อมแก้ไข", {position: "bottom-right"});
		showFindOndDialog();
		showFindCategoryDialog();
	});// document_ready
}) (jQuery);

function showFindOndDialog(){
	configFindOneDialog();
	$("#da-dialog-find-one").bind("click", function(event) {
		var findModule =$('#moduleList_id').find(":selected").val() ;
		loadFindOneTableData(findModule);
		$("#da-dialog-div-one").dialog("option", {modal: true}).dialog("open");
		event.preventDefault();
	});	
}
function showFindCategoryDialog(){
	configFindCategoryDialog();
	$("#da-dialog-find-cat").bind("click", function(event) {
		var findModule =$('#moduleList_id').find(":selected").val();
		loadFindCategoryTableData(findModule);
		$("#da-dialog-div-cat").dialog("option", {modal: false}).dialog("open");
		event.preventDefault();
	});	
}
function configFindOneDialog(){
	$("#da-dialog-div-one").dialog({
		autoOpen: false, 
		title: "เลือกรายการที่ต้องการ", 
		modal: true, 
		width: "80%",
		height:'500',
		buttons: [{
		text: "บันทึก", 
		click: function() {
			saveSelectedFindOne();
			oTable.fnDestroy();
			$(this).dialog("close");			
		}}]
	});	
}
function configFindCategoryDialog(){
	$("#da-dialog-div-cat").dialog({
		autoOpen: false, 
		title: "เลือกหมวดที่ต้องการ", 
		modal: true, 
		width: "80%",
		height:'500',
		buttons: [{
		text: "บันทีก", 
		click: function() {
			saveSelectedFindCategory();
			oTable.fnDestroy();
			$(this).dialog("close");
		}}]
	});	
}

function saveSelectedFindOne(){
	var findModule =$('#moduleList_id').find(":selected").val() ;
	var table_id =  '#da-ex-datatable-'+findModule; 
	$(table_id).find('input.table_checkbox:checked').each(function(i,u){
		var tr_id =  'tr#fineOneSelectedIDTR'+$(u).val() ;
		if($(document).find(tr_id).length==0){
			 var showName_id = '#showName_'+$(u).val() ;
			 var showSlug_id = '#showSlug_'+$(u).val() ;
			 var listSelected = '<tr id="fineOneSelectedIDTR'+$(u).val()+'" style="height:35px;"  >';
					listSelected +='<td width="20%"><input name="findOneSelectedID['+$(u).val()+']" id="findOneSelectedID['+$(u).val()+']" type="text" style="width:95%" placeholder="ID" value="'+$(u).val()+'" /></td>';
					listSelected +='<td width="32%"><input name="showSlug" id="showSlug['+$(u).val()+']" type="text" style="width:95%" placeholder="Slug" value="'+$(showSlug_id).val()+'" readonly="readonly" /></td>';
					listSelected +='<td ><input name="showSlugName[0]" id="showSlugName['+$(u).val()+']" type="text" style="width:95%" placeholder="Name" value="'+$(showName_id).val()+'"  readonly="readonly"  /> </td>';
					listSelected +='<td width="10%"><input name="removeFineOneSelectedID'+$(u).val()+'" id="removeFineOneSelectedID'+$(u).val()+'" type="button"  value="ลบ" class="defaultFieldRemoveBtn da-button red" onclick="removeFineOneSelectedID('+$(u).val()+')" /></td>';
					listSelected += '</tr>';
				$('#findOneSelected').append(listSelected);
		}
	});
	$('#showFindOneSelectedContainer').fadeIn();
}

function initSelectedFindOne(finds,findModule){
	//var finds = $.parseJSON(finds);
		$(finds).each(function(index, element) {
			var d = new Date();
			var json_url = "../../app/index.php?module="+findModule+"&task=loadFindOneInit&id="+element+"&d"+d.getTime() ;
			$.getJSON(json_url,function(data){
				var tr_id =  'tr#fineQuerySelectedIDTR'+element ;
				if($(document).find(tr_id).length==0){
					var listSelected = '<tr id="fineOneSelectedIDTR'+element+'" style="height:35px;"  >';
							listSelected +='<td width="20%"><input name="findOneSelectedID['+element+']" id="findOneSelectedID['+element+']" type="text" style="width:95%" placeholder="ID" value="'+element+'" /></td>';
							listSelected +='<td width="32%"><input name="showSlug" id="showSlug['+element+']" type="text" style="width:95%" placeholder="Slug" value="'+data.slug+'" readonly="readonly" /></td>';
							listSelected +='<td ><input name="showSlugName[0]" id="showSlugName['+element+']" type="text" style="width:95%" placeholder="Name" value="'+data.name+'"  readonly="readonly"  /> </td>';
							listSelected +='<td width="10%"><input name="removeFineOneSelectedID'+element+'" id="removeFineOneSelectedID'+element+'" type="button"  value="ลบ" class="defaultFieldRemoveBtn da-button red" onclick="removeFineOneSelectedID('+element+')" /></td>';
							listSelected += '</tr>';
						$('#findOneSelected').append(listSelected);
				}
			});
		});
	$('#showFindOneSelectedContainer').fadeIn();
}

function loadFindCategoryTableData(findModule){
	 var df_table =  '<table id="da-ex-datatable-'+findModule+'-cat" class="da-table"> <thead> <tr> <th width="40">ลำดับ</th> <th>ชื่อ</th><th>ระดับ</th><th>สถานนะ</th><th width="38">#ID</th></tr> </thead> <tbody></tbody></table>';
	 $("#da-ex-datatable-numberpaging-cat-container").html(df_table);	 
		oTable = $("table#da-ex-datatable-"+findModule+"-cat").dataTable({
			"sPaginationType": "full_numbers",
       		 "bProcessing": true,
        	"bServerSide": true,
			"bStateSave": true,
        	"sAjaxSource": "../../app/index.php?module="+findModule+"&task=getCategoriesDataInFinds",
			"oLanguage": {
           		 	"sLengthMenu": "แสดง _MENU_ รายการต่อหน้า",
           		 	"sZeroRecords": "ขออภัยไม่พบข้อมูล",
            		"sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
            		"sInfoEmpty": "แสดง 0 ถึิง 0 จาก 0 รายการ",
            		"sInfoFiltered": "(กรองข้อมูลจาก _MAX_ รายการทั้งหมด)",
					"oPaginate": {
       					 "sFirst": "หน้าแรก",
						  "sLast": "หน้าสุดท้าย",
						  "sNext": "ถัดไป",
						  "sPrevious": "ก่อนหน้า"
    				  },
					  "sSearch": "ค้นหา :",
					  "sLoadingRecords": "กรุณารอสักครู่ กำลังโหลดข้อมูล<div class='modal_show' onclick='reloadPageNow()'></div>",
					  "sProcessing": "กรุณารอสักครู่ กำลังโหลดข้อมูล<div class='modal_show' onclick='reloadPageNow()'></div>"
        	}
		});	
}

function saveSelectedFindCategory(){
	var findModule =$('#moduleList_id').find(":selected").val() ;
	var table_id =  '#da-ex-datatable-'+findModule+'-cat'; 
	$(table_id).find('input.table_checkbox:checked').each(function(i,u){
		var tr_id =  'tr#fineCategorySelectedIDTR'+$(u).val() ;
		if($(document).find(tr_id).length==0){
			 var showName_id = '#showName_'+$(u).val() ;
			 var showSlug_id = '#showSlug_'+$(u).val() ;
			 var listSelected = '<tr id="fineCategorySelectedIDTR'+$(u).val()+'" style="height:35px;"  >';
					listSelected +='<td width="20%"><input name="findCategorySelectedID['+$(u).val()+']" id="findCategorySelectedID['+$(u).val()+']" type="text" style="width:95%" placeholder="ID" value="'+$(u).val()+'" /></td>';
					listSelected +='<td width="32%"><input name="showSlug" id="showSlug['+$(u).val()+']" type="text" style="width:95%" placeholder="Slug" value="'+$(showSlug_id).val()+'"  readonly="readonly" /></td>';
					listSelected +='<td ><input name="showSlugName[0]" id="showSlugName['+$(u).val()+']" type="text" style="width:95%" placeholder="Name" value="'+$(showName_id).val()+'"  readonly="readonly"  /> </td>'; 
					listSelected +='<td width="10%"><input name="removeFineCategorySelectedID'+$(u).val()+'" id="removeFineCategorySelectedID'+$(u).val()+'" type="button"  value="ลบ" class="defaultFieldRemoveBtn da-button red" onclick="removeFineCategorySelectedID('+$(u).val()+')" /></td>';
					listSelected += '</tr>';
				$('#findCategorySelected').append(listSelected);
		}
	});
	$('#showFindCategorySelectedContainer').fadeIn();
}

function initSelectedFindCategory(finds,findModule){
	//var finds = $.parseJSON(finds);
	$(finds).each(function(index, element) {
		var d = new Date();
		var json_url = "../../app/index.php?module="+findModule+"&task=loadFindCategoryInit&id="+element+"&d"+d.getTime();
		$.getJSON(json_url,function(data){
			var tr_id =  'tr#fineQuerySelectedIDTR'+element;
			if($(document).find(tr_id).length==0){
				var listSelected = '<tr id="fineCategorySelectedIDTR'+element+'" style="height:35px;"  >';
					listSelected +='<td width="20%"><input name="findCategorySelectedID['+element+']" id="findCategorySelectedID['+element+']" type="text" style="width:95%" placeholder="ID" value="'+element+'" /></td>';
					listSelected +='<td width="32%"><input name="showSlug" id="showSlug['+element+']" type="text" style="width:95%" placeholder="Slug" value="'+data.slug+'"  readonly="readonly" /></td>';
					listSelected +='<td ><input name="showSlugName[0]" id="showSlugName['+element+']" type="text" style="width:95%" placeholder="Name" value="'+data.name+'"  readonly="readonly"  /> </td>'; 
					listSelected +='<td width="10%"><input name="removeFineCategorySelectedID'+element+'" id="removeFineCategorySelectedID'+element+'" type="button"  value="ลบ" class="defaultFieldRemoveBtn da-button red" onclick="removeFineCategorySelectedID('+element+')" /></td>';
					listSelected += '</tr>';
				$('#findCategorySelected').append(listSelected);
			}
		});
	});
	$('#showFindCategorySelectedContainer').fadeIn();
}

function loadFindOneTableData(findModule){
	 	var df_table = ' <table id="da-ex-datatable-'+findModule+'" class="da-table"><thead> <tr> <th width="40">ลำดับ</th><th>ชื่อ</th> <th>หมวดหมู่</th> <th>สถานนะ</th><th width="38">#ID</th> </tr> </thead><tbody></tbody></table>';
		$("#da-ex-datatable-numberpaging-container").html(df_table);
		oTable = $("table#da-ex-datatable-"+findModule).dataTable({
			"sPaginationType": "full_numbers",
       		 "bProcessing": true,
        	"bServerSide": true,
			"bStateSave": true,
        	"sAjaxSource": "../../app/index.php?module="+findModule+"&task=getDataInFinds",
			"oLanguage": {
           		 	"sLengthMenu": "แสดง _MENU_ รายการต่อหน้า",
           		 	"sZeroRecords": "ขออภัยไม่พบข้อมูล",
            		"sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
            		"sInfoEmpty": "แสดง 0 ถึิง 0 จาก 0 รายการ",
            		"sInfoFiltered": "(กรองข้อมูลจาก _MAX_ รายการทั้งหมด)",
					"oPaginate": {
       					 "sFirst": "หน้าแรก",
						  "sLast": "หน้าสุดท้าย",
						  "sNext": "ถัดไป",
						  "sPrevious": "ก่อนหน้า"
    				  },
					  "sSearch": "ค้นหา :",
					  "sLoadingRecords": "กรุณารอสักครู่ กำลังโหลดข้อมูล<div class='modal_show' onclick='reloadPageNow()'></div>",
					  "sProcessing": "กรุณารอสักครู่ กำลังโหลดข้อมูล<div class='modal_show' onclick='reloadPageNow()'></div>"
        	}
		});	
}

function gotoManagePage(){
	var d= new Date();
		var url = "../../app/index.php?module=finds&task=generateQueryFile&d"+d.getTime() ;
		$.get(url, function(data){
			var url = 'index.php'; 
			window.location.replace(url);
		});
}

function formInit(){
	var d = new Date();
	var request = window.location.search.replace('?','');
	var url = "../../app/index.php?module="+modules+"&task=formInit&"+request+"&d"+d.getTime();
	$.getJSON(url,function(data){ 
		if(typeof data=='object' && data!=null){
			$('#id').val(data.id);
			loadNowSystemModule(data.find_module);
			$('#name').val(data.find_name);
			// init find task
			$('#findTaskType').find('option:[value="'+data.find_task+'"]').attr('selected','selected');
			if(data.find_task!='find'){
				$('#task').val(data.find_task);
			}
			findTaskTypeFilter(data.find_task);
			// init find  type
			$('#findType').find('option:[value="'+data.find_type+'"]').attr('selected','selected');
			findTypeFilter(data.find_type);
			// init find Parameter
			var param_type = $.trim(data.find_key);
			//var param_type =  "param";
			switch(param_type){
				case "param":
					$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
					findParmFilter(data.find_type) ;
				break;
					case "param1+":
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					case "param2":
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_key) ;
					break;
					case "param2+":
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					case 'param3':
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					case 'param3+':
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					case 'param4':
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					case 'param4+':
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					case 'param5':
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					case 'param5+':
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					case 'param6':
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					case 'param6+':
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					case 'param7':
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					case 'param7+':
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					case 'param8':
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					case 'param8+':
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					case 'param9':
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					case 'param9+':
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					case 'param10':
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					case 'param10+':
						$('#findParameter').find('option:[value="'+data.find_key+'"]').attr('selected','selected');
						findParmFilter(data.find_type) ;
					break;
					default:
						$('#findParameter').find('option:[value="custom"]').attr('selected','selected');
						findParmFilter('custom') ;
						if(data.find_type=='one'){
							 initSelectedFindOne(data.find_key,data.find_module);
						}
						if(data.find_type=='in_category'){
						 	initSelectedFindCategory(data.find_key,data.find_module);
						}
					break;
				}// switch
				
				$('#isSlug').find('option:[value="'+data.find_is_slug+'"]').attr('selected','selected');
				$('#findStatus').find('option:[value="'+data.find_status+'"]').attr('selected','selected');
				
				// find search
				var check_param_search = data.find_search.substring(5) ; 
				switch(check_param_search){
					case "param":
						$('#searchParameter').find('option:[value="'+data.find_search+'"]').attr('selected','selected');
					break;
					case '':
						$('#searchParameter').find('option:[value="'+data.find_search+'"]').attr('selected','selected');
					break;
					default:
							$('#searchParameter').find('option:[value="'+data.find_search+'"]').attr('selected','selected');
							$('#search_key').val(data.find_search);
					break;
				}//find search
				
				$('#filter_key').val(data.find_filter);
				$('#find_order').val(data.find_order_by);
				$('#pageParameter').find('option:[value="'+data.find_paginate+'"]').attr('selected','selected');
				$('#paginateLength').val(data.find_length);
				$('#findSeparate').find('option:[value="'+data.find_separate+'"]').attr('selected','selected');
				$('#findCount').find('option:[value="'+data.find_is_count+'"]').attr('selected','selected');
				$('#status').find('option:[value="'+data.status+'"]').attr('selected','selected');
			}else{
				loadNowSystemModule(0) ;
			}
	}); // get
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

function loadNowSystemModule(selected){
	var d = new Date();
	var url = "../../app/index.php?module="+modules+"&task=getModuleList&d"+d.getTime() ;
	$.getJSON(url,function(data){
		var options_list = "";
		$.each(data,function(index,value){
			if(value.name==selected){
				options_list += '<option value="'+value.name+'" selected="selected">'+value.name+'</option>' ;
			}else{
				options_list += '<option value="'+value.name+'" >'+value.name+'</option>' ;
			}
		});
		$('#moduleList_id').html(options_list) ;
		$('#moduleList_id').removeAttr('disabled') ;
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
			 gotoManagePage();
		 }
	});
}

function findTaskTypeFilter(type){
	switch(type){
		case 'find':
			$('#findTask').fadeOut();
			$('#findTypeContainer').fadeIn();
			$('#findOneContainer').fadeOut();
			$('#findInCategoryContainer').fadeOut();
			$('#findParamContainer').fadeOut();
			$('#findIsSlugContainer').fadeOut();
			$('#findPublicStatusContainer').fadeOut();
			$('#findSearchParamContainer').fadeOut();
			$('#findFilterContainer').fadeOut();
			$('#findSearchContainer').fadeOut();
			$('#findQueryOrderContainer').fadeOut();
			$('#findPaginateContainer').fadeOut();
			$('#findPaginateLengthContainer').fadeOut();
			$('#findSeparateContainer').fadeOut();
			$('#findCountContainer').fadeOut();
		break;
		case "custom":
			$('#findTask').fadeIn();
			$('#findTypeContainer').fadeOut();
			$('#findOneContainer').fadeOut();
			$('#findInCategoryContainer').fadeOut();
			$('#findParamContainer').fadeOut();
			$('#findIsSlugContainer').fadeOut();
			$('#findPublicStatusContainer').fadeOut();
			$('#findSearchParamContainer').fadeOut();
			$('#findSearchContainer').fadeOut();
			$('#findFilterContainer').fadeOut();
			$('#findQueryOrderContainer').fadeOut();
			$('#findPaginateContainer').fadeOut();
			$('#findPaginateLengthContainer').fadeOut();
			$('#findSeparateContainer').fadeOut();
			$('#findCountContainer').fadeOut();
		break;
	}
}

function findTypeFilter(type){
	switch(type){
		case 'one':
			$('#findOneContainer').fadeOut();
			$('#findInCategoryContainer').fadeOut();
			$('#findParamContainer').fadeIn();
			$('#findIsSlugContainer').fadeOut();
			$('#findPublicStatusContainer').fadeOut();
			$('#findSearchParamContainer').fadeOut();
			$('#findSearchContainer').fadeOut();
			$('#findFilterContainer').fadeOut();
			$('#findQueryOrderContainer').fadeOut();
			$('#findPaginateContainer').fadeOut();
			$('#findPaginateLengthContainer').fadeOut();
			$('#findSeparateContainer').fadeOut();
			$('#findCountContainer').fadeOut();
		break;
		case 'in_category':
			$('#findOneContainer').fadeOut();
			$('#findInCategoryContainer').fadeOut();
			$('#findParamContainer').fadeIn();
			$('#findIsSlugContainer').fadeOut();
			$('#findPublicStatusContainer').fadeOut();
			$('#findSearchParamContainer').fadeOut();
			$('#findSearchContainer').fadeOut();
			$('#findFilterContainer').fadeIn();
			$('#findQueryOrderContainer').fadeOut();
			$('#findPaginateContainer').fadeOut();
			$('#findPaginateLengthContainer').fadeOut();
			$('#findSeparateContainer').fadeOut();
			$('#findCountContainer').fadeOut();
		break;
		case 'all':
			$('#findOneContainer').fadeOut();
			$('#findInCategoryContainer').fadeOut();
			$('#findParamContainer').fadeOut();
			$('#findIsSlugContainer').fadeOut();
			$('#findPublicStatusContainer').fadeIn();
			$('#findSearchParamContainer').fadeIn();
			$('#findSearchContainer').fadeOut();
			$('#findFilterContainer').fadeIn();
			$('#findQueryOrderContainer').fadeIn();
			$('#findPaginateContainer').fadeIn();
			$('#findPaginateLengthContainer').fadeOut();
			$('#findSeparateContainer').fadeIn();
			$('#findCountContainer').fadeIn();
		break;
		case 'category_one':
			$('#findOneContainer').fadeOut();
			$('#findInCategoryContainer').fadeOut();
			$('#findParamContainer').fadeIn();
			$('#findIsSlugContainer').fadeOut();
			$('#findPublicStatusContainer').fadeOut();
			$('#findSearchParamContainer').fadeOut();
			$('#findSearchContainer').fadeOut();
			$('#findQueryOrderContainer').fadeOut();
			$('#findPaginateContainer').fadeOut();
			$('#findPaginateLengthContainer').fadeOut();
			$('#findSeparateContainer').fadeOut();
			$('#findCountContainer').fadeOut();
		break;
		case 'category_all':
			$('#findOneContainer').fadeOut();
			$('#findInCategoryContainer').fadeOut();
			$('#findParamContainer').fadeOut();
			$('#findIsSlugContainer').fadeOut();
			$('#findPublicStatusContainer').fadeIn();
			$('#findSearchParamContainer').fadeOut();
			$('#findSearchContainer').fadeOut();
			$('#findQueryOrderContainer').fadeOut();
			$('#findPaginateContainer').fadeOut();
			$('#findPaginateLengthContainer').fadeOut();
			$('#findSeparateContainer').fadeOut();
			$('#findCountContainer').fadeOut();
		break;
		default:
			$('#findOneContainer').fadeOut();
			$('#findInCategoryContainer').fadeOut();
			$('#findParamContainer').fadeOut();
			$('#findIsSlugContainer').fadeOut();
			$('#findPublicStatusContainer').fadeIn();
			$('#findSearchParamContainer').fadeIn();
			$('#findSearchContainer').fadeOut();
			$('#findQueryOrderContainer').fadeIn();
			$('#findPaginateContainer').fadeIn();
			$('#findPaginateLengthContainer').fadeOut();
			$('#findSeparateContainer').fadeIn();
			$('#findCountContainer').fadeIn();
		break;
	}
}

function findParmFilter(findParameter){
	var type = $('#findType').val(); 
	switch(type){
		case 'one':
			switch(findParameter){
				case 'custom':
					$('#findOneContainer').fadeIn();
					$('#findInCategoryContainer').fadeOut();
					$('#findParamContainer').fadeIn();
					$('#findIsSlugContainer').fadeOut();
					$('#findPublicStatusContainer').fadeOut();
					$('#findSearchParamContainer').fadeOut();
					$('#findSearchContainer').fadeOut();
					$('#findQueryOrderContainer').fadeOut();
					$('#findPaginateContainer').fadeOut();
					$('#findPaginateLengthContainer').fadeOut();
					$('#findSeparateContainer').fadeOut();
					$('#findCountContainer').fadeOut();
				break;
				default:
					$('#findOneContainer').fadeOut();
					$('#findInCategoryContainer').fadeOut();
					$('#findParamContainer').fadeIn();
					$('#findIsSlugContainer').fadeOut();
					$('#findPublicStatusContainer').fadeOut();
					$('#findSearchParamContainer').fadeOut();
					$('#findSearchContainer').fadeOut();
					$('#findQueryOrderContainer').fadeOut();
					$('#findPaginateContainer').fadeOut();
					$('#findPaginateLengthContainer').fadeOut();
					$('#findSeparateContainer').fadeOut();
					$('#findCountContainer').fadeOut();
				break;
			}
		break;
		case 'in_category':
			switch(findParameter){
				case 'custom':
					$('#findOneContainer').fadeOut();
					$('#findInCategoryContainer').fadeIn();
					$('#findParamContainer').fadeIn();
					$('#findIsSlugContainer').fadeOut();
					$('#findPublicStatusContainer').fadeIn();
					$('#findSearchParamContainer').fadeIn();
					$('#findSearchContainer').fadeOut();
					$('#findQueryOrderContainer').fadeIn();
					$('#findPaginateContainer').fadeIn();
					$('#findPaginateLengthContainer').fadeOut();
					$('#findSeparateContainer').fadeIn();
					$('#findCountContainer').fadeIn();
				break;
				default:
					$('#findOneContainer').fadeOut();
					$('#findInCategoryContainer').fadeOut();
					$('#findParamContainer').fadeIn();
					$('#findIsSlugContainer').fadeIn();
					$('#findPublicStatusContainer').fadeIn();
					$('#findSearchParamContainer').fadeIn();
					$('#findSearchContainer').fadeOut();
					$('#findQueryOrderContainer').fadeIn();
					$('#findPaginateContainer').fadeIn();
					$('#findPaginateLengthContainer').fadeOut();
					$('#findSeparateContainer').fadeIn();
					$('#findCountContainer').fadeIn();
				break;
			}
		break;
		case 'category_one':
			switch(findParameter){
				case 'custom':
					$('#findOneContainer').fadeOut();
					$('#findInCategoryContainer').fadeIn();
					$('#findParamContainer').fadeIn();
					$('#findIsSlugContainer').fadeOut();
					$('#findPublicStatusContainer').fadeIn();
					$('#findSearchParamContainer').fadeOut();
					$('#findSearchContainer').fadeOut();
					$('#findQueryOrderContainer').fadeOut();
					$('#findPaginateContainer').fadeOut();
					$('#findPaginateLengthContainer').fadeOut();
					$('#findSeparateContainer').fadeOut();
					$('#findCountContainer').fadeOut();
				break;
				default:
					$('#findOneContainer').fadeOut();
					$('#findInCategoryContainer').fadeOut();
					$('#findParamContainer').fadeIn();
					$('#findIsSlugContainer').fadeOut();
					$('#findPublicStatusContainer').fadeIn();
					$('#findSearchParamContainer').fadeOut();
					$('#findSearchContainer').fadeOut();
					$('#findQueryOrderContainer').fadeOut();
					$('#findPaginateContainer').fadeOut();
					$('#findPaginateLengthContainer').fadeOut();
					$('#findSeparateContainer').fadeOut();
					$('#findCountContainer').fadeOut();
				break;
			}
		break;
		case 'category_all':
			switch(findParameter){
				case 'custom':
					$('#findOneContainer').fadeOut();
					$('#findInCategoryContainer').fadeIn();
					$('#findParamContainer').fadeOut();
					$('#findIsSlugContainer').fadeOut();
					$('#findPublicStatusContainer').fadeIn();
					$('#findSearchParamContainer').fadeOut();
					$('#findSearchContainer').fadeOut();
					$('#findQueryOrderContainer').fadeOut();
					$('#findPaginateContainer').fadeOut();
					$('#findPaginateLengthContainer').fadeOut();
					$('#findSeparateContainer').fadeOut();
					$('#findCountContainer').fadeOut();
				break;
				default:
					$('#findOneContainer').fadeOut();
					$('#findInCategoryContainer').fadeOut();
					$('#findParamContainer').fadeOut();
					$('#findIsSlugContainer').fadeOut();
					$('#findPublicStatusContainer').fadeIn();
					$('#findSearchParamContainer').fadeOut();
					$('#findSearchContainer').fadeOut();
					$('#findQueryOrderContainer').fadeOut();
					$('#findPaginateContainer').fadeOut();
					$('#findPaginateLengthContainer').fadeOut();
					$('#findSeparateContainer').fadeOut();
					$('#findCountContainer').fadeOut();
				break;
			}
		break;
	}
}

function findSearchParamFilter(findSearchParameter){
	switch(findSearchParameter){
		case "custom":
			$('#findSearchContainer').fadeIn();
		break;
		default:
			$('#findSearchContainer').fadeOut();
		break ;
	}
}

function findPaginateParamFilter(findPaginageParameter){
	switch(findPaginageParameter){
		case "0":
		$('#findPaginateLengthContainer').fadeOut();
		break;
		default:	
			$('#findPaginateLengthContainer').fadeIn();
		break ;
	}
}

function removeFineOneSelectedID(id){
	if(confirm('ยืนยันลบรายการที่เลือก')){
		var tr_id = '#fineOneSelectedIDTR'+id;
		$(tr_id).remove();
	}
}

function removeFineCategorySelectedID(id){
	if(confirm('ยืนยันลบรายการที่เลือก')){
		var tr_id = '#fineCategorySelectedIDTR'+id;
		$(tr_id).remove();
	}
}

