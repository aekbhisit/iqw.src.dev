<?php
session_start();
$oSupport = new Supports('configs_site');
$oMailer = new Mailer();
if(isset($_GET['task'])){
	$task = $_GET['task'] ;
	switch($task){
		case 'supportMail':
				$configs  =  $oSupport->siteConfig() ;
				if($_POST['client_msg_type']==0){
					$subj = "แจ้งปัญหา : " .$_POST['client_msg_title'];
				}else{
					$subj = "แนะนำ : " .$_POST['client_msg_title'];
				}
				
				$msgs = "\n \n".$_POST['client_msg_text'];
				$mailTo = 'iquickweb.com@gmail.com' ;
				$toName ="iQuickWeb" ;
				$ccTo = NULL ;
				$bccTo = NULL ;
				$subject = $subj ;
				$mailFrom = $configs['site_email'] ;
				$fromName=$configs['sitename'] ;
				$msg = $msgs ;
				$attachments = NULL ;
				$oMailer-> sendMail($mailTo,$toName,$ccTo,$bccTo,$subject,$mailFrom,$fromName,$msg,$attachments) ;
			break ;
	}// switch
}// if isset
?>