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


$mes = 'Job accidentari la ora '.Date("Y-m-d H:i:s");
$sql = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
		VALUES(0, 23, 'Job accidentari', '$mes' , '".Date("Y-m-d H:i:s")."', 0, 0)";
//mysql_query($sql);
echo "$sql<br/>";


global $prenume, $nume, $pid;

$day = jddayofweek(0);

if($day == 1 || $day == 5) {

$sql2 = "SELECT a.id, a.injury, a.fname, a.lname, b.userid
		FROM player a
		left join userplayer b
		on a.id=b.playerid
		WHERE a.accidentat=0 AND b.userid>0 AND a.training=0
		order by b.userid ASC";

} else {
	$sql2 = "SELECT";
}
echo "$sql2<br/>";
$res2 = mysql_query($sql2);
while(list($p_id, $p_injury, $p_fname, $p_lname, $u_userid) = mysql_fetch_row($res2)) {
		echo "$p_id $p_fname $p_lname ==== $p_injury<br/>";
		$prenume = $p_fname;
		$nume = $p_lname;
		
		$random = rand(0,100);
		if($random>84) {
			//verific daca are caracteristica de injury cu valoare corespunzatoare.
			//adica sa aiba peste o valoare - insumez randomul cu valoarea de accidentare
			if($random+$p_injury>140) {
				$s = "select count(a.id)
				      from player a
					  left join userplayer b
					  on a.id=b.playerid
					  where a.accidentat=1";
				$r = mysql_query($s);
				list($accidentati) = mysql_fetch_row($r);
				mysql_free_result($r);
				//sa nu aiba mai mult de 3 accidentati
				if($accidentati>2) {
				} else {
				
					//cite zile se accidenteaza
					$r_zile = rand(1,14);
					echo "$random => se accidenteaza $r_zile!<br/>";
					//il scot din echipa de start, daca este acolo
					//nu va mai face antrenamente
					$s = "UPDATE echipastart SET post=0 WHERE playerid=$p_id";
					mysql_query($s);
					//il pun accidentat in tabelul player
					$s = "UPDATE player SET accidentat=1 WHERE id=$p_id";
					mysql_query($s);
					//il pun si in tabelul accidentare
					$dnoua = date('Y-m-d 00:00:00', strtotime("+$r_zile days"));
					$s = "INSERT INTO accidentare(playerid, userid, data)
						  VALUES($p_id, $u_userid, '$dnoua')";
					echo "$s<br/>";
					mysql_query($s);
					
					$mes = 'Salut, sunt unul din antrenorii tai secunzi. Din nefericire unul din jucatorii tai, '.$p_fname.' '.$p_lname.', s-a accidentat astazi! Jucatorul va sta '.$r_zile.' zile, perioada in care nu te vei putea baza pe el!';
					$sql = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
						VALUES(0, $u_userid, 'Jucator acccidentat!', '$mes' , '".Date("Y-m-d H:i:s")."', 0, 0)";
					mysql_query($sql);
					//echo "$sql<br/>";
				}
				
			}
		}
	}
mysql_free_result($res2);

?>