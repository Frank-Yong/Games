<?php
error_reporting(E_ALL);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

//trebuie sa ruleze in fiecare zi, sa verifice daca este zi de meci
//ora: 13:50, dupa terminarea etapei

//INTRODUC IN clasament REZULTATUL MECIULUI, URMIND CA APOI SA FAC GRUPAREA SI SUMAREA SA AFISEZ CLASAMENTUL CORECT

//verific in gameinvitation daca exista etapa in acea zi, prin interogarea cimpurilor competitieid si datameci

/*
//totodata, modific ratingul echipei!!!!
regula:
daca ech1 bate pe ech2 cu un gol, rating ech1 creste cu 1
daca ech1 bate pe ech2 la mai mult de un gol si sub 5 goluri, rating creste cu 2
daca ech1 bate pe ech2 la mai mult de 5 goluri, rating creste cu 3

daca ech1 pierde acasa cu ech2 la un gol, rating ech2 creste cu 2
daca ech1 pierde acasa cu ech2 intre 2 si 5 goluri, rating creste cu 3 pt ech2
daca ech1 pierde cu ech2 cu peste 5 goluri, rating creste cu 4 pentru ech2

//in caz de egal, ratingul nu se modifica
//ratingul doar se mareste, nu si scade.
*/
error_reporting(63);
$sql = "SELECT a.userId_1, a.userId_2, a.competitionid, a.rround, a.score, b.name, a.age 
		FROM gameinvitation a
		LEFT OUTER JOIN competition b
		ON a.competitionid=b.id
		WHERE a.gamedate='".Date("Y-m-d")."' AND a.competitionid<>0 AND (b.name <>'League Cup' AND b.name <> 'Cupa Romaniei')";
echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
while(list($id1, $id2, $cid, $et, $scor, $numecomp, $grupavirsta) = mysqli_fetch_row($res)) {
	list($sc1,$sc2) = explode(':', $scor);
	//partea cu rating-ul
	$rating1 = $rating2 = 0;
	if($sc1-$sc2>=5) $rating1 = 2;
	if($sc1-$sc2<=-5) $rating2 = 2;
	switch($sc1-$sc2) {
		case 1: $rating1 = 0; break;
 		case 2:
		case 3:
		case 4: $rating1 = 1; break;
		case -1: $rating2 = 0; break;
		case -2: 
		case -3:
		case -4: $rating2 = 1; break;
	}
	if($sc1>$sc2) {
		$v1 = 1;
		$e1 = 0;
		$i1 = 0;
		$v2 = 0;
		$e2 = 0;
		$i2 = 1;
		$p1 = 3;
		$p2 = 0;
	} elseif($sc1==$sc2) {
		$v1 = 0;
		$e1 = 1;
		$i1 = 0;
		$v2 = 0;
		$e2 = 1;
		$i2 = 0;
		$p1 = 1;
		$p2 = 1;

	} else {
		$v1 = 0;
		$e1 = 0;
		$i1 = 1;
		$v2 = 1;
		$e2 = 0;
		$i2 = 0;
		$p1 = 0;
		$p2 = 3;
	
	}
	$sql = "INSERT INTO clasament(competitieid, etapa, userid, victorii, egaluri, infringeri, gm, gp, puncte) 
			VALUES($cid, $et, $id1, $v1, $e1, $i1, $sc1, $sc2, $p1),
			($cid, $et, $id2, $v2, $e2, $i2, $sc2, $sc1, $p2)";
	echo "$sql<br/>";
	mysqli_query($GLOBALS['con'],$sql);
	//modificare rating echipe 
	if($grupavirsta==1) {
		if($rating1<>0) {
			$sql = "UPDATE user SET rating=rating+$rating1 WHERE id=$id1";
			mysqli_query($GLOBALS['con'],$sql);
		}
		if($rating2<>0) {
			$sql = "UPDATE user SET rating=rating+$rating2 WHERE id=$id2";
			mysqli_query($GLOBALS['con'],$sql);
		}
	}
}
mysqli_free_result($res);

$mes = 'Job has run for updating Ranking at '.Date("Y-m-d H:i:s");
//ID=23 - your id in the game, to see when the job run
$sql = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
		VALUES(0, 23, 'Job run for Ranking', '$mes' , '".Date("Y-m-d H:i:s")."', 0, 0)";
//uncomment the line if you want a message
//$resmes = mysqli_query($GLOBALS['con'],$sql);
//echo "$sql<br/>";
?>