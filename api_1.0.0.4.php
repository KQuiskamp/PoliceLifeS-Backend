<?php
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'PoliceLifeSHttpRequest'){

	define("main", true);
	require_once (__DIR__.'/config/default.php');
	require_once (__DIR__.'/classes/mysql.class.php');
	$mysql = Database::getInstance($mysql_ip, $mysql_username, $mysql_password, $mysql_database);
	require_once (__DIR__.'/classes/utility.class.php');
	require_once (__DIR__.'/classes/PHPMailer/class.phpmailer.php');
	$utility = new utility();
	$pagedata = $utility->getPagedata();

	function LogAPI($text, $typ = "Info")
    {
        if($typ != "Error" && $typ != "Info" && $typ != "Warning")
            $typ = "Info";
        $filename = __DIR__.'/logs/API.log';
        $fh = fopen($filename, "a") or die("Could not open log file.");
        fwrite($fh, "[".strtoupper($typ)." - ".date("d-m-Y, H:i")." - ".session_id()."]: ".$text."\n");     
        fclose($fh);
    }

	if(isset($_POST["userAuthToken"])) {
		$sql = "SELECT * FROM `".$mysql_database."`.`User` WHERE `userAuthToken` = '".$mysql->real_escape_string($_POST["userAuthToken"])."' AND `Banned` = '0' AND `Aktiv` = '1'";
		$result = $mysql->query($sql);
		if($result && $result->num_rows > 0){
			$row = $result->fetch_assoc();
			$mysql->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
			try {
				switch($_POST["Methode"]) {
					case "LoginCall":
						$lastTick = $mysql->query("UPDATE `".$mysql_database."`.`User` SET `LastTick` = UNIX_TIMESTAMP() WHERE `ID` = '".$row["ID"]."'");
						if(!$lastTick) {
							throw new Exception($mysql->error);
						}							
						echo json_encode(array("userAuth" => true, "userName" => $row["Username"]));
						$mysql->commit();
						break;
					case "UpdateEXP":
						if(isset($_POST["Exp"]) && isset($_POST["Reason"])) {
							$exp = $row["Exp"] + $_POST["Exp"];
							if($exp <= 0){
								$exp = 0;
							}else {
								$exp = $_POST["Exp"];
							}

							if(isset($_POST["LogExp"]) && $_POST["LogExp"] == "true" || !isset($_POST["LogExp"])) {
								$insertExpLog = $mysql->query("INSERT INTO `".$mysql_database."`.`ExpLog`(`userID`, `Exp`, `Reason`, `Time`) VALUES (
									'".$row["ID"]."',
									'".$mysql->real_escape_string($exp)."',
									'".$mysql->real_escape_string($_POST["Reason"])."',
									UNIX_TIMESTAMP())");
							}

							LogAPI("EXP Update - userId: ".$row["ID"]." exp: ".$exp." reason: ".$_POST["Reason"]);

							$updateExp = $mysql->query("UPDATE `".$mysql_database."`.`User` SET `Exp` = `Exp` + (".$mysql->real_escape_string($_POST["Exp"]).") WHERE `ID` = '".$row["ID"]."'");
							if(!$updateExp) {
								throw new Exception($mysql->error);
							}
							echo json_encode(array("updateEXP" => true, "Exp" => $_POST["Exp"], "Reason" => $_POST["Reason"]));
							$mysql->commit();	
						}
						break;
					case "CalloutLog": 
						$insertCalloutLog = $mysql->query("INSERT INTO `".$mysql_database."`.`CalloutLog`(`userID`, `Name`, `Duration`) VALUES (
							'".$row["ID"]."',
							'".$mysql->real_escape_string($_POST["CallName"])."',
							'".$mysql->real_escape_string($_POST["CallDuration"])."')");
						echo json_encode(array("addCallout" => true));
						$mysql->commit();
						break;
					case "GetPlayerData":
						echo json_encode(array("getPlayerData" => true, "userLevel" => $utility->getLevel($row["Exp"]), "userExp" => $row["Exp"], "userMoney" => $row["Money"]));
						break;
					case "SendTicker": 
						if(isset($_POST["TickerMessage"]) && !empty($_POST["TickerMessage"])) {
							$insertTicker = $mysql->query("INSERT INTO `".$mysql_database."`.`LiveTicker`(`userID`, `Message`, `Time`) VALUES (
								'".$row["ID"]."',
								'".$mysql->real_escape_string($_POST["TickerMessage"])."',
								UNIX_TIMESTAMP())");
							if(!$insertTicker || $mysql->affected_rows <= 0) {
								throw new Exception($mysql->error);
							}

							$mysql->query("DELETE FROM `".$mysql_database."`.`LiveTicker` WHERE `ID` <= (SELECT `ID` FROM (SELECT `ID` FROM `".$mysql_database."`.`LiveTicker` ORDER BY `Time` DESC LIMIT 1 OFFSET 50) foo)");

							echo json_encode(array("sendTickerMessage" => true));
							$mysql->commit();
						}
						break;
					case "SaveDaten":
						if(isset($_POST["Money"]) && !empty($_POST["Money"])) {
							$saveDaten = $mysql->query("UPDATE `".$mysql_database."`.`User` SET `Money` = '".$mysql->real_escape_string($_POST["Money"])."', `LastTick` = UNIX_TIMESTAMP() WHERE `ID` = '".$row["ID"]."'");
							if(!$saveDaten) {
								throw new Exception($mysql->error);
							}
							echo json_encode(array("saveDaten" => true));
							$mysql->commit();
						}					
						break;
					case "AddArrestedPeds":
						if(isset($_POST["ArrestedPeds"]) && !empty($_POST["ArrestedPeds"])) {
							$addArrestedPeds = $mysql->query("UPDATE `".$mysql_database."`.`User` SET `ArrestedPeds` = `ArrestedPeds` + '".$mysql->real_escape_string($_POST["ArrestedPeds"])."' WHERE `ID` = '".$row["ID"]."'");
							if(!$addArrestedPeds) {
								throw new Exception($mysql->error);
							}
							echo json_encode(array("addArrestedPeds" => true, "PedCount" => $_POST["ArrestedPeds"]));
							$mysql->commit();
						}
						break;
					default:
						echo json_encode(array("ErrorMessage" => "Wrong Request-Call"));
						break;
				}
			}catch (Exception $e) {
				echo json_encode(array("ErrorMessage" => "Database Error, please try again later!", "DatabaseError" => $e->getMessage()));
				$mysql->rollback();
			}
		}else {
			echo json_encode(array("ErrorMessage" => "Your Login authentication-Token is ~r~incorrect, you play now offline\n\nYour authentication-Token can you enter in the PoliceLifeS-File\nLocation:~b~GTAV/Plugins/LSPDFR/PoliceLifeS/PoliceLifeS.ini"));
		}				
	}else {
		echo json_encode(array("ErrorMessage" => "Wrong userAuthToken"));
	}	
}else {
	die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");
}
?>
