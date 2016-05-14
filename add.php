<?php 
require './src/snapchat.php';
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['pass'])) {
    header('Location: ./');
    die();
}
$snapchat = new Snapchat($_SESSION['user'], $_SESSION['pass']);
?>
<!DOCTYPE html>
<html>
<head>
<title>WebSnap - Add Friends</title>
</head>
<body>
<?php
if(isset($_POST['user']) && $_POST['user']) {
	$snapchat->addFriend($_POST['user']);
	echo '<b>Friend successfully added.</b><br/>';
}
?>
<h1>Snapchat Web Client - Add Friends</h1>
Add your friends from here. You must have a friend added before you Snapchat them.<br/>
<form method="POST">
<input type="text" name="user" placeholder="Username of a friend..." /><br/>
<input type="submit" value="Add"/><br/>
List of friends:<br/>
<?php
function bold($text = '') {
	return '<b>'.$text.'</b>';
}
$friends = $snapchat->getFriends();
foreach($friends as $friend) {
	$name = $friend->name;
	if(strtolower($name) != strtolower($_SESSION['user'])) {
		echo bold($name);
			if(isset($friend->display) && $friend->display != NULL) {
				echo ' - '.$friend->display;
			}
	echo '<br/>';
	}
}
?>
</form>
</body>
</html>