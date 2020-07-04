


<?php
defined("main") or die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");

class utility
{

	public function login_hash($input)
	{
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');		
		$salt = '$5$rounds=50000$' . $salt;				
		return crypt($input, $salt);
	}

	public function getRangColor($rang) {
		switch($rang) {
			case 1:
				return "#c600e6";
				break;
			case 2:
				return "#ff5d00";
				break;
			case 3:
				return "#ff0023";
				break;
			default:
				return "#00A2FF";
				break;
		}
	}

	public function getRangTitle($staffrang, $level) {
		$rang = '<font color="'.$this->getRangColor($staffrang).'">';
		if($level <= 10) {
			$rang .= "Recruit";
		}else if($level >= 11 && $level <= 15) {
			$rang .= "Officer";
		}else if($level >= 16 && $level <= 20) {
			$rang .= "Officer II";
		}else if($level >= 21 && $level <= 30) {
			$rang .= "Officer III";
		}else if($level >= 31 && $level <= 40) {
			$rang .= "Detective";
		}else if($level >= 41 && $level <= 50) {
			$rang .= "Detective II";
		}else if($level >= 51 && $level <= 60) {
			$rang .= "Sergeant";
		}else if($level >= 61 && $level <= 70) {
			$rang .= "Sergeant II";
		}else if($level >= 71 && $level <= 80) {
			$rang .= "Lieutenant";
		}else if($level >= 81 && $level <= 100) {
			$rang .= "Captain";
		}else if($level >= 101 && $level <= 120) {
			$rang .= "Major";
		}else if($level >= 121 && $level <= 140) {
			$rang .= "Colonel";
		}else if($level >= 141 && $level <= 160) {
			$rang .= "Inspector";
		}else if($level >= 161 && $level <= 180) {
			$rang .= "Deputy Chief of Police";
		}else if($level >= 181 && $level <= 199) {
			$rang .= "Assistant Chief";
		}else {
			$sub_rank = floor(($level-200) / 100) + 1;
			$rang .= "Chief Degree ".$sub_rank;
		}
		$rang .= '</font>';
		return $rang;
	}

	public function getRangMPA($id) {
		global $mysql, $mysql_database;	
		$sql = "SELECT `ID` FROM `".$mysql_database."`.`User` WHERE `Aktiv` = '1' ORDER BY `ArrestedPeds` DESC";
		$result = $mysql->query($sql);
		$rank = 0;
		if($result && $result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$rank++;
				if($row["ID"] == $id) {
					return $rank;
				}
			}
		}
		return -1;
	}
	public function getRangHL($id) {
		global $mysql, $mysql_database;	
		$sql = "SELECT `ID` FROM `".$mysql_database."`.`User` WHERE `Aktiv` = '1' AND `Banned` = '0' ORDER BY `Exp` DESC";
		$result = $mysql->query($sql);
		$rank = 0;
		if($result && $result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$rank++;
				if($row["ID"] == $id) {
					return $rank;
				}
			}
		}
		return -1;
	}
	public function getRangMMC($id) {
		global $mysql, $mysql_database;	
		$sql = "SELECT `ID` FROM `".$mysql_database."`.`User` WHERE `Aktiv` = '1' AND `Banned` = '0' ORDER BY `Money` DESC";
		$result = $mysql->query($sql);
		$rank = 0;
		if($result && $result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$rank++;
				if($row["ID"] == $id) {
					return $rank;
				}
			}
		}
		return -1;
	}


	const EXPMultiplicator = 0.045;

	public function getLevel($exp) {
		if($exp < 0) {
			return 1;
		}
		$levelFloat = self::EXPMultiplicator * sqrt($exp) + 1;
		$level = floor($levelFloat);
		return $level;
	}

	public function getPercent($exp) {
		if($exp < 0) {
			return 0;
		}
		$levelFloat = self::EXPMultiplicator * sqrt($exp) + 1;
		return floor(($levelFloat - floor($levelFloat)) * 100);
	}

	public function Log($text, $typ = "Info")
	{
		if($typ != "Error" && $typ != "Info" && $typ != "Warning")
			$typ = "Info";
		$filename = getcwd().'/logs/Global.log';
	  	$fh = fopen($filename, "a") or die("Could not open log file.");
	  	fwrite($fh, "[".strtoupper($typ)." - ".date("d-m-Y, H:i")." - ".$this->getUserIP()." - ".session_id()."]: ".$text."\n"); 	
	  	fclose($fh);
	}
	
	public function makeuptime ($time)		//Generiert aus Sekunden Tage, Stunden, Minuten und Sekunden
	{
		$sekunden = $time % 60;
		$weiter = ($time - $sekunden) / 60;
		$minuten = $weiter % 60;
		$weiter = ($weiter - $minuten) / 60;
		$stunden = $weiter % 24;
		$tage = ($weiter - $stunden) / 24;
		return sprintf("%dD %02d:%02d:%02d", (int)$tage, (int)$stunden, (int)$minuten, (int)$sekunden);
	}

	public function emailcheck($mail)		//Prüft, ob gültiges E-Mail-Muster
	{
		$muster = "/^[a-zA-Z0-9-_.]+@[a-zA-Z0-9-_.]+\.[a-zA-Z]{2,4}$/";
		if (preg_match($muster, $mail) == 0) return false;
		return true;
	}

	public function datumcheck($datum)
	{
		//2001-01-01 01:01
	    $muster = "/^([0-9]{4})+\-([0-9]{2})+\-([0-9]{2})+\ ([0-9]{2})+\:([0-9]{2})$/";
	    if (preg_match($muster, $datum) == 0) return false;
	    return true;
	}

	public function datumsimplecheck($datum)
	{
		//2001-01-01 oder 01.01.2001
	    if (preg_match("/^([0-9]{4})+\-([0-9]{2})+\-([0-9]{2})$/", $datum) == 0 && preg_match("/^([0-9]{2})+\.([0-9]{2})+\.([0-9]{4})$/", $datum) == 0) return false;
	    return true;
	}


	public function preparequery($str)
	{
		$str = stripslashes($str);
		$str = str_replace("\n", " ", $str);
		$str = str_replace("\r", "", $str);
		return $str;
	}

	public function maketimefromdate($date)
	{
		return mktime(substr($date, 11, 2), substr($date, 14, 2), substr($date, 17, 2), substr($date, 3, 2), substr($date, 0, 2), substr($date, 6, 4));
	}

	public function size($size)
	{
		$sizes = Array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
		$ext = $sizes[0];
		for ($i=1; (($i < count($sizes)) && ($size >= 1024)); $i++) {
			$size = $size / 1024;
			$ext  = $sizes[$i];
		}
		$size = round($size, 2) . ' ' . $ext;
		return $size;
	}

	public function getColors($anzahl) {		
		$array = null;
		for($i = 0; $i < $anzahl; $i++) {
			$randomcolor = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
			$array[0][$i] = $randomcolor;
			$array[1][$i] = $this->adjustBrightness($randomcolor, -50);
		}
	    return $array;
	}
	public function adjustBrightness($hex, $steps) {
	    // Steps should be between -255 and 255. Negative = darker, positive = lighter
	    $steps = max(-255, min(255, $steps));

	    // Normalize into a six character long hex string
	    $hex = str_replace('#', '', $hex);
	    if (strlen($hex) == 3) {
	        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
	    }

	    // Split into three parts: R, G and B
	    $color_parts = str_split($hex, 2);
	    $return = '#';

	    foreach ($color_parts as $color) {
	        $color   = hexdec($color); // Convert to decimal
	        $color   = max(0,min(255,$color + $steps)); // Adjust color
	        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
	    }

	    return $return;
	}

	public function zufallscode($stellen)
	{
		$code = '';
		$keys = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", 1, 2, 3, 4, 5, 6, 7, 8, 9, 0);
		for ($i = 1; $i <= $stellen; $i++) {
			$zufall = mt_rand(0, count($keys) - 1);
			$code .= $keys[$zufall];
		}
		return $code;
	}

	public function is_ganzzahl($var)
	{
		if (preg_match("|^[1-9][0-9]*$|", $var)) return true;
		return false;
	}

	public function colorize($str)
	{
		$lines = explode("\n", $str);
		$str = "";
		foreach ($lines as $value) {
			$value = htmlentities($value, ENT_QUOTES);
			if (stripos($value, "error") === 0) {
				$str .= '<span style="color: #FF0000">'.$value.'</span>';
			}
			elseif (stripos($value, "ok") === 0) {
				$str .= '<span style="color: #00FF00">'.$value.'</span>';
			}
			else {
				$str .= $value;
			}
			$str .= "\n";
		}
		return trim($str);
	}

	public function getAlter($datum)
	{
	    $date = explode(".", $datum);
	    if(sizeof($date) == 3) {
	        $alter = date("Y",time())-$date[2];
	        if (mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time())) < mktime(0,0,0,$date[1],$date[0],date("Y",time())))
	            $alter--;
	        return $alter;    
	    }else {
	        return 0; 
	    }
	    
	}

	public function html_js($str)
	{
		return addslashes(htmlentities($str, ENT_COMPAT));
	}
	public function highlight_array (&$array, $string, $atag, $etag) {
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$array[$key] = highlight_array($value, $string, $atag, $etag);
			}
			else {
				$array[$key] = str_ireplace($string, $atag.$string.$etag, $value);
			}
		}
		return $array;
	}

	public function getPagedata() {
		global $mysql, $mysql_database;		
		$pagedata = array();
		$sql = "SELECT * FROM `".$mysql_database."`.`Data`";
		$result = $mysql->query($sql);
		if ($result && $result->num_rows > 0) {
		    while ($row = $result->fetch_assoc()) {
		        $pagedata[$row["name"]] = $row["wert"];
		    }
		}
		return $pagedata;
	}
	
	public function getUserIP() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		    $ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		    $ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}	
}

?>