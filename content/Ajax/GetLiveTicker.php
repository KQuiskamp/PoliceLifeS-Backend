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
	$data = null;
	
	$sql = "SELECT lt.*, IFNULL(u.`Username`, 'Officer') AS `Username`, u.`StaffRang` FROM `".$mysql_database."`.`LiveTicker` AS `lt` LEFT JOIN `".$mysql_database."`.`User` AS `u` ON (u.`ID` = lt.`userID`) ORDER BY lt.`Time` DESC LIMIT 50";
	$result = $mysql->query($sql);
	if($result && $result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$data[] = array(
				"ID" => $row["ID"],
				"Message" => $row["Message"].", Over",
				"userName" => $row["Username"],
				"userNameUrl" => urlencode($row["Username"]),
				"Time" => $row["Time"],
				"Color" => $utility->getRangColor($row["StaffRang"])
			);	
		}
	}
	if($data != null) {
		sort($data);
	}
	

	$onDuty = $mysql->query("SELECT `LastTick` FROM `".$mysql_database."`.`User` WHERE `Aktiv` = '1' AND `Banned` = '0'");
	$onDutyAnzahl = 0;
	if($onDuty && $onDuty->num_rows > 0) {
		while($dutyRow = $onDuty->fetch_assoc()){
			if($dutyRow["LastTick"] > strtotime("-1 minutes")) {
				$onDutyAnzahl++;
			}
		}
	}

	$statsUser = $mysql->query("SELECT COUNT(`ID`) AS `officerAnzahl`, SUM(`ArrestedPeds`) AS `arrestedAnzahl` FROM `".$mysql_database."`.`User` WHERE `Aktiv` = '1' AND `Banned` = '0'");
	$officerAnzahl = 0;
	$arrestedAnzahl = 0;
	if($statsUser && $statsUser->num_rows > 0) {
		$statsRow = $statsUser->fetch_assoc();
		$officerAnzahl = $statsRow["officerAnzahl"];
		$arrestedAnzahl = $statsRow["arrestedAnzahl"];
	}

	$statsCallout = $mysql->query("SELECT COUNT(`ID`) AS `calloutAnzahl` FROM `CalloutLog`");
	$calloutAnzahl = 0;
	if($statsCallout && $statsCallout->num_rows > 0) {
		$statsRow = $statsCallout->fetch_assoc();
		$calloutAnzahl = $statsRow["calloutAnzahl"];
	}

	echo json_encode(array("DataArray" => $data, "onDutyCount" => $onDutyAnzahl, "officerCount" => $officerAnzahl, "arrestedCount" => $arrestedAnzahl, "calloutCount" => $calloutAnzahl));
}else {
	die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");
}

?>