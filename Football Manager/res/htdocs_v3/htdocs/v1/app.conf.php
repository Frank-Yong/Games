<?php
// SPARE USERS FROM USELESS WARNINGS
//error_reporting(63);

// BACKEND CONNECTION
$cfg_host	= 'localhost'; 
$cfg_user	= 'root';
$cfg_pass	= '';
$cfg_db	= 'myFM';

mysql_connect($cfg_host, $cfg_user, $cfg_pass);
if (!mysql_select_db($cfg_db))
	die('FATAL: cannot select MySQL database');

$cfg_must_addslashes = get_magic_quotes_gpc() ? 0 : 1;


$cfg_tactici = array(
				"1" => "4-4-2", 
				"2" => "5-3-2", 
				"3" => "3-5-2", 
				"4" => "5-4-1", 
				"5" => "4-5-1", 
				"6" => "4-3-3" 

);

session_start();

$_SEASON = 9;
$_SESSION['_SEASON'] = $_SEASON;

if (empty($_LANGUAGE)) {
	$_LANGUAGE = 'EN';
}


?>