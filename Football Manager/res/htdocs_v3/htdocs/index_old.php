<?php 
error_reporting(63);
include('app.conf.php');
include('UserStadium.php');
include('player.php');

if(!empty($_REQUEST['key'])) 
{
	$sql = "SELECT id FROM user WHERE activationkey='".$_REQUEST['key']."' AND activated=0";
	$res = mysql_query($sql);
	list($for_activation) = mysql_fetch_row($res);
	mysql_free_result($res);
	if($for_activation > 0) 
	{
		$sql="UPDATE user SET activated=1 WHERE id=$for_activation";
		mysql_query($sql);
	}
}

if(!empty($_REQUEST['Signin'])) 
{
		$stadiumname = "Bernabeu";
		$teamname = "Real 01 Madrid";
		$username = "raul";
		$password = "raul";
		$email = "a@a.com";

		$sql = "SELECT id FROM user WHERE teamname='".$teamname."' OR email='$email' OR username='$username'";
		$res = mysql_query($sql);
		$sameUser = mysql_num_rows($res);
		mysql_free_result($res);
		if($sameUser>0) {
				$_MESSAGE = 'Same user exists!';
				exit;
		}

		$activationkey = md5(microtime().rand());
		$user = new user();

		$user->CreateTeam($teamname, $stadiumname, $username, $password, $email, $activationkey);
		//$user->CreateTeam($teamname, $stadiumname, $username, md5($password), $email, $activationkey);

		$idcreat = 0;
		$idcreat = $user->ReturnID();
						
		$_SESSION['_MESSAGE'] = "Success";
}

if(!empty($_REQUEST['player'])) 
{
		$young = 0;
		$coeficient_liga = 1.02;

		$i=1;
		$poz = 1;
		//aici urmeaza definirea de var
		$den = "juc".$i;
		$$den = new Player(1, 0, $country, $young, $poz, $coeficient_liga);
		//$$den->EchoPlayer();

}	

?>
This is my Football Manager