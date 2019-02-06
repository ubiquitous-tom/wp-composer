<?php 
/** 
 * Set future date in session variable to use it in all the calls
 * to content services.
 * 
 */

$futureDate = $wp_query->query_vars['section'];

rljeApiWP_setFutureDate($futureDate);

header("Location: ".get_site_url()); /* Redirect browser to homepage */

exit();