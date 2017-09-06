<?php
if(!empty($_POST)){
$filename = '../app/config.inc.php';
$configs = '<?php
// iquickweb 0.1 BETA
// create by aekbhisit bhiwatchayanan
// create date : 04/06/2012
//this is the configulation file for system
// -------------   database information  --------------------------///
//database server

define("DB_SERVER", "'.$_POST["database_server"].'");
define("DB_USER", "'.$_POST["database_user"].'");
define("DB_PASS", "'.$_POST["database_password"].'");
define("DB_DATABASE", "'.$_POST["database_name"].'");
// --------------------security code hash ----------------------------------
define("SECURITY_CODE ", "'.($_POST["security_code"]).'");

//defind Path
define("SITE_NAME", "'.$_POST["full_website_url"].'") ;
define("SITE_ROOT", "'.$_POST["website_root"].'") ;
define("FILE_ROOT",dirname(__FILE__)) ;

//language
define("SITE_LANGUAGE","'.$_POST["language"].'");
define("SITE_TRANSLATE",'.$_POST["is_translate"].');
define("LOCALE_DIR", $_SERVER["DOCUMENT_ROOT"]."/'.$_POST["folder_name"].'/languages/");
define("DEFAULT_LOCALE", SITE_LANGUAGE);
';

$check_worte_file = false ;
// Let's make sure the file exists and is writable first.
if (is_writable($filename)) {

    // In our example we're opening $filename in append mode.
    // The file pointer is at the bottom of the file hence
    // that's where $somecontent will go when we fwrite() it.
    if (!$handle = fopen($filename, 'w')) {
         echo "Cannot open file ($filename)";
         exit;
    }

    // Write $somecontent to our opened file.
    if (fwrite($handle, $configs) === FALSE) {
        echo "Cannot write to file ($filename)";
        exit;
    }
	$check_worte_file = true ;
    fclose($handle);
	if($check_worte_file){
		//header('Location: create_database.php');
		include('create_database.php');
	}
} else {
    echo "The file $filename is not writable";
}

}// !empty post
?>