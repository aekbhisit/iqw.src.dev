// JavaScript Document
var modules = "members";
(function($) {
	$(document).ready(function(e) {
		formInit() ;
		$.jGrowl("แจ้งเตือน ! <br> โหลดข้อมูลเสร็จแล้วพร้อมแก้ไข", {position: "bottom-right"});
		// selectImages();
	});// document_ready
}) (jQuery);

function gotoManagePage(){
	var url = 'index.php'; 
	window.location.replace(url);
}

function formInit(){
	var d = new Date();
	var request = window.location.search.replace('?','') ;
	var url = "../../app/index.php?module="+modules+"&task=usersFormInit&"+request+"&d"+d.getTime() ;
	$.getJSON(url,function(data){ 
			$.param(data); 
			if(typeof data=='object' && data!=null ){
				$('#users_id').val(data.id);
				 loadNowCategories(data.category_id) ;
				$('#users_categores').val(data.category_id);
				$('#users_name').val(data.name);
				$('#users_display_name').val(data.display_name);
				$('#users_email').val(data.email);
				$('#users_username').val(data.username);
				$('#users_password').val(data.password_hide);
				$('#users_password_chk').val(data.password_hide);
				if(data.avatar!=''){
					$('#users_avatar').val(data.avatar);
					$('#show_users_avatar').attr('src',data.avatar);
					$('#show_users_avatar').fadeIn('fast');
				}
				$('#news_status').find('option:[value="'+data.status+'"]').attr('selected','selected');
			}else{
				loadNowCategories(0) ;
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

function setSaveUsers(){
	var d = new Date();	
	var url = "../../app/index.php?module="+modules+"&task=saveUsers&d"+d.getTime() ;
	$.ajax({
		  type: 'POST', 
		  url: url, 
		  enctype: 'multipart/form-data', 
		  data: $('#users_form').serialize(),
		  beforeSend: function() {
				$('#users_form').validate({ 
					rules: {
					users_name: {
						required: true
					},
					users_display_name: {
						required: true
					},
					users_email: {
						required: true,
						email: true
					},
					users_username: {
						required: true,
						minlength: 5
					},
					users_password: {
						required: true,
						minlength: 5
					},
					users_password_chk: {
						required: true,
						equalTo: "#users_password"
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
				return $('#users_form').valid();
			  },
		  success: function(data){
			 gotoManagePage() ;
		 }
	});
}

function selectImages(){
		 var input = $('#users_avatar'),
  		  opts = { 
       		 url : '../../files/connectors/php/connector.php',
       		 editorCallback : function(url) { input.val(url) },
        	closeOnEditorCallback : true,
        	dialog : { title : 'Files Management'}
   		 };
    $(input).bind('click',function () {
		if($(document).has('#finder').length<=0){
			$('#users_avatar').after('<div id="finder"></div>');
		}
        $('#finder').elfinder(opts);
    });	
}

function setUserDelete(id){
	if(confirm('ยืนยันลบสมาชิก')){
		var d= new Date();
		var url = "../../app/index.php?module="+modules+"&task=setUsersDelete&id="+id+"&d"+d.getTime() ;
		$.get(url, function(data){
				reloadTableData(oTable);
				$.jGrowl("แจ้งเตือน ! <br> ลบสมาชิกสำเร็จ", {position: "bottom-right"});
			
		});
	}
}

function setUserStatus(id,status){
	var d= new Date();
	var url = "../../app/index.php?module="+modules+"&task=setUsersStatus&status="+status+"&id="+id+"&d"+d.getTime() ;
	$.get(url, function(data){
		reloadTableData(oTable);
		$.jGrowl("แจ้งเตือน ! <br>เปลี่ยนสถานะสมาชิกสำเร็จ", {position: "bottom-right"});
	});
}