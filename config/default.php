<?php
defined("main") or die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");

$mysql_ip = "localhost";
$mysql_username = "policelifes";
$mysql_password = "";
$mysql_database = "policelifes";

$fileUploadPath = (__DIR__.'/../images/upload/'); //relativer Path zum Upload Ordner
$domain = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://").$_SERVER["SERVER_NAME"];
$absoluteUploadPath = $domain."/images/upload/"; //$domain ist immer die Domain also z.B http(s)://www.example.com danach folgt der Path zum Upload Ordner


?>