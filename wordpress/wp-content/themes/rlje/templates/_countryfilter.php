<?php 
/** 
 * Set country filter in cookie to use it in all the calls
 * to content services.
 * 
 */

$countryFilter = $wp_query->query_vars['section'];

rljeApiWP_setCountryFilter($countryFilter);

wp_redirect(home_url()); /* Redirect browser to homepage */

exit();