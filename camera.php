<?php
require './src/snapchat.php';

function isIphone($user_agent=NULL) {
    if(!isset($user_agent)) {
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }
    return (stripos($user_agent, 'iPhone') !== FALSE);
}
function isAndroid($user_agent=NULL) {
    if(!isset($user_agent)) {
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }
    return (stripos($user_agent, 'Android') !== FALSE);
}

if(isIphone() || isAndroid()) {
    die('<!DOCTYPE html><html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>Snapchat Camera</title></head><body align="center" style="align:center;text-align:center;"><b>Error:</b> Phones cannot use this function as they do not support Adobe Flash player.<br/><input type="button" onclick="parent.close_dialog();" value="Close" style="color:white;font-weight:bold;border:0;background:#808080;height:40px;width:320px;"/></body></html>');
}

session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['pass'])) {
    header('Location: ./login.php');
    die();
}
$snapchat = new Snapchat($_SESSION['user'], $_SESSION['pass']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Snapchat Web Client - Camera</title>
</head>
<body align="center" style="align:center;text-align:center;">
<div id="result"></div><br/>
<script src="./webcam.js"></script>
<script>
webcam.set_api_url('./api.php');
webcam.set_quality(100);
webcam.set_shutter_sound(false);
document.write(webcam.get_html(320, 240));
</script>
<br/><br/>
<input type="button" value="Capture!" onclick="take_snapshot();" style="color:white;font-weight:bold;border:0;background:#FF2400;height:40px;width:320px;"><br/><input type="button" onclick="parent.close_dialog();" value="Close" style="color:white;font-weight:bold;border:0;background:#808080;height:40px;width:320px;"/>
<script>
webcam.set_hook('onComplete','finish');
function take_snapshot() {
    webcam.snap();
}
function finish(msg) {
    window.url = msg;
    document.body.innerHTML = '<img src="/snapchat/snaps/' + msg + '"/><br/><br/><input type="button" onclick="use();" value="Use" style="color:white;font-weight:bold;border:0;background:#FF2400;height:40px;width:320px;"/><br/><input type="button" onclick="window.location=\'/snapchat/camera.php\';" value="New" style="color:white;font-weight:bold;border:0;background:#808080;height:40px;width:320px;"/>';
}
function use() {
    msg = window.url;
    document.body.innerHTML = '<img src="/snapchat/snaps/'+msg+'"/><br/><b>Send to:</b><br/><select id="friend"><?php
    $friends = $snapchat->getFriends();
    foreach($friends as $friend) {
    	$name = $friend->name;
    	if(strtolower($name) != strtolower($_SESSION['user'])) {
    	    if(isset($friend->display) && $friend->display != NULL) {
		$extra = '<option value="'.$name.'">'.$friend->display.' ('.$name.') </option>';
	    } else {
	        $extra = '<option value="'.$name.'">'.$name.'</option>';
	    }
    	    echo $extra;
	}
    }
    echo '</select>\';';
    ?>
    document.body.innerHTML += '<br/><b>Time:</b><br/><select id="time"><option value="10" selected>10</option><option value="9">9</option><option value="8">8</option><option value="7">7</option><option value="6">6</option><option value="5">5</option><option value="4">4</option><option value="3">3</option><option value="2">2</option><option value="1">1</option></select>';
   document.body.innerHTML += '<br/><input type="button" style="color:white;font-weight:bold;border:0;background:#3BB9FF;height:40px;width:320px;" onmouseover="this.style.background=\'#82CAFF\'" onmouseout="this.style.background=\'#3BB9FF\'" onclick="this.style.background=\'#3BB9FF\';send();" value="SEND"/>';
   document.body.innerHTML += '<br/><input type="button" onclick="parent.close_dialog();" value="Close" style="color:white;font-weight:bold;border:0;background:#808080;height:40px;width:320px;"/>';
}
function send() {
    url = window.url;
    user = document.getElementById('friend');
    user = user.value;
    time = document.getElementById('time');
    time = time.value;
    send = parent.httpGet('./api/send.php?url='+url+'&user='+user+'&time='+time);
    if(send == 1) {
        parent.close_dialog();
        parent.notify('Snap successfully sent!');
    } else {
        alert('Error sending snap.');
        parent.close_dialog();
    }
}
</script>
</body>
</html>