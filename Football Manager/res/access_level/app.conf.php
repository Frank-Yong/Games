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

function access_level($module) {
  //global $_USER_ID;
  $cfg_security_basic = "SELECT count(*) FROM user_module um, module m WHERE um.user=".$_SESSION['USERID']. " AND um.module=m.id AND m.name='$module'";
  if (empty($_SESSION['USERID'])) {
	return 0;
  } else {
  	$cfg_security_sql = $cfg_security_basic;
	$qry = mysqli_query($GLOBALS['con'],$cfg_security_sql);
  	list($qry_result) = mysqli_fetch_row($qry);
  	mysqli_free_result($qry);
  	return $qry_result;
  }
}




?>