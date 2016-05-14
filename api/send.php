<?php

require '../src/snapchat.php';
session_start();

$snapchat = new Snapchat($_SESSION['user'], $_SESSION['pass']);

if(isset($snapchat->username) && $snapchat->username != '') {
} else {
	echo '0';
        die();
}

$url = $_GET['url'];
$user = $_GET['user'];
$time = $_GET['time'];

$id = $snapchat->upload(
    Snapchat::MEDIA_IMAGE,
    file_get_contents('../snaps/'.$url)
);
$send = $snapchat->send($id, array($user), $time);

if($send == true) {
    die('1');
} else {
    die('0');
}
?>