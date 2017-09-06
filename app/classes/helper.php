<?php
if(isset($helper)){
	if($helper['mailer']&&$helper['mailer']==true){
		//include('library/mailer/phpmailer.class.php') ;
		if(!class_exists('Mailer')){
		if( file_exists('library/mailer/class.phpmailer.php')){
			require_once('library/mailer/class.phpmailer.php'); 
		}else{
			require_once('app/library/mailer/class.phpmailer.php');
		}
		
		if(file_exists('mailer.php')){
				require_once('mailer.php') ;
		}else{
			if(file_exists('classes/mailer.php')){
				require_once('classes/mailer.php') ;
			}else{
				require_once('app/classes/mailer.php') ;
			}
		}
		$oMailer = new Mailer();   
		}
	}
	// PDF
	if($helper['PDF']&&$helper['PDF']==true){
		//include('library/mailer/phpmailer.class.php') ;
		if(!class_exists('PDF')){
		if( file_exists('library/html2pdf.class.php')){
			require_once('library/html2pdf.class.php'); 
		}else{
			require_once('app/library/html2pdf.class.php');
		}
		
		if(file_exists('pdf.php')){
				require_once('pdf.php') ;
		}else{
			if(file_exists('classes/pdf.php')){
				require_once('classes/pdf.php') ;
			}else{
				require_once('app/classes/pdf.php') ;
			}
		}
		$oPDF = new PDF();   
		}
	}
	
	// PDF
	if($helper['dynamic_search']&&$helper['dynamic_search']==true){
		//include('library/mailer/phpmailer.class.php') ;
		if(!class_exists('PDF')){
		if( file_exists('classes/dynamic_search.class.php')){
			require_once('classes/dynamic_search.class.php'); 
		}else{
			require_once('app/classes/dynamic_search.class.php');
		}
		$oWSD = new website_search_dynamic();
		}
	}
	
	// include module object 
	if($helper['objects']&&!empty($helper['objects'])){
		if(is_array($helper['objects'])){
				foreach($helper['objects'] as $obj_key => $obj){
					if($obj==true){
						$CLASS_NAME =$obj_key;
						$FILE_NAME = strtolower($obj_key);
						if(!class_exists($CLASS_NAME)){
							$obj_file = 'module/'.$FILE_NAME.'/objects.php';
							if(file_exists($obj_file)){
								require_once($obj_file) ;
							}else{
								$obj_file = 'app/module/'.$FILE_NAME.'/objects.php';
								if(file_exists($obj_file)){
									require_once($obj_file) ;
								}
							}
						}// class exist
					}// if obj == true
			}
		}
	}
}
?>