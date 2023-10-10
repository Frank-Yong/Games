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

if(!empty($_REQUEST['Premiu'])) {
		list($idcompetitie, $sezon) = explode('-', $_REQUEST['liga']);
		
		$sql = "SELECT name FROM competition WHERE id=$idcompetitie";
		$res = mysqli_query($GLOBALS['con'],$sql);
		list($numecomp) = mysqli_fetch_row($res);
		mysqli_free_result($res);
		
		$echnoua = $_REQUEST['echipanoua'];
		$suma = $_REQUEST['valoare'];
		

		$mes = 'Hi, i am your assistant for the relation with the sponspors! After the result from the previous season in '.$numecomp.', the sponsors have decided to award you with '.number_format($suma).' &euro;! The money are in the club account!';

		$subject = 'Award for the previous season';

		$sql = "INSERT INTO messages(fromID,toID, subject, body, data, meciID, sponsor)
				VALUES(0,$echnoua, '$subject', '$mes', '".Date("Y-m-d H:i:s")."', 0, 0)";
		mysqli_query($GLOBALS['con'],$sql);
		echo "$sql<br/>";
		
		$sql = "UPDATE user SET Funds=Funds+$suma WHERE id=$echnoua";
		mysqli_query($GLOBALS['con'],$sql);
		echo "$sql<br/>";
		
		
}

?>
<h1>Awards for results</h1>
<form action="PremiiSezon.php" method="POST">
<table>
<tr>
	<td>Liga pentru rezultate</td>
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
	<td>Team</td>
	<td>
	Team<br/>
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

</tr>
<tr>
	<td>Amount</td>
	<td><input type="text" name="valoare" size="20"></td>
</tr>
<tr>
	<td>&nbsp;</td>

	<td><input type="Submit" name="Premiu" value="Give the award"></td>
</tr>
</table>
</form>
