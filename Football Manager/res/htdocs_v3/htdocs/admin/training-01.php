<?php
//acest script se ruleaza o data pe zi
//aici se calculeaza cresterile jucatorilor la antrenament.
//trebuie ca acest fisier sa fie pus in cron si sa nu fie accesibil

//1.
//se preiau toti jucatorii valizi din baza de date (neaccidentati) accidentat=0
//jucatorii care fac parte dintr-o echipa, se antreneaza cu antrenorul echipei, dc exista

//2.
//daca jucatorii nu fac parte dintr-o echipa, se antreneaza cu un antrenor cu caracteristici initiale sau
//nu se antreneaza deloc, pentru a evita scaderea in Condition =>>>>>>
//se iau echipele pe rind (din tabelul user)
//si jucatorii din echipe 
//

//tipuri de antrenament:
//1 - antrenament de portar
//2 - antrenament de fundas
//3 - antrenament de mijlocas
//4 - antrenament de atacant
//5 - antrenament de jucator tinar
//6 - antrenament tactic
//7 - antrenament de motivatie


/*
----------------------------------------
Formula antrenament

Vcaracteristica= Vcaracteristica +
  + ((40% x Talent + 20% x TeamWork + 20% x ValoareAntrenor) * CoeficientAntrenament 
  * CoeficientCondition+ValoareRandom)*ProcentAntrenareCaracteristica
----------------------------------------
*/
include('../app.conf.php');

include('../user.php');
include('../Player.php');
include('../trainer.php');

$mes = 'Am inceput job training la ora '.Date("Y-m-d H:i:s");
$sql = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
		VALUES(0, 23, 'Am rulat job training nou3', '$mes' , '".Date("Y-m-d H:i:s")."', 0, 0)";
//mysql_query($sql);
echo "$sql<br/>";


global $prenume, $nume, $pid;

$sql = "SELECT playerID, userID 
		FROM userplayer
		WHERE userID<>0";
$res = mysql_query($sql);
while (list($playerID, $userID) = mysql_fetch_row($res)) {
$antrenament = array();
$sql2 = "SELECT p.Age, p.id, p.fname, p.lname, pr.procent, pr.caracteristica,
				tp.trainerID, tp.tip, tp.Post,
				t.GoalKeeping, t.Defence, t.Midfield, t.Attack,
				p.talent, p.teamwork, 
				(CASE  p.position
					WHEN 1 THEN 1
					WHEN 2 THEN 2
					WHEN 3 THEN 2
					WHEN 4 THEN 2
					WHEN 5 THEN 3
					WHEN 6 THEN 3
					WHEN 7 THEN 3
					WHEN 8 THEN 4
					WHEN 9 THEN 4
					WHEN 10 THEN 4
				END) AS pos
		FROM player p
		LEFT OUTER JOIN procente pr
		ON p.id=pr.playerID
		LEFT OUTER JOIN trainerplayer tp
		ON p.id=tp.playerId
		LEFT OUTER JOIN trainer t
		ON t.id=tp.TrainerID
		WHERE p.id=$playerID AND pr.procent>0 AND p.accidentat=0";
echo "$sql2<br/>";
$res2 = mysql_query($sql2);
while(list($p_age, $p_id, $p_fname, $p_lname, $procent,$caracteristica,$trainerID,$tip,$post,$Goalkeeping,$Defence,$Midfield,$Attack,$talent,$teamwork,$pos) = mysql_fetch_row($res2)) {
		echo "$p_id $p_fname $p_lname ==== $caracteristica<br/>";
		$prenume = $p_fname;
		$nume = $p_lname;
		if ($trainerID == 0) $Antrenament1=3;
		$procent = 0.47;
		switch($post) {
			case 1: 
				$Antren = $Goalkeeping*$procent>$Antrenament1?$Goalkeeping*$procent:$Antrenament1;			
				break;
			case 2: 
				$Antren = $Defence*$procent>$Antrenament1?$Defence*$procent:$Antrenament1;
				break;
			case 3: 
				$Antren = $Midfield*$procent>$Antrenament1?$Midfield*$procent:$Antrenament1;
				break;
			case 4: 
				$Antren = $Attack*$procent>$Antrenament1?$Attack*$procent:$Antrenament1;
				break;
			default:
				$Antren = $Antrenament1;
		}

		$factorAntrenament = 0.5;
		switch($tip) {
			case 0: 
				$factorAntrenament = TRAINING_EASY;
				$forma = 3;
				break;
			case 1: 
				$factorAntrenament = TRAINING_NORMAL;
				$forma = 5;
				break;
			case 2: 
				$factorAntrenament = TRAINING_HARD;
				$forma = 8;
				break;
			case 3: 
				$factorAntrenament = TRAINING_VERYHARD;
				$forma = 11;
				break;
			
		}
		$condition = 1;
		
//		$valoare_antrenament
//		aici trebuie sa existe pentru fiecare caracteristica in parte
		$factorRandom = rand(20,40)/100;
		
		//trebuie sa verific virsta
		//in functie de virsta are cresteri mai mari sau mai mici - schimb formula de antrenament
		//daca este peste 31 de ani, trebuie sa scada, dupa o formula invers proportionala cu teamwork si talent
		if($p_age<19)
			$antrenament[$caracteristica] = (($talent*.275 +$teamwork*.16 + $Antren*.16)*$factorAntrenament*$condition + $factorRandom)*$procent;
		if($p_age>=19 && $p_age<30)
			$antrenament[$caracteristica] = (($talent*.275 +$teamwork*.21 + $Antren*.21)*$factorAntrenament*$condition + $factorRandom)*$procent;
		if($p_age>=30 && $p_age<32)
			$antrenament[$caracteristica] = (($talent*.225 +$teamwork*.15 + $Antren*.14)*$factorAntrenament*$condition + $factorRandom)*$procent;
		if($p_age>=32)
			$antrenament[$caracteristica] = -(($talent*.15 +$teamwork*.15 + $Antren*.15)*5*$condition + $factorRandom)*$procent;
		//echo "$caracteristica :: ".$antrenament[$caracteristica].'<br/>';
		echo "Player: $playerID <==> $caracteristica: " . $antrenament[$caracteristica] . "<br/>";

	}
	mysql_free_result($res2);
	$de_scris = array();
	reset($antrenament);
	while(list($key,$value) = each($antrenament)) {
		$de_scris[] =  "$key=$key+$value";		
	}
	$sql = "UPDATE cresteri SET " . implode($de_scris, ", ") . " WHERE playerID=$playerID";
	echo $sql.'<br/>';
	mysql_query($sql);

	//scad si forma dupa antrenament
	$fsql = "UPDATE player SET 
								form=CASE
										WHEN form-$forma<=0  THEN 0 
										WHEN form-$forma>0 THEN form-$forma
									  END
			 WHERE id=$playerID";
	mysql_query($fsql);
}
?>