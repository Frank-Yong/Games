<?php
//acest script se ruleaza o data pe zi
//aici se genereaza accidentarile.
//trebuie ca acest fisier sa fie pus in cron si sa nu fie accesibil

//1.
//se preiau toti jucatorii valizi din baza de date (neaccidentati)


include('../app.conf.php');

include('../user.php');
include('../Player.php');
include('../trainer.php');

include('admin.head.php');

global $prenume, $nume, $pid;


$sql2 = "SELECT a.id, a.injury, c.data, a.fname, a.lname, b.userid, d.TeamName 
		FROM player a
		left join userplayer b
		on a.id=b.playerid
		left join accidentare c
		ON a.id=c.playerid
		left join user d
		on b.userid=d.id
		WHERE a.injured=1
		order by d.lastactive DESC";

echo "<br/><h1>Injured players</h1><br/>";
echo "$sql2<br/>";
$res2 = mysqli_query($GLOBALS['con'],$sql2);
while(list($p_id, $p_injury, $c_data, $p_fname, $p_lname, $u_userid, $echipa) = mysqli_fetch_row($res2)) {
		echo "$p_id $p_fname $p_lname ++ $c_data ==== $echipa<br/>";
		
	}
mysqli_free_result($res2);

?>