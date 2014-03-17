<?php

require_once 'bizData/checkTurn.php';
require_once 'db.class.php';

?>
<html>
<head>
<title>login</title>


 <script src="http://code.jquery.com/jquery-latest.js"></script>
 <script src="scripts/popup.js" type="text/javascript"></script> 
 
	<link rel="stylesheet" type="text/css" href="css/login.css" />
	<link rel="stylesheet" type="text/css" href="css/demo.css" />
	<link rel="stylesheet" type="text/css" href="css/registration.css" />
	
 <script src="scripts/login_script.js"></script>
 
</head>

<body>

<div class="main">

<div class="done"></div>
<div class="battle">BattleShip</div>
<div class="center">
<div class="error"></div>
<div class="input"><div class="left" id="un">Username :</div><div class="right">
<input type="text" class="log" id="u"></div><div class="c"></div></div>
<div class="input"><div class="left" id="up">Password :</div><div class="right">
<input type="password" class="log" id="p"></div><div class="c"></div></div>
<div class="sign_b_btn"><div class="sign_btn">Sign In</div></div>
<div class="register_b_btn"><div class="register_btn">Register</div></div>

</div>
</div>

<div id="popupContact"><a id="popupContactClose">x</a>
<div class="title">Sign Up</div>
<div class="errorReg"></div>

<form id="regForm" method="post">

<table>
  <tbody>
  <tr height="50">
    <td><label for="fname">First Name:</label></td>
    <td><div class="right"><input type="text" class="log" id="sf"></div><div class="c"></div></td>
  </tr>
  <tr height="50">
    <td><label for="lname">Last Name:</label></td>
    <td><div class="right"><input type="text" class="log" id="sn"></div><div class="c"></div></td>
  </tr>
  <tr height="50">
    <td><label for="email">Your Email:</label></td>
    <td><div class="right"><input type="text" class="log" id="se"></div><div class="c"></div></td>
  </tr>
  <tr height="50">
    <td><label for="pass">New User ID:</label></td>
    <td><div class="right"><input type="text" class="log" id="su"></div><div class="c"></div></td>
  </tr>
  <tr height="50">
    <td><label for="pass">New Password:</label></td>
    <td><div class="right"><input type="text" class="log" id="sp"></div><div class="c"></div></td>
  </tr>

  <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  </tr>

  <tr>
  <td>&nbsp;</td>
  <td align="center"><div class="signup"><div class="register_btn">SignUp</div></div>
	</td>
  </tr>
  
  </tbody>
</table>
</form>
<div class="doneReg"></div>
</div>

<div id="backgroundPopup"></div> 

</body>
</html>