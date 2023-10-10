<?php
error_reporting(E_ALL);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

//actualizare socios -> ruleaza in fiecare zi, sa faca update la numarul de like-uri 
//preia fiecare utilizator si citeste nr de like-uri

$mes = 'O noua functionalitate disponibila pentru clubul tau: tribuna! Fiecare club are posibilitatea de aduce fani/socios in tribuna (Meniu/Club). Trimiti linkul aferent clubului tau (linkul este de forma: http://www.cupaligii.ro/index.php?fbclub=xxx) prietenilor pe facebook, twitter, yahoo sau oriunde altundeva, sa te ajute cu un like.

In functie de acest numar de "socios", vor veni mai multi suporteri in tribuna, salariile vor fi suportate partial de acestia, iar ratingul clubului va creste, aducand astfel sponsori mai puternici pentru club!';

$subject = 'Tribuna/Socios';

$sql = "SELECT id from user WHERE activated=1 and botteam=0 ORDER BY id ASC";
$res = mysql_query($sql);
while(list($userid) = mysql_fetch_row($res)) {
	$sql = "INSERT INTO messages(fromID,toID, subject, body, data, meciID, sponsor)
			VALUES(0,$userid, '$subject', '$mes', '".Date("Y-m-d H:i:s")."', 0, 0)";
	mysql_query($sql);
}
mysql_free_result($res);
?>

