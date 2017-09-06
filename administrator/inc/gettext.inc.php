<?php
// define constants
require_once('../../app/config.inc.php');
require_once('../../languages/library/gettext.inc');
$locale = (isset($_SESSION['SITE_LANGUAGE']))? $_SESSION['SITE_LANGUAGE'] : DEFAULT_LOCALE;
$encoding = 'UTF-8';
// gettext setup
T_setlocale( LC_MESSAGES, $locale);
// Set the text domain as 'messages'
$domain = 'messages';
T_bindtextdomain($domain, LOCALE_DIR);
T_bind_textdomain_codeset($domain, $encoding);
T_textdomain($domain);
?>