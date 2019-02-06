<?php
/**
 * Smart App Banner used for Android
 *
 * $.smartbanner({
 *      title: null, // What the title of the app should be in the banner (defaults to <title>)
 *      author: null, // What the author of the app should be in the banner (defaults to <meta name="author"> or hostname)
 *      price: 'FREE', // Price of the app
 *      appStoreLanguage: 'us', // Language code for App Store
 *      inAppStore: 'On the App Store', // Text of price for iOS
 *      inGooglePlay: 'In Google Play', // Text of price for Android
 *      icon: null, // The URL of the icon (defaults to <link>)
 *      iconGloss: null, // Force gloss effect for iOS even for precomposed (true or false)
 *      button: 'VIEW', // Text on the install button
 *      scale: 'auto', // Scale based on viewport size (set to 1 to disable)
 *      speedIn: 300, // Show animation speed of the banner
 *      speedOut: 400, // Close animation speed of the banner
 *      daysHidden: 15, // Duration to hide the banner after being closed (0 = always show banner)
 *      daysReminder: 90, // Duration to hide the banner after "VIEW" is clicked (0 = always show banner)
 *      force: null // Choose 'ios' or 'android'. Don't do a browser check, just always show this banner
 *  });
 */
$iconURL = get_template_directory_uri().'/img/atv-app-mobile.png';
?>
<script>
    document.onreadystatechange = function() {
        //Init SmartBanner
        jQuery.smartbanner({
            title: 'Acorn TV - The Best British TV',
            author: 'RLJ Entertainment, Inc.',
            price: '',
            inAppStore: '',
            inGooglePlay: '',
            icon: '<?php echo $iconURL?>',
            button: 'OPEN'
        })
        
        if(jQuery( "#smartbanner" ).length) {
            jQuery('html').addClass('smartBanner');
            window.onscroll = changePos;
            window.onresize = checkWindowWidth;
            changePos();
            function changePos() {
                var $navBarFixed = jQuery('.navbar-fixed-top'),
                    $sideCollapse = jQuery('.navbar-collapse.side-collapse:not(.in)'),
                    containersClasses = [
                        '.feature-slide .carousel-inner',
                        '.container.franchise',
                        '.container.schedule',
                        'section.browse',
                        'section#contact-hero',
                        'body>div.hero'
                    ];
                if (window.pageYOffset > 78) {
                    $navBarFixed.attr('style','position: fixed');
                    $sideCollapse.attr('style', '');
                    for(var i=0; i < containersClasses.length; i++) {
                        jQuery(containersClasses[i]).removeAttr('style');
                    }
                } else {
                    $navBarFixed.attr('style','margin-bottom:0; position: relative');
                    for(var i=0; i < containersClasses.length; i++) {
                        jQuery(containersClasses[i]).attr('style', 'margin-top:0');
                    }
                    checkWindowWidth();
                }
            }
            function checkWindowWidth() {
                var left = '',
                    height = '',
                    $sideCollapse = jQuery('.navbar-collapse.side-collapse');
                if(window.screen.width < 720) {
                    left = 'left:-18px;';
                }
                else if(window.screen.width < 1024) {
                    left = 'left:-25px;';
                }
                if($sideCollapse.hasClass('in')) {
                    height = 'height:0;';
                }
                $sideCollapse.attr('style', 'position: relative; top: -2px; display: inline-block; ' + left + height);
            }
            
            //// Fix issue in the navbar collapse when it is not open.
            jQuery("button.navbar-toggle").bind('click', function(){ 
                if(jQuery( "#smartbanner" ).length) {
                    var $sideCollapse = jQuery('.navbar-collapse.side-collapse');
                    if($sideCollapse.hasClass('in')) {
                        $sideCollapse.css('height', '0');
                    }
                    else {
                        $sideCollapse.css('height', 'auto');
                    }
                    return false;
                }
            });
        }
    };
</script>