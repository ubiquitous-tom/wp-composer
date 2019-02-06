<?php
if (count($_POST) > 0 && function_exists('rljeApiWP_signupNewsletter')) {
    if(!rljeApiWP_signupNewsletter($_POST['SignupEmail'])) {
        header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
    }
}
else {
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
}
exit();
