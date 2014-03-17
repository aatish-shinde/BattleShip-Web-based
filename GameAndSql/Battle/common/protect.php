<?php

function protect($str) {
    $blacklist = array("/`/", "/'/", "/</", "/>/", '/"/', "/%/", "/\(/", "/\)/", "/\\\/", "/\//", "/\_/", "/\|/");
    $str = htmlentities($str);
    $str = strip_tags($str);
    $str = stripslashes($str);
	$str = mysql_real_escape_string($str);
    $str = preg_replace($blacklist, "", $str);
    $str = trim($str);
    return $str;
}


?>