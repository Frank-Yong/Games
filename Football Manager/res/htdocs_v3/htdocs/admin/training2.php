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

$mes = 'Am rulat job training la ora '.Date("Y-m-d H:i:s");
$sql = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
		VALUES(0, 23, 'Am rulat job training nou3', '$mes' , '".Date("Y-m-d H:i:s")."', 0, 0)";
//mysql_query($sql);
//echo "$sql<br/>";


global $prenume, $nume, $pid;

$sql = "SELECT playerID, userID 
		FROM userplayer
		WHERE userID<>0 and playerid between 1501 and 3000";
$res = mysql_query($sql);
while (list($playerID, $userID) = mysql_fetch_row($res)) {
$antrenament = array();
$sql2 = "SELECT p.id, p.fname, p.lname, pr.procent, pr.caracteristica,
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
		WHERE p.id=$playerID";
echo "$sql2<br/>";
$res2 = mysql_query($sql2);
while(list($p_id, $p_fname, $p_lname, $procent,$caracteristica,$trainerID,$tip,$post,$Goalkeeping,$Defence,$Midfield,$Attack,$talent,$teamwork,$pos) = mysql_fetch_row($res2)) {
		echo "$p_id $p_fname $p_lname<br/>";
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
		$antrenament[$caracteristica] = (($talent*.4 +$teamwork*.2 + $Antren*.2)*$factorAntrenament*$condition + $factorRandom)*$procent;
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

	//se incrementeaza zilele de la salt la fiecare antrenament
	$sql = "UPDATE salt SET delasalt=delasalt+1 WHERE playerID=$playerID";
	mysql_query($sql);

	//parcurgere tabel salt pentru a vedea dc s-a ajuns in ziua de salt
	$sql = "SELECT delasalt, cstsalt FROM salt WHERE playerId=$playerID";
	$res2 = mysql_query($sql);
	list($deLaSalt, $cstSalt) = mysql_fetch_row($res2);
	mysql_free_result($res2);
	if ($deLaSalt >= constant($cstSalt)) {
		$cstRandom = rand(0,9);
		if ($cstRandom>0) {
			echo "------------------------------------------------>Este zi de salt!!!!!<br/>";
			//se preiau valorile din tabela cresteri
			$sql = "SELECT reflexes, OneOnOne, Handling, Communication, Tackling, Passing, LongShot, Shooting, Heading, Creativity, Crossing, Marking, TeamWork, FirstTouch, 		Strength, Speed, Aggresivity, Injury, Dribbling, Positioning, Rating 
					FROM cresteri WHERE playerId = $playerID";
			$res2 = mysql_query($sql);
			$row = mysql_fetch_assoc($res2);
			mysql_free_result($res2);
			//se ordoneaza astfel incit prima valoare sa fie si cea mai mare
			arsort($row);
			foreach($row as $caracteristica => $valoare){
				//se iese din bucla, pentru ca doar prima caracteristica (cea la care a crescut cel mai tare)
				//intereseaza
				break;
			}
			if($valoare > 1.4) {
				echo "---------------------------------------------------------------------->Aici este: $caracteristica :: $valoare<br/>";
				$crestereRandom = rand(0,100);
				echo "CrestereRandom este: $crestereRandom (daca este peste 30 intra in crestere)<br/>";
				if($crestereRandom>30) {
					//se face cresterea pentru acea caracteristica
					//am facut, prin randomul de mai sus, sa fie 70% sanse de a face cresterea
					$valoare = $valoare - 1.4;
					
					
					//preiau valoarea caracteristicii
					//daca este 50, nu se mai face nici o crestere
					$sql = "SELECT $caracteristica
							FROM player
							WHERE playerId=$playerID";
					$car = mysql_query($sql);
					list($v_car) = mysql_fetch_row($car);
			
					if($v_car<50) {
						//resetez cresterea pt caracteristica
						$sql = "UPDATE cresteri SET $caracteristica = CASE
																		WHEN $caracteristica<=49 THEN $valoare 
																		WHEN $caracteristica=50 THEN 0
								WHERE playerId=$playerID";
						echo "$sql<br/>";
						mysql_query($sql);
						//modific valoarea
						$sql = "UPDATE player SET $caracteristica=CASE
																	WHEN $caracteristica<=49  THEN $caracteristica+1 
																	WHEN $caracteristica = 50 THEN 50
																  END
								WHERE id=$playerID";
						echo "$sql<br/>";
						mysql_query($sql);
						switch($caracteristica) {
							case 'Communication': $deafisat='Comunicatie'; break;
							case 'reflexes': $deafisat='Reflexe'; break;
							case 'OneOnOne': $deafisat='Unu la unu'; break;
							case 'Handling': $deafisat='Manevrare'; break;
							case 'Tackling': $deafisat='Deposedare'; break;
							case 'Marking': $deafisat='Marcaj'; break;
							case 'Heading': $deafisat = 'Jocul cu capul'; break;
							case 'Shooting': $deafisat='Sut'; break;
							case 'LongShot': $deafisat='Suturi de la distanta'; break;
							case 'Positioning': $deafisat='Pozitionare'; break;
							case 'FirstTouch': $deafisat='Atingere'; break;
							case 'Crossing': $deafisat='Lansari'; break;
							case 'TeamWork': $deafisat='Joc de echipa'; break;
							case 'Speed': $deafisat='Viteza'; break;
							case 'Dribbling': $deafisat='Dribling'; break;
							case 'Passing': $deafisat='Pase'; break;
							case 'Creativity': $deafisat='Creativitate'; break;
							case 'Conditioning': $deafisat='Conditie fizica'; break;
							case 'Aggresivity': $deafisat='Agresivitate'; break;
							case 'Experience': $deafisat='Experienta'; break;
							case 'Strength': $deafisat='Rezistenta'; break;

						}
						
						$sql = "INSERT INTO logcresteri (playerid, data, caracteristica)
								VALUES($playerID, '".Date('Y-m-d')."', '$caracteristica')";
						mysql_query($sql);
						
												//se reseteaza cimpul de salt
						$sql = "UPDATE salt SET delasalt=0 WHERE playerID=$playerID";
						mysql_query($sql);
				}
				mysql_free_result($car);


					
				} else {
					$valoare = $valoare/4;
					//daca nu face saltul ca nu se indeplineste conditia de random, ii scad jumate din valoare
					$sql = "UPDATE cresteri SET $caracteristica = $valoare WHERE playerId=$playerID";
					mysql_query($sql);
				}
			}
			//aici urmeaza setarile tabelelor
		} else {
			//????
			//se muta ziua de salt pe ziua urmatore
			//se decrementeaza valoarea din tabela salt
		}

	}

}
mysql_free_result($res);


?>