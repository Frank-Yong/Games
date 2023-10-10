<?php
//stergere echipa inactiva
//cu tot cu jucatori, care ingreuneaza antrenamente si alte.

error_reporting(63);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

include('admin.head.php');

if(!empty($_REQUEST['Trimite'])) {
	$sql = "SELECT id, username, teamname, email, botteam, stadiumid FROM user WHERE id=".$_REQUEST['echipa'];
	$res = mysqli_query($GLOBALS['con'],$sql);
	list($userid, $username, $teamname, $emailadv, $estebot, $stadionadv) = mysqli_fetch_row($res);
	mysqli_free_result($res);

	$sql = "SELECT playerid FROM userplayer 
			WHERE userid=$userid";
	echo "$sql<br>";
	$res = mysqli_query($GLOBALS['con'],$sql);
	while(list($pid) = mysqli_fetch_row($res)) {
		$sql2 = "DELETE FROM player WHERE id=$pid";
		mysqli_query($GLOBALS['con'],$sql2);
		//echo "$sql2<br>";
		
		$sql2 = "DELETE FROM grow where playerid=$pid";
		mysqli_query($GLOBALS['con'],$sql2);
		//echo "$sql2<br>";

		$sql2 = "DELETE FROM loggrows where playerid=$pid";
		mysqli_query($GLOBALS['con'],$sql2);
		//echo "$sql2<br>";

		$sql2 = "DELETE FROM percentage where playerid=$pid";
		mysqli_query($GLOBALS['con'],$sql2);
		//echo "$sql2<br>";

		$sql2 = "DELETE FROM leap where playerid=$pid";
		mysqli_query($GLOBALS['con'],$sql2);

		$sql2 = "DELETE FROM lineup where playerid=$pid";
		mysqli_query($GLOBALS['con'],$sql2);
		//echo "$sql2<br>";

		$sql2 = "DELETE FROM morale where playerid=$pid";
		mysqli_query($GLOBALS['con'],$sql2);
		//echo "$sql2<br>";
	
	}
	
$sql2 = "DELETE FROM stadium where id=$stadionadv";
mysqli_query($GLOBALS['con'],$sql2);
		echo "$sql2<br>";


$sql2 = "UPDATE user SET activated=0 where id=$userid";
mysqli_query($GLOBALS['con'],$sql2);
		echo "$sql2<br>";

	
mysqli_free_result($res);
	
}
?>
<h2>Delete Team</h2>
<form action="DeleteTeam.php" method="POST">
	<br/>
	<select name="echipa" size="12">
	<?php
		$sql = "SELECT a.id, a.TeamName, a.LastActive, a.LeagueID, b.name, a.email, a.activated
				FROM user a
				LEFT JOIN competition b
				on a.LeagueID=b.id
				
				ORDER BY a.LastActive ASC";
		$res = mysqli_query($GLOBALS['con'],$sql);
		while(list($id, $echipa,$activ,$ligaid,$liganume, $email, $activated) = mysqli_fetch_row($res)) {
			echo "<option value=\"$id\">$activated: $echipa($activ) - $liganume -- $email";
		}
		mysqli_free_result($res);
	?>
	</select>
<br/><br/>
<textarea name="mesaj" rows="5" cols="20"></textarea>
<br/>	<br/>
<input type="Submit" name="Trimite" value="Delete team...">
	
</form>