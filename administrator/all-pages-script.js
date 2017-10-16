// JavaScript Document
"use strict";
var APP_ROOT = '../../';
var FILE_ROOT = '../../';
var oSettings = '';
(function($) {
	$(document).ready(function(e) {
		getAdminLoginUser() ;
		getCommentsTopRight();
		getMessagesTopRight();
		setAdminLogOut();
		setLoadingPage();
		overWriteValidateMesssage();
		inputBrowserFile();
	});// document_ready
}) (jQuery);	
// default function
function getUrlVars(){
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}
function reloadTableData(oTable){	
	oSettings=oTable.fnSettings() ;
	if(oSettings.oFeatures.bServerSide === false){
		var before = oSettings._iDisplayStart;
		oSettings.oApi._fnReDraw(oSettings);
		oSettings._iDisplayStart = before;
		oSettings.oApi._fnCalculateEnd(oSettings);
	}
	oSettings.oApi._fnDraw(oSettings);
}
function getTableCheckboxChecked(){
	var id = new Array();
	var cnt = 0 
	 $('#da-ex-datatable-numberpaging').find('input.table_checkbox:checked').each(function(){
		id[cnt] = $(this).val();
		cnt++;
	});
	return id;
}
// load user login
function getAdminLoginUser(){
	var d = new Date();
	var url = APP_ROOT+"app/index.php?module=users&task=getAdminLoginUser&"+d.getTime();
	$.getJSON(url,function(data){
		if(data.id!=false){
			$('#da-user-info').html(data.info);
			$('#da-user-avatar img').attr('src',data.avatar);
		}else{
			var url = '../';
			window.location.replace(url);
		}
	});
}

// load notif top_right
function getCommentsTopRight(){
	var d = new Date();
	var url = APP_ROOT+"app/index.php?module=comments&task=getCommentsTopRight&"+d.getTime();
	$.get(url,function(data){
		if(data!=null&&$.trim(data)!=''){
			var data = $.parseJSON(data);  
			if(data!=null&&$.trim(data)!=''&&data.length>0){
				$('#show-comment-unread').html(data.length);
				$.each(data,function(key,val){
					var comments = '<li class="'+val.read+'"><a href="../comments/comments.php"><span class="message">'+val.title+'</span><span class="time">'+val.date+'</span></a></li>';
					$('#show-comments').append(comments);
				});
			}else{
				$('#show-comment-unread').fadeOut();
			}
		}else{
			$('#show-comment-unread').fadeOut();
		}
	});
}
// load contact us top_right
function getMessagesTopRight(){
	var d = new Date();
	var url = APP_ROOT+"app/index.php?module=contacts&task=getMessagesTopRight&"+d.getTime();
	$.get(url,function(data){
		if(data!=null&&$.trim(data)!=''){
			var data = $.parseJSON(data);  
			if(data!=null&&data.length>0){
				$('#show-messages-unread').html(data.length);
				$.each(data,function(key,val){
					var messages = '<li class="'+val.read+'"><a href="#"><span class="message">'+val.title+'</span><span class="time">'+val.date+'</span></a></li>';
					$('#show-messages').append(messages);
				});
			}else{
				$('#show-messages-unread').fadeOut();
			}
		}else{
			$('#show-messages-unread').fadeOut();
		}	
	});	
}
// logout
function setAdminLogOut(){
	var d = new Date();
	var url = APP_ROOT+"app/index.php?module=users&task=setAdminLogout&"+d.getTime();
	$('#logout').bind('click',function(){
		$.get(url,function(data){
		 	$.jGrowl("แจ้งเตือน ! <br> ท่านได้ทำการออกจากระบบแล้ว", {position: "bottom-right"});
			setTimeout(window.location.replace('../'),5000);
		});
	});
}
function setLoadingPage(){
	$("body").on({
		// When ajaxStart is fired, add 'loading' to body class
		ajaxStart: function() { 
			$(this).addClass("loading"); 
		},
		// When ajaxStop is fired, rmeove 'loading' from body class
		ajaxStop: function() { 
			$(this).removeClass("loading");    
		}    
	});
}
function overWriteValidateMesssage(){
	if($.fn.validate) {
		jQuery.extend(jQuery.validator.messages, {
			required: "ต้องใส่ข้อมูล.",
			remote: "ข้อมูลไม่ถูกต้อง.",
			email: "อีเมลไม่ถูกต้อง.",
			url: "URL ไม่ถูกต้อง.",
			date: "วันที่ไม่ถูกต้อง",
			dateISO: "วันที่ไม่ถูกต้อง (ISO).",
			number: "กรุณาใส่ตัวเลข.",
			digits: "ใส่ตัวเลขเท่านั้น.",
			creditcard: "กรุณาใส่เลขที่เครติดการ์ดให้ถูกต้อง.",
			equalTo: "ข้อมูลไม่ตรงกัน.",
			accept: "ข้อมูลไม่ถูกต้อง.",
			maxlength: jQuery.validator.format("กรุณาใส่ข้อมูลไม่เกิน {0} ตัวอักษร."),
			minlength: jQuery.validator.format("กรุณาใส่ข้อมูลอย่างน้อย {0} ตัวอักษร."),
			rangelength: jQuery.validator.format("กรุณาใส่ข้อมูลระหว่าง {0} และ {1} ตัวอักษร."),
			range: jQuery.validator.format("กรุณาใส่ข้อมูลระหว่าง{0} และ {1}."),
			max: jQuery.validator.format("กรุณาใส่ข้อมูลที่น้อยกว่าหรือเท่ากับ {0}."),
			min: jQuery.validator.format("กรุณาใส่ข้อมูลที่มากว่าหรือเท่ากับ {0}.")
		});
	}
}
function reloadPageNow(){
	window.location.reload(true);
}
function setSiteSearch(){
	var keyword = $('#siteSearch_keyword').val();
	var url = "../search/search.php?find="+keyword;
	window.location.replace(url);	
}
function inputBrowserFile(){
	$(document).find('.elfinder-browse').each(function(){
		$(this).bind('click',function () {
			var input = $(this) ;
			// create new elFinder
			var dialog = $('<div id="finder" />').dialogelfinder({
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
}
function getCurrentDomainURL(){
	return location.protocol + "//" + location.host;
}
function getPreImageURL(){
	var admin_index = location.href.indexOf('administrator');
	var image_url = location.href.substr(0,admin_index);
	return image_url;
}
function initFormTextEditor(){
	tinymce.init({
		verify_html: false,
		selector: "textarea.texteditor",
		inline: false,
		height:250,
		mode : "textareas",
		relative_urls: true,
		//extended_valid_elements :  "iframe[src|frameborder|style|scrolling|class|width|height|name|align]",
		fontsize_formats: "8pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 19pt 20pt 21pt 22pt 23pt 24pt 25pt 26pt 28pt 30pt 32pt 34pt 36pt",
		// save_onsavecallback:function(){ suSetSaveEditElement();},
		file_browser_callback : elFinderBrowser,
		plugins: [
			"advlist autoresize autolink lists link image charmap print preview anchor emoticons",
			"searchreplace visualblocks code fullscreen",
			"insertdatetime media table contextmenu paste textcolor hr  template ",
			"fontawesome"
		],
		autoresize_overflow_padding: 5,
		autoresize_min_height: 250,
		autoresize_max_height: 500,
		extended_valid_elements: 'i[class|style|title],span[class|style|title],a[accesskey|charset|class|contenteditable|contextmenu|coords|dir|download|draggable|dropzone|hidden|href|hreflang|id|lang|media|name|rel|rev|shape|spellcheck|style|tabindex|target|title|translate|type|onclick|onfocus|onblur],marquee',
		content_css : "../../themes/assets/css/bootstrap.min.css",
		content_css: '../../files/font-awesome/css/font-awesome.min.css',
		toolbar1: " insertfile | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image   | forecolor backcolor |  formatselect styleselect  fontselect fontsizeselect  | template emoticons fontawesome  ",
		bootstrapConfig: {
			'bootstrapCssPath': '../../themes/assets/css/bootstrap.min.css', // <= replace with your custom bootstrap path
			'imagesPath': '../../media/Images/', // replace with your images folder path 
			// 'tinymceBackgroundColor': '#fff' // replaces editor background-color with custom
		}
	});
}
function elFinderBrowser (field_name, url, type, win) {
	tinymce.activeEditor.windowManager.open({
		// file: '../../assets/filesmanagement/embed_services.php',// use an absolute path!
		file: FILE_ROOT+'files/embed.php',
		title: 'Files Management',  
		width: 900,  
		height: 450,
		resizable: 'yes'
	},{
		setUrl: function (url) {
			win.document.getElementById(field_name).value = url;
		}
	});
	return false;
}