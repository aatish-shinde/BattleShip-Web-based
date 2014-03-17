<?php
require_once 'db.class.php';
require_once 'common/protect.php';

$db = new DB();

$username = protect($_POST['username']);
$message = protect($_POST['message']);
$time = time();

$sql = 'INSERT INTO messages
	(Userid,
	 Message_Content,
	 Message_Time)
		VALUES
	("' . $username . '",
	 "' . $message . '",
	 CURRENT_TIMESTAMP)';
	 
$db->query($sql);

?>