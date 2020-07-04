<?php
defined("main") or die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");

require_once "config/default.php";
require_once "classes/utility.class.php";
require_once "classes/mysql.class.php";
require_once "classes/PHPMailer/class.phpmailer.php";
require_once "classes/PHPMailer/class.pop3.php";
require_once "classes/PHPMailer/class.smtp.php";