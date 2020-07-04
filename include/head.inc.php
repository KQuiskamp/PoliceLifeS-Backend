<?php
defined("main") or die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");

//Error Reporting
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);

//Location und Zeit
date_default_timezone_set("UTC");
setlocale(LC_MONETARY, 'en_US');

// Session starten
session_name("policelifes");
session_start();

$in_wartungs = false; 
if($in_wartungs && $_SESSION["userData"]["StaffRang"] != 3) {
    include "wartungsmodus.html";
    die();
}

// Alles benötigte includen
require_once "include/loader.inc.php";

//Objekte initialisieren
$mysql = Database::getInstance($mysql_ip, $mysql_username, $mysql_password, $mysql_database);
$utility = new utility();
//Page-Settings bekommen
$pagedata = $utility->getPagedata();

//Visitor Count
$timestamp=time();
$timeout=$timestamp-600;
$mysql->query("REPLACE INTO `".$mysql_database."`.`Online` (`Time`, `IP`) VALUES ('".$timestamp."','".$utility->getUserIP()."')");
$mysql->query("DELETE FROM `".$mysql_database."`.`Online` WHERE `Time` < ".$timeout."");

$result = $mysql->query("SELECT `IP` FROM `".$mysql_database."`.`Online`");
if($result)
	$totalVisitor = $result->num_rows;

//Magic Quotes
if (!get_magic_quotes_gpc()) {
    function addslashes_deep($value) {
        $value = is_array($value) ?
         	array_map('addslashes_deep', $value) :
            addslashes($value);
        return $value;
    }

    $_POST = array_map('addslashes_deep', $_POST);
    $_GET = array_map('addslashes_deep', $_GET);
    $_COOKIE = array_map('addslashes_deep', $_COOKIE);
    $_REQUEST = array_map('addslashes_deep', $_REQUEST);
}
//PageName. auslesen
if (isset($_GET["page"]) && !empty($_GET["page"])) {
    $page = $_GET["page"];   
}
else {
    $page = "dashboard";
}