<?php
//job crestere rating
//daca jucatorul a avut in ultimele 30 de zile mai mult de 3 cresteri la caracteristici, creste si la rating.
//in acest fel, jucatorii pot primi mai multi bani la salariu


include('../app.conf.php');

include('../user.php');
include('../Player.php');
include('../trainer.php');
include('../definitions.inc');

$acum30 = strtotime(date('Y-m-d') . ' -30 days');

$sql = "SELECT COUNT(id), playerid
		FROM logcresteri
		WHERE data>'".date('Y-m-d',$acum30)."' 
		GROUP BY playerid";
echo "CRESTERILE: $sql<br/>";
$res = mysql_query($sql);
while(list($numar, $playerid) = mysql_fetch_row($res)) {
	if($numar>=2) {
		$sql = "UPDATE player SET 
						Rating=CASE
								WHEN Rating<=49  THEN Rating+1 
								WHEN Rating = 50 THEN 50
						END
				WHERE id=$playerid";
		echo "$sql<br/>";
		mysql_query($sql);
	}
}	

mysql_free_result($res);
?>
