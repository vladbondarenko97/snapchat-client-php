<?php
require '../src/snapchat.php';
session_start();
$snapchat = new Snapchat($_SESSION['user'], $_SESSION['pass']);

if(isset($snapchat->username) && $snapchat->username != '') {

} else {
	die('0');
}

function human_time($time) {

    $time = time() - $time;

    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}

$snaps = array_reverse($snapchat->getSnaps());
if(isset($snaps) && $snaps != NULL) {
	foreach($snaps as $snap) { 

	    $id = $snap->id;
	    $sender = $snap->sender;
            $recipient = $snap->recipient;
            if($sender == $recipient) continue;
            if($sender == $snapchat->username) $sender = $recipient;
            $type_d = substr($id, -1);
	    $sent = substr($snap->sent, 0, -3);
            $opened = $snap->opened;
            $opened_f = substr($snap->opened, 0, -3);
            $opened_f2 = substr($snap->opened, 0, -3);
            // year
            $year = gmdate("Y", $opened_f);
            if(isset($opened) && $opened > 0 && $year > 2013) {
                $opened_f = 'Opened';
            } else {
                $opened_f = 'Sent';
            }
            if($type_d == 's') $opened_f = 'Opened';
            if($type_d == 'r') {
                $data = $snapchat->getMedia($id);
                if(isset($data) && $data != '') {
                    if($snap->media_type == '0') {
                        file_put_contents('../snaps/'.$id.'.jpg', $data);
                        $url = 'snapchat/snaps/'.$id.'.jpg';
                    } else {
                        file_put_contents('../snaps/'.$id.'.mp4', $data);
                        $url = 'snapchat/snaps/'.$id.'.mp4';
                    }
                } else {
                    $url = '0';
                }
            } else {
                $url = '0';
            }
            if($snap->sent == $snap->opened) $opened_f = 'Delivered';
            $sent = human_time($sent);
	    $data = $snapchat->getMedia($id);
	    $type = $snap->media_type;
	    if($type == 0) $type = 'img';
	    if($type == 1) $type = 'vid';
            echo PHP_EOL.$sender.':'.$type.':'.$id.':'.$opened_f.':'.$sent.' ago:'.$type_d.':'.$url;
    	}
}
	
?>