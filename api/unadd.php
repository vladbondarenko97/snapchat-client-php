<?php 
require '../src/snapchat.php';
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['pass'])) {
    header('Location: ./');
    die();
}
$snapchat = new Snapchat($_SESSION['user'], $_SESSION['pass']);

if(isset($_GET['user'])) {
	$snapchat->deleteFriend($_GET['user']);
	echo 1;
}
?>