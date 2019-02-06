jQuery(function($) {

    $('form#contact-us').on('submit', function(event) {
        var submit_button = jQuery(this).find('button');
        var submit_button_width = submit_button.width();
        var submit_button_content = submit_button.html();
        submit_button.prop('disabled', true).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
        submit_button.width(submit_button_width);
        event.preventDefault();
        var name = $(this).find('input#full-name').val();
        var email_address = $(this).find('input#email').val();
        var subject = $(this).find('input#subject').val();
        var desc = $(this).find('textarea#description').val();
        $.post(
            contact_vars.ajax_url,
            {
                'action': 'process_contact_us',
                'name': name,
                'email': email_address,
                'subject': subject,
                'desc': desc
            },
            function (response) {
                if (response.success == false) {
                    $("#msg").html("<div class=\"alert alert-danger\">There was a problem with your submission, please try again.</div>");
                } else {
                    $('#msg').html("<div class=\"alert alert-success\"><strong>Thank you for contacting UMC.</strong><br>We’ve received your email. A UMC support representative will review your request and send you a personal response.</div>");
                }
                $(window).scrollTop(0);
                submit_button.prop('disabled', false).html(submit_button_content);
            }
        )
    })
})