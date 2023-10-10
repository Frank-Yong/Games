<?php
//acest script se ruleaza o data pe zi
//aici se calculeaza growle jucatorilor la antrenament.
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
//mysqli_query($GLOBALS['con'],$sql);
//echo "$sql<br/>";


global $prenume, $nume, $pid;

$sql = "SELECT playerID, userID 
		FROM userplayer
		WHERE userID<>0 and playerid<=1500";
$res = mysqli_query($GLOBALS['con'],$sql);
while (list($playerID, $userID) = mysqli_fetch_row($res)) {
$antrenament = array();
$sql2 = "SELECT p.id, p.fname, p.lname, pr.procent, pr.characteristic,
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
		LEFT OUTER JOIN percentage pr
		ON p.id=pr.playerID
		LEFT OUTER JOIN trainerplayer tp
		ON p.id=tp.playerId
		LEFT OUTER JOIN trainer t
		ON t.id=tp.TrainerID
		WHERE p.id=$playerID";
echo "$sql2<br/>";
$res2 = mysqli_query($GLOBALS['con'],$sql2);
while(list($p_id, $p_fname, $p_lname, $procent,$caracteristica,$trainerID,$tip,$post,$Goalkeeping,$Defence,$Midfield,$Attack,$talent,$teamwork,$pos) = mysqli_fetch_row($res2)) {
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
	mysqli_free_result($res2);
	$de_scris = array();
	reset($antrenament);
	while(list($key,$value) = each($antrenament)) {
		$de_scris[] =  "$key=$key+$value";		
	}
	$sql = "UPDATE grow SET " . implode($de_scris, ", ") . " WHERE playerID=$playerID";
	echo $sql.'<br/>';
	mysqli_query($GLOBALS['con'],$sql);

	//se incrementeaza zilele de la salt la fiecare antrenament
	$sql = "UPDATE leap SET fromlastleap=fromlastleap+1 WHERE playerID=$playerID";
	mysqli_query($GLOBALS['con'],$sql);

	//parcurgere tabel salt pentru a vedea dc s-a ajuns in ziua de salt
	$sql = "SELECT fromlastleap, cstsalt FROM leap WHERE playerId=$playerID";
	$res2 = mysqli_query($GLOBALS['con'],$sql);
	list($fromlastleap, $cstSalt) = mysqli_fetch_row($res2);
	mysqli_free_result($res2);
	if ($fromlastleap >= constant($cstSalt)) {
		$cstRandom = rand(0,9);
		if ($cstRandom>0) {
			echo "------------------------------------------------>Este zi de salt!!!!!<br/>";
			//se preiau valorile din tabela grow
			$sql = "SELECT reflexes, OneOnOne, Handling, Communication, Tackling, Passing, LongShot, Shooting, Heading, Creativity, Crossing, Marking, TeamWork, 	FirstTouch, Strength, Speed, Aggresivity, Injury, Dribbling, Positioning, Rating 
					FROM grow WHERE playerId = $playerID";
			echo "$sql<br/>";
			$res2 = mysqli_query($GLOBALS['con'],$sql);
			$row = mysqli_fetch_assoc($res2);
			mysqli_free_result($res2);
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
					$car = mysqli_query($GLOBALS['con'],$sql);
					list($v_car) = mysqli_fetch_row($car);
			
					if($v_car<50) {
						//resetez cresterea pt caracteristica
						$sql = "UPDATE grow SET $caracteristica = CASE
																		WHEN $caracteristica<=49 THEN $valoare 
																		WHEN $caracteristica=50 THEN 0
								WHERE playerId=$playerID";
						echo "$sql<br/>";
						mysqli_query($GLOBALS['con'],$sql);
						//modific valoarea
						$sql = "UPDATE player SET $caracteristica=CASE
																	WHEN $caracteristica<=49  THEN $caracteristica+1 
																	WHEN $caracteristica = 50 THEN 50
																  END
								WHERE id=$playerID";
						echo "$sql<br/>";
						mysqli_query($GLOBALS['con'],$sql);
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
						
						$sql = "INSERT INTO loggrows (playerid, data, characteristic)
								VALUES($playerID, '".Date('Y-m-d')."', '$caracteristica')";
						mysqli_query($GLOBALS['con'],$sql);
						
						//se reseteaza cimpul de salt
						$sql = "UPDATE leap SET fromlastleap=0 WHERE playerID=$playerID";
						mysqli_query($GLOBALS['con'],$sql);

				}
				mysqli_free_result($car);


					
				} else {
					$valoare = $valoare/4;
					//daca nu face saltul ca nu se indeplineste conditia de random, ii scad trei sferturi din valoare
					$sql = "UPDATE grow SET $caracteristica = $valoare WHERE playerId=$playerID";
					mysqli_query($GLOBALS['con'],$sql);
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
mysqli_free_result($res);


?>