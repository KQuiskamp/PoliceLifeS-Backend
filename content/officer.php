<?php
defined("main") or die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");
if(isset($_GET["action"]) && $_GET["action"] == "forgotpw") {
	if(isset($_GET["code"]) && preg_match("|^[a-z0-9]{40}$|", $_GET["code"])) {
		$result = $mysql->query("SELECT * FROM `".$mysql_database."`.`Forgotpw` WHERE `Code` = '".$_GET["code"]."'");
		if ($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			$success = false;
			if (time() - $row["Time"] > 86400) {	//Wenn Anfrage zu alt ist
				echo '<p class="msg-error">The code has expired!</p>';
				$mysql->query("DELETE FROM `".$mysql_database."`.`Forgotpw` WHERE `Code` = '".$_GET["code"]."'");	//Anfrage löschen
			}else {
				if (isset($_POST["submit"])) {		//Ist Formular abgeschicht
					if (strlen(stripslashes($_POST["pw1"])) >= 4) {	//Passwort lang genug
						if ($_POST["pw1"] === $_POST["pw2"]) {	//Eingegebene Passwörter stimmen überein
							$mysql->query("UPDATE `".$mysql_database."`.`User` SET `Password` = '".$utility->login_hash($_POST["pw1"])."' WHERE `ID`= '".$row["userID"]."'");
							$mysql->query("DELETE FROM `".$mysql_database."`.`Forgotpw` WHERE `userID` = '".$row["userID"]."'");
							echo '<p class="msg-success">Password has been changed succesfully!<br /><a href="dashboard">Sign in now!</a></p>';
							$success = true;
						}
						else {
							echo '<p class="msg-error">The passwords do not match!</p>';
						}
					}
					else {
						echo '<p class="msg-error">The password must have at least 4 characters!</p>';
					}
				}
			}
			if(!$success) {
				echo '
				<div class="officerProfil">
					<div class="officerProfilForgot_inner">
						<b>Please choose a new password.</b><br /><br />
						<form action="index.php?page=officer&action=forgotpw&code='.$_GET["code"].'" method="post">
							<div class="ui-input-text ui-corner-all ui-shadow-inset ui-icon-password-img" style="position: relative;">
								<input type="password" name="pw1" id="forgotpw-password1" title="Password" placeholder="New Password" class="ui-input-text-icon">
							</div>
							<div class="ui-input-text ui-corner-all ui-shadow-inset ui-icon-password-img" style="position: relative;">
								<input type="password" name="pw2" id="forgotpw-password2" title="Repeat Password" placeholder="Repeat Password" class="ui-input-text-icon">
							</div>
							<div class="ui-btn ui-input-btn ui-corner-all ui-shadow">
								Change
								<input type="submit" name="submit" value="Change">
							</div>
						</form>
					</div>
				</div>';
			}
		}else {
			echo '<p class="msg-error">The Code is invalid or has expired</p>';
		}
	}else {
		echo '<p class="msg-error">Invalid ForgotPW Code</p>';
	}
}else if(isset($_GET["action"]) && $_GET["action"] == "activate") {
	if (isset($_GET["code"]) && preg_match("|^[a-z0-9]{40}$|", $_GET["code"])) {
	   	$result = $mysql->query("SELECT * FROM `".$mysql_database."`.`User` WHERE `Code` = '".$_GET["code"]."'");
	    if ($result->num_rows == 1) {
	        $row = $result->fetch_assoc();
	        $mysql->query("UPDATE `".$mysql_database."`.`User` SET `Aktiv` = '1', `Code` = '', `Created` = UNIX_TIMESTAMP() WHERE `ID`= '".$row["ID"]."'");
	        echo '<p class="msg-success">Activation successful, you can Sign In now!</p>';
	    }else {
	        echo '<p class="msg-error">Invalid Registration Code</p>';
	    }
	}else if(isset($_GET["newmail"])) {
	   	$result = $mysql->query("SELECT * FROM `".$mysql_database."`.`User` WHERE `ID` = '".$_GET["newmail"]."' AND `Aktiv` = '0'");
	    if ($result->num_rows == 1) {
	        $row = $result->fetch_assoc();
	        $diff = time() - $row["LastCode"];
	        if ($diff > $pagedata["time_for_newcode"]) {
	            $code = $utility->zufallscode(40);
	            $mysql->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
	            $result = $mysql->query("UPDATE `".$mysql_database."`.`User` SET `Code` = '".$code."', `LastCode` = UNIX_TIMESTAMP() WHERE `ID`= '".$row["ID"]."'");
	            if($result && $mysql->affected_rows > 0) {
	            	$replace = array();                //Replace für E-Mail-Content u. Subject
	                $search = array();              //Search für E-Mail-Content u. Subject
	                foreach ($pagedata as $key => $value) {     //Pagedata in Replace und Search einlesen
	                    $search[] = "{pagedata." . $key . "}";
	                    $replace[] = $value;
	                }
	                $search[] = "{name}";
	                $replace[] = $row["Username"];
	                $search[] = "{email}";
	                $replace[] = $row["EMail"];
	                $search[] = "{link}";
	                $replace[] = $pagedata["url_interface"] . "index.php?page=officer&action=activate&code=" . $code;
	                $subject = str_replace($search, $replace, $pagedata["email_register_aktiv_subject"]);
	                $content = str_replace($search, $replace, $pagedata["email_register_aktiv_content"]);

                    $mail = new PHPMailer;
                    $mail->isSMTP();
                    $mail->Host = '';
                    $mail->SMTPAuth = true;
                    $mail->Username = '';
                    $mail->Password = '';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;
                    $mail->CharSet = 'UTF-8';

					$mail->setFrom($pagedata["contact_email"], $pagedata["page_name"]);
					$mail->addAddress($row["EMail"]);

					$mail->isHTML(true);

					$mail->Subject = $subject;
					$mail->Body    = html_entity_decode($content);
					if($mail->send()) {
	                	echo '<p class="msg-success">You get a new verification mail, use the link to activate the account!</p>';
	                	$mysql->commit();
					}else {
					    echo '<p class="msg-error">E-Mail could not be sent, Please try again later!</p>';
					    $mysql->rollback();
					}

	            }else {
					echo '<p class="msg-error">Database Error, Developer has been contacted!/p>';
	            }
	        }else {
				echo '<p class="msg-error">The last verification mail is less than 5 minutes ago, please wait a little</p>';
	        }
	    }else {
	        echo '<p class="msg-error">Officer not found or already active!</p>';
	    }
	}else {
		echo '<p class="msg-error">Code has not a valid Format (40 Chars, Contact a Admin)!</p>';
	}
}else {
	$result = $mysql->query("SELECT u.*, b.`Bild` FROM `".$mysql_database."`.`User` AS `u` LEFT JOIN `".$mysql_database."`.`Bilder` AS `b` ON(b.`userID` = u.`ID`)  WHERE u.`Username` = '".$mysql->real_escape_string($_GET["userName"])."' AND u.`Aktiv` = '1'");
	if($result && $result->num_rows == 1) {
		$row = $result->fetch_assoc();


		//Insert Into Profile Log, when loggedin
		if($row["ID"] != $_SESSION["userData"]["ID"] && isset($_SESSION["angemeldet"])) {
			$mysql->query("INSERT INTO `".$mysql_database."`.`Profile_Log`(`Timestamp`, `ProfileId`, `UserId`) VALUES (
				UNIX_TIMESTAMP(),
				'".$mysql->real_escape_string($row["ID"])."',
				'".$mysql->real_escape_string($_SESSION["userData"]["ID"])."') ON DUPLICATE KEY UPDATE `Timestamp` = UNIX_TIMESTAMP()");

			$mysql->query("DELETE FROM `".$mysql_database."`.`Profile_Log` WHERE `ID` <= (SELECT `ID` FROM (SELECT `ID` FROM `".$mysql_database."`.`Profile_Log` WHERE `ProfileId` = '".$mysql->real_escape_string($row["ID"])."' ORDER BY `Timestamp` DESC LIMIT 1 OFFSET 10) foo)");
		}
		$lastInGameNow = false;
		if($row["LastTick"] > strtotime("-10 minutes")) {
			$lastInGameNow = true;
		}

		$level = $utility->getLevel($row["Exp"]);
		$percent = $utility->getPercent($row["Exp"]);
		$mPARank = $utility->getRangMPA($row["ID"]);
		$hLRank = $utility->getRangHL($row["ID"]);
		$mMCRank = $utility->getRangMMC($row["ID"]);
		echo '
		<div class="officerProfil">
			<img src="'.(isset($row["Bild"]) ? "images/upload/".$row["Bild"] : "images/officer.jpg").'" alt="'.$row["Username"].'" />			
			<div class="officerProfil_inner">
				<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
	  				<tbody>
	    				<tr>
	      					<td style="width: 270px"><b>'.$utility->getRangTitle($row["StaffRang"], $level).'</b>&nbsp;'.$row["Username"].'</td>
	      					<td>Last Login: '.($row["Banned"] == 1 ? "<span class=\"ui-spacer ui-spacer-red\">Banned</span>" : ($lastInGameNow ? "<span class=\"ui-spacer ui-spacer-green\">Now</span>" : ($row["LastTick"] == 0 ? "Never" : "<span data-lasttick='".$row["LastTick"]."'>".date("d.m.Y H:i", $row["LastTick"])."</span>"))).'</td>
	    				</tr>
	    				<tr>
	      					<td>Member since:</td>
	      					<td data-created="'.$row["Created"].'">'.date("d.m.Y H:i", $row["Created"]).'</td>
	    				</tr>
	    				<tr>
	      					<td>Money:</td>
	      					<td>'.$row["Money"].'<img src="images/dollar.png" alt="Dollar" width="18" align="top"/></td>
	    				</tr>
	    				<tr>
	      					<td>Level:</td>
	      					<td><div id="rang" data-clevel="'.$level.'" data-percent="'.$percent.'" data-totalexp="'.$row["Exp"].'"></div></td>
	    				</tr>
	    				<tr>
	      					<td align="center">Most people arrested:</td>
	      					<td align="center">Highest Level:</td>
	    				</tr>
	    				<tr>
	      					<td align="center">';
	      						if($mPARank == 1) {
	      							echo '<img src="images/medal_gold.png" title="Rank 1 in Most People Arrested" alt="Rank 1" />';
	      						}else if($mPARank == 2) {
	      							echo '<img src="images/medal_silver.png" title="Rank 2 in Most People Arrested" alt="Rank 2" />';
	      						}else if($mPARank == 3) {
	      							echo '<img src="images/medal_bronze.png" title="Rank 3 in Most People Arrested" alt="Rank 3" />';
	      						}else {
	      							echo '<div class="ranktext">'.$mPARank.'</div><img src="images/medal_normal.png" title="Rank '.$mPARank.' in Most People Arrested" alt="Rank '.$mPARank.'" />';
	      						}
	echo '					</td>
	      					<td align="center">';
	      						if($hLRank == 1) {
	      							echo '<img src="images/medal_gold.png" title="Rank 1 in Highest Level" alt="Rank 1" />';
	      						}else if($hLRank == 2) {
	      							echo '<img src="images/medal_silver.png" title="Rank 2 in Highest Level" alt="Rank 2" />';
	      						}else if($hLRank == 3) {
	      							echo '<img src="images/medal_bronze.png" title="Rank 3 in Highest Level" alt="Rank 3" />';
	      						}else {
	      							echo '<div class="ranktext">'.$hLRank.'</div><img src="images/medal_normal.png" title="Rank '.$hLRank.' in Highest Level" alt="Rank '.$hLRank.'"/>';
	      						}
	echo '					</td>
	    				</tr>
	    				<tr>
	      					<td align="center">Most money collected:</td>
	      					<td align="center"></td>
	    				</tr>
	    				<tr>
	      					<td align="center">';
	      						if($mMCRank == 1) {
	      							echo '<img src="images/medal_gold.png" title="Rank 1 in Most money collected" alt="Rank 1" />';
	      						}else if($mMCRank == 2) {
	      							echo '<img src="images/medal_silver.png" title="Rank 2 in Most money collected" alt="Rank 2" />';
	      						}else if($mMCRank == 3) {
	      							echo '<img src="images/medal_bronze.png" title="Rank 3 in Most money collected" alt="Rank 3" />';
	      						}else {
	      							echo '<div class="ranktext">'.$mMCRank.'</div><img src="images/medal_normal.png" title="Rank '.$mMCRank.' in Most money collected" alt="Rank '.$mMCRank.'" />';
	      						}
	echo '					</td>
	      					<td align="center"></td>
	    				</tr>
	  				</tbody>
				</table>
			</div>';
			$profileVisitor = $mysql->query("SELECT `u`.`Username`, `u`.`StaffRang`, `p`.`Timestamp` FROM `".$mysql_database."`.`Profile_Log` AS `p` INNER JOIN `".$mysql_database."`.`User` AS `u` ON(`u`.`ID` = `p`.`UserId`) WHERE `p`.`ProfileId` = '".$row["ID"]."' ORDER BY `p`.`Timestamp` DESC");
	  		if($profileVisitor && $profileVisitor->num_rows > 0) {
	echo '		<div class="officerProfil_LastVisit">
				<h4 style="text-align: center; margin-top: 5px">Last profile visitors:</h4>
				<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
	  				<tbody>';
	  						while($rowProfileVisitor = $profileVisitor->fetch_assoc()) {
	  							echo '<tr>
	      							<td style="text-align: center;"><a href="officer/'.urlencode($rowProfileVisitor["Username"]).'"><font color="'.$utility->getRangColor($rowProfileVisitor["StaffRang"]).'">'.$rowProfileVisitor["Username"].'</font></a></td>
	      							<td><span data-visitortime="'.$rowProfileVisitor["Timestamp"].'">'.date("d.m.Y H:i", $rowProfileVisitor["Timestamp"]).'</span></td>
	    						</tr>';
	  						}
	 echo '			</tbody>
				</table>
			</div>
			';
			}
	echo '  </div>';
	}else{
		echo '<h4 class="msg-error">Username not found, or Profile is not active</h4>';
	}
}


?>
