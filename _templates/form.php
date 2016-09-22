<?php

	if (
		isset($_POST['dear']) ||
		isset($_POST['subject']) ||
		isset($_POST['message']) ||
		isset($_POST['regards'])
	) {
		echo '<div class="warning error">';
		if ( !isset($_POST['dear']) ) {
			echo 'dear';
		}
		if ( !isset($_POST['subject']) ) {
			echo 'subject';
		}
		if ( !isset($_POST['message']) ) {
			echo 'message';
		}
		if ( !isset($_POST['regards']) ) {
			echo 'regards';
		}
		echo '</div>';
	}

?>
						<form class="whole grid" action="" method="post">

							<h2>Send a Carrier Pigeon</h2>
							<br>

							<div class="grid">
								<div class="whole medium-third equalize">
									<label for="dear"><h3>Dear</h3></label>
								</div>
								<div class="whole medium-two-thirds equalize">
									<input type="email" name="dear" placeholder="spitfire@hanger6.com" required class="background-midnight-blue color-white rounded whole" />
								</div>
							</div>

							<div class="grid">
								<div class="whole medium-third equalize">
									<label for="subject"><h3>Subject</h3></label>
								</div>
								<div class="whole medium-two-thirds equalize">
									<input type="text" name="subject" placeholder="You're recent failure to deliver." required class="background-midnight-blue color-white rounded whole" />
								</div>
							</div>

							<div class="grid">
								<div class="whole medium-third equalize">
									<label for="message"><h3>Message</h3></label>
								</div>
								<div class="whole medium-two-thirds equalize">
									<textarea rows="5" name="message" placeholder="Perhaps if you were to fly a little faster, your outside guns wouldn't keep freezing up." required class="background-midnight-blue color-white rounded whole"></textarea>
								</div>
							</div>

							<div class="grid">
								<div class="whole medium-third equalize">
									<label for="regards"><h3>Regards</h3></label>
								</div>
								<div class="whole medium-two-thirds equalize">
									<input type="text" name="regards" placeholder="Hurricane 3-40U" required class="background-midnight-blue color-white rounded whole" />
								</div>
							</div>

							<?php
								if ( $Recaptcha['Enable'] ) {
										?>
							<div class="grid">
								<div class="whole medium-third"></div>
								<div class="whole medium-two-thirds">
										<script src="https://www.google.com/recaptcha/api.js" async></script>
										<div class="g-recaptcha float-right" data-sitekey="<?php echo $Recaptcha['SiteKey']; ?>"></div>
								</div>
							</div>
									<?php
								}
							?>

							<div class="grid">
								<div class="whole medium-third equalize"></div>
								<div class="whole medium-third equalize">
									<input class="false-text-button background-transparent color-white" type="reset" value="Reset" />
								</div>
								<div class="whole medium-third equalize">
									<input class="button background-white color-belize-hole display-block rounded float-right min-width-10vw text-center" type="submit" value="Send E-Mail" />
								</div>
							</div>

						</form>
