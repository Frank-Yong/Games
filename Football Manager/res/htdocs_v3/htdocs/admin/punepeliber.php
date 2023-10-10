<?php
error_reporting(63);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

include('admin.head.php');

echo "<img src=\"../baragrafica.php?percentage=58'\">";

if(!empty($_REQUEST['Trimite'])) {
	echo "<br/><br/>";
	$sql = "SELECT b.playerid 
			FROM user a	
			LEFT OUTER JOIN userplayer b
			ON a.id=b.userid
			WHERE a.id=".$_REQUEST['echipa'];
	echo "$sql<br/>";
	$res = mysqli_query($GLOBALS['con'],$sql);
	while(list($pid) = mysqli_fetch_row($res)) {
		$sql = "UPDATE userplayer SET userid=0 where playerid=$pid";
		mysqli_query($GLOBALS['con'],$sql);
		
		$sql = "UPDATE lineup SET post=0, userid=0 WHERE playerid=$pid";
		mysqli_query($GLOBALS['con'],$sql);
		
		$sql = "UPDATE player SET transfer=1 WHERE id=$pid";
		mysqli_query($GLOBALS['con'],$sql);
	}
	mysqli_free_result($res);
}
?>
<h1>FIRE PLAYERS FROM INACTIVE TEAMS</h1>
<form action="punepeliber.php" method="POST">
	<select name="echipa" size="12">
	<?php
		$sql = "SELECT a.id, a.TeamName, a.LastActive, a.LeagueID, b.name, a.email
				FROM user a
				LEFT JOIN competition b
				on a.LeagueID=b.id
				WHERE a.activated=1
				ORDER BY a.LastActive DESC";
		$res = mysqli_query($GLOBALS['con'],$sql);
		while(list($id, $echipa,$activ,$ligaid,$liganume, $email) = mysqli_fetch_row($res)) {
			echo "<option value=\"$id\">$echipa($activ) - $liganume -- $email";
		}
		mysqli_free_result($res);
	?>
	</select>
<br/><br/>
<input type="Submit" name="Trimite" value="Fire players...">
	
</form>