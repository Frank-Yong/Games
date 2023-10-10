<?php
error_reporting(63);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

include('admin.head.php');

echo "<img src=\"../baragrafica.php?percentage=58'\">";

if(!empty($_REQUEST['Trimite'])) {
	$gr = $_REQUEST['grupa'];
	echo "<br/><br/>";
	$sql = "SELECT a.playerId, a.post, LEFT(b.fname,1), LEFT(b.lname,7) 
			FROM lineup a
			LEFT OUTER JOIN player b
			ON a.playerId=b.id
			WHERE a.pgroup=$gr AND a.post<>0 AND a.userId=".$_REQUEST['echipa']. " ORDER BY a.post ASC";
	$res = mysqli_query($GLOBALS['con'],$sql);
	$pozitii = array();
	$jucatori = array();
	$i=0;
	$findex=0;
	$mindex=0;
	$aindex=0;
	$fs=$fd=$f1=$f2=$f3=$ml=$mc=$mr=$m1=$m2=$m3=$a1=$a2=$a3="";
	while(list($e_pid, $e_post, $juc_fname, $juc_lname)=mysqli_fetch_row($res)) {
		$pozitii[$e_pid] = $e_post;
		$jucatori[$e_pid] = $juc_fname.'.'.$juc_lname;
		switch ($e_post) {
			case 1: $pos = "GK"; $portar = $juc_lname; break;
			case 2: $pos = "DR"; $fd = $juc_lname;break;
			case 3: 
				$pos = "DC"; 
				switch($findex) {
					case 0: $f1=$juc_lname; break;
					case 1: $f2=$juc_lname; break;
					case 2: $f3=$juc_lname; break;
				}
				$findex++;
				break;
			case 4: $pos = "DL"; $fs = $juc_lname; break;
			case 5: $pos = "MR"; $mr = $juc_lname; break;
			case 6: 
				$pos = "MC"; 
				switch($mindex) {
					case 0: $m1=$juc_lname; break;
					case 1: $m2=$juc_lname; break;
					case 2: $m3=$juc_lname; break;
				}
				$mindex++;
				
				break;
			case 7: $pos = "ML"; $ml = $juc_lname; break;
			case 8: 
				$pos = "FR"; 
				switch($aindex) {
					case 0: $a1=$juc_lname; break;
					case 1: $a2=$juc_lname; break;
					case 2: $a3=$juc_lname; break;
				}
				$aindex++;
				break;
			case 9: 
				$pos = "FC"; 
				switch($aindex) {
					case 0: $a1=$juc_lname; break;
					case 1: $a2=$juc_lname; break;
					case 2: $a3=$juc_lname; break;
				}
				$aindex++;
				
				break;
			case 10: 
				$pos = "FL"; 
				switch($aindex) {
					case 0: $a1=$juc_lname; break;
					case 1: $a2=$juc_lname; break;
					case 2: $a3=$juc_lname; break;
				}
				$aindex++;
				
				break;
	}
		echo "$juc_fname.$juc_lname($pos)";
		if($i<10) echo "-";
		$i++;
	}
	mysqli_free_result($res);
}
?>
<h1>Show lineup</h1>
<form action="echipastart.php" method="POST">
	<select name="grupa">
	<option value="1">first team
	<option value="2">youth
	</select>
	<select name="echipa" size="12">
	<?php
		$sql = "SELECT a.id, a.TeamName, a.LastActive, c.competitionid, b.name, a.email
				FROM user a
				LEFT JOIN leagueuser c
				ON a.id=c.userid
				LEFT JOIN competition b
				on c.competitionid=b.id
				WHERE a.activated=1 AND c.season=".$_SESSION['_SEASON']."
				ORDER BY a.LastActive DESC";
		$res = mysqli_query($GLOBALS['con'],$sql);
		while(list($id, $echipa,$activ,$ligaid,$liganume, $email) = mysqli_fetch_row($res)) {
			echo "<option value=\"$id\">$echipa($activ) - $liganume -- $email";
		}
		mysqli_free_result($res);
	?>
	</select>
<br/><br/>
<input type="Submit" name="Trimite" value="Show...">
	
</form>