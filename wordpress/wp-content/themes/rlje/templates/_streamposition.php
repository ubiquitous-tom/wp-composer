<?php

/* 
 * Get the streampotion to set it in the plugin
 */

if(count($_POST) > 0 && isset($_COOKIE["ATVSessionCookie"], $_POST['Position'], $_POST['EpisodeID'], $_POST['LastKnownAction'])) {
    rljeApiWP_addStreamPosition($_POST['EpisodeID'], $_COOKIE["ATVSessionCookie"], $_POST['Position'], $_POST['LastKnownAction']);
}
else {
    header($_SERVER["SERVER_PROTOCOL"]." 401 Unauthorized");
    exit;
}