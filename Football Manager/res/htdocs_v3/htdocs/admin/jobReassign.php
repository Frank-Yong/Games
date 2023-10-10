<?php
error_reporting(63);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');
include('../definitions.inc');

//job pentru reprofilarea jucatorilor
//verific tabelul respecializare
//daca in ziua curenta se termina reprofilarea, fac urmatoarele modificari:
//1. modific in tabelul player cimpul training, il fac 0
//2. modific pozitia jucatorului in tabelul player, in noua valoare
//3. sterg inregistrarea din tabelul respecializare, unde id-ul jucatorului este acela
//4. sterg procentele in tabelul procente pentru acest jucator si le adaug pentru noul post

$s1 = "SELECT a.userid, a.playerid, a.post, a.data
	   FROM reassign a
	   WHERE str_to_date(a.data,'%Y-%m-%d')='".Date("Y-m-d")."'";
echo "$s1<br/>";
$r1 = mysql_query($s1);
while(list($uid, $pid, $post1, $data) = mysql_fetch_row($r1)) {
	$s2 = "UPDATE player SET training=0, position=$post1 WHERE id=$pid";
	mysql_query($s2);
	$s2 = "DELETE FROM reassign WHERE playerid=$pid";
	mysql_query($s2);

	$PlayerID = $pid;
			//portar
		switch($post1) {
			case 1: //Goalkeeper
				$post = 1;
				break;
			case 2: //defender DR
				$post = 2;
				break;
			case 3: //defender DC
				$post = 2;
				break;
			case 4: //defender DL
				$post = 2;
				break;
			case 5: //midfielder MR
				$post = 3;
				break;
			case 6: //midfielder MC
				$post = 3;
				break;
			case 7: //midfielder ML
				$post = 3;
				break;
			case 8: //forward FR
				$post = 4;
				break;
			case 9: //forward FC
				$post = 4;
				break;
			case 10: //forward FL
				$post = 4;
				break;
		}


			if ($post == 1) {
			$sql = "INSERT INTO percentage (PlayerId, percent, status, Characteristic, Redist)
					VALUES
					($PlayerID, ".rand(G_REFLEXES_PERCENT_1,G_REFLEXES_PERCENT_2).", 0, 'Reflexes', 0),
					($PlayerID, ".rand(G_ONEONONES_PERCENT_1, G_ONEONONES_PERCENT_2).", 0, 'OneonOne', 0),
					($PlayerID, ".rand(G_HANDLING_PERCENT_1, G_ONEONONES_PERCENT_2).", 0, 'Handling', 0),
					($PlayerID, ".rand(G_COMMUNICATION_PERCENT_1, G_ONEONONES_PERCENT_2).", 0, 'Communication', 0),
					($PlayerID, ".rand(G_POSITIONING_PERCENT_1,G_POSITIONING_PERCENT_2).", 0, 'Positioning', 0),
					($PlayerID, ".rand(G_PASSING_PERCENT_1, G_PASSING_PERCENT_2).", 0, 'Passing', 0),
					($PlayerID, ".rand(G_CROSSING_PERCENT_1, G_CROSSING_PERCENT_2).", 0, 'Crossing', 0),
					($PlayerID, ".rand(G_LONGSHOTS_PERCENT_1, G_LONGSHOTS_PERCENT_2).", 0, 'LongShot', 0)";
			mysql_query($sql);
		}

		//fundas
		if ($post == 2) {
			$sql = "INSERT INTO percentage (PlayerId, percent, status, Characteristic, Redist)
					VALUES
					($PlayerID, ".rand(D_TACKLING_PERCENT_1,D_TACKLING_PERCENT_2).", 0, 'Tackling', 0),
					($PlayerID, ".rand(D_MARKING_PERCENT_1,D_MARKING_PERCENT_2).", 0, 'Marking', 0),
					($PlayerID, ".rand(D_HEADING_PERCENT_1,D_HEADING_PERCENT_2).", 0, 'Heading', 0),
					($PlayerID, ".rand(D_POSITIONING_PERCENT_1,D_POSITIONING_PERCENT_2).", 0, 'Positioning', 0),
					($PlayerID, ".rand(D_PASSING_PERCENT_1, D_PASSING_PERCENT_2).", 0, 'Passing', 0),
					($PlayerID, ".rand(D_CROSSING_PERCENT_1,D_CROSSING_PERCENT_2).", 0, 'Crossing', 0),
					($PlayerID, ".rand(D_COMMUNICATION_PERCENT_1,D_COMMUNICATION_PERCENT_2).", 0, 'Communication', 0),
					($PlayerID, ".rand(D_FIRSTTOUCH_PERCENT_1,D_FIRSTTOUCH_PERCENT_2).", 0, 'FirstTouch', 0)";

			mysql_query($sql);
		}
		//mijlocas
		if ($post == 3) {
			$sql = "INSERT INTO percentage (PlayerId, percent, status, Characteristic, Redist)
					VALUES
					($PlayerID, ".rand(M_PASSING_PERCENT_1,M_PASSING_PERCENT_2).", 0, 'Passing', 0),
					($PlayerID, ".rand(M_CREATIVITY_PERCENT_1,M_CREATIVITY_PERCENT_2).", 0, 'Creativity', 0),
					($PlayerID, ".rand(M_CROSSING_PERCENT_1,M_CROSSING_PERCENT_2).", 0, 'Crossing', 0),
					($PlayerID, ".rand(M_LONGSHOTS_PERCENT_1,M_LONGSHOTS_PERCENT_2).", 0, 'LongShot', 0),
					($PlayerID, ".rand(M_DRIBBLING_PERCENT_1, M_DRIBBLING_PERCENT_2).", 0, 'Dribbling', 0),
					($PlayerID, ".rand(M_POSITIONING_PERCENT_1,M_POSITIONING_PERCENT_2).", 0, 'Positioning', 0),
					($PlayerID, ".rand(M_TACKLING_PERCENT_1,M_TACKLING_PERCENT_2).", 0, 'Tackling', 0),
					($PlayerID, ".rand(M_FIRSTTOUCH_PERCENT_1,M_FIRSTTOUCH_PERCENT_2).", 0, 'FirstTouch', 0),
					($PlayerID, ".rand(M_MARKING_PERCENT_1,M_MARKING_PERCENT_2).", 0, 'Marking', 0)";

			mysql_query($sql);
		}
		//atacant
		if ($post == 4) {
			$sql = "INSERT INTO percentage (PlayerId, percent, status, Characteristic, Redist)
					VALUES
					($PlayerID, ".rand(F_SHOOTING_PERCENT_1,F_SHOOTING_PERCENT_2).", 0, 'Shooting', 0),
					($PlayerID, ".rand(F_HEADING_PERCENT_1,F_HEADING_PERCENT_2).", 0, 'Heading', 0),
					($PlayerID, ".rand(F_POSITIONING_PERCENT_1,F_POSITIONING_PERCENT_2).", 0, 'Positioning', 0),
					($PlayerID, ".rand(F_DRIBBLING_PERCENT_1,F_DRIBBLING_PERCENT_2).", 0, 'Dribbling', 0),
					($PlayerID, ".rand(F_FIRSTTOUCH_PERCENT_1,F_FIRSTTOUCH_PERCENT_2).", 0, 'FirstTouch', 0)";


			mysql_query($sql);
		}
		echo "$sql<br/>";

}
mysql_free_result($r1);

?>