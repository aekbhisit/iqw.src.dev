// JavaScript Document
var modules = "catalogs";
(function($) {
	$(document).ready(function(e) {
		formInit() ;
		//selectImages();
		$.jGrowl("แจ้งเตือน ! <br> โหลดข้อมูลเสร็จแล้วพร้อมแก้ไข", {position: "bottom-right"});
	});// document_ready
}) (jQuery);

function gotoManagePage(){
	var url = 'attr.php'; 
	window.location.replace(url);
}

function formInit(){
	var d = new Date();
	var request = window.location.search.replace('?','') ;
	var url = "../../app/index.php?module="+modules+"&task=formAttrInit&"+request+"&d"+d.getTime() ;
	$.getJSON(url,function(data){ 
			if(typeof data=='object' && data!=null){
				$('#id').val(data.id);
				 loadNowCategories(data.category_id) ;
				$('#attr_name').val(data.attr_name);
				$('#attr_label').val(data.attr_label);
				$('#attr_type').val(data.attr_type);
				$('#attr_value').val(data.attr_value);
				$('#attr_style').val(data.attr_style);
				$('#attr_class').val(data.attr_class);
				$('#attr_placeholder').val(data.attr_placeholder);
				$('#attr').val(data.attr);
				$('#note').val(data.note);
				$('#require').find('option:[value="'+data.require+'"]').attr('selected','selected');
				$('#status').find('option:[value="'+data.status+'"]').attr('selected','selected');
				checkSelectType();
				if(data.attr_type=='select'||data.attr_type=='checkbox'||data.attr_type=='radio'){
					initFieldValueList(data.default_val)	;
				}
			}else{
				loadNowCategories(0) ;
				checkSelectType();
			}
	});
}

function loadNowCategories(selected){
	var d = new Date();
	var url = "../../app/index.php?module="+modules+"&task=loadAttrCategories&d"+d.getTime() ;
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

function setSaveAttrData(){
	var d = new Date();	
	var url = "../../app/index.php?module="+modules+"&task=saveAttrData&d"+d.getTime() ;
	$.ajax({
		  type: 'POST', 
		  url: url, 
		  enctype: 'multipart/form-data', 
		  data: $('#form').serialize(),
		  beforeSend: function() {
				$('#form').validate({ 
					rules: {
					attr_name: {
						required: true
					}, 
					attr_label: {
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

function initFieldValueList(values){
	$(values).each(function(i,v){
				$('#defaultFieldTR0').remove();
				 $('#fieldValNumber').val(v.id);
				 var list  = ' <tr id="defaultFieldTR'+v.id+'" style="height:35px;" ><input name="defaultFieldIsInsert['+v.id+']"  id="defaultFieldIsInsert['+v.id+']" type="hidden" value="0" />';
						 list  +='<td width="32%"><input name="defaultFieldValue['+v.id+']" id="defaultFieldValue['+v.id+']" type="text" style="width:95%" placeholder="Value" value="'+v.value+'"  /></td>';
						 list  +='<td width="32%"><input name="defaultFieldLabel['+v.id+']" id="defaultFieldLabel['+v.id+']" type="text" style="width:95%" placeholder="Label" value="'+v.label+' " /></td>';
						 if(parseInt(v.selected)==1){
							 list  +='<td width="20%"><label><input name="defaultFieldChecked['+v.id+']" id="defaultFieldChecked['+v.id+']" type="checkbox" value="1" checked="checked" />Checked/Selected</label></td>';
						 }else{
							  list  +='<td width="20%"><label><input name="defaultFieldChecked['+v.id+']" id="defaultFieldChecked['+v.id+']" type="checkbox" value="1" />Checked/Selected</label></td>';
						 }
						 list  +='<td><input name="defaultFieldRemoveBtn'+v.id+'" id="defaultFieldRemoveBtn'+v.id+'"  type="button"  value="ลบ" class="defaultFieldRemoveBtn da-button red" onclick="removeFieldValueList('+v.id+',1)"/>&nbsp;&nbsp;<input name="defaultFieldAddBtn'+v.id+'" id="defaultFieldAddBtn'+v.id+'"  type="button"  value="เพิ่ม" class="defaultFieldAddBtn da-button blue" onclick="addFieldValueList()" /></td>';
						 list  +='</tr>';
				 $('#fieldListContainer').append(list);
				 $('#fieldListContainer').find('.defaultFieldAddBtn').hide();
				 $('#fieldListContainer').find('.defaultFieldAddBtn:last').fadeIn();
				 $('#fieldValNumber').val(parseInt($('#fieldValNumber').val())+1);
	});
}

function addFieldValueList(){
	var numItem = $('#fieldValNumber').val();
	 var list  = ' <tr id="defaultFieldTR'+numItem+'" style="height:35px;" ><input name="defaultFieldIsInsert['+numItem+']"  id="defaultFieldIsInsert['+numItem+']" type="hidden" value="1" />';
			 list  +='<td width="32%"><input name="defaultFieldValue['+numItem+']" id="defaultFieldValue['+numItem+']" type="text" style="width:95%" placeholder="Value" /></td>';
			 list  +='<td width="32%"><input name="defaultFieldLabel['+numItem+']" id="defaultFieldLabel['+numItem+']" type="text" style="width:95%" placeholder="Label" /> </td>';
			 list  +='<td width="20%"><label><input name="defaultFieldChecked['+numItem+']" id="defaultFieldChecked['+numItem+']" type="checkbox" value="1" />Checked/Selected</label></td>';
			 list  +='<td><input name="defaultFieldRemoveBtn'+numItem+'" id="defaultFieldRemoveBtn'+numItem+'"  type="button"  value="ลบ" class="defaultFieldRemoveBtn da-button red" onclick="removeFieldValueList('+numItem+',0)"/>&nbsp;&nbsp;<input name="defaultFieldAddBtn'+numItem+'" id="defaultFieldAddBtn'+numItem+'"  type="button"  value="เพิ่ม" class="defaultFieldAddBtn da-button blue" onclick="addFieldValueList()" /></td>';
			 list  +='</tr>';
	 $('#fieldListContainer').append(list);
	 $('#fieldListContainer').find('.defaultFieldAddBtn').hide();
	 $('#fieldListContainer').find('.defaultFieldAddBtn:last').fadeIn();
	 $('#fieldValNumber').val(parseInt($('#fieldValNumber').val())+1);
}

function removeFieldValueList(id,chk_removedata){
	var trLength = $('#fieldListContainer').find('tr').length;
	if(parseInt(trLength)>1){
		if(chk_removedata){
			if(confirm('ข้อมูลจะถูกลบอย่างถาวร ยืนยันลบข้อมูล ใช่หรือไม่')){
				var d = new Date();	
				var url = "../../app/index.php?module="+modules+"&task=removeDefaultValue&id="+id +"&d"+d.getTime() ;
				$.get(url,function(data){
					var tr_id = "#defaultFieldTR"+id ;
					$(tr_id).remove();
					$('#fieldListContainer').find('.defaultFieldAddBtn').hide();
					$('#fieldListContainer').find('.defaultFieldAddBtn:last').fadeIn();
				});
			}
		}else{
			var tr_id = "#defaultFieldTR"+id ;
			$(tr_id).remove();
			$('#fieldListContainer').find('.defaultFieldAddBtn').hide();
			$('#fieldListContainer').find('.defaultFieldAddBtn:last').fadeIn();
		}
	}else{
		var numItem = parseInt(id)-1 ;
		var btnAdd= '#defaultFieldAddBtn'+numItem ;
		$(btnAdd).fadeIn();
	}
}

function checkSelectType() {
	var value = $('#attr_type').val();
	switch(value){
		case 'select':
			$('#defaultListValue').fadeIn();
			$('#defaultTextValue').fadeOut();
			$('#attr_placeholder_tr').fadeOut();
		break;
		case 'checkbox':
			$('#defaultListValue').fadeIn();
			$('#defaultTextValue').fadeOut();
			$('#attr_placeholder_tr').fadeOut();
		break;
		case 'radio':
			$('#defaultListValue').fadeIn();
			$('#defaultTextValue').fadeOut();
			$('#attr_placeholder_tr').fadeOut();
		break;
		default:
			$('#defaultListValue').fadeOut();
			$('#defaultTextValue').fadeIn();
			$('#attr_placeholder_tr').fadeIn();
		break;
	}
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


