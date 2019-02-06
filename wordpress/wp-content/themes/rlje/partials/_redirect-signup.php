<?php
$environment = apply_filters('atv_get_extenal_subdomain', '');
?>
<script>
    if (window.location.hash === '' && window.location.pathname === '/') {
        if (!docCookies.hasItem('ATVSessionCookie')) {
            if (!docCookies.hasItem('visited')) {
                docCookies.setItem('visited', true);
                window.location = 'https://signup<?= $environment; ?>.acorn.tv';
            }
        }
    }
    docCookies.setItem('visited', true);
</script>
