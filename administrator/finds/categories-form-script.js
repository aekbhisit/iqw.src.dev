// JavaScript Document
var modules = "finds";
(function($) {
	$(document).ready(function(e) {
		categoryFormInit() ;
		//selectImages() ;
		showFindQueryDialog();
		$.jGrowl("แจ้งเตือน ! <br> โหลดข้อมูลเสร็จแล้วพร้อมแก้ไข", {position: "bottom-right"});
	});// document_ready
}) (jQuery);

function gotoManagePage(){
	var d= new Date();
		var url = "../../app/index.php?module=finds&task=generateQueryFile&d"+d.getTime() ;
		$.get(url, function(data){
			var url = 'categories.php'; 
			window.location.replace(url);
		});
}

function categoryFormInit(){
	var d = new Date();
	var request = window.location.search.replace('?','') ;
	var url = "../../app/index.php?module="+modules+"&task=categoryFormInit&"+request+"&d"+d.getTime() ;
	$.getJSON(url,function(data){
			if(typeof data=='object' && data!=null){
				$('#categories_id').val(data.id);
				 loadNowCategories(data.parent_id) ;
				$('#categories_name').val(data.name);
				$('#categories_slug').val(data.slug);
				$('#categories_layout').val(data.layout);
				 initSelectedFindQuery(data.finds);
				$('#categories_status').find('option:[value="'+data.status+'"]').attr('selected','selected');
			}else{
				loadNowCategories(0) ;
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
		$('#categories_parent').html(options_list) ;
		$('#categories_parent').removeAttr('disabled') ;
	});
}

function setSaveCategories(){
	var d = new Date();	
	var url = "../../app/index.php?module="+modules+"&task=saveCategory&d"+d.getTime() ;
	$.ajax({
		  type: 'POST', 
		  url: url, 
		  enctype: 'multipart/form-data', 
		  data: $('#categories_form').serialize(),
		  beforeSend: function() {
				$('#categories_form').validate({ 
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
						$("#category-form-error").html(message).show();
					} else {
						$("#form-error").hide();
					}
				}
				 });
				return $('#categories_form').valid();
			  },
		  success: function(data){
			 gotoManagePage();
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

function showFindQueryDialog(){
	configFindQuerDialog();
	$("#da-dialog-find-query").bind("click", function(event) {
			loadFindQueryTableData()	;
			$("#da-dialog-div-query").dialog("option", {modal: false}).dialog("open");
			event.preventDefault();
	});	
}

function configFindQuerDialog(){
	$("#da-dialog-div-query").dialog({
			autoOpen: false, 
			title: "เลือกข้อมูลที่ต้องการ", 
			modal: true, 
			width: "80%",
			height:'500',
			buttons: [{
					text: "บันทีก", 
					click: function() {
						saveSelectedFindQuery();
						 oTable.fnDestroy();
						$( this ).dialog( "close" );
					}}]
		});	
}

function loadFindQueryTableData(){
	 var df_table =  '<table id="da-ex-datatable-query" class="da-table"> <thead> <tr> <th width="40">ลำดับ</th> <th>ชื่อ</th><th>Module</th><th>Task</th><th>Type</th><th>Key</th><th width="38">#ID</th></tr> </thead> <tbody></tbody></table>';
	 $("#da-ex-datatable-query-container").html(df_table);	 
		oTable = $("table#da-ex-datatable-query").dataTable({
			"sPaginationType": "full_numbers",
       		 "bProcessing": true,
        	"bServerSide": true,
			"bStateSave": true,
        	"sAjaxSource": "../../app/index.php?module=finds&task=getFindQueryInRoute",
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

function saveSelectedFindQuery(){
	var table_id =  '#da-ex-datatable-query'; 
	$(table_id).find('input.table_checkbox:checked').each(function(i,u){
		var tr_id =  'tr#fineQuerySelectedIDTR'+$(u).val() ;
		if($(document).find(tr_id).length==0){
			 var showName_id = '#showName_'+$(u).val() ;
			 var showSlug_id = '#showModule_'+$(u).val() ;
			 var listSelected = '<tr id="fineQuerySelectedIDTR'+$(u).val()+'" style="height:35px;"  >';
					listSelected +='<td width="20%"><input name="findQuerySelectedID['+$(u).val()+']" id="findQuerySelectedID['+$(u).val()+']" type="text" style="width:95%" placeholder="ID" value="'+$(u).val()+'" /></td>';
					listSelected +='<td width="32%"><input name="showSlug" id="showSlug['+$(u).val()+']" type="text" style="width:95%" placeholder="Slug" value="'+$(showSlug_id).val()+'"  readonly="readonly" /></td>';
					listSelected +='<td ><input name="showSlugName[0]" id="showSlugName['+$(u).val()+']" type="text" style="width:95%" placeholder="Name" value="'+$(showName_id).val()+'"  readonly="readonly"  /> </td>'; 
					listSelected +='<td width="10%"><input name="removeFineQuerySelectedID'+$(u).val()+'" id="removeFineQuerySelectedID'+$(u).val()+'" type="button"  value="ลบ" class="defaultFieldRemoveBtn da-button red" onclick="removeFineQuerySelected('+$(u).val()+')" /></td>';
					listSelected += '</tr>';
				$('#findQuerySelected').append(listSelected);
		}
	});
	$('#showFindQuerySelectedContainer').fadeIn();
}

function initSelectedFindQuery(finds){
	$(finds).each(function(index, element) {
		var tr_id =  'tr#fineQuerySelectedIDTR'+element.find_id ;
		if($(document).find(tr_id).length==0){
			 var listSelected = '<tr id="fineQuerySelectedIDTR'+element.find_id+'" style="height:35px;"  >';
					listSelected +='<td width="20%"><input name="findQuerySelectedID['+element.find_id+']" id="findQuerySelectedID['+element.find_id+']" type="text" style="width:95%" placeholder="ID" value="'+element.find_id+'" /></td>';
					listSelected +='<td width="32%"><input name="showSlug" id="showSlug['+element.find_id+']" type="text" style="width:95%" placeholder="Slug" value="'+element.name+'"  readonly="readonly" /></td>';
					listSelected +='<td ><input name="showSlugName[0]" id="showSlugName['+element.find_id+']" type="text" style="width:95%" placeholder="Name" value="'+element.module+'"  readonly="readonly"  /> </td>'; 
					listSelected +='<td width="10%"><input name="removeFineQuerySelectedID'+element.find_id+'" id="removeFineQuerySelectedID'+element.find_id+'" type="button"  value="ลบ" class="defaultFieldRemoveBtn da-button red" onclick="removeFineQuerySelected('+element.find_id+')" /></td>';
					listSelected += '</tr>';
				$('#findQuerySelected').append(listSelected);
		}
	});
	$('#showFindQuerySelectedContainer').fadeIn();
}

function removeFineQuerySelected(id){
	if(confirm('ยืนยันลบรายการที่เลือก')){
		var tr_id = '#fineQuerySelectedIDTR'+id;
		$(tr_id).remove();
	}
}
