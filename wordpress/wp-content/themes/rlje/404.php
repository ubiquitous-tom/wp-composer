<?php
header("HTTP/1.0 404 Not Found");
get_header();
?>
<section id="page404">
    <div class="container browse">
        <div id="contentPane">
            <div class="row-fluid">
                <h3 id="pageNotFound">PAGE NOT FOUND <span>:(</span></h3>
                <p>Sorry, we couldn’t find that page. You may have mistyped the address or the page may have moved.</p>
                <p>Visit the <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( get_bloginfo( 'name' ), 'acorntv' ); ?> home page</a></p>
            </div>
        </div>
    </div>
</section>
<?php
get_footer();
?>
