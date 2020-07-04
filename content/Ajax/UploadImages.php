<?php
// Session starten (Wenn man den Session Namen ändert bitte auch in der Header Include, sonst können Fehler auftreten!)
session_name("policelifes");
session_start();

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && isset($_SESSION["angemeldet"])){

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

	//---------------------------------------------------------
	$valid_formats = array("jpg", "png", "gif");
	$max_file_size = 307200; //300kb
	$max_width = 500;
	$max_height = 500;
	$count = 0;
	$oldmask = umask(0);
	$message = null;
	$fileArray = null;
	//---------------------------------------------------------

	if(isset($_FILES) && !empty($_FILES)){
		foreach ($_FILES as $key => $file){
			if($file["error"] == UPLOAD_ERR_OK) {
				//Upload
				list($Filewidth, $Fileheight) = getimagesize($file["tmp_name"]);
				if ($file["size"] > $max_file_size) {
					$message[] = array(
						"Typ" => "msg-error",
						"Msg" => $file["name"]." is too large (max. 300KB)!"
					);
					continue;
				}elseif( !in_array(pathinfo($file["name"], PATHINFO_EXTENSION), $valid_formats) ){
					$message[] = array(
						"Typ" => "msg-error",
						"Msg" => $file["name"]." doesn't have the correct format (jpg, png or gif)!"
					);
					continue;
				}elseif($Filewidth > $max_width || $Fileheight > $max_height) {
					$message[] = array(
						"Typ" => "msg-error",
						"Msg" => $file["name"]." must have a maximum resolution of 500 x 500!"
					);
					continue;
				}else {

					$imageName = uniqid("images_");
					$filename = $imageName.".".pathinfo($file["name"], PATHINFO_EXTENSION);
					if(!file_exists($fileUploadPath.$filename)) {
						//Versuch Bild zu verschieben
						if(move_uploaded_file($file["tmp_name"], $fileUploadPath.$filename)) {
							chmod($fileUploadPath.$filename, 0777);
							$sql = "SELECT `Bild` FROM `".$mysql_database."`.`Bilder` WHERE `userID` = '".$mysql->real_escape_string($_SESSION["userData"]["ID"])."'";
							$result = $mysql->query($sql);
							if($result && $result->num_rows > 0){
								while($row = $result->fetch_assoc()){
									if(unlink($fileUploadPath.$row["Bild"])) {
										$mysql->query("DELETE FROM `".$mysql_database."`.`Bilder` WHERE `userID` = '".$mysql->real_escape_string($_SESSION["userData"]["ID"])."' AND `Bild` = '".$row["Bild"]."'");
									}
								}
							}
							$sql = "INSERT INTO `".$mysql_database."`.`Bilder` (`Bild`, `userID`) VALUES ('".$mysql->real_escape_string($filename)."', '".$mysql->real_escape_string($_SESSION["userData"]["ID"])."')";
							$result = $mysql->query($sql);
							if($result && $mysql->affected_rows > 0){
								$_SESSION["userData"]["Bild"] = $filename;
								$message[] = array(
									"Typ" => "msg-success",
									"Msg" => $file["name"]." successfully added!"
								);

                                $mail = new PHPMailer;
                                $mail->isSMTP();
                                $mail->Host = '';
                                $mail->SMTPAuth = true;
                                $mail->Username = '';
                                $mail->Password = '';
                                $mail->SMTPSecure = 'tls';
                                $mail->Port = 587;
                                $mail->CharSet = 'UTF-8';

								$mail->AddReplyTo($_SESSION["userData"]["EMail"], $_SESSION["userData"]["Username"]);
								$mail->setFrom($pagedata["contact_email"],"PoliceLifeS Image-Upload");
		            			$mail->addAddress($pagedata["contact_email"], "PoliceLifeS Image-Upload");

								$mail->isHTML(true);

								$mail->Subject = $_SESSION["userData"]["Username"]." uploaded a new User-Profile Image";
								$mail->Body = "	<h4>Username: ".$_SESSION["userData"]["Username"]." | E-Mail: ".$_SESSION["userData"]["EMail"]."</h4><br />
											<h4>Please check is the image against our policies? If yes, Tag it for the \"Developer\"</h4>
		            						<pre><img src=\"".$absoluteUploadPath.$filename."\" width=\"130\" height=\"130\" alt=\"The UserProfile Image\" /></pre>";
								$mail->send();
							}else{
								unlink($fileUploadPath.$filename);
								$message[] = array(
									"Typ" => "msg-error",
									"Msg" => $file["name"]." could not be added to the database!"
								);
							}
						}else{
							$message[] = array(
								"Typ" => "msg-error",
								"Msg" => $file["name"]." could not be moved to the server!"
							);
						}
					}else {
						$message[] = array(
							"Typ" => "msg-error",
							"Msg" => $file["name"]." could not be moved to the server!"
						);
					}
				}
			}else if($file["error"] == UPLOAD_ERR_INI_SIZE || $file["error"] == UPLOAD_ERR_FORM_SIZE) {
				//Size error
				$message[] = array(
					"Typ" => "msg-error",
					"Msg" => $file["name"]." is too large (max. 300KB)"
				);
			}else if($file["error"] == UPLOAD_ERR_PARTIAL || $file["error"] == UPLOAD_ERR_NO_FILE) {
				//Datei nicht hochgeladen oder nur teilweise
				$message[] = array(
					"Typ" => "msg-error",
					"Msg" => $file["name"]." could not be fully uploaded, please try again!"
				);
			}else if($file["error"] == UPLOAD_ERR_CANT_WRITE || $file["error"] == UPLOAD_ERR_NO_TMP_DIR) {
				//Speicher auf Festplatte fehlgeschlagen
				$message[] = array(
					"Typ" => "msg-error",
					"Msg" => $file["name"]." could not be saved, please try again!"
				);
			}
		}
	}else {
		$message[] = array(
			"Typ" => "msg-error",
			"Msg" => "You must selected a Image-File"
		);
	}
	echo json_encode (array("Message" => $message));
	umask($oldmask);
}else {
	die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");
}

?>
