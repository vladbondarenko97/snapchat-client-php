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
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://malsup.github.io/jquery.blockUI.js"></script>
<title>Snapchat Web Client - Friends</title>
</head>
<body>
<script>
function notify(m) {
$.blockUI({ 
            message: m, 
            fadeIn: 700, 
            fadeOut: 700, 
            timeout: 5000, 
            showOverlay: false, 
            centerY: false, 
            css: { 
                width: '350px', 
                top: '10px', 
                left: '', 
                right: '10px', 
                border: 'none', 
                padding: '5px', 
                backgroundColor: '#000', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: .8, 
                color: '#fff' 
            } 
        }); 
}
function httpGet(theUrl){
    var xmlHttp = null;

    xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", theUrl, false);
    xmlHttp.send(null);
    return xmlHttp.responseText;
}
function unfriend(name) {
    unadd = httpGet('./api/unadd.php?user='+name);
    if(unadd != 1) {
        alert('Error has occured');
        return 0;
    }
    document.getElementById(name).innerHTML = '<img src="./images/unfriend.png"/>';
    document.getElementById(name).setAttribute('onclick', 'javascript:friend(\''+name+'\')');
    notify(name+' has been unfriended.');
}
function friend(name) {
    add = httpGet('./api/add.php?user='+name);
    if(add != 1) {
        alert('Error has occured');
        return 0;
    }
    document.getElementById(name).innerHTML = '<img src="./images/friend.png"/>';
    document.getElementById(name).setAttribute('onclick', 'javascript:unfriend(\''+name+'\')');
    notify(name+' has been added to your friend list.');
}
function get_bfs(name) {
    result = httpGet('./api/bfs.php?user='+name);
    alert(result);
    return 0;
}
</script>
<div align="center"><input type="button" style="color:white;font-weight:bold;border:0;background:#808080;height:20px;width:30%;" onclick="parent.close_dialog();" value="CLOSE"/></div>
<table bgcolor="white" align="center" cellspacing="4" width="100%" id="snaps"><?php
$friends = $snapchat->getFriends();
    foreach($friends as $friend) {
    	$name = $friend->name;
    	if(strtolower($name) != strtolower($_SESSION['user'])) {
    	    if(isset($friend->display) && $friend->display != NULL) {
		$extra = '<tr><td style="cursor:pointer" onclick="get_bfs(\''.$name.'\');"><br/><b>'.$friend->display.'</b><br/><span style="color:gray;"></span></td><td width="5%" height="5%" align="right"><br/><span id="'.$name.'" class="1" onclick="javascript:unfriend(\''.$name.'\');"><img src="./images/friend.png"/></span></td></tr>'.PHP_EOL;
	    } else {
	        $extra = '<tr><td style="cursor:pointer" onclick="get_bfs(\''.$name.'\');"><br/><b>'.$name.'</b><br/><span style="color:gray;"></span></td><td width="5%" height="5%" align="right"><br/><span id="'.$name.'" class="1" onclick="javascript:unfriend(\''.$name.'\');"><img src="./images/friend.png"/></span></td></tr>'.PHP_EOL;
	    }
    	    echo $extra;
	}
    }
    
echo '</table>
<div align="center"><input type="button" style="color:white;font-weight:bold;border:0;background:#808080;height:20px;width:30%;" onclick="parent.close_dialog();" value="CLOSE"/></div>
</body>
</html>';
?>