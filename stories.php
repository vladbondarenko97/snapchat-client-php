<?php

/* Beta */
require './src/snapchat.php';
session_start();
if(!isset($_SESSION['user']) || !isset($_SESSION['pass'])) {
    header('Location: ./');
    die();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Snapchat Web Client - Stories</title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"> 
<body>
<table>
<script>
function toggle_name(name) {
	var toggle = document.getElementById(name);
	toggle.style.display = toggle.style.display === 'none' ? '' : 'none';
	
}
</script>
<div align="center"><input type="button" style="color:white;font-weight:bold;border:0;background:#808080;height:20px;width:30%;" onclick="parent.close_dialog();" value="CLOSE"/></div>
<?php
$snapchat = new Snapchat($_SESSION['user'], $_SESSION['pass']);

$snaps = $snapchat->getFriendStories();
$users = array();
$user_data = array();
foreach($snaps as $story) {
	if($story->media_type == 0) {
		$ext = '.jpg';
	} else {
		$ext = '.mp4';
	}
	$caption = $story->caption_text_display;
	if($caption == NULL) {
		$caption = '0';
	}
	if(!in_array($story->username, $users)) {
		$users[] = $story->username;
		$user_data[$story->username] = $story->username.',,'.$story->media_id.':'.$story->media_key.':'.$story->media_iv.':'.$ext;
	} else {

		$user_data[$story->username] = $user_data[$story->username].'::'.$story->media_id.':'.$story->media_key.':'.$story->media_iv.':'.$ext;
	}
}
foreach($user_data as $value) {
	$name = explode(',,', $value);
	$stories = explode('::', $name[1]);
	if(!isset($stories[1])) {
		echo '<tr style="cursor:pointer" onclick="javascript:toggle_name(\''.$name[0].'\');"><td><br/><b>'.$name[0].'</b><br/></td></tr>';
		$story1 = explode(':', $name[1]);
		echo '<tr id="'.$name[0].'" style="display:none;"><td>';
		if(!file_exists('./snaps/stories/'.$name[0].'-'.$story1[0].'.jpg')) {
			$data = $snapchat->getStory($story1[0], $story1[1], $story1[2]);
			file_put_contents('./snaps/stories/'.$name[0].'-'.$story1[0].$story1[3], $data);
			echo '<a target="_blank" href="./snaps/stories/'.$name[0].'-'.$story1[0].$story1[3].'">';
			if($story1[3] == '.mp4') {
				echo '<img width="12px" height="12px" src="./images/video.gif" alt="Video"/>;';
			} else {
				echo '<img width="12px" height="12px" src="./images/camera_small.png" alt="Image"/>;';
			}
			echo 'View #1</a><br/>';
		} else {
			echo '<a target="_blank" href="./snaps/stories/'.$name[0].'-'.$story1[0].$story1[3].'">';
			if($story1[3] == '.mp4') {
				echo '<img width="12px" height="12px" src="./images/video.gif" alt="Video"/>';
			} else {
				echo '<img width="12px" height="12px" src="./images/camera_small.png" alt="Image"/>';
			}
			echo 'View #1</a><br/>';
		}
		echo '</td></tr>';
	} else {
		echo '<tr style="cursor:pointer" onclick="javascript:toggle_name(\''.$name[0].'\');"><td><br/><b>'.$name[0].'</b><br/></td></tr>';
		echo '<tr id="'.$name[0].'" style="display:none;"><td>';
                $n=1;
		foreach($stories as $story_long) {
			$story_more = explode(':', $story_long);
			if(!file_exists('./snaps/stories/'.$name[0].'-'.$story_more[0].$story_more[3])) {
				$data = $snapchat->getStory($story_more[0], $story_more[1], $story_more[2]);
				file_put_contents('./snaps/stories/'.$name[0].'-'.$story_more[0].$story_more[3], $data);
				echo '<a target="_blank" href="./snaps/stories/'.$name[0].'-'.$story_more[0].$story_more[3].'">';
				if($story_more[3] == '.mp4') {
					echo '<img width="12px" height="12px" src="./images/video.gif" alt="Video"/>';
				} else {
					echo '<img width="12px" height="12px" src="./images/camera_small.png" alt="Image"/>';
				}
				echo 'View #'.$n++.'</a><br/>';
			} else {
				echo '<a target="_blank" href="./snaps/stories/'.$name[0].'-'.$story_more[0].$story_more[3].'">';
				if($story_more[3] == '.mp4') {
					echo '<img width="12px" height="12px" src="./images/video.gif" alt="Video"/>';
				} else {
					echo '<img width="12px" height="12px" src="./images/camera_small.png" alt="Image"/>';
				}
				echo 'View #'.$n++.'</a><br/>';
			}
		}
                echo '</td></tr>';
	}
}

echo '</table>
<div align="center"><input type="button" style="color:white;font-weight:bold;border:0;background:#808080;height:20px;width:30%;" onclick="parent.close_dialog();" value="CLOSE"/></div>
</body>
</html>';
?>