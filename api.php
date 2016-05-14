<?php

$time = time();
$filename = 'snaps/'.$time . '.jpg';
$result = file_put_contents('./'.$filename, file_get_contents('php://input'));
if(!$result) {
	die('Error');
}
echo $time.'.jpg';

?>
