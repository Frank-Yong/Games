<?php
error_reporting(0);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

include('admin.head.php');

		$sss = "SELECT  b.LastActive
				FROM userplayer a
				LEFT OUTER JOIN user b
				ON a.userid=b.id
				WHERE a.playerid=10177";
		$rrr = mysqli_query($GLOBALS['con'],$sss);
		list($d_activ) = mysqli_fetch_row($rrr);
		mysqli_free_result($rrr);
		 $dEnd  = date('Y-m-d H:i:s');
		
		echo "$d_activ :::   $dEnd";
		$date1 = new DateTime($d_activ);
		$date2 = new DateTime($dEnd);

		$diff = $date2->diff($date1);
		echo "difference " . $diff->days . " days ";
		
		 //$dDiff = $d_activ->diff($dEnd);
		 //echo $dDiff->format('%R'); // use for point out relation: smaller/greater
		 //echo $dDiff->days;



echo "Sezon curent: $_SEASON<br>";

if(!empty($_REQUEST['Trimite'])) {
	
	foreach ($_REQUEST['echipa'] as $selectedOption) {
		echo $selectedOption."\n";
	
		$sql = "INSERT INTO trofee(userid, trofeuid, sezon) VALUES($selectedOption, ".$_REQUEST['trofeutip'].", $_SEASON)";
		echo "$sql<br/>";
		mysqli_query($GLOBALS['con'],$sql);
	}
	
}
?>
<form action="AlocaTrofeu.php" method="POST">
	<select name="trofeutip" size="12">
	<?php
		$sql = "SELECT a.id, a.nume
				FROM trofeutip a
				ORDER BY a.id ASC";
		$res = mysqli_query($GLOBALS['con'],$sql);
		while(list($id, $nume) = mysqli_fetch_row($res)) {
			echo "<option value=\"$id\">$nume";
		}
		mysqli_free_result($res);
	?>
	</select>
	<br/>
	<select name="echipa[]" size="12" multiple>
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
<textarea name="mesaj" rows="5" cols="20"></textarea>
<br/>	<br/>
<input type="Submit" name="Trimite" value="Allocate trophy...">
	
</form>