<?php
error_reporting(63);
include('app.conf.php');
include('player.php');
include('UserStadium.php');
include('trainer.php');

if(isset($_REQUEST['Login'])) {
	//dupa login normal
	$username = $_REQUEST['userName'];
	$password = $_REQUEST['password'];

	$user = new User();
	$user->Login($username, $password);
	
	$user->EchoClub();

	$user->ComputeTeamValues();

	$user->EchoTeam();

}
if(isset($_REQUEST['userid'])) {
	//dupa registration_step1
	//vine id-ul inregistrarii prin GET, dupa home.php?id=...
	$user = new User();
	$user->LoginID($_REQUEST['userid']);
	
	$user->EchoClub();
	$user->EchoTeam();

}
?>
