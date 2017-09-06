<?

require_once('crontab.php');
$crontab=new crontab("/www/test/cron/", "filename");
$crontab->setDateParams(5, 10, 5, 5, "*");
$crontab->setCommand("curl http://www.mysite.com/?action=do_me");
$crontab->saveCronFile();
$crontab->addToCrontab();
$crontab->destroyFilePoint(); // OPTIONAL

?>