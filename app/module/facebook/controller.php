<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
if ( is_session_started() === FALSE ) { session_start(); }
$oUsers = new Users('users');
$oFacebook = new IQW_Facebook('configs_site');
if(isset($_GET['task'])){
	$task = $_GET['task'];
	switch($task){
		case 'getFacebookApp':
			$oFacebook->getFacebookApp();
			if(isset($_GET['slug'])&&$_GET['slug']=='facebook_logout'){
				$oFacebook->facebook_logout();
			}
			if($oFacebook->facebook_user) {
				$email = $oFacebook->facebook_user_profile['email'];
				$name = $oFacebook->facebook_user_profile['name'];
				$username = $oFacebook->facebook_user_profile['username'];
				$password = $oFacebook->facebook_user_profile['id']; 
				if($oUsers->checkExistedUserByEmail($email)&&$oUsers->checkExistedUserByUsername($username)){
					echo 'not exist';
					$oUsers->insertUsers($oFacebook->member_category_id,$email,$username,$password,$name,$name,'',1);
					$oUsers->setLogin($username,$password,true);
				}else{
					echo 'exist';
					$oUsers->setLogin($username,$password,true);
					print_r($getLoginUser);
				}
				echo '<p><a href="'.$oFacebook->facebook_logout_url.'">Logout</a>';
			}else{
				echo '<p><a href="'.$oFacebook->facebook_login_url.'">Login with Facebook</a>';
			}
		break;
	}// switch
}// if isset
?>