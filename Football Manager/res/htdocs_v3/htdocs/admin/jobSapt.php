<?php
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');
//job pentru scaderea banilor pe salarii si alte lucruri saptaminal


//1. scadere salarii jucatori
$sql = "SELECT id
		FROM user
		WHERE activated=1";
$res2 = mysql_query($sql);
while(list($useri) = mysql_fetch_row($res2)) {
	$sql = "SELECT SUM(a.Wage) 
			FROM player a
			LEFT OUTER JOIN userplayer b
			ON a.id=b.PlayerID
			WHERE b.UserID = $useri";
	$res = mysql_query($sql);
	list($salarii) = mysql_fetch_row($res);
	mysql_free_result($res);

	$sql = "INSERT INTO balanta (userid, motiv, suma, sezon)
			VALUES($useri, 'Jucator', -$salarii, $_SEZON)";
	mysql_query($sql);
	
	$sql = "SELECT SUM(a.Wage) 
		FROM trainer a
		LEFT OUTER JOIN usertrainer b
		ON a.id=b.TrainerID
		WHERE b.UserID = $useri";
	$res = mysql_query($sql);
	list($salarii_an) = mysql_fetch_row($res);
	mysql_free_result($res);

	$sql = "INSERT INTO balanta (userid, motiv, suma, sezon)
			VALUES($useri, 'Antrenor', -$salarii_an, $_SEZON)";
	mysql_query($sql);

	$sal_total = $salarii + $salarii_an;
	
	$sql = "UPDATE user SET Funds=Funds-$sal_total WHERE id=$useri";
	echo "$sql<br/>";
	mysql_query($sql);

	$sql = "INSERT INTO messages(fromID, toID, subject, body, data, citit, meciID, sponsor)
		VALUES(0, $useri, 'Plata salarii si utilitati!', 'Salut,<br/>Sunt adjunctul tau pe probleme financiare. Vreau sa te anunt ca am platit salariile si utilitatile, in valoare de ".number_format($sal_total)." &euro;! Banii au fost retrasi din cont!', '".Date("Y-m-d H:i:s")."',0,0,0)";
	echo "$sql<br/>";
	mysql_query($sql);

}
mysql_free_result($res2);
?>