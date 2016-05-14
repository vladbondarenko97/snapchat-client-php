<?php

require '../src/snapchat.php';
session_start();

$snapchat = new Snapchat($_SESSION['user'], $_SESSION['pass']);

if(!isset($snapchat->username) || $snapchat->username == '') {
        die(0);
}

$clear = $snapchat->markSnapViewed($_GET['id']);
if($clear == true) die('true');

die('false');
?>