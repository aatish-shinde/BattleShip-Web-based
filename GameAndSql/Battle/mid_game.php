<?php
include "db.class.php";
require_once 'common/protect.php';
require_once('common/gameFunc.php');

	$db = new DB();

	//will always send in:
	//method - for which method to call
	//a - for which area to load
	//$result = array();
	
	if(isset($_REQUEST['method'])){
	
		foreach(glob("services/".$_REQUEST['a']."/*.php") as $fileName){
			include $fileName;
		}
		
		$serviceMethod=$_REQUEST['method'];
		$data=$_REQUEST['type'];
	    //$userid=$_REQUEST['u'];
	    
		$ip = $_SERVER['REMOTE_ADDR'];
		
		switch($data){
			
			case 'shipsPos' :
				$userid=$_REQUEST['u'];
				
				$result_game = call_user_func($serviceMethod, $userid);
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
