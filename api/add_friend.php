<?php

require '../src/snapchat.php';
session_start();

$snapchat = new Snapchat($_SESSION['user'], $_SESSION['pass']);

if(isset($snapchat->username) && $snapchat->username != '') {
} else {
	echo '0';
        die();
}

$add = $snapchat->addFriend($_GET['u']);
if($add == true) {
    echo htmlentities($_GET['u']);
} else {
    echo 'Error adding a friend.';
}
?>