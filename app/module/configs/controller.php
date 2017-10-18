<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ( is_session_started() === FALSE ) { session_start(); }
$oUsers = new Users('users');
$oConfigsSite = new Configs('configs_site');
$oConfigsEmail = new Configs('configs_email');
if(isset($_GET['task'])){
	$task = $_GET['task'];
	switch($task){
		// configs-site.html
		case 'configsSiteFormInit':
			$data = $oConfigsSite->getConfigsSite();
			echo json_encode($data);
		break;
		case 'saveConfigsSite':
			$id = $oConfigsSite->setInt($_POST['configs_id']);
			$opensite = $oConfigsSite->setInt($_POST['configs_open']);
			$sitename = $oConfigsSite->setString($_POST['configs_sitename']);
			$meta_keywords = $oConfigsSite->setString($_POST['configs_meta_keywords']);
			$meta_descritption = $oConfigsSite->setString($_POST['configs_meta_description']);
			$favicon_url = $oConfigsSite->setString($_POST['configs_favicon_url']);
			$underconstruction_text = $oConfigsSite->setString($_POST['configs_underconstruction_text']);
			$background_url = $oConfigsSite->setString($_POST['configs_background_url']);
			$languages = $_POST['configs_languages'];
			// update site language 
			$oConfigsSite->updateSiteLanguage($languages);
			if(empty($id)){		
				$oConfigsSite->insertConfigsSite($opensite,$sitename,$meta_keywords,$meta_descritption,$favicon_url,$underconstruction_text,$background_url);
			}else{
				$oConfigsSite->updateConfigsSite($id,$opensite,$sitename,$meta_keywords,$meta_descritption,$favicon_url,$underconstruction_text,$background_url);
			}
		break;
		//  configs-email.html
		case 'configsEmailFormInit':
			$data = $oConfigsEmail->getConfigsEmail();
			echo json_encode($data);
		break;
		case 'saveConfigsEmail':
			$id = $oConfigsEmail->setInt($_POST['configs_id']);
			$smtp = $oConfigsEmail->setInt($_POST['configs_smtp']);
			$smtp_secure = $oConfigsEmail->setString($_POST['configs_smtp_secure']);
			$smtp_server = $oConfigsEmail->setString($_POST['configs_smtp_server']);
			$smtp_port = $oConfigsEmail->setString($_POST['configs_smtp_port']);
			$smtp_user = $oConfigsEmail->setString($_POST['configs_smtp_user']);
			$smtp_password = $oConfigsEmail->setString($_POST['configs_smtp_password']);
			if(empty($id)){
				$oConfigsEmail->insertConfigsEmail($smtp,$smtp_secure,$smtp_server,$smtp_port,$smtp_user,$smtp_password);
			}else{
				$oConfigsEmail->updateConfigsEmail($id,$smtp,$smtp_secure,$smtp_server,$smtp_port,$smtp_user,$smtp_password);
			}
		break;
		// for frontend_function 
		case "front_getSiteConfig";
			$_DATA["configs"] = $oConfigsSite->getConfigsSiteFront();
		break ;
		case "front_setSiteLanguage";
			$_SESSION['SITELANGUAGE'] = $oConfigsEmail->setString($_GET['language']);
			setcookie("SITELANGUAGE",$_SESSION['SITELANGUAGE'],time()+3600*24*30, "/", DOMAIN);
		break ;
	}// switch
}// if isset
?>