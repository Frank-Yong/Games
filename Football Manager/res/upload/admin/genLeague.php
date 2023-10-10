<?php
//phpinfo();

include('../app.conf.php');
error_reporting(E_ALL);
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

include('admin.head.php');




if(!empty($_REQUEST['SetGames'])) {
	list($idcompetitie, $sezon) = explode('-', $_REQUEST['liga']);

	$sql = "SELECT a.userid 
			FROM competitiestadiu a 
			WHERE a.competitieid=$idcompetitie AND a.sezon=$sezon";
	echo "$sql<br/>";
	$res = mysqli_query($GLOBALS['con'],$sql);
	$iduri = array();
	$i=1;
	while(list($id) = mysqli_fetch_row($res)) {
		$iduri[$i++] = $id;
	}

	mysqli_free_result($res);

	//matrix with all the rounds
	$sql = "SELECT rround, game
			FROM roundmatrix
			ORDER BY rround ASC";
	$res = mysqli_query($GLOBALS['con'],$sql);

	$datameci = date("Y-m-d", mktime(0, 0, 0, $_REQUEST['luna'], $_REQUEST['zi'], $_REQUEST['an']));

	$i=0;
	$etini = 0;
	while(list($etapa, $meci) = mysqli_fetch_row($res)) {
		if($etini <> $etapa) {
			$etini = $etapa;
			if($i<>0) {
				if ($i%2==0) {
					$datameci = Date("Y-m-d", strtotime($datameci." +3 days"));
				} else {
					$datameci = Date("Y-m-d", strtotime($datameci." +4 days"));
				}
			}
			$i++;
		}
		list($ech1, $ech2) = explode('-',$meci);
		
		$sql3 = "SELECT StadiumID
				 FROM user
				 WHERE id=".$iduri[$ech1];
		echo "$sql3<br/>";
		$res3 = mysqli_query($GLOBALS['con'],$sql3);
		list($stadium) = mysqli_fetch_row($res3);
		mysqli_free_result($res3);

		$grupa = $_REQUEST['grupa'];
		//for youth = 2
		$sql2 = "INSERT INTO gameinvitation (userId_1, userId_2, gametype, rround, gamedate, age, stadium, accepted, competitionid)
				 VALUES(".$iduri[$ech1].",".$iduri[$ech2].", $idcompetitie, $etapa, '".$datameci."', $grupa, $stadium, 1, $idcompetitie)";
		echo "$sql2<br/>";
		mysqli_query($GLOBALS['con'],$sql2);

	}
	mysqli_free_result($res);
}


if(!empty($_REQUEST['SetTeamsToLeague'])) {
	//assign team to league

	list($idcompetitie, $sezon) = explode('-', $_REQUEST['liga']);
	
	while(list($k,$ech)=each($_REQUEST['echipe'])) {
		$sql = "UPDATE leagueuser SET competitionid=$idcompetitie WHERE userid=$ech AND season=$sezon";
		mysqli_query($GLOBALS['con'],$sql);
		
		$sql = "INSERT INTO competitiestadiu (userid, competitieid, sezon, stadiu)
				VALUES($ech, $idcompetitie, $sezon, 1)";
		mysqli_query($GLOBALS['con'],$sql);
		echo "$sql<br/>";

		//insert into round table with value 0
		$sql = "INSERT INTO clasament (competitieid, etapa, userid, victorii, egaluri, infringeri, gm, gp, puncte)
				VALUES($idcompetitie, 0, $ech, 0, 0, 0, 0, 0, 0)";
		mysqli_query($GLOBALS['con'],$sql);
	}
}


if(!empty($_REQUEST['DeleteTeamsFromLeague'])) {
	//delete teams from the league

	
	//while(list($k,$ech)=each($_REQUEST['echipesterge'])) {
	foreach($_REQUEST['echipesterge'] as $k => $ech) {
		$sql = "SELECT competitionid FROM leagueuser WHERE userid=$ech";
		$res = mysqli_query($GLOBALS['con'],$sql);
		list($compid) = mysqli_fetch_row($res);
		mysqli_free_result($res);
		
		$sql = "UPDATE leagueuser SET competitionid=0 WHERE userid=$ech AND season=".$_SESSION['_SEASON'];
		mysqli_query($GLOBALS['con'],$sql);
		echo "$sql<br/>";
		
		$sql = "DELETE FROM competitiestadiu WHERE userid=$ech AND competitieid=$compid";
		mysqli_query($GLOBALS['con'],$sql);
		echo "$sql<br/>";

		//delete from the round table
		$sql = "DELETE FROM clasament WHERE userid=$ech AND competitieid=$compid";
		mysqli_query($GLOBALS['con'],$sql);
		echo "$sql<br/>";

	}
}

?>
<h1>Display teams in the league</h1>
<form action="genLeague.php" method="POST">
<table>
<tr>
	<td>Choose league</td>
	<td>
	<?php
		$sql = "SELECT a.id, a.name, a.season
				FROM competition a
				where a.season='".$_SESSION['_SEASON']."' 
				ORDER BY a.season DESC";
		//echo "$sql<br/>";
		$res = mysqli_query($GLOBALS['con'],$sql);
		?>
	<select name="liga">
	<?php
		while(list($id, $nume, $sezon) = mysqli_fetch_row($res)) {
			echo "<option value=\"$id-$sezon\">$nume - $sezon";
		}
		mysqli_free_result($res);
	?>
	</select>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="Submit" name="ViewLeague" value="Teams in league"></td>
</tr>
</table>
</form>
<?php
if(!empty($_REQUEST['ViewLeague'])) {
		list($idcompetitie, $sezon) = explode('-', $_REQUEST['liga']);

		$sql = "SELECT b.TeamName 
				FROM competitiestadiu a
				LEFT JOIN user b
				ON a.userid=b.id
				WHERE a.competitieid=$idcompetitie";
		//echo "$sql<br/>";
		$res = mysqli_query($GLOBALS['con'],$sql);
		$i=1;
		while(list($ec) = mysqli_fetch_row($res)) {
			echo "$i. $ec - ";
			$i++;
		}
		mysqli_free_result($res);
}
?>

<hr>
<h1>Delete team from the league</h1>
<form action="genLeague.php" method="POST">
<table>
<tr>
	<td>Delete team</td>
	<td>
	</td>
	<td>
	<select name="echipesterge[]" multiple size="12">
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
</tr>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td><input type="Submit" name="DeleteTeamsFromLeague" value="Delete team from the league"></td>
</tr>
</table>
</form>


<hr>
<h1>Put teams in the league</h1>
<form action="genLeague.php" method="POST">
<table>
<tr>
	<td>Allocate team</td>
	<td>
	<select name="liga">
	<?php
		$sql = "SELECT a.id, a.name, a.season
				FROM competition a
				where a.season='".$_SESSION['_SEASON']."' 
				ORDER BY a.season DESC";
		$res = mysqli_query($GLOBALS['con'],$sql);
		while(list($id, $nume, $sezon) = mysqli_fetch_row($res)) {
			echo "<option value=\"$id-$sezon\">$nume - $sezon";
		}
		mysqli_free_result($res);
	?>
	</select>
	</td>
	<td>
	<select name="echipe[]" multiple size="12">
	<?php
		$sql = "SELECT a.id, a.TeamName, a.LastActive, c.competitionid, b.name
				FROM user a
				LEFT JOIN leagueuser c
				ON a.id=c.userid
				LEFT JOIN competition b
				on c.competitionid=b.id
				WHERE a.activated=1 AND c.season=".$_SESSION['_SEASON']."
				ORDER BY a.LastActive DESC";
				echo "$sql<br/>";
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
	<td><input type="Submit" name="SetTeamsToLeague" value="Add team in the league"></td>
</tr>
</table>
</form>
<hr>
<h1>Generate games in the league</h1>
<form action="genLeague.php" method="POST">
<table>
<tr>
	<td>Age (1-senior, 2-youth)</td>
	<td>
	<select name="grupa">
	<option value="1">Senior
	<option value="2">Youth
	</select>
	</td>
</tr>
<tr>

	<td>League</td>
	<td>
	<select name="liga">
	<?php
		$sql = "SELECT a.id, a.name, a.season
				FROM competition a
				where a.season='".$_SESSION['_SEASON']."' 
				ORDER BY a.season DESC";
		$res = mysqli_query($GLOBALS['con'],$sql);
		while(list($id, $nume, $sezon) = mysqli_fetch_row($res)) {
			echo "<option value=\"$id-$sezon\">$nume - $sezon";
		}
		mysqli_free_result($res);
	?>
	</select>
	</td>
</tr>
<tr>
	<td>Start date</td>
	<td>
	<select name="an">
	<option><?php echo Date("Y"); ?>
	<option><?php echo Date("Y")+1; ?>
	</select>
	<select name="luna">
	<?php 
	for($i=1;$i<13;$i++)
		echo "<option>$i";
	?>
	</select>
	<select name="zi">
	<?php 
	for($i=1;$i<32;$i++)
		echo "<option>$i";
	?>
	</select>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="Submit" name="SetGames" value="Set up the games"></td>
</tr>
</table>
</form>