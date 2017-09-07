<?php 
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
$host = $_SERVER['HTTP_HOST'];
$full_path = $protocol.'://'.$host;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Files Management</title>
		<!-- jQuery and jQuery UI (REQUIRED) -->
		<link rel="stylesheet" type="text/css" media="screen" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css">
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?=$full_path?>/files/css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="<?=$full_path?>/files/css/theme.css">
		<!-- elFinder JS (REQUIRED) -->
		<script type="text/javascript" src="<?=$full_path?>/files/js/elfinder.min.js"></script>
		<!-- elFinder translation (OPTIONAL) -->
		<script type="text/javascript" src="<?=$full_path?>/files/js/i18n/elfinder.ru.js"></script>
		<script type="text/javascript">
  		var FileBrowserDialogue = {
    		init: function() {
      			// Here goes your code for setting your custom things onLoad.
    		},
    		mySubmit: function (URL) {
      			// pass selected file path to TinyMCE
      			parent.tinymce.activeEditor.windowManager.getParams().setUrl(URL);
      			// close popup window
      			parent.tinymce.activeEditor.windowManager.close();
    		}
  		}
  		$(document).ready(function() {
    		var elf = $('#elfinder').elfinder({
	      		// set your elFinder options here
	      		url: '<?=$full_path?>/files/php/connector.php',  // connector URL
	      		getFileCallback: function(file) { // editor callback
					// actually file.url - doesnt work for me, but file does. (elfinder 2.0-rc1)
					//var _SITE_URL =  "http://site2.shopup2014.com/";
					//var _filename = file.replace(_SITE_URL,'')
	        		FileBrowserDialogue.mySubmit(file); // pass selected file path to TinyMCE 
	      		}
   			}).elfinder('instance');      
  		});
		</script>
	</head>
	<body>
		<!-- Element where elFinder will be created (REQUIRED) -->
		<div id="elfinder"></div>
	</body>
</html>