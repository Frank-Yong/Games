<?php
error_reporting(63);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');


//procesare jucatori
//resetez moral si forma
//crestere varsta + scadere perioada contract
//daca contract devine 0, se face update la userplayer, punindu-se userid=0 si se modifica in player ca Transfer=1
$sql = "SELECT id, age, contract, transfer, moral, form
		FROM player";

$res = mysql_query($sql);
while(list($pid, $page, $pcontract, $ptransfer, $pmoral, $pform) = mysql_fetch_row($res)) {
	if($page<=18) {
		$sql = "UPDATE echipastart SET grupa=2 WHERE playerid=$pid";
		mysql_query($sql);
		echo "$sql<br/>";
	}
}
mysql_free_result($res);
		


?>