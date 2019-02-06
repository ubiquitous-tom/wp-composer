<?php
get_header();
?>

<section class="content">
	<header class="page-hero">
		<div class="container">
			<h3>Customer Support</h3>
		</div>
	</header>
	<section class="page-body" id="contact-form">
		<div class="container">
			<div class="col-sm-8 col-sm-offset-2 well well-lg">

				<div id="msg"></div>

				<form method="post" id="customer-support">

					<p>
						Many common solutions are provided on the <a href="/faqs">Frequently Asked Questions</a> page. If you do not find your answer there, please select the category below that best fits and then tell us about any specifics in the comments box.
					</p>

					<div class="form-group">
						<label for="full-name">Name *</label>
						<input type="text" id="full-name" class="form-control" required>
					</div>

					<div class="form-group">
						<label for="email">E-Mail *</label>
						<input type="email" id="email" name="email" class="form-control" required>
					</div>

					<div class="form-group">
						<label for="topic">Support Topic</label>
						<select name="" id="topic" class="form-control">
							<option value="Billing &amp; Account Management">Billing &amp; Account Management</option>
							<option value="Audio/Video playback">Audio/Video playback</option>
							<option value="Login/Password/Sign Up">Login/Password/Sign Up</option>
							<option value="Gifting">Gifting</option>
							<option value="Apple TV/iOS">Apple TV/iOS</option>
							<option value="Roku">Roku</option>
							<option value="PC/Mac">PC/Mac</option>
							<option value="Amazon Fire TV">Amazon Fire TV</option>
						</select>
					</div>

					<div class="form-group">
						<label for="device-type">Type of Device *</label>
						<input type="text" id="device-type" class="form-control" placeholder="Desktop, Phone, Tablet, Roku" required>
					</div>

					<div class="form-group">
						<label for="device-model">Model</label>
						<input type="text" id="device-model" class="form-control" placeholder="iPhone 8, Samsung Galaxy, Macbook, Windows Desktop etc.">
					</div>

					<div class="form-group">
						<label for="browser-version">Browser &amp; Version</label>
						<input type="text" id="browser-version" class="form-control" placeholder="Safari 11.1.2, MS Edge 42">
					</div>

					<div class="form-group">
						<label for="description">Description of the problem *</label>
						<textarea id="description" class="form-control" rows="5" required></textarea>
					</div>


					<button class="btn btn-primary btn-large btn-block">Submit</button>

				</form>

			</div>
		</div>
	</section>
</section>

<?php
get_footer();
