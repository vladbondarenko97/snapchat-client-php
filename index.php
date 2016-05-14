<?session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Snapchat Web Client - Index</title>
<meta oame="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=no; target-densityDpi=device-dpi"/>
<meta charset="utf-8"/>
<link rel="icon" type="image/png" href="./favicon.png" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://malsup.github.io/jquery.blockUI.js"></script>
<script src="./webcam.js"></script>
<style>
img { -ms-interpolation-mode: bicubic; }
table, td, th{
    border:0;
}
html, body { margin: 0; padding: 0; }
body {
    background-color: #EFE200;
    font-family: "Trebuchet MS",Helvetica,sans-serif;
    -moz-user-select: -moz-none;
    -khtml-user-select: none;
    -webkit-user-select: none;
    -o-user-select: none;
    user-select: none;
}
#logo {
    position: absolute;
    top: 20%;
    left: 49%;
}
#intro {
    display: block;
    position: fixed;
    background-color: white;
    width: 40%;
    top: 40%;
    text-align: center;
    display: block;
    position: fixed;
    background-color: white;
    width: 40%;
    top: 40%;
    text-align: center;
    margin-right: auto;
    left: 30%;
}
#footer{
    position:fixed;
    bottom:0;
    left:50%;
    margin-left:-200px; /*negative half the width */
    color:red;
    width:400px;
    height:20px;
}
</style>
<script>
window.busy = 'no';
function httpGet(theUrl){
    var html;
    $.get(theUrl,function(data){
       html = data;
    });
    alert(html);
    return html;
}
function login_click() {
    footer_login = document.getElementById('footer-login');
    logo = document.getElementById('logo');
    intro = document.getElementById('intro');
    intro.INNERHTML = '';
    middle_login = document.getElementById('middle-login');

    logo.style.top = '5%';
    footer_login.innerHTML = '';
    middle_login.innerHTML = '<input type="text" onkeyup="check_login(event);" id="username" style="border:0;height:40px;width:30%;" placeholder="Username or Email" value="<?=$_SESSION['user'];?>"/><br/>';
    middle_login.innerHTML += '<input type="password" onkeyup="check_login(event);" id="password" style="border:0;height:40px;width:30%;" placeholder="Password" value=""/>';
    middle_login.innerHTML += '<div id="login-button-div"><input type="button" onclick="javascript:login_s();" id="login-button" style="color:white;font-weight:bold;border:0;background:#808080;height:40px;width:30%;" value="LOG IN"/></div>';
    middle_login.innerHTML += '<br/>Please be patient when you click login.<br/>It may take up to 30 seconds to load the client.';
}
function change_btn(){
    button_div = document.getElementById('login-button-div');
    button_div.innerHTML = '<img src="./images/load.gif" alt="Loading..."/><br/>Logging In';
}
function login_s() {
    user = document.getElementById('username');
    pass = document.getElementById('password');
    if(user.value == '' || pass.value == '') {
    	return null;
    }
    button = document.getElementById('login-button');
    middle = document.getElementById('middle-login');
    button_div = document.getElementById('login-button-div');
    button_div.innerHTML = '<img src="./images/load.gif" alt="Loading..."/><br/>Logging In';
    $.get('./api/check_login.php?user='+user.value+'&pass='+pass.value,function(data){
	if(data == '1') {
     	   button_div.innerHTML = '<img src="./images/load.gif" alt="Loading..."/><br/>Fetching Snaps';
     	   get_snaps();
     	   document.body.data = '1';
     	} else {
    	   notify('We can\'t find an account with that username.');
     	   pass.value = '';
      	   button_div.innerHTML = '<div id="login-button-div"><input type="button" onclick="javascript:login_s();" id="login-button" style="color:white;font-weight:bold;border:0;background:#808080;height:40px;width:30%;" value="LOG IN"/></div>';
       	   button.style.background = '#808080';
   	 }
    });
}
function get_snaps() {
    if(window.busy == 'yes') {
        return '0';
    }
    var snaps;
    $.get('./api/get_snaps.php',function(data){
       snaps = data;
    if(snaps != '0') {
        document.body.style.background = 'lightgray';
        document.body.innerHTML = '<span id="camera" height="100%" width="100%" style="margin:0px;padding:0px;overflow:hidden;display:none;height:100%;width:100%;"><iframe style="height:100%;width:100%;" height="100%" width="100%" frameBorder="0" src="./camera.php"/></span>';
        document.body.innerHTML += '<span id="friends" height="100%" width="100%" style="margin:0px;padding:0px;overflow:hidden;display:none;height:100%;width:100%;"><iframe style="height:95%;width:100%;" height="95%" width="100%" frameBorder="0" src="./friends.php"/></span>';
        document.body.innerHTML += '<span id="stories" height="100%" width="100%" style="margin:0px;padding:0px;overflow:hidden;display:none;height:100%;width:100%;">It might take up to a minute to load all stories.<br/><iframe style="height:95%;width:100%;" height="95%" width="100%" frameBorder="0" src="./stories.php"/></span>';
        document.body.innerHTML += '<div id="add" style="display:none;"><br/><input type="text" id="friend" style="border:0;height:20px;width:30%;" placeholder="Username"/><br/><input type="button" style="color:white;font-weight:bold;border:0;background:#6495ED;height:20px;width:30%;" value="ADD" onclick="add_friend_action();"/><br/><input type="button" style="color:white;font-weight:bold;border:0;background:#808080;height:20px;width:30%;" onclick="close_dialog();" value="CLOSE"/><br/>&nbsp;</div><div><table bgcolor="#4EA885" align="center" style="border-style:solid;" border="1" width="70%"><tr><td><img src="/snapchat/images/logo.gif" height="25px width="25px" style="vertical-align:middle;-moz-box-align:center;-webkit-box-align:center;"/><span style="color:white;font-size:25px;">snapchat</span></td><td align="right"><img width="32px" height="32px" style="vertical-align:middle;-moz-box-align:center;-webkit-box-align:center;" src="./images/stories.png" alt="Stories" onclick="stories();"/>&nbsp;<img width="32px" height="32px" style="vertical-align:middle;-moz-box-align:center;-webkit-box-align:center;" src="./images/friends.png" alt="Friends" onclick="friends();"/>&nbsp;<img width="32px" height="23.125px" style="vertical-align:middle;-moz-box-align:center;-webkit-box-align:center;" src="./images/camera.gif" alt="Camera" onclick="camera();"/>&nbsp;<img width="25px" height="25px" style="vertical-align:middle;-moz-box-align:center;-webkit-box-align:center;" src="./images/add.gif" alt="Add a Friend" onclick="add_friend();"/>&nbsp;<img width="25px" height="25px" style="vertical-align:middle;-moz-box-align:center;-webkit-box-align:center;" src="http://hfchat.x10.mx/snapchat/images/refresh.gif" alt="Refresh" onclick="dance();"/>&nbsp;<img width="25px" height="25px" style="vertical-align:middle;-moz-box-align:center;-webkit-box-align:center;" src="./images/clear.gif" alt="Clear Feed" onclick="clear_snaps();"/></td></tr></table> <table bgcolor="white" align="center" style="border-style:solid;" border="1" width="70%" id="snaps"></table></div><br/>';
        snap = snaps.split('\n');
        table = document.getElementById('snaps');
        var index;
        if(snap.length < 2) {
            table.innerHTML = '<tr><td></td><td align="center"><br/>You don\'t have any Snaps :(</td><br/></tr>';
            return '0';
        }
        for (index = 0; index < snap.length; ++index) {
            element = snap[index].split(':');
            name = element[0];
            console.log(element);
            if(name != '') {
                type = element[1];
                if(element[1] == '3' || element[1] == '2') continue;
                id = element[2];
                status = element[3];
                elapsed = element[4];
                type_d = element[5];
                url = element[6];
                if(element[4] == '1 day ago') {
                    elapsed = 'yesterday';
                }
                if(element[5] == 's' && element[3] == 'Delivered') {
                    table.innerHTML = '<tr><td width="5%" align="center"><img src="http://hfchat.x10.mx/snapchat/images/icons/sent-'+element[1]+'.gif" alt="Sent"/></td><td>'+name+'<br/><span style="color:gray;">'+elapsed+' - '+element[3]+'</span></td></tr>'+table.innerHTML;
                } else if(element[5] == 's' && element[3] != 'Delivered') {
                    table.innerHTML = '<tr><td width="5%" align="center"><img src="http://hfchat.x10.mx/snapchat/images/icons/sent-opened-'+element[1]+'.gif" alt="Sent and Opened"/></td><td>'+name+'<br/><span style="color:gray;">'+elapsed+' - '+element[3]+'</span></td></tr>'+table.innerHTML;
                } else if(element[5] == 'r' && element[6] != '0') {
                    table.innerHTML = '<tr style="cursor:pointer"><td width="5%" align="center" onclick="view(\'http://hfchat.x10.mx/'+element[6]+'\');"><img id="'+element[2]+'" src="http://hfchat.x10.mx/snapchat/images/icons/receive-'+element[1]+'.gif" alt="Received"/></td><td onclick="view(\'http://hfchat.x10.mx/'+element[6]+'\');" id="'+element[2]+'u"><b>'+name+'</b><br/><span id="'+element[2]+'e" style="color:gray;">'+elapsed+' - Press to view</span></td><td align="right"><img id="'+element[2]+'i" onclick="javascript:delete_snap(\''+element[2]+'\', \''+element[1]+'\');" src="http://hfchat.x10.mx/snapchat/images/view.gif" alt="View and Delete the Snap" height="18px" width="32px" style="vertical-align:center;"/></td></tr>'+table.innerHTML;

                } else if(element[5] == 'r' && element[6] == '0') {
                    table.innerHTML = '<tr><td width="5%" align="center"><img src="http://hfchat.x10.mx/snapchat/images/icons/receive-opened-'+element[1]+'.gif" alt="Received and Opened"/></td><td>'+name+'<br/><span style="color:gray;">'+elapsed+'</span></td></tr>'+table.innerHTML;
                } 
            }
        }
    } else {
        return '0';
    }
    });
}
function enter(e) {
    if(e && e.keyCode == 13) {
        login_s();
    }
}
function add_friend_action() { 
        u = document.getElementById('friend');
        $.get('./api/add_friend.php?u='+u.value,function(data){
           add = data;
           notify('Success adding a friend!');
        });
}
function add_friend() { 
        $.blockUI({ message: $('#add') }); 
        window.busy = 'yes';
}
function friends() { 
    $.blockUI({ 
            message: $('#friends'), 
            css: { 
                top: '10%',
                width: '40%',
                height: '80%'
            } 
        }); 
    window.busy = 'yes';
}
function stories() { 
    $.blockUI({ 
            message: $('#stories'), 
            css: { 
                top: '10%',
                width: '30%',
                height: '80%'
            } 
        }); 
    window.busy = 'yes';
}
function close_dialog() { 
        $.unblockUI();
        window.busy = 'no';
}
function dance() {
    table.innerHTML = '<tr align="center"><td></td><td align="center"><img height="64px" width="64px"src="./images/ghost.gif" alt="Reloading..."/></td></tr>'+table.innerHTML;
    get_snaps();
    notify('Snap list is refreshing...');
}
function delEL(element) {
    element && element.parentNode && element.parentNode.removeChild(element);
}
function view(url) {
    var win=window.open(url, '_blank');
    win.focus();
}
function delete_snap(id, type) {
    if(confirm('Are you sure you want to mark this snap as viewed?')) {
        $.get('./api/delete.php?id='+id,function(data){
           req = data;
           if(req != 'true') {
               alert('Error marking the image viewed. Try again in a little while.');
               return 'error';
           }
        });
        img = document.getElementById(id);
        img2 = document.getElementById(id+'i');
        elapsed = document.getElementById(id+'e');
        user = document.getElementById(id+'u');
        elapsed.innerHTML = elapsed.innerHTML.replace('- Press to view', '');
        user.innerHTML = user.innerHTML.replace('</b>', '');
        user.innerHTML = user.innerHTML.replace('<b>', '');
        img.src = 'http://hfchat.x10.mx/snapchat/images/icons/receive-opened-'+type+'.gif';
        delEL(img2);
        if(type == 'img') {
            notify('The image has been marked as seen.');
        } else {
            notify('The video has been marked as seen.');
        }
    }
}
function clear_snaps() {
    if(confirm('Are you sure you want to clear your feed?')) {
        table = document.getElementById('snaps');
        table.innerHTML = '<tr><td></td><td align="center"><br/>You don\'t have any Snaps :(</td><br/></tr>';
        $.get('./api/clear.php',function(data){});
        notify('Feed has been successfully cleared!')
    }
}
function camera() {
    $.blockUI({ 
            message: $('#camera'), 
            css: { 
                top: '10%',
                width: '400px',
                height: '80%'
            } 
        }); 
    window.busy = 'yes';
}


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
                border: 'solid', 
                padding: '5px', 
                backgroundColor: '#3BB9FF', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: .8, 
                color: '#fff' 
            } 
        }); 
}
function notify2(m) {
$.blockUI({ 
            message: m, 
            fadeIn: 700, 
            fadeOut: 700, 
            timeout: 10000, 
            showOverlay: false, 
            centerY: false, 
            css: { 
                width: '30%', 
                top: '10px', 
                left: '', 
                right: '10px', 
                border: 'solid', 
                padding: '5px', 
                backgroundColor: 'red', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: .8, 
                color: '#fff' 
            } 
        }); 
}
function check_login(e) {
    user = document.getElementById('username');
    pass = document.getElementById('password');
    button = document.getElementById('login-button');
    if(user.value != '' && pass.value != '') {
        button.style.background = '#FF2400';
        enter(e);
    } else {
        button.style.background = '#808080';
    }
}

<?php
if(isset($_SESSION['user'])) {
	echo "window.onload = function() { 
	login_click();
	document.getElementById('password').value = '".htmlentities($_SESSION['pass'])."';
	notify('Welcome back <b>".htmlentities($_SESSION['user'])."</b>!<br/>Click login to proceed.');
	button = document.getElementById('login-button');
	button.style.background = '#FF2400';
}";
} elseif(isset($_GET['error']) && $_GET['error'] == 404) {
	echo "window.onload = function() {notify('Error: Page not found.');}";
} else {
	echo "window.onload = function() {notify2('Due to the nature of events regarding the Snapchat API, this client does not anymore :(. I will be releasing the source code in the near future. Thanks to everyone that used it!');}";
}
?>
</script>
<body>
<div id="body">
<img id="logo" src="./images/logo.gif" width="32px" height="32px" alt="Snapchat Client with stories, camera support, and friends."/>
<div id="middle-login" style="position:absolute;text-align:center;top:15%;width:100%;"></div>
<div id="intro"></div>
<div id="footer-login" style="text-align:center;position:fixed;bottom:5%;width:100%;">
<input type="button" id="login_btn" style="color:white;font-weight:bold;border:0;background:#FF2400;height:40px;width:30%;" onmouseover="this.style.background ='#F9966B'" onmouseout="this.style.background ='#FF2400'" onclick="this.style.background ='#FF2400';login_click();" value="LOG IN"/><br/>
<input type="button" style="color:white;font-weight:bold;border:0;background:#3BB9FF;height:40px;width:30%;" onmouseover="this.style.background ='#82CAFF'" onmouseout="this.style.background ='#3BB9FF'" onclick="this.style.background ='#3BB9FF'" onclick="script:alert('Please sign up with the official Snapchat application from the App Store or the Play Store.');" value="SIGN UP"/>
</div>
</div>

<div id="footer">&nbsp; &copy; 2014. Design, client, and programming by <a href="http://vlad.tk/">Vlad Bondarenko</a>.</div>
</body>
</html>