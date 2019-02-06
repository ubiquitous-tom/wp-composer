<?php 
/* Template Name: iOS Support */
?>
<html>
<html>
<head>
	<title>iOS Support</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/css/ios-static.css">
</head>

<body>
<section id="support">
    <div class="form-wrap">
        <h2>Contact Us</h2>
        <p>Please be sure to visit the Acorn TV Help Center at support.acorn.tv for answers to frequently asked questions, up-to-the-minute alerts, and solutions to many common issues. If you need additional assistance, please select a category and provide a description of the issue you are experiencing. </p>
        <?php gravity_form('ios-support', false, false, false, '', false);?>
    </div>
</section>

</body>
</html>
