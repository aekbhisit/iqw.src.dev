// JavaScript Document
var modules = "contacts";
(function($) {
	$(document).ready(function(e) {
		formInit() ;
		$.jGrowl("แจ้งเตือน ! <br> โหลดข้อมูลเสร็จแล้วพร้อมแก้ไข", {position: "bottom-right"});
		//$(".datetimepicker").datetimepicker();
		 //selectImages();
	});// document_ready
}) (jQuery);

function gotoManagePage(){
	var url = 'index.php'; 
	window.location.replace(url);
}

function formInit(){
	var d = new Date();
	var request = window.location.search.replace('?','') ;
	var url = "../../app/index.php?module="+modules+"&task=contactsFormInit&"+request+"&d"+d.getTime() ;
	$.getJSON(url,function(data){ 
			if(typeof data=='object' && data!=null ){
				$('#contacts_id').val(data.id);
				$('#contacts_from').html(data.from_name);
				$('#contacts_subject').html(data.subject);
				$('#reply_subject').val(data.subject);
				$('#contacts_messages').html(data.content);
				// $('#contacts_embed').html(data.embed);
				$('#from_email').val(data.to_email);
				$('#replyto_email').val(data.from_email);
				if(data.reply_subject!=null&&data.reply_subject!=''){
					$('#show_replyed').fadeIn('slow');
					$('#replied_subject').html(data.reply_subject);
					$('#replied_messages').html(data.reply_messages);
				}else{
						$('#show_replyed').hide();
				}
			}else{
			//	loadNowCategories(0) ;
			}
	});
}


function setSaveContacts(){
	var d = new Date();	
	var url = "../../app/index.php?module="+modules+"&task=sentReplyContact&d"+d.getTime() ;
	$('#contacts_content').elrte('updateSource');
	$.ajax({
		  type: 'POST', 
		  url: url, 
		  enctype: 'multipart/form-data', 
		  data: $('#contacts_form').serialize(),
		  beforeSend: function() {
				$('#contacts_form').validate({ 
					rules: {
					contacts_content: {
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
				return $('#contacts_form').valid();
			  },
		  success: function(data){
			//  alert(data);
			 gotoManagePage()
		 }
	});
}
