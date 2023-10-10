<?php
//phpinfo();
error_reporting(E_ALL);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

include('admin.head.php');

if(!empty($_REQUEST['SetGamesKO'])) {
	list($idcompetitie, $sezon) = explode('-', $_REQUEST['liga']);
	$et = $_REQUEST['etapa'];

	//iau din competitie stadiu, pentru liga dorita, echipele care sunt inca active
	//dupa ce pierde o echipa, o scot de acolo - o pun pe 0 la stadiu

	$sql = "SELECT a.userid 
			FROM competitiestadiu a 
			WHERE a.competitieid=$idcompetitie AND a.sezon=$sezon AND a.stadiu=1";
	echo "$sql<br/>";
	$res = mysqli_query($GLOBALS['con'],$sql);
	$iduri = array();
	$i=1;
	while(list($id) = mysqli_fetch_row($res)) {
		$iduri[$i++] = $id;
	}

	mysqli_free_result($res);

	//le ametesc, sa le pun meciurile aleator
	shuffle($iduri);

	for($i=0;$i<count($iduri);$i++) {
		$j=$i;
		$k=$i+1;
		
		$datameci = date("Y-m-d", mktime(0, 0, 0, $_REQUEST['luna'], $_REQUEST['zi'], $_REQUEST['an']));
		
		$sql3 = "SELECT StadiumID
				 FROM user
				 WHERE id=".$iduri[$j];
		//echo "$sql3<br/>";
		$res3 = mysqli_query($GLOBALS['con'],$sql3);
		list($stadium) = mysqli_fetch_row($res3);
		mysqli_free_result($res3);

		
		$s2 = "DELETE FROM evenimente WHERE (userid=".$iduri[$j]." OR userid=".$iduri[$k].") AND data='$datameci'";
		mysqli_query($GLOBALS['con'],$s2);
		echo "$s2<br/>";
		
		$s2 = "DELETE FROM gameinvitation WHERE (userId_1=".$iduri[$j]." OR userId_2=".$iduri[$k].") AND gamedate='$datameci'";
		mysqli_query($GLOBALS['con'],$s2);
		echo "$s2<br/>";

		
		$sql2 = "INSERT INTO gameinvitation (userId_1, userId_2, gametype, rround, gamedate, age, stadium, accepted, competitionid)
				 VALUES(".$iduri[$j].",".$iduri[$k].", $idcompetitie, $et, '".$datameci."', 1, $stadium, 1, $idcompetitie)";
		echo "$sql2<br/>";
		mysqli_query($GLOBALS['con'],$sql2);

		$i++;
	}
}



if(!empty($_REQUEST['ProcesareKO'])) {
	list($idcompetitie, $sezon) = explode('-', $_REQUEST['liga']);
	$datameci = $_REQUEST['datameci'];
	
	//procesez rezultatele, prin punerea in competitiestadiu pe 0 pentru echipele care au fost eliminate
	//imi trebuie si data meciului, sa iau toate meciurile din acea data
	
	$sql = "SELECT a.userId_1, a.userId_2, a.scor
			FROM gameinvitation a 
			WHERE a.competitionid=$idcompetitie AND a.gamedate='$datameci'";
	echo "$sql<br/>";
	$res = mysqli_query($GLOBALS['con'],$sql);
	while(list($ech1, $ech2, $scor) = mysqli_fetch_row($res)) {
		list($g1, $g2) = explode(':', $scor);
		if($g1>$g2) {
			//echipa 2 parareste competitia
			$s2 = "UPDATE competitiestadiu SET stadiu=0 WHERE userid=$ech2 AND competitieid=$idcompetitie AND sezon=$sezon";
			echo "$s2<br/>";
			mysqli_query($GLOBALS['con'],$s2);
		} else {
			//echipa 1 parareste competitia
			$s2 = "UPDATE competitiestadiu SET stadiu=0 WHERE userid=$ech1 AND competitieid=$idcompetitie AND sezon=$sezon";
			echo "$s2<br/>";
			mysqli_query($GLOBALS['con'],$s2);
		}
	}

	mysqli_free_result($res);


}







if(!empty($_REQUEST['SetTeamsToLeagueKO'])) {
	//asociere echipe cu liga

	list($idcompetitie, $sezon) = explode('-', $_REQUEST['liga']);
	$i=0;
	while(list($k,$ech)=each($_REQUEST['echipe'])) {
	
		$sql = "INSERT INTO competitiestadiu (userid, competitieid, sezon, stadiu)
				VALUES($ech, $idcompetitie, $sezon, 1)";
		mysqli_query($GLOBALS['con'],$sql);
		echo "$sql<br/>";
		$i++;
	}
	echo "Inserted $i teams<br/>";
}
?>

<hr>
<h1>Add to the league</h1>
<form action="genLeagueKO.php" method="POST">
<table>
<tr>
	<td>Set the teams</td>
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
	<td>
	<select name="echipe[]" multiple size="12">
	<?php
		$sql = "SELECT a.id, a.TeamName, a.LastActive, a.LeagueID, b.name
				FROM user a
				LEFT JOIN competition b
				on a.LeagueID=b.id
				WHERE a.activated=1
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
	<td><input type="Submit" name="SetTeamsToLeagueKO" value="Set teams for KO League (Cup)"></td>
</tr>
</table>
</form>
<hr>
<h1>Generate games</h1>
<form action="genLeagueKO.php" method="POST">
<table>
<tr>
	<td colspan="2">
	</td>
</tr>
<tr>
	<td>Liga</td>
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
<tr>
	<td colspan="2">Round:
	<select name="etapa">
		<option value="1">1
		<option value="2">2
		<option value="3">3
		<option value="4">4
		<option value="5">5
		<option value="6">6
		<option value="7">7
		<option value="8">8
	</select>
	</td>
</tr>
<tr>
	<td>Start date</td>
	<td>
	<select name="an">
	<option><?php echo Date("Y"); ?>
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
	<td><input type="Submit" name="SetGamesKO" value="Set KO games"></td>
</tr>
</table>
</form>

<hr>
<h1>Process the league</h1>
<form action="genLeagueKO.php" method="POST">
<table>
<tr>
	<td colspan="2">
	</td>
</tr>
<tr>
	<td>League</td>
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
<tr>
	<td>Start date</td>
	<td>
	<?php
	$sql = "SELECT DISTINCT a.gamedate, b.name 
			FROM gameinvitation a
			LEFT JOIN competition b
			ON a.competitionid = b.id
			WHERE a.competitionid<>0
			ORDER BY a.gamedate DESC";
	$res = mysqli_query($GLOBALS['con'],$sql);
	echo "<select name=\"datameci\">";
	while(list($dmeci, $numecomp) = mysqli_fetch_row($res)) {
			$denzi = date("l", strtotime($dmeci));
			echo "<option value=\"$dmeci\">$dmeci ($denzi) -- $numecomp";
	}	
	echo "</select>";
	mysqli_free_result($res);
	?>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="Submit" name="ProcesareKO" value="Process KO games"> - after the games end!</td>
</tr>
</table>
</form>