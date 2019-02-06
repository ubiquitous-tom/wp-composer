<?php 
/** 
 * Enable or Disable video debugger mode
 * 
 */

$videoDegugger = strtolower(get_query_var('section'));

rljeApiWP_setVideoDebugger($videoDegugger);

wp_redirect(home_url()); /* Redirect browser to homepage */

exit();
