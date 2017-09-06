<html>
<head>
<title> Online Demo - Twitter Auto Publish (without oAuth)</title>
</head>
<body>
<h2> Online Demo - Twitter Auto Publish (without oAuth)</h2>
<form action="#" method="post">
<div>Twitter Username: <input type="text" name="uname" /></div>
<div>Twitter Password: <input type="password" name="password" /></div>
<div>Status: <input type="text" name="status" size="140" /></div>
<div><input type="submit" name="publish" value="Publish" /></div>
</form>
<?php
if(isset($_POST['uname'])){

	@session_start();

	require_once("openinviter_base.inc.php");

	require_once("twitter_auto_publish.inc.php");



	$user=trim($_POST['uname']);

	$pass=trim($_POST['password']);	

	

	$msg=trim($_POST['status']);

	$rochak=new twitter_auto_publish();

	$rochak->login($user,$pass);

	$rochak->updateTwitterStatus($msg);
	echo "SUCCESS: Status updated !";
	echo "<h3>Following: </h3>".$rochak->getFollowings();
	echo "<h3>Followers: </h3>".$rochak->getFollowers();
	$rochak->logout();
	
}


?>
</body></html>