<?php
defined("main") or die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");

if(isset($_SESSION["angemeldet"]) && $_SESSION["userData"]["FirstLogin"] == 0) {
	//First Login, zeige Token

	$result = $mysql->query("UPDATE `".$mysql_database."`.`User` SET `FirstLogin`= '1' WHERE `ID` = '".$_SESSION["userData"]["ID"]."'");
	if($result && $mysql->affected_rows > 0) {
		$_SESSION["userData"]["FirstLogin"] = 1;
		echo '
		<div class="officerFirstLogin">
			Hello <b>Officer '.$_SESSION["userData"]["Username"].'</b>, Welcome in the PoliceLifeS Officer-Network.<br />
			Add this Token to your "PoliceLifeS.ini"-File, then you create a connection to the account.<br /><br />
			<font color="red"><b>'.$_SESSION["userData"]["userAuthToken"].'</b></font><br /><br />
			<img src="images/token.png" alt="tokenSetup" /><br />
			<a href="dashboard">Continue to the Dashboard</a>
		</div>';
	}else {
		echo '<p class="msg-error">Database Error, please try it later!</p>';
	}	
}else if(isset($_SESSION["angemeldet"]) && isset($_GET["visitToken"])) {
	echo '
	<div class="officerFirstLogin">
		Hello <b>Officer '.$_SESSION["userData"]["Username"].'</b>, Hope you have Fun in the PoliceLifeS Officer-Network.<br />
		Add this Token to your "PoliceLifeS.ini"-File, then you create a connection to the account.<br /><br />
		<font color="red"><b>'.$_SESSION["userData"]["userAuthToken"].'</b></font><br /><br />
		<img src="images/token.png" alt="tokenSetup" /><br />
		<a href="dashboard">Continue to the Dashboard</a>
	</div>';
}else {
	echo '
	<div class="officerContent">';
		$result = $mysql->query("SELECT u.`Username`, b.`Bild` FROM `".$mysql_database."`.`User` AS `u` LEFT JOIN `".$mysql_database."`.`Bilder` AS `b` ON(b.`userID` = u.`ID`)  WHERE u.`Aktiv` = '1' ORDER BY u.`Created` DESC LIMIT 12");
		if($result && $result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				echo '
				<div class="officerBox">
					<a href="officer/'.urlencode($row["Username"]).'">
						<img src="'.(isset($row["Bild"]) ? "images/upload/".$row["Bild"] : "images/officer.jpg").'" alt="'.$row["Username"].'" />
						<span>'.$row["Username"].'</span>
					</a>
				</div>';
			}
		}
	echo '</div>';	

	if(!isset($_SESSION["angemeldet"])) {
	echo'
	<div class="officerLogin" id="loginDialog">
		<form method="POST" id="loginForm">
			<div class="ui-input-text ui-input-officer ui-corner-all ui-shadow-inset ui-icon-login-img" style="position: relative;">
				<span>Officer </span>
				<input type="text" name="username" id="login-username" title="Username" placeholder="Bob" class="ui-input-text-icon">
			</div>
			<div class="ui-input-text ui-corner-all ui-shadow-inset ui-icon-password-img" style="position: relative;">
				<input type="password" name="password" id="login-password" title="Password" placeholder="Password" class="ui-input-text-icon">
			</div>
			<div class="ui-btn ui-input-btn ui-corner-all ui-shadow">
				User Exits? Sign In
				<input type="submit" name="submit" value="Sign In">
			</div>
			<a href="#" id="openForgotPWDialog" class="ui-btn ui-btn-b ui-input-btn ui-corner-all ui-shadow">Forgot Password?</a>
			<a href="#" id="openRegisterDialog" class="ui-btn ui-btn-b ui-input-btn ui-corner-all ui-shadow">Sign Up</a>
		</form>
	</div>

	<div class="officerRegister" id="registerDialog" title="Registration">
		<form method="POST" id="registerForm">
			Welcome Officer! PoliceLifeS is a virtual Officer-Network. Hope you enjoy!<br/><br />
			<span style="font-size: 13px;">Allowed Characters: <b>a-z, A-Z, 0-9, _</B></span>
			<div class="ui-input-text ui-input-officer ui-corner-all ui-shadow-inset ui-icon-login-img" style="position: relative;">
				<span>Officer </span>
				<input type="text" name="username" id="register-username" title="Name" placeholder="Bob">
			</div>			
			<div class="ui-input-text ui-corner-all ui-shadow-inset ui-icon-email-img" style="position: relative;">
				<input type="email" name="email" id="register-email" title="E-Mail" placeholder="E-Mail Address" class="ui-input-text-icon">
			</div>
			<div class="ui-input-text ui-corner-all ui-shadow-inset ui-icon-password-img" style="position: relative;">
				<input type="password" name="password" id="register-password" title="Password" placeholder="Password" class="ui-input-text-icon">
			</div>
			<div class="ui-btn ui-btn-b ui-input-btn ui-corner-all ui-shadow">
				Sign Up
				<input type="submit" name="submit" value="Sign Up">
			</div>
		</form>
	</div>

	<div id="forgotPWDialog" title="Forgot Password">
		<form method="POST" id="forgotPWForm">
			You need the Username or the E-Mail to reset your password<br/><br />
			<div class="ui-input-text ui-input-officer ui-corner-all ui-shadow-inset ui-icon-login-img" style="position: relative;">
				<span>Officer </span>
				<input type="text" name="username" id="forgotpw-username" title="Name" placeholder="Bob">
			</div>
			<div class="ui-input-text ui-corner-all ui-shadow-inset ui-icon-email-img" style="position: relative;">
				<input type="email" name="email" id="forgotpw-email" title="E-Mail" placeholder="E-Mail Address" class="ui-input-text-icon">
			</div>
			<div class="ui-btn ui-btn-b ui-input-btn ui-corner-all ui-shadow">
				Reset 
				<input type="submit" name="submit" value="Reset">
			</div>
		</form>
	</div>';
	}else {	
	echo '
	<div class="officerLogin" id="loginDialog">
		<img src="'.(isset($_SESSION["userData"]["Bild"]) ? "images/upload/".$_SESSION["userData"]["Bild"] : "images/officer.jpg").'" alt="'.$_SESSION["userData"]["Username"].'" />
		<div class="ui-login-inline-wrapper">
			<div class="ui-btn ui-input-btn ui-corner-all ui-shadow">
				Profile
				<a href="./officer/'.urlencode($_SESSION["userData"]["Username"]).'"></a>
			</div>
			<div class="ui-btn ui-input-btn ui-corner-all ui-shadow">
				Your Token
				<a href="index.php?page=dashboard&visitToken"></a>
			</div>
			<div class="ui-btn ui-input-btn ui-corner-all ui-shadow">
				Upload Photo
				<a href="#" id="openUploadProfilePhoto" class="ui-btn ui-btn-b ui-input-btn ui-corner-all ui-shadow"></a>
			</div>
		</div>
		
		<div class="ui-btn ui-btn-b ui-input-btn ui-corner-all ui-shadow">
			Logout
			<a href="logout"></a>
		</div>
	</div>
	
	<div id="uploadProfilePhoto" title="Upload Profile-Photo">
		<form method="POST" id="uploadProfileForm" enctype="multipart/form-data">
			<p class="msg-info">If you Upload pornography or offensive material, you get a Ban</p><br />
			<input type="file" id="profileUpload" accept="image/*" />
			<div id="previewBox" style="display: none;">
				<br />
				<img id="previewProfileImage" style="background-color: #30302F;"/>
			</div>
			<div class="ui-btn ui-btn-a ui-input-btn ui-corner-all ui-shadow">
				Upload 
				<input type="submit" name="submit" value="Upload">
			</div>
		</form>
	</div>';
	}

	echo '
	<div class="officerDashboardInfo">	
		<div class="officerDashboardInfo_inner">
			<div class="msg-info">
			We updated the Ranking Page, you see now the Top 100 Players
			<br/><small>posted: 15/08/2018</small>
			</div>			
		</div>
	</div>
	';

	//LiveTicker
	echo ' 
	<div class="policeRadio">
		<div class="policeRadio_box">
			<div class="policeRadio_inner" id="liveticker">
			</div>			
		</div>
	</div>
	<div style="clear: both;"></div>';

}