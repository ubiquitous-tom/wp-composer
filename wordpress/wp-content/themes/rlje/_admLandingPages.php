<?php

/**
 * Landing Pages Section - WP Administrator
 */

/*
* Creates Landing Page post type as page.
*/
add_action( 'init', 'acorntv_create_landing_pages_post_type' );
function acorntv_create_landing_pages_post_type() {
    register_post_type(
        'atv_landing_page',
        array(
            'labels' => array(
                'name' => __( 'Landing Pages' ),
                'singular_name' => __( 'Landing Page' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'landing'),
            'capability_type' => 'page',
            'menu_position' => 5,
            'menu_icon'   => 'dashicons-format-aside',
            'supports' => array(
                'title',
                'editor',
                'excerpt',
                //'thumbnail',
                //'author',
                //'trackbacks',
                //'custom-fields',
                //'comments',
                'revisions',
                //'page-attributes', // (menu order, hierarchical must be true to show Parent option)
                //'post-formats',
            ),
            'register_meta_box_cb' => 'acorntv_add_landing_page_metaboxs'
        )
    );
}
// Add the metaboxs for landing pages.
function acorntv_add_landing_page_metaboxs() {
    add_meta_box( 'atv_trailer_metabox', 'Trailer', 'acorntv_trailer_metabox', 'atv_landing_page', 'normal' );
    add_meta_box( 'atv_quote_metabox', 'Quote', 'acorntv_quote_metabox', 'atv_landing_page', 'normal' );
    add_meta_box( 'atv_franchiseId_metabox', 'Franchise', 'acorntv_franchiseId_metabox', 'atv_landing_page', 'side' );
    add_meta_box( 'atv_featuredImageUrl_metabox', 'Featured Image', 'acorntv_featuredImageUrl_metabox', 'atv_landing_page', 'side' );
}
function acorntv_trailer_metabox() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="atv_trailer_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

    // Get the data if it's already been entered
    $trailer_id = get_post_meta($post->ID, '_atv_trailer_id', true);
    ?>

    <table class="form-table">
        <tr>
            <th>
                <label>Trailer ID</label>
            </th>
            <td>
                <input type="text" name="_atv_trailer_id" id="_atv_trailer_id" placeholder="3566879545470" class="regular-text" value="<?= $trailer_id; ?>">
                <!-- classes: .small-text .regular-text .large-text -->
            </td>
        </tr>
        <tr>
            <th>
                <label>Preview</label>
            </th>
            <td>
                <div id="preview-container">
                    <video id="preview-trailer"
                        <?php if(!empty($trailer_id)): ?>
                        data-video-id="<?= $trailer_id; ?>"
                        <?php endif; ?>
                        data-account="3392051363001"
                        data-player="default"
                        data-embed="default"
                        class="video-js"
                        controls></video>
                    <script src="//players.brightcove.net/3392051363001/default_default/index.min.js"></script>
                </div>

                <script>
                    (function($){
                        var timeOut,
                            player = '<video id="preview-trailer" data-account="3392051363001" data-video-id="%videoId" data-player="default" data-embed="default" class="video-js" controls><\/video><script src="//players.brightcove.net/3392051363001/default_default/index.min.js"><\/script>',
                            loading = '<img src="/wp-admin/images/spinner-2x.gif" alt="loading..." />';

                        $('#_atv_trailer_id').keyup(function(elm){
                            clearTimeout(timeOut);
                            $('#preview-container').html(loading);
                            timeOut = setTimeout(function(){
                                var newPlayer = player.replace('%videoId', elm.target.value);
                                $('#preview-container').html(newPlayer);
                            }, 3000);
                        });
                    })(jQuery);
                </script>
            </td>
        </tr>
    </table>
    <style>
        #preview-container {
            height: 233px;
            width: 415px;
            position: relative;
        }
        #preview-trailer {
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0px;
            bottom: 0px;
            right: 0px;
            left: 0px;
        }
    </style>
<?php
}
function acorntv_quote_metabox() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="atv_quote_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

    // Get the data if it's already been entered
    $quote_auth = get_post_meta($post->ID, '_atv_quote_auth', true);
    $quote_desc = get_post_meta($post->ID, '_atv_quote_desc', true);
    ?>
    <style>
        #_atv_quote_desc {
            height: 100px;
        }
    </style>
    <table class="form-table">
        <tr>
            <th>
                <label>Description:</label>
            </th>
            <td>
                <textarea name="_atv_quote_desc" id="_atv_quote_desc" placeholder="Quote Description" class="large-text"><?php echo $quote_desc; ?></textarea>
            </td>
        </tr>
        <tr>
            <th>
                <label>Author:</label>
            </th>
            <td>
                <input type="text" name="_atv_quote_auth" class="regular-text" placeholder="Quote Author" value="<?php echo $quote_auth; ?>">
            </td>
        </tr>
    </table>
<?php
}
function acorntv_franchiseId_metabox() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="atv_franchiseId_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

    // Get the data if it's already been entered
    $franchise_id = get_post_meta($post->ID, '_atv_franchiseId', true);
    ?>

    <table class="form-table">
        <tr>
            <th>
                <label>Franchise ID</label>
            </th>
            <td>
                <input type="text" name="_atv_franchiseId"  placeholder="franchiseid" class="medium-text" value="<?= $franchise_id; ?>">
            </td>
        </tr>
    </table>
<?php
}
function acorntv_featuredImageUrl_metabox() {
    global $post;
    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="atv_featuredImageUrl_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

    // Get the data if it's already been entered
    $featuredImageUrl = get_post_meta($post->ID, '_atv_featuredImageUrl', true);
    ?>
    <style>
        .featureImg-loading {
            display: none;
        }
        .featuredImg-preview {
            width: 100%;
        }
        td.featureImg-preview-td {
            padding: 0;
        }
    </style>
    <table class="form-table">
        <tr>
            <th>
                <label>Image URL</label>
            </th>
            <td>
                <input type="text" name="_atv_featuredImageUrl" id="_atv_featuredImageUrl" class="medium-text" placeholder="http://domain.com/img/my-image.jpg" value="<?= $featuredImageUrl; ?>">
            </td>
        </tr>
        <tr>
            <th>
                <label>Preview</label>
            </th>
            <td>
                <img class="featureImg-loading" src="/wp-admin/images/spinner-2x.gif" alt="loading..." />
            </td>
        </tr>
        <tr>
            <td class="featureImg-preview-td" colspan="2">
                <div id="featuredImg-preview-container">
                    <img class="featuredImg-preview" src="<?= (!empty($featuredImageUrl)) ? $featuredImageUrl : 'https://placeholdit.imgix.net/~text?txtsize=33&txt=No%20Image&w=250&h=150'; ?>" alt="Featured Image"/>
                </div>
           </td>
            <script>
                (function($){
                    var timeOut,
                        $featureImg = $('.featuredImg-preview'),
                        $loading = $('.featureImg-loading');
                    $featureImg.on('error', function(){
                        $featureImg.attr('src', 'https://placeholdit.imgix.net/~text?txtsize=33&txt=No%20Image&w=350&h=250');
                        $loading.hide();
                    });
                    $featureImg.on('load', function(){
                        $loading.hide();
                    });

                    $('#_atv_featuredImageUrl').keyup(function(elm){
                        if(elm.target.value.length > 4) {
                            clearTimeout(timeOut);
                            $loading.show();
                            timeOut = setTimeout(function() {
                                $featureImg.attr('src', elm.target.value);
                            }, 3000);
                        }
                        else {
                            $featureImg.attr('src', 'https://placeholdit.imgix.net/~text?txtsize=33&txt=No%20Image&w=350&h=250');
                        }
                    });
                })(jQuery);
            </script>
        </tr>
    </table>
<?php
}

// Save the acorntv landing page data
add_action( 'save_post', 'acorntv_landing_page_save_meta', 1, 2 );
function acorntv_landing_page_save_meta( $post_id, $post ) { // save the data
    acorntv_save_metabox_data($post, 'atv_trailer_noncename',  array('_atv_trailer_id'));
    acorntv_save_metabox_data($post, 'atv_quote_noncename',  array('_atv_quote_desc', '_atv_quote_auth'));
    acorntv_save_metabox_data($post, 'atv_franchiseId_noncename',  array('_atv_franchiseId'));
    acorntv_save_metabox_data($post, 'atv_featuredImageUrl_noncename',  array('_atv_featuredImageUrl'));
}

/**
 * Save the metabox data in db.
 * @param string $noncename
 * @param array $fieldsName
 * @return null
 */
function acorntv_save_metabox_data($post, $nonceName, $fieldsName) {
    /**
     * Verify if this came from our screen and with the proper authorization,
     * because the save_post action can be triggered at other times,
     * and check if the user is allowed to edit the post or page.
     */
    if (!empty( $_POST[$nonceName] ) && wp_verify_nonce( $_POST[$nonceName], plugin_basename(__FILE__) ) && current_user_can( 'edit_post', $post->ID )) {
        // Find and save the data even if it is an auto-save (preview), draft or review.
        foreach( $fieldsName as $fieldName ) {
            $fieldValue = $_POST[$fieldName]; // if $_POST[$fieldName] is an array change for implode(',', (array)$_POST[$fieldName]) to make it a CSV (unlikely)
            $getMetaData = get_post_meta( $post->ID, $fieldName, true );

            if( is_string($getMetaData) ) {
                update_metadata( 'post', $post->ID, $fieldName, $fieldValue );
            }
            else { // if the custom field doesn't have a value
                add_metadata( 'post', $post->ID, $fieldName, $fieldValue );
            }
        }
    }
}

/***** WP REVISION *****/

//Get Fields created.
function acorntv_getFields($fields = array()) {
    //Add here the custom fields added to show it in the wp revision screen.
    $fields['_atv_trailer_id'] = 'Trailer Id';
    $fields['_atv_quote_desc'] = 'Quote Description';
    $fields['_atv_quote_auth'] = 'Quote Author';
    $fields['_atv_franchiseId'] = 'Franchise Id';
    $fields['_atv_featuredImageUrl'] = 'Featured Image URL';
    foreach($fields as $fieldKey=>$fieldValue) {
        // Add the values to each meta fields in the revision screen.
        add_filter( '_wp_post_revision_field_'.$fieldKey, 'acorntv_landing_page_revision_field', 10, 2 );
    }
    return $fields;
}
function acorntv_landing_page_revision_field( $value, $field ) {
    return $value;
}

//Get ALL the MetaData values from each meta field created.
function acorntv_getMetaDatas($post_id) {
    $fields = acorntv_getFields();
    foreach ($fields as $fieldKey=>$fieldValue) {
        $metaDatas[$fieldKey]  = get_metadata( 'post', $post_id, $fieldKey, true );
    }
    return $metaDatas;
}


// Indicate to wordpress which fields to show in the revision screen.
add_filter( '_wp_post_revision_fields', 'acorntv_landing_page_revision_fields' );
function acorntv_landing_page_revision_fields( $fields ) {
    return acorntv_getFields($fields);
}

// Check changes in the custom metadata to create a revision.
add_filter('wp_save_post_revision_check_for_changes', 'force_save_revision', 10, 3);
function force_save_revision( $return, $last_revision, $post )
{
    // Force to save a revision only if it is an atv_landing_page and it has a change.
    if( isset($_POST['post_type']) && $_POST['post_type'] == 'atv_landing_page' )
    {
        $metaDatas = acorntv_getMetaDatas($_POST['post_ID']);
        $isChanged = false;
        foreach ($metaDatas as $metaDataKey => $metaDatasValue) {
            // Only save a revision if the any of the meta data has a change.
            if( is_string($metaDatasValue) && trim($_POST[$metaDataKey]) != trim($metaDatasValue)) {
                $isChanged = true;
                break;
            }
        }
        if($isChanged) {
            $return = false;
        }
    }

    return $return;
}

// Add metabox data to post revision
add_action( 'wp_restore_post_revision', 'acorntv_landing_page_restore_revision', 10, 2 );
function acorntv_landing_page_restore_revision( $post_id, $revision_id ) {
    $revision = get_post( $revision_id );
    $metaDatas = acorntv_getMetaDatas($revision->ID);
    foreach($metaDatas as $metaName => $metaValue) {
        if ( false !== $metaValue ) {
            update_post_meta( $post_id, $metaName, $metaValue );
        }
    }
}
/***** END WP REVISION *****/
