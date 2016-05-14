<?php 
require '../src/snapchat.php';
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['pass'])) {
    header('Location: ./');
    die();
}
$snapchat = new Snapchat($_SESSION['user'], $_SESSION['pass']);

echo htmlentities($_GET['user']).'\'s Bestfriends:'.PHP_EOL.PHP_EOL;
if(isset($_GET['user'])) {
	$list = $snapchat->getBests(array($_GET['user']));
	foreach($list as $item) {
		foreach($item['best_friends'] as $bfs) {
			echo $bfs.PHP_EOL;
		}
			echo PHP_EOL.'User\'s Score: '.number_format($item['score']);
	}
}
?>