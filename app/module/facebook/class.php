<?php
class IQW_Facebook extends Database {
	var $facebook_app   ;
	var $facebook_user ;
	var $facebook_user_profile ;
	var $facebook_login_url ;
	var $facebook_logout_url ;
	var $facebook_session ;
	var $facebook_access_token ;
	var $member_category_id ;
	public function __construct($module,$table=NULL){
		$this->module = (empty($table))?$module:$table  ;
		 parent::__construct((empty($table))?$module:$table );
	}
	
	function getFacebookApp($redirect=NULL){
		$this->sql ="select facebook_app_id, facebook_app_secret, facebook_member_category  from configs_site ";
		$this->select();
		$app_id  = $this->rows[0]['facebook_app_id'] ;	
		$app_secret  = $this->rows[0]['facebook_app_secret'] ;	
		$this->member_category_id =   $this->rows[0]['facebook_member_category'] ;	
		$this->facebook_app = new Facebook(
			array(
  				'appId'  => $app_id,
 		 		'secret' => $app_secret ,
			)	
			);
		$this->facebook_user = $this->facebook_app->getUser();
		$this->facebook_access_token =   $this->facebook_app->getAccessToken();
		if ($this->facebook_user ) {
		  try {
			// Proceed knowing you have a logged in user who's authenticated.
			$this->facebook_user_profile = $this->facebook_app->api('/me');
		  } catch (FacebookApiException $e) {
			error_log($e);
			$user = null;
		  }
		}
		
		if ($this->facebook_user ) {
		$redirect =  (!empty($redirect))?$redirect:SITE_ROOT ;
		$mystring = (string)$redirect ;
		$findme   = '?';
		$pos = strpos($mystring, $findme);
		  $q = ($pos===false)?'?':'&' ;
		  $redirect_logout_url = (!empty($redirect))? $redirect.$q.'slug=facebook_logout':SITE_ROOT.$q.'slug=facebook_logout' ;
		  $this->facebook_logout_url= $this->facebook_app->getLogoutUrl(array(
		  	'next'=> $redirect_logout_url  
		  ));
		} else {
			$redirect =  (!empty($redirect))?$redirect:SITE_ROOT ;
		    $redirect_login_url = str_replace("slug=facebook_logout","",$redirect);
			 $this->facebook_login_url = $this->facebook_app->getLoginUrl(array(
     			   'scope' => 'publish_stream,email' ,
				   'redirect_uri' =>$redirect_login_url
 		 ));
		}
	}
	
	function facebook_logout() {
		$this->facebook_user = 0 ;
		$redirect =  (!empty($redirect))?$redirect:SITE_ROOT ;
		$redirect_login_url = str_replace("slug=facebook_logout","",$redirect);
		 $this->facebook_login_url = $this->facebook_app->getLoginUrl(array(
     			   'scope' => 'publish_stream,email' ,
				   'redirect_uri' =>$redirect_login_url
 		 ));
	}
	
	
}
?>