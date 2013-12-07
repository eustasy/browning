<?php

	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	error_reporting(-1);

	if(isset($_POST['dear'])&&isset($_POST['subject'])&&isset($_POST['message'])) { // If you're ready to fire

		require 'Browning_Send.php';
		// Make sure you've loaded the script and the config before running this function
		$Mail =  Browning_Send($_POST['dear'], $_POST['subject'], $_POST['message'], $_POST['regards'], '', true);
		// Browning_Send('to email', 'subject', 'message', 'from name', 'reply-to email', recaptcha [boolean: true, false]);
		require 'header.php';
		if($Mail===true) echo '<h2>Done!</h2>';
		else echo '<h2>'.$Mail.'</h2>';
		//require 'done.php';
		require 'footer.php';

	} else {
		require 'header.php';
		require 'style.php';
		require 'form.php';
		require 'footer.php';
	}

 ?>
