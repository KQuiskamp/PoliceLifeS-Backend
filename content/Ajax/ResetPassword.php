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

	if(isset($dataArray["username"]) && !empty($dataArray["username"]) || (isset($dataArray["email"]) && !empty($dataArray["email"]) && $utility->emailcheck($dataArray["email"]))) {

		if(isset($dataArray["username"])) {
			$sql = "SELECT * FROM `".$mysql_database."`.`User` WHERE `Username` = '".$mysql->real_escape_string($dataArray["username"])."'";
		}else {
			$sql = "SELECT * FROM `".$mysql_database."`.`User` WHERE `EMail` = '".$mysql->real_escape_string($dataArray["email"])."'";
		}
		$result = $mysql->query($sql);
		if ($result->num_rows == 1) {
			//Nur wenn EIN user existiert
			$row = $result->fetch_assoc();
			$code = $utility->zufallscode(40);
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
            $replace[] = $pagedata["url_interface"] . "index.php?page=officer&action=forgotpw&code=" . $code;

            $subject = str_replace($search, $replace, $pagedata["email_forgotpw_subject"]);
            $content = str_replace($search, $replace, $pagedata["email_forgotpw_content"]);

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
			    $mysql->query("DELETE FROM `".$mysql_database."`.`Forgotpw` WHERE `userID` = ".$row["ID"]);  //Alte Passwortanfragen des Users löschen
            	$mysql->query("INSERT INTO `".$mysql_database."`.`Forgotpw` (`userID`, `Code`, `Time`) VALUES(".$row["ID"].", '".$code."', UNIX_TIMESTAMP())");  //Passwortanfrage in DB eintragen
            	$message[] = array(
					"Typ" => "msg-success",
					"Msg" => "If the username is found in our system, you have just received an EMail!"
				);
			}else {
			    $message[] = array(
					"Typ" => "msg-error",
					"Msg" => "EMail could not be sent, please try again later!"
				);
			}
 		}else {
 			$message[] = array(
				"Typ" => "msg-error",
				"Msg" => "The username was not found in our system! Please contact our technical support if you have any problems!"
			);
		}

	}else{
		$message[] = array(
			"Typ" => "msg-error",
			"Msg" => "You must fill all fields!"
		);
	}
	echo json_encode(array("Message" => $message));
}else {
	die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");
}

?>
