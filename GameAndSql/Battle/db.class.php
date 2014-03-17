<?php
/**
 * Simple MySQLi Class 0.3
 *
 * @author		JREAM
 * @link		http://jream.com
 * @copyright		2010 Jesse Boyer (contact@jream.com)
 * @license		GNU General Public License 3 (http://www.gnu.org/licenses/)
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details:
 * http://www.gnu.org/licenses/
 *
 * @uses	----------------------------------------
 *
		// A Config array should be setup from a config file with these parameters:
 		$config = array();
		$config['host'] = 'localhost';
		$config['user'] = 'axs5316';
		$config['pass'] = 'fr1end';
		$config['table'] = 'axs5316';

		// Then simply connect to your DB this way:
 		$db = new DB($config);

		// Run a Query:
 		$db->query('SELECT * FROM someplace');

 		// Get an array of items:
 		$result = $db->get();
 		print_r($result);
 		
 		// Optional fetch modes (1 and 2)
 		$db->setFetchMode(1);
 		
 		// Get a single item:
 		$result = $db->get('field');
 		print_r($result);

 		What you do with displaying the array result is up to you!
  		----------------------------------------
 */

class DB
{

	/**
	* @var <str> The mode to return results, defualt is MYSQLI_BOTH, use setFetchMode() to change.
	*/
	private $fetchMode = MYSQLI_BOTH;
	
	private $posArray = array("userid" => array(5, 12, 13, 17, 21, 28, 31, 35, 40, 43, 46, 49),
						"ip" => array(6, 10, 11, 16, 19, 23, 27, 33, 39, 42),
						"timestamp" => array(4, 8, 12, 14, 20, 22, 25, 30, 34, 38, 45, 48) );
					
	private $userDetails = array ();
	
	private $checkSum="131982";

	/**
	* @desc		Creates the MySQLi object for usage.
	*
	* @param	<arr> $db Required connection params.
	*/
	public function  __construct() {
		
		$db = array();
		$db['host'] = 'localhost';
		$db['user'] = 'axs5316';
		$db['pass'] = 'fr1end';
		$db['table'] = 'axs5316';
		
		$this->mysqli = new mysqli($db['host'], $db['user'], $db['pass'], $db['table']);

		if (mysqli_connect_errno()) 
		{
			printf("<b>Connection failed:</b> %s\n", mysqli_connect_error());
			exit;
		}
	}
	
	/** 
	* @desc		Optionally set the return mode.
	*
	* @param	<int> $type The mode: 1 for MYSQLI_NUM, 2 for MYSQLI_ASSOC, default is MYSQLI_BOTH
	*/
	public function setFetchMode($type)
	{
		switch($type)
		{			
			case 1:
			$this->fetchMode = MYSQLI_NUM;
			break;
			
			case 2:
			$this->fetchMode = MYSQLI_ASSOC;
			break;
			
			default:
			$this->fetchMode = MYSQLI_BOTH;
			break;

		}

	}

	/**
	 * @desc	Simple preparation to clean the SQL/Setup result Object.
	 *
	 * @param	<str> SQL statement
	 * @return	<bln|null>
	 */
	public function query($SQL)
	{
		$this->SQL = $this->mysqli->real_escape_string($SQL);
		$this->result = $this->mysqli->query($SQL);

		if ($this->result == true)
		{
			return true;
			echo "true";
		}
		else
		{
			printf("<b>Problem with SQL:</b> %s\n", $this->SQL);
			exit;
		}
	}

	/**
	 * @desc	Get the results
	 *
	 * @param	<str|int> $field Select a single field, or leave blank to select all.
	 * @return	<mixed>
	 */
	public function get($field = NULL)
	{
			
		if ($field == NULL)
		{
			/** Grab all the data */
			$data = array();

			while ($row = $this->result->fetch_array($this->fetchMode))
			{
				$data[] = $row;
			}
		}
		else
		{
			/** Select the specific row */
			$row = $this->result->fetch_array($this->fetchMode);
			$data = $row[$field];
			
		}

		/** Make sure to close the result Set */
		$this->result->close();

		return $data;

	}
	
	/**
	* @desc		Returns the automatically generated insert ID
	* 			This MUST come after an insert Query.
	*/
	public function id()
	{
		return $this->mysqli->insert_id;
	}

	/**
	 * @desc	Automatically close the connection when finished with this object.
	 */
	public function __destruct()
	{
		$this->mysqli->close();
	}
	
	public function getToken($user, $ip, $timestamp)
	{
		$username = strtoupper($user);

		$conv_user = base_convert($username, 36, 10);
		$this->createTokenArray($conv_user, "userid");
		
		$conv_ip = base_convert($ip, 10, 16);
		$this->createTokenArray($conv_ip, "ip");
		
		$conv_date_time = base_convert($timestamp, 10, 16);
		$this->createTokenArray($conv_date_time, "timestamp");
		
		$token = $conv_user . $conv_ip . $conv_date_time;
		
		return $token;
	}
	
	public function createTokenArray($data, $type){
	
		$count = count($this->posArray[$type]);
		
		$str = str_split($data);
		$i=0;
		foreach($str as $char) {
			$j = $this->posArray[$type][$i];
			$this->userDetails[$j] = $char;
			$i++;
			$count--;
		}	
		$total = $i + $count;
		
		for($j=$i; $j<$total; $j++){

			$this->userDetails[$this->posArray[$type][$j]] = '';
			
		}
		
	}
	
	public function getUserDetails($type){
	
		$userValue="";
		$val="";
		
		foreach ($this->posArray[$type] as $j) {
			$userValue = $userValue . $val;
			
			if($this->userDetails[$j] == NULL)
				$val = "";
			else
				$val = $this->userDetails[$j];
		}
		
		switch ($type) {
			case 'userid' : 
				$userValue = base_convert($userValue, 10, 36);
				break;
			case 'ip' :
				$userValue = base_convert($userValue, 16, 10);
				break;
			case 'timestamp' :
				$userValue = base_convert($userValue, 16, 10);
				break;
		}
		
		return $userValue;
	}
	
	
	
	

} 
