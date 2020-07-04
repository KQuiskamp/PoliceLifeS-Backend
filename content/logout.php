<?php
defined("main") or die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");


$_SESSION = array();
session_destroy();

echo '<meta http-equiv="refresh" content="0; URL=dashboard">';
?>