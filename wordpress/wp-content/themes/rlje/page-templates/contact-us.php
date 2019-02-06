<?php
/* Template Name: Contact Us Template */
if( is_page( [ 'ios-contactus' ] ) ) {
    get_header('ios');
} else {
    get_header();
}

?>

<section class="content">
	<header class="page-hero">
		<div class="container">
			<h3>Contact Us</h3>
		</div>
	</header>
	<section class="page-body" id="contact-form">
		<div class="container">
			<div class="col-sm-8 col-sm-offset-2 well well-lg">

				<div id="msg"></div>

				<form method="post" id="contact-us">
					<p>
						If you’re having trouble with watching films, signing up, or any difficulty with using the site, please see our <a href="/help">support page</a> for assistance. Many common solutions are provided on our <a href="/faqs"> Frequently Asked Questions</a> page. For all other inquiries, please use the form below and we’ll get in touch.
					</p>

					<div class="form-group">
						<label for="full-name">Name</label>
						<input type="text" id="full-name" class="form-control" required>
					</div>

					<div class="form-group">
						<label for="email">E-Mail Address</label>
						<input type="email" id="email" name="email" class="form-control" required>
					</div>

					<div class="form-group">
						<label for="subject">Subject</label>
						<input type="text" id="subject" name="subject" class="form-control" required>
					</div>

					<div class="form-group">
						<label for="description">Message</label>
						<textarea id="description" title="" name="description" rows="6" class="form-control" required></textarea>
					</div>

					<button class="btn btn-primary btn-large btn-block">Submit</button>

				</form>
			</div>

		</div>
	</section>
</section>

<?php
if( is_page( [ 'ios-contactus' ] ) ) {
    get_footer('ios');
} else {
    get_footer();
}
