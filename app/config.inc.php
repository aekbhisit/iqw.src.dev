<?php
//ini_set('display_errors',false);
//ini_set('error_reporting',E_ALL);
// iquickweb 0.1 BETA
// create by aekbhisit bhiwatchayanan
// create date : 04/06/2012
//this is the configulation file for system
// -------------   database information  --------------------------///
//database server
//define('DB_SERVER', "localhost");
//define('DB_USER', "root");
//define('DB_PASS', "");
//define('DB_DATABASE', "th");
/*define('DB_SERVER', "localhost");
define('DB_USER', "root");
define('DB_PASS', "");
define('DB_DATABASE', "bangkokrama");*/
define('DB_SERVER', "localhost");
define('DB_USER', "irradiance");
define('DB_PASS', "Server1214!");
define('DB_DATABASE', "irradiance_db");
/*
//database server
define('DB_SERVER', "localhost");
define('DB_USER', "root");
define('DB_PASS', "");
define('DB_DATABASE', "iqw_restaurant");
*/
/*
define('DB_SERVER', "localhost");
define('DB_USER', "cmscrea1_iqwaac");
define('DB_PASS', "8OMlH0KD");
define('DB_DATABASE', "cmscrea1_iqwaae");
*/
//smart to define your table names also
// --------------------security code hash ----------------------------------
define('SECURITY_CODE', "z,0tme.shiqickwebgxHol6fpvfcms-v'F]d");
//defind Path
define('SITE_NAME','/');
define('SITE_ROOT','/');
define('FILE_ROOT',dirname(__FILE__));
//language
define('SITE_LANGUAGE','th');
define('SITE_TRANSLATE',true);
define('LOCALE_DIR', $_SERVER['DOCUMENT_ROOT'].'/languages/');
define('DEFAULT_LOCALE',SITE_LANGUAGE);
// dynamic search
if(!session_id()){
	@session_start();
}
////////////////////////////////////////////  search config //////////////////////////////////////////////////////////////////////////////////////
$GLOBALS['_SEARCH_HTML_WEBSITES'] = array(
	SITE_ROOT
);
$GLOBALS['_SEARCH_HTML_DEPTH'] = 10;
$GLOBALS['_SEARCH_CACHE_LENGTH'] = 1;
$GLOBALS['_SEARCH_ALL_IGNORE'] = array(
	"#~$#",
	"#/\.#",
	"#/\.ht#",
	"#private#i",
	"#search#i",
	"#adminisitrator#i",
	"#app#i",
	"#install#i",
	"#languages#i",
	"#themes#i",
	"#search\.php#i"
);
$GLOBALS['_SEARCH_FILES_INCLUDE'] = array(
	"#\.jpg$#i",
	"#\.jpeg$#i",
	"#\.gif$#i",
	"#\.png$#i",
	"#\.exe$#i",
	"#\.pdf$#i",
	"#\.zip$#i",
	"#\.doc$#i",
	"#\.docx$#i",
	"#\.avi$#i",
	"#\.mov$#i",
	"#\.mpg$#i",
	"#\.mpeg$#i",
);
// any content type matching these regex's will be indexed and searched
$GLOBALS['_SEARCH_HTML_INCLUDE'] = array(
	"#text/html#i",
);
// any content type matching these regex's will be downloaded and treated as a pdf, converted to text, and indexed
// (if supported by server software)
$GLOBALS['_SEARCH_PDF_INCLUDE'] = array(
	"#application/pdf#i",
);
// nfi why i've used globals instead of define ... meh. same dif.
// min number of characters in search string.
$GLOBALS['_SEARCH_MIN_CHARS'] = 4; 
$GLOBALS['_SEARCH_SUMMARY_LENGTH'] = 110;
$GLOBALS['_SEARCH_PER_PAGE'] = 10;
$GLOBALS['_SEARCH_SHOW_BOX'] = true;
$GLOBALS['_SEARCH_SHOW_STYLESHEET'] = true; // use the inbuilt stylesheet or not? ie: phpsearch.css
$GLOBALS['_SEARCH_COMBINE']	= true; // set this to false if results take long time
$GLOBALS['_SEARCH_FILES_FOLDER'] = "search/"; // end it in a slash. path from search.php has to be writable 
$GLOBALS['_SEARCH_CACHE_FOLDER'] = $GLOBALS['_SEARCH_FILES_FOLDER']."data/"; // end it in a slash. path from search.php has to be writable 
define("_SEARCH_DEBUG",false);
///////////////////////////////////////////  search config //////////////////////////////////////////////////////////////////////////////////////
?>