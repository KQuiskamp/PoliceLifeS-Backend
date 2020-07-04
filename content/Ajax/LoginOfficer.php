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

	if(isset($dataArray["username"]) && !empty($dataArray["username"]) && isset($dataArray["password"]) && !empty($dataArray["password"])) {
		$sql = "SELECT u.*, b.`Bild` FROM `".$mysql_database."`.`User` AS `u` LEFT JOIN `".$mysql_database."`.`Bilder` AS `b` ON(b.`userID` = u.`ID`) WHERE u.`Username` = '".$mysql->real_escape_string($dataArray["username"])."'";
		$result = $mysql->query($sql);
		if($result && $result->num_rows > 0) {
			$row = $result->fetch_assoc();

			if(hash_equals($row["Password"], crypt($dataArray["password"], $row["Password"]))){
				if(isset($row["Banned"]) && $row["Banned"] == 0 && $row["Aktiv"] == 1) {
					$_SESSION["angemeldet"] = true;
					$_SESSION["userData"] = $row;
					$message[] = array(
						"Typ" => "msg-success",
						"Msg" => "Successfully! Welcome Officer-".$row["Username"]
					);
				}else if($row["Aktiv"] == 0) {
					//Not Aktiv
					$message[] = array(
						"Typ" => "msg-error",
						"Msg" => "You must confirm your EMail-Address!<br />Request new verification mail <a href=\"index.php?page=officer&action=activate&newmail=".$row["ID"]."\">here</a>"
					);
				}else {
					//Banned
					$message[] = array(
						"Typ" => "msg-error",
						"Msg" => "Your account has been banned! Reason: <b>".$row["BanReason"]."</b>"
					);
				}
			}else {
				$message[] = array(
					"Typ" => "msg-error",
					"Msg" => "Password is invalid!"
				);
			}
		}else{
			$message[] = array(
				"Typ" => "msg-error",
				"Msg" => "Officer-Name is invalid!"
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