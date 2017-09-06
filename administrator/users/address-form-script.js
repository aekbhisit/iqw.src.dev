// JavaScript Document
(function($) {
	$(document).ready(function(e) {
		formInit() ;
		$.jGrowl("แจ้งเตือน ! <br> โหลดข้อมูลเสร็จแล้วพร้อมแก้ไข", {position: "bottom-right"});
		// selectImages();
	});// document_ready
}) (jQuery);

function gotoManagePage(){
	var url = 'users.php'; 
	window.location.replace(url);
}

function formInit(){
	var d = new Date();
	var request = window.location.search.replace('?','') ;
	var url = "../../app/index.php?module=users&task=loadUsersAddress&"+request+"&d"+d.getTime() ;
	$.getJSON(url,function(data){ 
			if(typeof data=='object' && data!=null ){
				 loadNowAddress(data) ;
			}else{
				loadNowAddress(null) ;
			}
	});
}

function loadNowAddress(data){
		var 	options_list = '<option value="add" selected="selected">-- เพิ่มที่อยู่ใหม่ --</option>' ;
		if(data!=''&&data!=null){
			$.each(data,function(index,value){
					options_list += '<option value="'+value.id+'">'+value.address+'</option>' ;
			});
		}
		$('#address_type').html(options_list) ;
		$('#address_type').removeAttr('disabled') ;
		$('#address_type').bind('click',function(){
				setEditAddress($(this).val(),data);
		});
}

function setEditAddress(id,data){
	if(id=='add'||id==''){
			$('#address_type').find('option:[value="add"]').attr('selected','selected');
			$('#users_address').val('');
			$('#users_city').val('');
			$('#users_state').val('');
			$('#users_country').val('');
			$('#users_zipcode').val('');
			$('#users_moblie').val('');
			$('#users_moblie').val('');
			$('#users_moblie').val('');
			$('#users_address_status').find('option:[value="1"]').attr('selected','selected');
			$('#setDeleteAddress').hide();
	}else{
			$.each(data,function(index,value){
				if(value.id==id){
					$('#address_type').find('option:[value="'+value.id+'"]').attr('selected','selected');
					$('#user_address').val(value.address);
					$('#users_city').val(value.city);
					$('#users_state').val(value.state);
					$('#users_country').val(value.country);
					$('#users_zipcode').val(value.zipcode);
					$('#users_moblie').val(value.mobile);
					$('#users_moblie').val(value.tel);
					$('#users_moblie').val(value.fax);
					$('#users_address_status').find('option:[value="'+value.status+'"]').attr('selected','selected');
					$('#setDeleteAddress').show();
				}
			});
	}
}

function setSaveUsersAddress(){
	var d = new Date();	
	var request = window.location.search.replace('?','') ;
	var url = "../../app/index.php?module=users&task=saveUsersAddress&"+request+"&d"+d.getTime() ;
	$.ajax({
		  type: 'POST', 
		  url: url, 
		  enctype: 'multipart/form-data', 
		  data: $('#users_address_form').serialize(),
		  beforeSend: function() {
			 // alert(url);
				$('#users_address_form').validate({ 
					rules: {
					users_address: {
						required: true
					},
					users_city: {
						required: true
					},
					users_state: {
						required: true
					},
					users_country: {
						required: true
					},
					users_zipcode: {
						required: true,
						digits:true,
						maxlength:5,
						minlength:5
					},
					users_moblie: {
						digits:true
					},
					users_tel: {
						digits:true
					},
					users_fax: {
						digits:true
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
				return $('#users_address_form').valid();
			  },
		  success: function(data){
			//  alert('success'+data);
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

function setUsersAddressDelete(){
	var id = $('#address_type').val();
	if(id!=''&&id!='add'){
		if(confirm('ยืนยันลบที่อยู่สมาชิก')){
			var d= new Date();
			var url = "../../app/index.php?module=users&task=setUsersAddressDelete&id="+id+"&d"+d.getTime() ;
				$.get(url, function(data){
						$.jGrowl("แจ้งเตือน ! <br> ลบสมาชิกสำเร็จ", {position: "bottom-right"});
						gotoManagePage() ;
				});
			}
	}
}

function setUserAddressStatus(id,status){
	var d= new Date();
	var url = "../../app/index.php?module=users&task=setUsersAddressStatus&status="+status+"&id="+id+"&d"+d.getTime() ;
	$.get(url, function(data){
		reloadTableData(oTable);
		$.jGrowl("แจ้งเตือน ! <br>เปลี่ยนสถานะสมาชิกสำเร็จ", {position: "bottom-right"});
	});
}

