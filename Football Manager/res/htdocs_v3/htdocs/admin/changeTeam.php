<?php
error_reporting(E_ALL);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

include('admin.head.php');
//inlocuire echipa cu alta echipa in liga

/*
UPDATE competitiestadiu SET userid=98 where userid=31
UPDATE clasament SET userid=98 where userid=31
UPDATE invitatiemeci SET userId_1=98 where userId_1=31
UPDATE invitatiemeci SET userId_2=98 where userId_2=31
*/
//modificare in user pt echipa inlocuita sa fie 0 la ligaid
//modificare pentru user nou sa fie id-ul ligii la ligaid

if(!empty($_REQUEST['ChangeLeague'])) {
		list($idcompetitie, $sezon) = explode('-', $_REQUEST['liga']);
		
		$echnoua = $_REQUEST['echipanoua'];
		$echveche = $_REQUEST['echipaveche'];
		
		$sql = "UPDATE competitiestadiu SET userid=$echnoua where userid=$echveche AND competitieid=$idcompetitie";
		mysqli_query($GLOBALS['con'],$sql);
		echo "$sql<br/>";
		
		$sql = "UPDATE clasament SET userid=$echnoua where userid=$echveche AND competitieid=$idcompetitie";
		mysqli_query($GLOBALS['con'],$sql);
		echo "$sql<br/>";
		
		$sql = "UPDATE gameinvitation SET userId_1=$echnoua where userId_1=$echveche and competitionid=$idcompetitie";
		mysqli_query($GLOBALS['con'],$sql);
		echo "$sql<br/>";
		
		$sql = "UPDATE gameinvitation SET userId_2=$echnoua where userId_2=$echveche and competitionid=$idcompetitie";
		mysqli_query($GLOBALS['con'],$sql);
		echo "$sql<br/>";
		
		$sql = "UPDATE leagueuser SET competitionid=0 where userid=$echveche AND competitionid=$idcompetitie";
		mysqli_query($GLOBALS['con'],$sql);
		echo "$sql<br/>";
		
		$sql = "UPDATE leagueuser SET competitionid=$idcompetitie WHERE userid=$echnoua AND season=".$_SESSION['_SEASON'];
		mysqli_query($GLOBALS['con'],$sql);
		echo "$sql<br/>";
}

?>
<h1>Replace team in the league</h1>
<form action="changeTeam.php" method="POST">
<table>
<tr>
	<td>Choose league</td>
	<td>
	<select name="liga">
	<?php
		$sql = "SELECT id, name, season
				FROM competition
				ORDER BY season DESC";
		$res = mysqli_query($GLOBALS['con'],$sql);
		while(list($id, $nume, $sezon) = mysqli_fetch_row($res)) {
			echo "<option value=\"$id-$sezon\">$nume - $sezon";
		}
		mysqli_free_result($res);
	?>
	</select>
	</td>
</tr>
</table>
<table>
<tr>
	<td>Replace team</td>
	<td>
	New team<br/>
	<select name="echipanoua" size="12">
	<?php
		$sql = "SELECT a.id, a.TeamName, a.LastActive, c.competitionid, b.name
				FROM user a
				LEFT JOIN leagueuser c
				ON a.id=c.userid
				LEFT JOIN competition b
				on c.competitionid=b.id
				WHERE a.activated=1 AND c.season=".$_SESSION['_SEASON']."
				ORDER BY a.LastActive DESC";
		$res = mysqli_query($GLOBALS['con'],$sql);
		while(list($id, $echipa,$activ,$ligaid,$liganume) = mysqli_fetch_row($res)) {
			echo "<option value=\"$id\">$echipa($activ) - $liganume";
		}
		mysqli_free_result($res);
	?>
	</select>
	</td>
	<td>
	Old team (existing team)<br/>
	<select name="echipaveche" size="12">
	<?php
		$sql = "SELECT a.id, a.TeamName, a.LastActive, c.competitionid, b.name
				FROM user a
				LEFT JOIN leagueuser c
				ON a.id=c.userid
				LEFT JOIN competition b
				on c.competitionid=b.id
				WHERE c.season=".$_SESSION['_SEASON']."
				ORDER BY a.LastActive DESC";
		$res = mysqli_query($GLOBALS['con'],$sql);
		while(list($id, $echipa,$activ,$ligaid,$liganume) = mysqli_fetch_row($res)) {
			echo "<option value=\"$id\">$echipa($activ) - $liganume";
		}
		mysqli_free_result($res);
	?>
	</select>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td><input type="Submit" name="ChangeLeague" value="Replace the team"></td>
</tr>
</table>
</form>
