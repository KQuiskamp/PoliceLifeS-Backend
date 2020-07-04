<?php
// Session starten (Wenn man den Session Namen ändert bitte auch in der Header Include, sonst können Fehler auftreten!)
session_name("policelifes");
session_start();

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){

	define("main", true);

	//Inialisierung
	require_once (__DIR__.'/../../config/default.php');
	require_once (__DIR__.'/../../classes/mysql.class.php');
	$mysql = Database::getInstance($mysql_ip, $mysql_username, $mysql_password, $mysql_database);
	require_once (__DIR__.'/../../classes/utility.class.php');
	require_once (__DIR__.'/../../classes/PHPMailer/class.phpmailer.php');
	require_once (__DIR__.'/../../classes/PHPMailer/class.pop3.php');
	require_once (__DIR__.'/../../classes/PHPMailer/class.smtp.php');
	$utility = new utility();
	$pagedata = $utility->getPagedata();
	$message = null;
	$dataArray = null;
	parse_str($_POST["FormData"], $dataArray);

	if(isset($dataArray["username"]) && !empty($dataArray["username"]) && isset($dataArray["email"]) && $utility->emailcheck($dataArray["email"]) && isset($dataArray["password"]) && !empty($dataArray["password"])) {
		if(strlen($dataArray["username"]) <= 20) {
			if(preg_match('/^[a-zA-Z0-9_]+$/', $dataArray["username"])) {
				$sql = "SELECT * FROM `".$mysql_database."`.`User` WHERE `Username` = '".$mysql->real_escape_string($dataArray["username"])."' OR `EMail` = '".$mysql->real_escape_string($dataArray["email"])."'";
				$result = $mysql->query($sql);
				if($result && $result->num_rows <= 0) {
					$code = $utility->zufallscode(40);
		            $mysql->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
		            $result = $mysql->query("INSERT INTO `".$mysql_database."`.`User`(`Username`, `EMail`, `Password`, `userAuthToken`, `Code`, `LastCode`, `Created`) VALUES (
		            	'".$mysql->real_escape_string($dataArray["username"])."',
		            	'".$mysql->real_escape_string($dataArray["email"])."',
		            	'".$utility->login_hash($dataArray["password"])."',
		            	'".uniqid('policelifes_', true)."',
		            	'".$code."',
		            	UNIX_TIMESTAMP(),
		            	UNIX_TIMESTAMP())");
		            if($result && $mysql->affected_rows > 0) {
		            	 $replace = array();                //Replace für E-Mail-Content u. Subject
			            $search = array();              //Search für E-Mail-Content u. Subject
			            foreach ($pagedata as $key => $value) {     //Pagedata in Replace und Search einlesen
			                $search[] = "{pagedata." . $key . "}";
			                $replace[] = $value;
			            }
			            $search[] = "{name}";
			            $replace[] = $dataArray["username"];
			            $search[] = "{email}";
			    		$replace[] = $dataArray["email"];
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
						$mail->addAddress($dataArray["email"]);

						$mail->isHTML(true);

						$mail->Subject = $subject;
						$mail->Body    = html_entity_decode($content);
						if($mail->send()) {
			            	$message[] = array(
								"Typ" => "msg-success",
								"Msg" => "Registration successful! Officer, please confirm your email!"
							);
			            	$mysql->commit();
						}else {
						    $message[] = array(
								"Typ" => "msg-error",
								"Msg" => "E-Mail could not be sent! Please try again later"
							);
						    $mysql->rollback();
						}
		            }else {
		            	$message[] = array(
							"Typ" => "msg-error",
							"Msg" => "Database Error, Developer has been contacted!"
						);
		            }

				}else{
					$message[] = array(
						"Typ" => "msg-error",
						"Msg" => "Officer-Name or E-Mail Address already exists!"
					);
				}
			}else{
				$message[] = array(
					"Typ" => "msg-error",
					"Msg" => "Officer-Name is not valid (a-z, A-Z, 0-9, _)!"
				);
			}
		}else {
			$message[] = array(
				"Typ" => "msg-error",
				"Msg" => "Officer-Name is too long (max. 20 chars)"
			);
		}
	}else{
		$message[] = array(
			"Typ" => "msg-error",
			"Msg" => "You must fill all fields or E-Mail Address is invalid!"
		);
	}
	echo json_encode(array("Message" => $message));
}else {
	die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");
}

?>
