<?php
//training partea a II-a
//pentru toti jucatorii care au echipa, verific nivelul de crestere.
//ar trebui cumva sa fac, ca dupa ce are cresterea la acea caracteristica, sa-i micsorez procentul de crestere, fara sa-l redistribui
//totodata, daca a ajuns la maxim cu o caracteristica, procentul sa devina 0 pt ea.

include('../app.conf.php');

include('../user.php');
include('../Player.php');
include('../trainer.php');
include('../definitions.inc');

$sql = "SELECT a.playerID, a.userID, b.delasalt, b.cstsalt 
		FROM userplayer a
		LEFT JOIN salt b
		ON a.playerid=b.playerid
		WHERE a.userID<>0";
$res = mysql_query($sql);
while (list($playerID, $userID, $deLaSalt, $cstSalt) = mysql_fetch_row($res)) {

	//se incrementeaza zilele de la salt la fiecare antrenament
	$sql = "UPDATE salt SET delasalt=delasalt+1 WHERE playerID=$playerID";
	//mysql_query($sql);

	echo "DELASALT :: $deLaSalt ----- ".constant($cstSalt).'<br/>';
}
mysql_free_result($res);


?>