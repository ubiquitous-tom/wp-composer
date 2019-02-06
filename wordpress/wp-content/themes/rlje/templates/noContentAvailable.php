<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="utf-8">
    <meta name="code" content="<?php echo ($code = rljeApiWP_getCountryCode())? $code : 'us'; ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="QCrNnLN11eCtEq_RIVjUQEXRabEJewu4tPwxbjJHHj4" />
    <link rel="shortcut icon" href="<?php echo get_bloginfo('template_url') ?>/img/favicon.ico">
    <title>Acorn TV | <?php bloginfo('description'); ?></title>
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <link rel="stylesheet" id="bootstrap_css-css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css" media="all">
    <?php
    get_template_part('/partials/google-analytics');
    ?>
    <style>
/* IMPORTS */
@import url(https://fonts.googleapis.com/css?family=Oxygen:400,300);
@import url(https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,600);

/* BASE CSS */
body { /*background:#0e0e0e;*/ font-family:'oxygen',sans-serif; color:#ffffff; font-size:14.5px; letter-spacing:.03em; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;}

/* LAYOUT */
.container{width:96%;padding-left: 10px;padding-right: 10px;}

/* NAVBAR */
.atv-logo{width:154px; margin-top:16px; margin-left:11px;}
.navbar{background:#161616;border:none;height:56px;box-shadow: 0 0 30px -5px #050505;-moz-box-shadow: 0 0 20px -5px #000000;-webkit-box-shadow: 0 0 20px -5px #050505;}

/* Active Features Block - Header Block */
.active-features {position: relative;}

/* Country Filter - Header Block */
.navbar-country-filter {position: absolute;bottom: 25px;left: 45px;display: inline-block;}

.footer{width:100%; bottom:0; position:fixed;padding-top:20px;padding-bottom:5px;}
.footer p{font-size: 12px;}

.message-block {margin: 0 auto;max-width: 800px;text-align: center;}
.message-block p {color: white;font-size: 25.5px;}
.message {vertical-align: middle;position: relative;margin-top: 290px;display: inline-block;}
.message-container {position: fixed;top: 20%;left: 0;right: 0;top: 0;bottom: 0;background-image: url(<?php echo esc_url( get_template_directory_uri() . 'hero-desktop_bb2.png' ); ?>);height: 100%;background-position: center;background-repeat: no-repeat;background-size: cover;}
.message-container:after {content: '';position: relative;display: inline-block;width: 0.01px;height: 30%;}

/* Button */
.button {
    background-color: #800000;
    border: none;
    color: white;
    padding: 5px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 12px;
    cursor: pointer;
}

.button:last-child {
  margin-left: 10px;
}

#buttons {
  width: 100%;
  height: 100%;
  position: relative;
  top: 0; bottom: 0;
  left: 0; right: 0;
  margin-top: 0;
}

@media all and (min-width: 480px) {
    /* BASE */
    p {font-size: 14.5px;}
}

@media (min-width: 768px){
    /* NAVBAR */
    .navbar{height:75px;}
    .atv-logo{width:215px;margin-top:20px;margin-left: 0px;}
    /* Country Filter - Header Block */
    .navbar-country-filter {bottom: 35px;left: 60px;}
    /* FOOTER */
    .footer p{font-size: 13.5px;}
}
@media all and (min-width: 1100px) {
    /* LAYOUT */
    .container{width:92%;}
}
@media all and (min-width: 1150px) {
    /* Country Filter - Header Block */
    .navbar-country-filter {top: 0px;}
}
@media (min-width:1400px){
    /* LAYOUT */
    .container{width:1350px}
}
@media (max-width: 1150px) {
    /*.navbar-header {float: none;}]/
}

/*/////////////////////////////////////////////////////////////////////
////////////////////////////// ACORN TV 3.01 ///////////////////////////
/////////////////////////////////////////////////////////////////////*/
    </style>
</head>
<body>
    <!-- Fixed Bootstrap navbar -->
    <div class="navbar" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <a href="/"><img src="https://api.rlje.net/acorn/artwork/size/atvlogo?t=Icons&w=300" class="atv-logo"></a>
        </div>
        <div class="active-features">
            <?php
                $countryFilter = rljeApiWP_getCountryFilter();
                if($countryFilter):
            ?>
            <div class="navbar-country-filter"><span>Country: <?= $countryFilter; ?></span></div>
            <?php
                endif;
            ?>
        </div>
      </div>
    </div>

    <div id="contentPane" class="browse container message-block">
        <div class="message-container">
            <p class="message">
                We are sorry but Acorn TV<br> is not yet available in your country.<br><br><br>
	        Our team is still working to bring you world-class TV<br> from Britain and beyond.
            </p>
    	    <div id="buttons">
  	       <div class="button">
                  <button class="button" onclick="window.location.href='http://link.acorn.tv/join/5hb/international'">NOTIFY ME WHEN ACORN TV IS AVAILABLE</button>
  	       </div>
            </div>
        </div>
    </div>

    <div class="container footer">
        <div class="col-md-6 col-sm-12 column">
            <p>© <?php echo date('Y'); ?> RLJ Entertainment, Inc. All Rights Reserved</p>
        </div>
    </div>

</body>
</html>
