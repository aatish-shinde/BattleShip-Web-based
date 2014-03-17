<?php
session_start();

$userid = $_REQUEST['userid'];

$_SESSION['userid'] = $userid;

header("Location: ../chat.php");


?>
