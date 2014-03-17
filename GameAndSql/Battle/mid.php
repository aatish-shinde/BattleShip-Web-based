<?php
include 'db.class.php';
require_once 'common/protect.php';
require_once('common/gameFunc.php');

session_name("Battle");
session_start();

	$db = new DB();

	//will always send in:
	//method - for which method to call
	//a - for which area to load
	
	
	if(isset($_REQUEST['method'])){
	
		foreach(glob("services/".$_REQUEST['a']."/*.php") as $fileName){
			include $fileName;
		}
		
		$serviceMethod=$_REQUEST['method'];
		$data=$_REQUEST['type'];
	    //$userid=$_REQUEST['u'];
	    //$result='a';
		
		$ip = $_SERVER['REMOTE_ADDR'];
		
		switch($data){
		
			case 'signin':
				$result = array();
				$userid=protect($_REQUEST['u']);
				$password=protect($_REQUEST['p']);
				
				$timestamp =  date('his');
				//echo 'timestamp' . $timestamp . "<br/>";
				
				$token = $db->getToken($userid, $ip, $timestamp);
				
				$result=call_user_func($serviceMethod, $userid, $password, $token);
				break;
			
			case 'register':
				$result = array();
				$userid=protect($_REQUEST['u']);
				$password=protect($_REQUEST['p']);
				$fname = protect($_REQUEST['f']);
				$lname = protect($_REQUEST['l']);
				$email = protect($_REQUEST['e']);
				
				$result=call_user_func($serviceMethod, $fname, $lname, $email, $userid, $password);
					
				break;
			
			case 'out':
				$result = array();
				$userid=protect($_REQUEST['u']);
				$result = call_user_func($serviceMethod, $userid);
				break;
			
			case 'challenge' :
				
				$touserid=protect($_REQUEST['to']);
				$byUserId=protect($_REQUEST['by']);
				$result = call_user_func($serviceMethod, $touserid, $byUserId);
				break;
				
			case 'chat' :
				$result = array();
				$result = call_user_func($serviceMethod);
				break;
				
			case 'users' :
				$result = array();
				$userid=protect($_REQUEST['u']);
				$result = call_user_func($serviceMethod, $userid);
				break;
			
			case 'ischallenged' :
				$result = array();
				$userid=protect($_REQUEST['u']);
				$result = call_user_func($serviceMethod, $userid);
				break;
				
			case 'update' :
				$result = array();
				$touserid=protect($_REQUEST['to']);
				$byUserId=protect($_REQUEST['by']);
				$result = call_user_func($serviceMethod, $touserid, $byUserId);
				break;
			
			case 'shipsPos' :
				
				$userid=protect($_REQUEST['u']);
				
				$result = call_user_func($serviceMethod, $userid);
				
				break;
			
			case 'game' :
				
				$data = $_REQUEST['data'];
				
				$result = @call_user_func($serviceMethod, $data);
				
				break;		
				
			default:
                throw new Exception('Wrong action');
		}
	
		echo json_encode($result);
		
	}
	
	/*
	function getships($serviceMethod, $data, $a, $userid)
	{
		
		
		foreach(glob("services/".$a."/*.php") as $fileName){
			include $fileName;
		}
		
			
		switch($data){
			case 'shipsPos' :
								
				$result = call_user_func($serviceMethod, $userid);
				break;
				
			default:
                throw new Exception('Wrong action');
		}
		
		echo json_encode($result);
	}*/
	
?>
