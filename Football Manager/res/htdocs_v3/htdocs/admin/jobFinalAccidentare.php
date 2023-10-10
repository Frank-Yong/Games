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


$mes = 'Job final accidentari la ora '.Date("Y-m-d H:i:s");
$sql = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
		VALUES(0, 23, 'Job verifica final accidentari', '$mes' , '".Date("Y-m-d H:i:s")."', 0, 0)";
//mysql_query($sql);
echo "$sql<br/>";


global $prenume, $nume, $pid;


$dcurenta = Date("Y-m-d 00:00:00");

$sql2 = "SELECT a.id, b.data, b.id
		FROM player a
		left join accidentare b
		on a.id=b.playerid
		WHERE a.accidentat=1 AND b.data ='$dcurenta'";

echo "$sql2<br/>";
$res2 = mysql_query($sql2);
while(list($p_id, $a_data, $a_id) = mysql_fetch_row($res2)) {
		//il pun neaccidentat in tabelul player
		$s = "UPDATE player SET accidentat=0 WHERE id=$p_id";
		mysql_query($s);
		//il scot si din tabelul accidentare
		$s = "DELETE FROM accidentare WHERE id=$a_id";
		echo "$s<br/>";
		mysql_query($s);
}
mysql_free_result($res2);

?>