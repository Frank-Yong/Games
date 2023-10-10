<?php
//job moral pentru echipa
//parcurge fiecare echipa si in functie de numarul de jucatori, ii scade moralul sau nu.
//daca moralul este mai mic de 100 si numarul de jucatori este indeplinit, moralul creste
//oblig astfel echipele sa scada numarul de jucatori, prin vinzare sau punere pe liber
include('../app.conf.php');

$sql = "SELECT id, Moral
		FROM user ORDER BY id ASC";
$res = mysql_query($sql);
while(list($uid, $umoral) = mysql_fetch_row($res)) {
	$sql2 = "SELECT a.playerid 
			 FROM userplayer a
			 LEFT JOIN player b
			 ON a.playerid=b.id
			 WHERE b.youth=0 AND a.userid=$uid";
	$res2 = mysql_query($sql2);
	$nrjuc = mysql_num_rows($res2);
	mysql_free_result($res2);
	if($nrjuc>=32 && $nrjuc<36) $scademoral=2;
	if($nrjuc>=36 && $nrjuc<40) $scademoral=4;
	if($nrjuc>=40 && $nrjuc<46) $scademoral=6;
	if($nrjuc>=46) $scademoral=10;
	
	if($nrjuc<32) $scademoral = -5;
	
	$moral = $umoral-$scademoral;
	if($moral>100) $moral=100;
	if($moral<0) $moral=0;
	$sq = "UPDATE user SET Moral=$moral WHERE id=$uid";
	echo "$sql<br/>";
	mysql_query($sq);
}
mysql_free_result($res);
?>