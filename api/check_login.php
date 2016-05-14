<?php

require '../src/snapchat.php';
session_start();

$snapchat = new Snapchat($_GET['user'], $_GET['pass']);

if(isset($snapchat->username) && $snapchat->username != '') {
        $_SESSION['user'] = $_GET['user'];
        $_SESSION['pass'] = $_GET['pass'];
	echo '1';
} else {
	echo '0';
	session_destroy();
}
?>