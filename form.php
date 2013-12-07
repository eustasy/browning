<?php

	if(isset($_POST['dear']) || isset($_POST['subject']) || isset($_POST['message']) || isset($_POST['regards']) || isset($_POST['replyto'])) { // Something is jammed
		echo '<div class="warning error">';
		if(!isset($_POST['dear'])) echo 'dear';
		if(!isset($_POST['subject'])) echo 'subject';
		if(!isset($_POST['message'])) echo 'message';
		if(!isset($_POST['regards'])) echo 'regards';
		if(!isset($_POST['replyto'])) echo 'replyto';
		echo '</div>';
	}

?>
						<form class="span_1_of_1" action="" method="post">
							<h2>Send a Carrier Pigeon</h2>
							<br>
							<div class="section group">
								<div class="col span_1_of_12"><br></div>
								<div class="col span_2_of_12"><label for="dear"><h3>Dear</h3></label></div>
								<div class="col span_6_of_12"><input type="email" name="dear" placeholder="spitfire@hanger6.com" required /></div>
								<div class="col span_3_of_12"><br></div>
							</div>
							<div class="section group">
								<div class="col span_1_of_12"><br></div>
								<div class="col span_2_of_12"><label for="subject"><h3>Subject</h3></label></div>
								<div class="col span_6_of_12"><input type="text" name="subject" placeholder="You're recent failure to deliver." required /></div>
								<div class="col span_3_of_12"><br></div>
							</div>
							<div class="section group">
								<div class="col span_1_of_12"><br></div>
								<div class="col span_2_of_12"><label for="message"><h3>Message</h3></label></div>
								<div class="col span_6_of_12"><textarea rows="5" name="message" placeholder="Perhaps if you were to fly a little faster, your outside guns wouldn't keep freezing up, what what." required></textarea></div>
								<div class="col span_3_of_12"><br></div>
							</div>
							<div class="section group">
								<div class="col span_1_of_12"><br></div>
								<div class="col span_2_of_12"><label for="regards"><h3>Regards</h3></label></div>
								<div class="col span_6_of_12"><input type="text" name="regards" placeholder="Hurricane 3-40U" required /></div>
								<div class="col span_3_of_12"><br></div>
							</div>
							<div class="section group">
								<div class="col span_1_of_12"><br></div>
								<div class="col span_2_of_12"><label for="replyto"><h3>Reply To</h3></label></div>
								<div class="col span_6_of_12"><input type="email" name="replyto" placeholder="hurricane@hanger3.com" required /></div>
								<div class="col span_3_of_12"><br></div>
							</div>
							<div class="section group">
								<div class="col span_3_of_12"><br></div>
								<div class="col span_6_of_12">
								<?php
									require('recaptcha/recaptchalib.php');
									require('Browning_Config.php');
									echo recaptcha_get_html($Recaptcha_Public);
								?>
								</div>
								<div class="col span_3_of_12"><br></div>
							</div>
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
