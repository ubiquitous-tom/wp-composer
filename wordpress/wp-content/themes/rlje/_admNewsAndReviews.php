<?php

/*
 * News & Reviews Section - WP Administrator
 */

/*
* Checks if the options exist else set marketing_placeholder and latest_news with default data.
*/
add_action( 'after_setup_theme', 'newsAndReviews_init_setup' );
function newsAndReviews_init_setup() {
    if(empty(get_option('acorntv_marketing_placeholder'))) {
        //Set default values to MarketingPlaceholder
        $marketingPlaceholder = array(
            array(
                "type" => "image",
                "franchiseId" => "jackirish",
                "src" => "http://atv3.us/wp-content/uploads/homepage-ad.png"
            ),
            array(
                "type" => "video",
                "franchiseId" => "",
                "src" => "4328731797001"
            ),
            array(
                "type" => "image",
                "franchiseId" => "",
                "src" => ""
            ),
            array(
                "type" => "image",
                "franchiseId" => "",
                "src" => ""
            ),
            array(
                "type" => "image",
                "franchiseId" => "",
                "src" => ""
            )
        );
        add_option('acorntv_marketing_placeholder', $marketingPlaceholder);
    }
    if(empty(get_option('acorntv_latest_news'))) {
        //Set default values to LatestNews
        $newsOptions = array(
            array(
                "title" => "Variety reviews British Miniseries ‘New Worlds’",
                "image" => "http://atv3.us/wp-content/uploads/variety.png",
                "link" => "http://variety.com/2015/digital/news/british-miniseries-new-worlds-starring-jamie-dornan-coming-to-acorn-tv-svod-service-1201410610/"
            ),
            array(
                "title" => "Wall Street Journal  ‘Serangoon Road’ Review",
                "image" => "http://atv3.us/wp-content/uploads/wsj_v4.png",
                "link" => "http://www.wsj.com/articles/tv-review-serangoon-roadsleuthing-in-singapore-1418351931"
            ),
            array(
                "title" => "NY Daily News 4 Star ‘Jamaica Inn’ Review",
                "image" => "http://atv3.us/wp-content/uploads/dailynews_v1.png",
                "link" => "http://www.nydailynews.com/entertainment/tv/review-jamaica-inn-article-1.2148676"
            ),
            array(
                "title" => "Variety reviews British Miniseries ‘New Worlds’",
                "image" => "http://atv3.us/wp-content/uploads/variety.png",
                "link" => "http://variety.com/2015/digital/news/british-miniseries-new-worlds-starring-jamie-dornan-coming-to-acorn-tv-svod-service-1201410610/"
            )
        );
        add_option('acorntv_latest_news', $newsOptions);
    }
    if(empty(get_option('acorntv_news_options'))) {
        //Set default values to NewsOptions
        $newsOptions = array(
            array(
                "title" => "Variety",
                "image" => "http://atv3.us/wp-content/uploads/variety.png"
            ),
            array(
                "title" => "Wall Street Journal",
                "image" => "http://atv3.us/wp-content/uploads/wsj_v4.png"
            ),
            array(
                "title" => "NY Daily News",
                "image" => "http://atv3.us/wp-content/uploads/dailynews_v1.png"
            )
        );
        add_option('acorntv_news_options', $newsOptions);
    }
}

/*
* Actions applied only in the WP Admin.
*/
if ( is_admin() ) { 
    $FIELD_PREFIX = 'acorntv_';

    /**
     * Add javascript and css files only if it is a News and Reviews page.
     */
    add_action( 'admin_enqueue_scripts', 'admin_enqueue_list' );
    function admin_enqueue_list($hook) {
        if('toplevel_page_newsAndReviews' == $hook || 'news-reviews_page_reviewsLogoSettings' == $hook ) {
            wp_enqueue_style( 'jquery-ui-theme-css', get_template_directory_uri() . '/lib/jquery/ui/jquery-ui.theme.min.css' );
            wp_enqueue_script( 'jquery-ui-js', get_template_directory_uri() . '/lib/jquery/ui/jquery-ui.min.js' );
            wp_enqueue_media();
        }
    }

    /**
     * Register custom settings options in the admin.
     */
    add_action('admin_init', 'create_settings');
    function create_settings() {
        global $FIELD_PREFIX;

        register_setting(
            $FIELD_PREFIX.'news_and_reviews',
            $FIELD_PREFIX.'marketing_placeholder',
            null
        );
        register_setting(
            $FIELD_PREFIX.'news_and_reviews',
            $FIELD_PREFIX.'latest_news',
            null
        );

        add_settings_section(
            $FIELD_PREFIX.'marketing_placeholder_options',
            'News',
            function() {
                print '<p>Load the News images (marketing) or video trailers to show in homepage.</p>';
            },
            'newsAndReviews'
        );
        add_settings_section(
            $FIELD_PREFIX.'latest_news_options',
            'Reviews',
            function() {
                print '<p>Load the Reviews with logo, title and link to show in homepage.</p>';
            },
            'newsAndReviews'
        );

        $reviewItems = array(
            '1st',
            '2nd',
            '3rd',
            '4th',
            '5th'
        );

        for($i=0; $i < 5; $i++) {
            add_settings_field(
                'marketing_placeholder_'.$i,
                '<span class="ui-icon ui-icon-arrowthick-2-n-s" style="display:inline-block;vertical-align:top;"></span><span>'.$reviewItems[$i].' News:</span>',
                'acorntv_marketing_placeholder_field',
                'newsAndReviews',
                $FIELD_PREFIX.'marketing_placeholder_options',
                array($i)
            );

            if($i < 4) {
                add_settings_field(
                    'latest_news_fields_'.$i,
                    '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span><span>'.$reviewItems[$i].' Review:</span>',
                    'acorntv_latest_news_fields',
                    'newsAndReviews',
                    $FIELD_PREFIX.'latest_news_options',
                    array($i)
                );
            }
        }

        register_setting(
            $FIELD_PREFIX.'reviews_logo_settings',
            $FIELD_PREFIX.'news_options',
            null
        );

        add_settings_section(
            $FIELD_PREFIX.'news_options',
            'Reviews Logo Options',
            function() {
                print '<p>Add or Edit the Reviews Logo.</p><button id="addReviewOption">Add New Review Logo</button>';
            },
            'reviewsLogoSettings'
        );

        $news_opts = get_option($FIELD_PREFIX.'news_options');

        if(is_array($news_opts)) {
            foreach ($news_opts as $key=>$opt) {
                add_settings_field(
                    'news_option_fields_'.$key,
                    'Review Logo <br/>Title and Image:',
                    'acorntv_news_option_fields',
                    'reviewsLogoSettings',
                    $FIELD_PREFIX.'news_options',
                    array($key)
                );
            }
        }
    }

    /**
     * Marketing placeholder field template.
     * @param Array $keyParam Array parameters with a key to identify each field.
     */
    function acorntv_marketing_placeholder_field($keyParam) {
        global $FIELD_PREFIX;
        $key = absint($keyParam[0]);
        $opt_name = $FIELD_PREFIX.'marketing_placeholder';
        $opt_value = get_option($opt_name);
        $value = (!empty($opt_value[$key]['src'])) ? esc_attr($opt_value[$key]['src']) : '';
        $franchiseId = (!empty($opt_value[$key]['franchiseId'])) ? esc_attr($opt_value[$key]['franchiseId']) : '';
        $externalLink = (!empty($opt_value[$key]['externalLink'])) ? esc_attr($opt_value[$key]['externalLink']) : '';
        $imageSelected = 'selected';
        $videoSelected = $extImageSelected = '';
        $displayNone = 'style="display:none"';
        $defaultImageSrcSize = 70;
        $defaultExtImageSrcSize = 45;
        $defaultVideoSrcSize = 20;
        $srcSize = $defaultImageSrcSize;
        $defaultImageSrcPlaceholder = 'http://[image-url]';
        $defaultVideoSrcPlaceholder = 'ID Number';
        $srcPlaceholder = $defaultImageSrcPlaceholder;
        if(!empty($opt_value[$key]['type'])) {
            switch ($opt_value[$key]['type']) {
                case 'video': 
                    $imageSelected = '';
                    $videoSelected = 'selected';
                    $srcSize = $defaultVideoSrcSize;
                    $srcPlaceholder = $defaultVideoSrcPlaceholder;
                    break;
                case 'extImage': 
                    $imageSelected = $videoSelected = '';
                    $extImageSelected = 'selected';
                    $srcSize = $defaultExtImageSrcSize;
                    break;
            }
        }
?>
        <select class="mkgType" name="<?= $opt_name; ?>[<?= $key; ?>][type]" style="vertical-align:top">
            <option value="image" <?= $imageSelected; ?>>Franchise ID & Image</option>
            <option value="extImage" <?= $extImageSelected; ?>>External Link & Image</option>
            <option value="video" <?= $videoSelected; ?>>Trailer ID</option>
        </select>
        <input class="mkgFranchiseId" <?= (empty($imageSelected)) ? $displayNone : ''; ?> name="<?= $opt_name; ?>[<?= $key; ?>][franchiseId]" type="text" size="20" placeholder="FranchiseId" value="<?= $franchiseId; ?>"/>
        <input class="mkgExternalLink" <?= (empty($extImageSelected)) ? $displayNone : ''; ?> name="<?= $opt_name; ?>[<?= $key; ?>][externalLink]" type="text" size="45" placeholder="http://[external-link]" value="<?= $externalLink; ?>"/>
        <input class="mkgSrc uploadImage" name="<?= $opt_name; ?>[<?= $key; ?>][src]" type="text" size="<?= $srcSize; ?>" placeholder="<?= $srcPlaceholder; ?>" value="<?= $value; ?>"
               data-videoSrcSize="<?= $defaultVideoSrcSize; ?>" 
               data-imageSrcSize="<?= $defaultImageSrcSize; ?>"
               data-extImageSrcSize="<?= $defaultExtImageSrcSize; ?>"
               data-imagePlaceholder="<?= $defaultImageSrcPlaceholder; ?>"
               data-videoPlaceholder="<?= $defaultVideoSrcPlaceholder; ?>"
        />
        <button class="uploadBtn" <?= (!empty($videoSelected)) ? $displayNone : ''; ?>>Upload Image</button>
<?php
    }

    /**
     * Latest News field template.
     * @param Array $keyParam Array parameters with a key to identify each field.
     */
    function acorntv_latest_news_fields($keyParam) {
        global $FIELD_PREFIX;
        $key = absint($keyParam[0]);
        $opt_name = $FIELD_PREFIX.'latest_news';
        $opt_value = get_option($opt_name);
        $news_options = get_option($FIELD_PREFIX.'news_options');
        $valueTitle = (!empty($opt_value[$key]['title'])) ? esc_attr($opt_value[$key]['title']) : '';
        $valueImage = (!empty($opt_value[$key]['image'])) ? esc_attr($opt_value[$key]['image']) : '';
        $valueLink = (!empty($opt_value[$key]['link'])) ? esc_attr($opt_value[$key]['link']) : '';
?>
<table class="latest_news_table">
    <tbody>
        <tr>
            <th scope="row">Review Logo:</th>
            <td>
                <select class="lnwsImage" name="<?= $opt_name; ?>[<?= $key; ?>][image]" style="vertical-align:top">
                    <option value="" <?= (!empty($valueImage)) ? '': 'selected'; ?>>-- Select a Review Logo --</option>
                    <?php 
                        if(is_array($news_options) && count($news_options) > 0) :
                            foreach($news_options as $news_option) : 
                    ?>
                    <option value="<?= $news_option['image']; ?>" <?= ($valueImage === $news_option['image']) ? 'selected="selected"' : '' ; ?>><?= $news_option['title']; ?></option>
                    <?php 
                            endforeach;
                        endif;
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row">Review Title:</th>
            <td>
                <input class="lnwsText" name="<?= $opt_name; ?>[<?= $key; ?>][title]" type="text" size="70" placeholder="Title..." value="<?= $valueTitle;?>">
            </td>
        </tr>
        <tr>
            <th scope="row">Review Link:</th>
            <td><input class="lnwsLink" name="<?= $opt_name; ?>[<?= $key; ?>][link]" type="text" size="70" placeholder="http://[external-link]" value="<?= $valueLink ?>"></td>
        </tr>
    </tbody>
</table>
<?php
    }

    /**
     * News options template.
     * @param Array $keyParam Array parameters with a key to identify each field.
     */
    function acorntv_news_option_fields($keyParam) {
        global $FIELD_PREFIX;
        $key = absint($keyParam[0]);
        $opt_name = $FIELD_PREFIX.'news_options';
        $opt_value = get_option($opt_name);
        $title = (!empty($opt_value[$key]['title'])) ? esc_attr($opt_value[$key]['title']) : '';
        $image = (!empty($opt_value[$key]['image'])) ? esc_attr($opt_value[$key]['image']) : '';
?>
    <input class="rwsOptionTitle" name="<?= $opt_name; ?>[<?= $key; ?>][title]" type="text" size="20" placeholder="Title..." value="<?= $title; ?>" />
    <input class="rwsOptionSrc uploadImage" name="<?= $opt_name; ?>[<?= $key; ?>][image]" type="text" size="70" placeholder="http://..." value="<?= $image; ?>" />
    <button class="uploadBtn">Upload Image</button>
    <?php if($key > 0): ?>
    <button class="removeReviewOption">Remove</button>
<?php
        endif;
    }

    /**
     * Register news and review page in admin menu page.
     */
    add_action( 'admin_menu', 'register_news_and_reviews_page' );
    function register_news_and_reviews_page() {
        add_menu_page(
            'News & Reviews',
            'News & Reviews',
            'manage_options',
            'newsAndReviews',
            'acorntv_news_and_reviews_page',
            'dashicons-feedback',
            4
        );

        add_submenu_page(
            'newsAndReviews',
            'Reviews Logo Options',
            'Reviews Logo Settings',
            'manage_options',
            'reviewsLogoSettings',
            'acorntv_reviews_logo_settings_page'
        );
    }

    /**
     * Admin News and Review page template.
     */
    function acorntv_news_and_reviews_page() {
        global $FIELD_PREFIX;
?>
    <div class="wrap" id="news_reviews">
        <h1>News & Reviews</h1>
        <form method="post" action="options.php">
<?php

        settings_fields($FIELD_PREFIX.'news_and_reviews');

        do_settings_sections('newsAndReviews');

        submit_button();

?>
        </form>
        <script>
            (function($){

                $('.mkgType').change(changeType);

                function changeType(event) {
                    var elm = event.target,
                        $parent = $(elm).parent(),
                        $mkgFranchiseId = $parent.find('.mkgFranchiseId'),
                        $mkgExternalLink = $parent.find('.mkgExternalLink'),
                        $mkgSrc = $parent.find('.mkgSrc'),
                        $uploadBtn = $parent.find('.uploadBtn'),
                        defaultImageSrcSize = $mkgSrc.attr('data-imageSrcSize'),
                        defaultVideoSrcSize = $mkgSrc.attr('data-videoSrcSize'),
                        defaultExtImageSrcSize = $mkgSrc.attr('data-extImageSrcSize'),
                        defaultImagePlaceholder = $mkgSrc.attr('data-imagePlaceholder'),
                        defaultVideoPlaceholder = $mkgSrc.attr('data-videoPlaceholder');
                    if($(elm).val() === "image") {
                      $mkgExternalLink.hide();
                      $mkgFranchiseId.show();
                      $uploadBtn.show();
                      $mkgExternalLink.attr("value", "");
                      $mkgSrc.attr({"size": defaultImageSrcSize, "placeholder": defaultImagePlaceholder, "value": ""});
                    }
                    else if($(elm).val() === "extImage") {
                      $mkgFranchiseId.hide();
                      $mkgExternalLink.show();
                      $uploadBtn.show();
                      $mkgFranchiseId.attr("value", "");
                      $mkgSrc.attr({"size": defaultExtImageSrcSize, "placeholder": defaultImagePlaceholder, "value": ""});
                    }
                    else {
                      $mkgFranchiseId.hide();
                      $mkgExternalLink.hide();
                      $uploadBtn.hide();
                      $mkgFranchiseId.attr("value", "");
                      $mkgExternalLink.attr("value", "");
                      $mkgSrc.attr({"size": defaultVideoSrcSize, "placeholder": defaultVideoPlaceholder, "value": ""});
                    }
                };

                $('.mkgType').parents('tbody').addClass('sortable news_section');
                $('.lnwsText').parents('.form-table > tbody').addClass('sortable');

                $('.sortable').sortable({
                    placeholder: "sortable-placeholder",
                    connectWith: ".sortable",
                    start: function(e, ui){
                        ui.placeholder.height(ui.item.height());
                    }
                });
                $('.sortable').sortable({
                    update: function( event, ui ) {
                        $(event.target).find('> tr > td').each(function(key, elem) {
                            $(elem).parents('tr.ui-sortable-handle').attr("id", key);
                            $(elem).children().each(updateFields);
                        });
                    }
                });

                function updateFields(key,elm) {
                    var id = $(elm).parents('tr[id]').attr('id'),
                        name = $(elm).attr('name');
                    if(typeof name !== 'undefined') {
                        $(elm).attr('name', name.replace( /(.+)\[[\d]+\](.+)/, "$1["+id+"]$2"));
                    }
                    else{
                        $(elm).find('td *').each(updateFields);
                    }
                }

                $('.mkgType').each(function(key,elem){
                    $(elem).parents('tr').addClass('ui-state-default').attr('id',key);
                });

                $('.lnwsText').each(function(key,elem){
                    $(elem).parents('.ui-sortable-handle').addClass('ui-state-default').attr('id',key);
                });

                //Add break lines as separetor after to News section.
                $('.news_section').parent().after('<div class="section-separator"><br/><br/><br/></div>');

                //Upload image action button
                $('.uploadBtn').click(function(e) {
                    e.preventDefault();
                    var setImageUrl = function(value) {
                            var imageUrl = value.replace('http', 'https');
                            $(e.target).parent().find('input.uploadImage').val(imageUrl);
                        },
                        image = wp.media({ 
                        title: 'Upload Image',
                        multiple: false // True if you want to upload multiple files at once.
                    })
                    .open()
                    .on('select', function(){
                        // Return the selected image from the Media Uploader (the result is an object).
                        var uploaded_image = image.state().get('selection').first();
                        // Convert uploaded_image to a JSON object doing it more easy to handle.
                        var image_url = uploaded_image.toJSON().url;
                        // Set the url value to the proper input field.
                        setImageUrl(image_url);
                    });
                });
            })(jQuery);
        </script>
        <style>
            .form-table tr.ui-sortable-handle {
                cursor: move;
            }
            .form-table .ui-state-default th {
                vertical-align: middle;
            }
            .sortable-placeholder {
                background-color: #ccc;
            }
            .ui-icon-arrowthick-2-n-s {
                display:inline-block;
                vertical-align:top;
            }
            .news_section th {
                width: 125px;
            }
        </style>
    </div>
<?php
    }

    /**
     * Admin Review Logo Settings page template.
     */
    function acorntv_reviews_logo_settings_page() {
        global $FIELD_PREFIX;
?>
    <div class="wrap" id="reviews_logo_settings">
        <h1>Reviews Logo Settings</h1>
        <form method="post" action="options.php">
<?php

        settings_fields($FIELD_PREFIX.'reviews_logo_settings');

        do_settings_sections('reviewsLogoSettings');

        submit_button();

?>
        </form>
        <p class="notice">
            * After to save any change, you need go to <a href="<?= get_admin_url().'admin.php?page=newsAndReviews'; ?>">News and Reviews</a> and update the proper Review Logo fields then clicks on <b>Save Changes</b> to apply it.
        </p>
        <script>
            (function($){

                $('.rwsOptionTitle').each(updateNewsOptionsKeys);

                function updateNewsOptionsKeys(key, elem) {
                    $(elem).parents('tr').attr('id',key);
                    if(key == 0) {
                        $(elem).parents('tbody').attr('id', 'news_options');
                    }
                }

                $('#addReviewOption').click(addNewsOption);

                function addNewsOption(event) {
                    event.preventDefault();
                    var $lastTr = $('#news_options > tr:last-child'),
                        id = $lastTr.attr('id'),
                        $newTr = $lastTr.clone().attr('id', id*1+1);
                    //Update ids and clean fields
                    $newTr.find('input').each(function(key,elem){
                        updateFields(key,elem);
                        emptyFields(elem);
                    });
                    $newTr.find('.removeReviewOption').on('click', removeReviewOption);
                    $newTr.find('.uploadBtn').on('click', uploadBtn);
                    $lastTr.after($newTr);
                }

                function updateFields(key,elm) {
                    var id = $(elm).parents('tr[id]').attr('id'),
                        name = $(elm).attr('name');
                    if(typeof name !== 'undefined') {
                        $(elm).attr('name', name.replace( /(.+)\[[\d]+\](.+)/, "$1["+id+"]$2"));
                    }
                }

                function emptyFields(elem) {
                    $(elem).val('');
                }

                $('.removeReviewOption').click(removeReviewOption);

                function removeReviewOption(event) {
                    event.preventDefault();
                    $(event.target).parents('tr').remove();
                    //Reorder the tr element id.
                    $('.rwsOptionTitle').each(function(key, elem) {
                        $(elem).parents('tr').attr('id', key);
                    });
                    $('#news_options td > input').each(updateFields);
                }

                //Upload image action button
                $('.uploadBtn').click(uploadBtn);

                function uploadBtn(e) {
                    e.preventDefault();
                    var setImageUrl = function(value) {
                            var imageUrl = value.replace('http', 'https');
                            $(e.target).parent().find('input.uploadImage').val(imageUrl);
                        },
                        image = wp.media({ 
                        title: 'Upload Image',
                        multiple: false // True if you want to upload multiple files at once.
                    })
                    .open()
                    .on('select', function(){
                        // Return the selected image from the Media Uploader (the result is an object).
                        var uploaded_image = image.state().get('selection').first();
                        // Convert uploaded_image to a JSON object doing it more easy to handle.
                        var image_url = uploaded_image.toJSON().url;
                        // Set the url value to the proper input field.
                        setImageUrl(image_url);
                    });
                }

            })(jQuery);
        </script>
    </div>
<?php
    }
}