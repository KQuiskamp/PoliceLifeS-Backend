<?php
defined("main") or die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");

function checkRecaptcha() {
	$post_data = http_build_query(
	    array(
	        'secret' => "",
	        'response' => $_POST['g-recaptcha-response'],
	        'remoteip' => $_SERVER['REMOTE_ADDR']
	    )
	);
	$opts = array('http' =>
	    array(
	        'method'  => 'POST',
	        'header'  => 'Content-type: application/x-www-form-urlencoded',
	        'content' => $post_data
	    )
	);
	$context  = stream_context_create($opts);
	$response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
	$result = json_decode($response);
	if (!$result->success) {
	    return false;
	}

	return true;
}

if(isset($_POST["submit"])) {
	if(checkRecaptcha() == true) {
		if(isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["message"])) {
			if(!empty($_POST["username"])) {
				if(!empty($_POST["email"]) && $utility->emailcheck($_POST["email"])) {
					if(!empty($_POST["message"]) && strlen($_POST["message"]) >= 10) {

                        $mail = new PHPMailer;
                        $mail->isSMTP();
                        $mail->Host = '';
                        $mail->SMTPAuth = true;
                        $mail->Username = '';
                        $mail->Password = '';
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;
                        $mail->CharSet = 'UTF-8';

			            $mail->AddReplyTo($_POST["email"], $_POST["username"]);
			            $mail->setFrom($pagedata["contact_email"],"PoliceLifeS Contact-Form");
			            $mail->addAddress($pagedata["contact_email"], "PoliceLifeS Contact-Form");
			            $mail->isHTML(true);

			            $mail->Subject = 'PoliceLifeS Contact-Form from '.$_POST["username"];
			            $mail->Body = "	<h4>Username: ".$_POST["username"]." | E-Mail: ".$_POST["email"]."</h4><br />
			            				<pre>".htmlentities($_POST["message"])."</pre>";
			            if($mail->send()) {
			            	unset($_POST);
			            	echo '<p class="msg-success">Your email has been sent successfully! We will contact you!</p>';
			            }else {
			            	echo '<p class="msg-error">E-Mail was not sent, please try it later!</p>';
			            }
					}else {
						echo '<p class="msg-error">You must enter a valid Message with min. 10 characters!</p>';
					}
				}else {
					echo '<p class="msg-error">You must enter a valid E-Mail Address (test@example.com)!</p>';
				}
			}else {
				echo '<p class="msg-error">You must enter a Username!</p>';
			}
		}else {
			echo '<p class="msg-error">You must fill all fields!</p>';
		}
	}else{
		echo '<p class="msg-error">The reCAPTCHA is not valid!</p>';
	}
}

echo '
<div class="officerFeatures">
	<div class="officerFeatures_inner">
		<h4>Domain Owner:</h4>
		Kevin Quiskamp<br />
		Baldurstraße 28<br />
		45772 Marl<br />
		E-Mail: mail@PoliceLifeS.de<br />
		<h4>Any Bugs, Suggestions or Questions?</h4>
		<small>Supported Language: English or German</small>
		<form method="POST" action="contact">
			<div class="ui-input-text ui-corner-all ui-shadow-inset ui-icon-login-img" style="position: relative;">
				<input type="text" name="username" id="contact-username" title="Name" placeholder="Name" value="'.(isset($_POST["username"]) ? $_POST["username"]: "").'">
			</div>			
			<div class="ui-input-text ui-corner-all ui-shadow-inset ui-icon-email-img" style="position: relative;">
				<input type="email" name="email" id="contact-email" title="E-Mail" placeholder="E-Mail Address" class="ui-input-text-icon" value="'.(isset($_POST["email"]) ? $_POST["email"]: "").'">
			</div>		
			<textarea name="message" id="contact-message" title="Message" placeholder="Message" class="ui-input-text ui-shadow-inset ui-body-inherit ui-corner-all" style="height: 52px; overflow: hidden;">'.(isset($_POST["message"]) ? $_POST["message"]: "").'</textarea>
			<div class="g-recaptcha" data-sitekey="6Ldy7nwUAAAAAKiFYDEcgXoFKPJHRw291hrMoRos"></div>
			<div class="ui-btn ui-btn-b ui-input-btn ui-corner-all ui-shadow">
				Transmit
				<input type="submit" name="submit" value="Transmit">
			</div>
		</form>
	</div>
</div>';
