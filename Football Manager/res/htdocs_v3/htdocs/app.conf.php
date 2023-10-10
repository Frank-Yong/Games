<?php
// SPARE USERS FROM USELESS WARNINGS
error_reporting(0);

// BACKEND CONNECTION
$cfg_host	= 'localhost'; 
$cfg_user	= 'root';
$cfg_pass	= '';
$cfg_db	= 'myfm';

global $con;

$con = mysqli_connect($cfg_host, $cfg_user, $cfg_pass);
if (!mysqli_select_db($con, $cfg_db))
	die('FATAL: cannot select MySQL database');

session_start();

$_SEASON = 9;
$_SESSION['_SEASON'] = $_SEASON;

require('translate.php');


$lang = "EN"; // that's default language key
$GLOBALS['defaultLanguage'] = require_once('languages_'.$lang.'.php');

if(isset($_GET['lang'])){
   $lang = $_GET['lang'];
}
$GLOBALS['language'] = include('languages_'.$lang.'.php');

if(!is_array($GLOBALS['language'])) {
   $GLOBALS['language'] = [];
}
?>