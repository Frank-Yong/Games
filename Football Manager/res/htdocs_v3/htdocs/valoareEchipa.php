<?php
//acest script se ruleaza o data pe zi
//aici se calculeaza cresterile jucatorilor la antrenament.
//trebuie ca acest fisier sa fie pus in cron si sa nu fie accesibil

//1.
//se preiau toti jucatorii valizi din baza de date (neaccidentati)
//jucatorii care fac parte dintr-o echipa, se antreneaza cu antrenorul echipei, dc exista

//2.
//daca jucatorii nu fac parte dintr-o echipa, se antreneaza cu un antrenor cu caracteristici initiale sau
//nu se antreneaza deloc, pentru a evita scaderea in Condition =>>>>>>
//se iau echipele pe rind (din tabelul User)
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
include('app.conf');

include('User.php');
include('Player.php');
include('trainer.php');


$sql = "SELECT playerID FROM UserPlayer";
$res = mysql_query($sql);
while (list($playerID) = mysql_fetch_row($res)) {
	$antrenament = array();
	$sql2 = "SELECT pr.procent, pr.caracteristica,
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
			LEFT OUTER JOIN Procente pr
			ON p.id=pr.playerID
			LEFT OUTER JOIN TrainerPlayer tp
			ON p.id=tp.playerId
			LEFT OUTER JOIN Trainer t
			ON t.id=tp.TrainerID
			WHERE p.id = $playerID";
	$res2 = mysql_query($sql2);
	while(list($procent,$caracteristica,$trainerID,$tip,$post,$Goalkeeping,$Defence,$Midfield,$Attack,$talent,$teamwork,$pos) = mysql_fetch_row($res2)) {
		if ($trainerID == 0) $Antrenament=25;
		switch($post) {
			case 1: 
				$Antren = $Goalkeeping;
				break;
			case 2: 
				$Antren = $Defence;
				break;
			case 1: 
				$Antren = $Midfield;
				break;
			case 1: 
				$Antren = $Attack;
				break;
		}

		switch($tip) {
			case 0: 
				$factorAntrenament = TRAINING_EASY;
				break;
			case 1: 
				$factorAntrenament = TRAINING_NORMAL;
				break;
			case 2: 
				$factorAntrenament = TRAINING_HARD;
				break;
			case 3: 
				$factorAntrenament = TRAINING_VERYHARD;
				break;
		}

		$condition = 1;
	
//		$valoare_antrenament
//		aici trebuie sa existe pentru fiecare caracteristica in parte
		$factorRandom = rand(20,40)/100;
		$antrenament[$caracteristica] = (($talent*.4 +$teamwork*.2 + $Antren*.2)*$factorAntrenament*$condition + $factorRandom)*$procent/100;

		//echo "Player: $playerID <==> $caracteristica: " . $antrenament[$caracteristica] . "<br/>";

	}
	mysql_free_result($res2);
	$de_scris = array();
	reset($antrenament);
	while(list($key,$value) = each($antrenament)) {
		$de_scris[] =  "$key=$key+$value";		
	}
	$sql = "UPDATE Cresteri SET " . implode($de_scris, ", ") . " WHERE playerID=$playerID";
	echo $sql.'<br/>';
	mysql_query($sql);

	//se incrementeaza zilele de la salt la fiecare antrenament
	$sql = "UPDATE Salt SET delasalt=delasalt+1 WHERE playerID=$playerID";
	mysql_query($sql);

	//parcurgere tabel salt pentru a vedea dc s-a ajuns in ziua de salt
	$sql = "SELECT delasalt, cstsalt FROM salt WHERE playerId=$playerID";
	$res2 = mysql_query($sql);
	list($deLaSalt, $cstSalt) = mysql_fetch_row($res2);
	mysql_free_result($res2);
	if ($deLaSalt == constant($cstSalt)) {
		$cstRandom = rand(0,6);
		if ($cstRandom>0) {
			//echo "Trebuie sa faca salt!!!!!";
			//se preiau valorile din tabela cresteri
			$sql = "SELECT * FROM cresteri WHERE playerId = $playerID";
			$res2 = mysql_query($sql);
			$row = mysql_fetch_assoc($res2);
			mysql_free_result($res2);
			//se ordoneaza astfel incit prima valoare sa fie si cea mai mare
			arsort($row);
			foreach($row as $caracteristica => $valoare){
				echo "$caracteristica :: $valoare)";
				//se iese din bucla, pentru ca doar prima caracteristica (cea la care a crescut cel mai tare)
				//intereseaza
				break;
			}
			//aici urmeaza setarile tabelelor
			//ziua de la salt se face 0, sa o ia de la inceput
			$sql = "UPDATE salt SET delasalt=0 WHERE playerId=$playerID";
			mysql_query($sql);

			/*
			$sql = "SELECT $caracteristica FROM Player WHERE id=$playerID";
			echo "$sql";
			$res3 = mysql_query($sql);
			list($val) = mysql_fetch_row($res3);
			mysql_free_result($res3);
			echo "<br><br>..:: $val ::..<br><br>";
			*/
			//se incrementeaza valoarea caracteristicii in tabelul Player
			$sql = "UPDATE Player SET $caracteristica=$caracteristica+1 WHERE id=$playerID";
			mysql_query($sql);

			$sql = "UPDATE Cresteri 
					SET reflexes=0, OneOnOne=0, Handling=0, Communication=0, Tackling=0, Passing=0, LongShot=0, Shooting=0, Heading=0,
						Creativity=0, Crossing=0, Marking=0, FirstTouch=0, Strength=0, Speed=0, Aggresivity=0, Injury=0, Dribbling=0,
						Positioning=0, Rating=0
					WHERE playerID=$playerID";
			mysql_query($sql);

		} else {
			//????
			//se muta ziua de salt pe ziua urmatore
			//se decrementeaza valoarea din tabela salt
		}

	}

}
mysql_free_result($res);



?>