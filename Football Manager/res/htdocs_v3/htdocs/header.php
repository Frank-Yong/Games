<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Football Manager - MariusLacatus.ro</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="style2.css" type="text/css" media="screen" />
<script src="menu.js" type="text/javascript"></script>
</head>
<?php
include('app.conf');

$sql = "SELECT Count(toID) FROM messages
		WHERE citit=0 AND toID=".$_SESSION['USERID'];
$res = mysql_query($sql);
list($necitite)=mysql_fetch_row($res);
mysql_free_result($res);
?>
<body>
<!--TopPan-->
<div id="topPan">
	<ul>
		<?php
		if($_SESSION['USERID']>0) { 
		?>
		<li class="home"><a href="index.php">NOUTATI</a></li>
		<li class="menupadding"><a href="index.php?option=club">CLUB</a></li>
		<li class="menupadding"><a href="index.php?option=meciuri">MECIURI</a></li>
		<li class="menupadding"><a href="index.php?option=messages">
		<?php
			if($necitite>0) {
				echo "<font color=\"lightgreen\">MESAJE($necitite)</font>";
		} else {
				echo "MESAJE";
		}
		?>
		</a></li>
		<li class="menupadding"><a href="index.php?option=tactics">TACTICA</a></li>
		<li class="menupadding"><a href="index.php?option=search">CAUTARE</a></li>
		<li class="menupadding"><a href="index.php?option=logoff">LOG OFF</a></li>
		<?php } else { ?>
		<li class="home">Home</li>
		<li class="menupadding"><a href="#">REGISTER</a></li>
		<?php } ?>
	</ul>
	<h1>Fotbal Manager</h1>
	<div>
	<?php 
	if ($_SESSION['USERID']>0) {
		$user = new User();
		$user->LoginId($_SESSION['USERID']);
		echo "<font color=\"white\">Bine ai venit, ".$user->GetManagerName()."!</font>";
	} else {
	?>
	<form action="index.php" method="post">
		<input type="text" name="userName" value="User" />
		<input name="password" type="password" value="Password" />
		<input name="Login" type="submit" value="LOGIN"/>
	</form>
	<?php } ?>
	</div>
</div>
<div id="bodyPan">
<form>
</form>
