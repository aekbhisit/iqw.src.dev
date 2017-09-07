<?php
class Mailer extends Database {
	var $module   ;
	var $smtp ;
	public function __construct($module=NULL,$table=NULL){
		$this->module = (empty($table))?$module:$table  ;
		 parent::__construct((empty($table))?$module:$table );
		 
		 $this->sql ="select * from configs_email limit 0,1";
		 $this->select();
		 $this->smtp = $this->rows[0] ;
	}
	
// function for mailer  /////////////////////////////////////////////////
// Develop by iQuickweb.com 28/06/2012
///////////////////////////////////////////////////////////////////////////////////
public function sendMail($mailTo,$toName,$ccTo,$bccTo,$subject,$mailFrom,$fromName,$msg,$attachments){
	if((int)$this->smtp['smtp']==1){
		return $this->sendBySMTP($mailTo,$toName,$ccTo,$bccTo,$subject,$mailFrom,$fromName,$attachments,$msg);
	}else{
		return $this->sendByMailer($mailTo,$toName,$ccTo,$bccTo,$subject,$mailFrom,$fromName,$attachments,$msg);
	}
}

public function sendByMailer($mailTo,$toName,$ccTo,$bccTo,$subject,$mailFrom,$fromName,$attachments,$msg){
		$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
		$mail->AddReplyTo($mailFrom,$fromName);
		$mail->AddAddress($mailTo, $toName);
		$mail->Subject    = $subject;
		$mail->AltBody    =  $msg  ;//""; // optional, comment out and test
		$mail->Body=$msg;
		if(!empty($attachments)){
			if(is_array($attachments)){
				foreach($attachments as $atta){
					if(!empty($atta)){
						$mail->AddAttachment($atta);
					}
				}
			}else{
				$mail->AddAttachment($attachments) ;
			}
		}
		if(!$mail->Send()) {
			// echo "Mailer Error: " . $mail->ErrorInfo;
			return false ;
		} else {
			// echo "Message sent!";
			return true ;
		}
}

public function sendBySMTP($mailTo,$toName,$ccTo,$bccTo,$subject,$mailFrom,$fromName,$attachments,$msg){
	$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
	$mail->IsSMTP(); // telling the class to use SMTP
	try {
	  $mail->Host       = $this->smtp['smtp_server']; // SMTP server
	  $mail->SMTPSecure = $this->smtp['smtp_secure'] ;  
	  $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
	  $mail->SMTPAuth   = true;                  // enable SMTP authentication
	//  $mail->Host       = "mail.yourdomain.com"; // sets the SMTP server
	  $mail->Port       = $this->smtp['smtp_port'] ;                    // set the SMTP port for the GMAIL server
	  $mail->Username   = $this->smtp['smtp_user'] ; // SMTP account username
	  $mail->SMTPAuth   = true;                  // enable SMTP authentication
	  $mail->Password   = $this->smtp['smtp_password'] ;        // SMTP account password
	  $mail->SetFrom($mailFrom, $fromName);
	  $mail->AddReplyTo($mailFrom, $fromName);
	  $mail->AddAddress($mailTo, $toName);
	  $mail->Subject = $subject ;
	  $mail->AltBody = $msg; // ''; // optional - MsgHTML will create an alternate automatically
	  $mail->Body=$msg;
		if(!empty($attachments)){
			if(is_array($attachments)){
				foreach($attachments as $atta){
					if(!empty($atta)){
						$mail->AddAttachment($atta);
					}
				}
			}else{
				$mail->AddAttachment($attachments) ;
			}
		}
	if(!$mail->Send()) {
  		// echo "Mailer Error: " . $mail->ErrorInfo;
  		return false ;
	} else {
		// echo "Message sent!";
		return true ;
	}
	} catch (phpmailerException $e) {
	  echo $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
	  echo $e->getMessage(); //Boring error messages from anything else!
	}
    
}



}
?>