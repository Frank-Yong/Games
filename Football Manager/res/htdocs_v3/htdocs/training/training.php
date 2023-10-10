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

//ttypeuri de antrenament:
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

Vcharacteristic= Vcharacteristic +
  + ((40% x Talent + 20% x TeamWork + 20% x ValoareAntrenor) * CoeficientAntrenament 
  * CoeficientCondition+ValoareRandom)*percentAntrenarecharacteristic
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


global $firstname, $lastname, $pid;

$sql = "SELECT playerID, userID 
		FROM userplayer
		WHERE userID<>0 and playerid<=1500";
$res = mysql_query($sql);
while (list($playerID, $userID) = mysql_fetch_row($res)) {
$training = array();
$sql2 = "SELECT p.id, p.fname, p.lname, pr.percent, pr.characteristic,
				tp.trainerID, tp.ttype, tp.Post,
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
$res2 = mysql_query($sql2);
while(list($p_id, $p_fname, $p_lname, $percent,$characteristic,$trainerID,$ttype,$post,$Goalkeeping,$Defence,$Midfield,$Attack,$talent,$teamwork,$pos) = mysql_fetch_row($res2)) {
		echo "$p_id $p_fname $p_lname ==== $characteristic<br/>";
		$firstname = $p_fname;
		$lastname = $p_lname;
		if ($trainerID == 0) $Training1=3;
		$percent = $percent/100;
		switch($post) {
			case 1: 
				$_T = $Goalkeeping*$percent>$Training1*$percent?$Goalkeeping*$percent:$Training1*$percent;			
				break;
			case 2: 
				$_T = $Defence*$percent>$Training1*$percent?$Defence*$percent:$Training1*$percent;
				break;
			case 3: 
				$_T = $Midfield*$percent>$Training1*$percent?$Midfield*$percent:$Training1*$percent;
				break;
			case 4: 
				$_T = $Attack*$percent>$Training1*$percent?$Attack*$percent:$Training1*$percent;
				break;
			default:
				$_T = $Training1;
		}

		$factorTraining = 0.5;
		switch($ttype) {
			case 0: 
				$factorTraining = TRAINING_EASY;
				break;
			case 1: 
				$factorTraining = TRAINING_NORMAL;
				break;
			case 2: 
				$factorTraining = TRAINING_HARD;
				break;
			case 3: 
				$factorTraining = TRAINING_VERYHARD;
				break;
			
		}
		$condition = 1;
		
//		$biggestvalue_antrenament
//		aici trebuie sa existe pentru fiecare characteristic in parte
		$factorRandom = rand(20,40)/100;
		$training[$characteristic] = (($talent*.4 +$teamwork*.2 + $_T*.2)*$factorTraining*$condition + $factorRandom)*$percent;
		//echo "$characteristic :: ".$training[$characteristic].'<br/>';
		echo "Player: $playerID <==> $characteristic: " . $training[$characteristic] . "<br/>";

	}
	mysql_free_result($res2);
	$to_write = array();
	reset($training);
	while(list($key,$value) = each($training)) {
		$to_write[] =  "$key=$key+$value";		
	}
	$sql = "UPDATE grow SET " . implode($to_write, ", ") . " WHERE playerID=$playerID";
	echo $sql.'<br/>';
	mysql_query($sql);

	//se incrementeaza zilele de la leap la fiecare antrenament
	$sql = "UPDATE leap SET fromLastLeap=fromLastLeap+1 WHERE playerID=$playerID";
	mysql_query($sql);

	//parcurgere tabel leap pentru a vedea dc s-a ajuns in ziua de leap
	$sql = "SELECT fromLastLeap, cstleap FROM leap WHERE playerId=$playerID";
	$res2 = mysql_query($sql);
	list($fromLastLeap, $cstleap) = mysql_fetch_row($res2);
	mysql_free_result($res2);
	if ($fromLastLeap >= constant($cstleap)) {
		$cstRandom = rand(0,9);
		if ($cstRandom>0) {
			echo "------------------------------------------------>Leap Day!!!!!<br/>";
			//take the values from grow table
			$sql = "SELECT reflexes, OneOnOne, Handling, Communication, Tackling, Passing, LongShot, Shooting, Heading, Creativity, Crossing, Marking, TeamWork, 	FirstTouch, Strength, Speed, Aggresivity, Injury, Dribbling, Positioning, Rating 
					FROM grow WHERE playerId = $playerID";
			$res2 = mysql_query($sql);
			$row = mysql_fetch_assoc($res2);
			mysql_free_result($res2);
			//order it to have the biggest value first
			arsort($row);
			foreach($row as $characteristic => $biggestvalue){
				//take the first one and leave
				break;
			}
			if($biggestvalue > 100 {
				echo "---------------------------------------------------------------------->Here it is: $characteristic :: $biggestvalue<br/>";
				$randomGrow = rand(0,100);
				echo "First random: $randomGrow (if it is bigger than 30, go inside)<br/>";
				if($randomGrow>30) {
					//make it for that characteristic
					//so, 70/% chances to grow
					$biggestvalue = $biggestvalue - 100;
					
					
					//take the value of the skill, not how much he accumulated after training
					//if the skill is 50, no growth
					$sql = "SELECT $characteristic
							FROM player
							WHERE playerId=$playerID";
					$car = mysql_query($sql);
					list($v_car) = mysql_fetch_row($car);
			
					if($v_car<50) {
						//reset the growth for characteristic
						$sql = "UPDATE grow SET $characteristic = CASE
																		WHEN $characteristic<=49 THEN $biggestvalue 
																		WHEN $characteristic=50 THEN 0
								WHERE playerId=$playerID";
						echo "$sql<br/>";
						mysql_query($sql);
						//change the value in player
						$sql = "UPDATE player SET $characteristic=CASE
																	WHEN $characteristic<=49  THEN $characteristic+1 
																	WHEN $characteristic = 50 THEN 50
																  END
								WHERE id=$playerID";
						echo "$sql<br/>";
						mysql_query($sql);
						switch($characteristic) {
							case 'Communication': $fordisplay='Comunicatie'; break;
							case 'reflexes': $fordisplay='Reflexe'; break;
							case 'OneOnOne': $fordisplay='Unu la unu'; break;
							case 'Handling': $fordisplay='Manevrare'; break;
							case 'Tackling': $fordisplay='Deposedare'; break;
							case 'Marking': $fordisplay='Marcaj'; break;
							case 'Heading': $fordisplay = 'Jocul cu capul'; break;
							case 'Shooting': $fordisplay='Sut'; break;
							case 'LongShot': $fordisplay='Suturi de la distanta'; break;
							case 'Positioning': $fordisplay='Pozitionare'; break;
							case 'FirstTouch': $fordisplay='Atingere'; break;
							case 'Crossing': $fordisplay='Lansari'; break;
							case 'TeamWork': $fordisplay='Joc de echipa'; break;
							case 'Speed': $fordisplay='Viteza'; break;
							case 'Dribbling': $fordisplay='Dribling'; break;
							case 'Passing': $fordisplay='Pase'; break;
							case 'Creativity': $fordisplay='Creativitate'; break;
							case 'Conditioning': $fordisplay='Conditie fizica'; break;
							case 'Aggresivity': $fordisplay='Agresivitate'; break;
							case 'Experience': $fordisplay='Experienta'; break;
							case 'Strength': $fordisplay='Rezistenta'; break;

						}
						
						$sql = "INSERT INTO loggrows (playerid, data, characteristic)
								VALUES($playerID, '".Date('Y-m-d')."', '$characteristic')";
						mysql_query($sql);
						
						//se reseteaza cimpul de leap
						$sql = "UPDATE leap SET fromLastLeap=0 WHERE playerID=$playerID";
						mysql_query($sql);

				}
				mysql_free_result($car);


					
				} else {
					$biggestvalue = $biggestvalue/4;
					//if the leap was not made, because of the random factor, decrease the value 
					$sql = "UPDATE grow SET $characteristic = $biggestvalue WHERE playerId=$playerID";
					mysql_query($sql);
				}
			}
			//more actions
		} else {
			//leap on the very first day?
		}

	}

}
mysql_free_result($res);


?>