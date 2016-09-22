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
						<form class="span_1_of_1" action="" method="post">
							<h2>Send a Carrier Pigeon</h2>
							<br>
							<div class="section group">
								<div class="col span_1_of_12"><br></div>
								<div class="col span_2_of_12">
									<label for="dear"><h3>Dear</h3></label>
								</div>
								<div class="col span_6_of_12">
									<input type="email" name="dear" placeholder="spitfire@hanger6.com" required />
								</div>
								<div class="col span_3_of_12"><br></div>
							</div>
							<div class="section group">
								<div class="col span_1_of_12"><br></div>
								<div class="col span_2_of_12">
									<label for="subject"><h3>Subject</h3></label>
								</div>
								<div class="col span_6_of_12">
									<input type="text" name="subject" placeholder="You're recent failure to deliver." required />
								</div>
								<div class="col span_3_of_12"><br></div>
							</div>
							<div class="section group">
								<div class="col span_1_of_12"><br></div>
								<div class="col span_2_of_12">
									<label for="message"><h3>Message</h3></label>
								</div>
								<div class="col span_6_of_12">
									<textarea rows="5" name="message" placeholder="Perhaps if you were to fly a little faster, your outside guns wouldn't keep freezing up, what what." required></textarea>
								</div>
								<div class="col span_3_of_12"><br></div>
							</div>
							<div class="section group">
								<div class="col span_1_of_12"><br></div>
								<div class="col span_2_of_12"><label for="regards"><h3>Regards</h3></label></div>
								<div class="col span_6_of_12"><input type="text" name="regards" placeholder="Hurricane 3-40U" required /></div>
								<div class="col span_3_of_12"><br></div>
							</div>
							<?php
								if ( $Recaptcha['Enable'] ) {
										?>
							<div class="section group">
								<div class="col span_3_of_12"><br></div>
								<div class="col span_6_of_12">
										<script src="https://www.google.com/recaptcha/api.js"></script>
										<div class="g-recaptcha" data-sitekey="<?php echo $Recaptcha['SiteKey']; ?>"></div>
								</div>
								<div class="col span_3_of_12"><br></div>
							</div>
									<?php
								}
							?>
							<div class="section group">
								<div class="col span_3_of_12"><br></div>
								<div class="col span_6_of_12">
									<div class="section group">
										<div class="col span_1_of_3">
											<input type="reset" value="Reset" />
										</div>
										<div class="col span_1_of_6"><br></div>
										<div class="col span_1_of_2">
											<input type="submit" value="Send" />
										</div>
									</div>
								</div>
							</div>
						</form>
