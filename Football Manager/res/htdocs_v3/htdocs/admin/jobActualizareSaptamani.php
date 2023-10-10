<?php
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');
//job actualizare saptamini pentru jucatori la club
//se ruleaza zilnic
//in momentul in care se ruleaza, se verifica daca a trecut saptamina de la data inregistrata in userplayer
//daca da, se actualizeaza saptamina si se modifica data cu cea curenta

$sql = "SELECT playerid, saptamini, data FROM userplayer
		WHERE userid>0";
$res = mysql_query($sql);
while(list($playerid, $saptamini, $data) = mysql_fetch_row($res)) {
	$date1=  new DateTime();
	$date2= new DateTime($data.' 00:00:00');
	if ($date1->diff($date2)->format("%d") == 7) {
		//a trecut o saptamina
		$s = "UPDATE userplayer SET saptamini = saptamini + 1, data = '".Date("Y-m-d")."' WHERE playerid=$playerid";
		mysql_query($s);
		echo "$s<br/>";
	}
}
mysql_free_result($res);
?>