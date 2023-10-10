<?php
//Moralul
//maximum moral is 100%
//if a player is not used for 3 official games, moral will drop 10%. For each more game, another 7%.
		//table withall the players in the squad, and after each game, a counter for this purpose
		//table: moral (id, playerid, counter)
//if he is replaced till minute 45, he will drop 7% for moral
//if he is in the lineup, increase with 10%
//--------if he enters during the game, increase with 5%
//--------more than 10 days without free day, decrease with 10%
			//for each more day, decrease with 5%
			//--------for each free day, increase with 7%
//--------if they go to cineva, restaurant, mall, increase with 7%
//ok-if the team lost with more than 5 goals, decrease with 2%
//ok-if the team wins, increase 2%
//moral should not drop below 0


//in moral is low, training is affected and also the game of the player

//this use, the players have to go in the lineup, otherwise their values will not be so great

include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');
error_reporting(63);

//it comes gameID
//$gameID = 13;

global $formula1;
global $formula2;
global $fname;
global $fname1;
global $fname2;
global $lname;
global $lname1;
global $lname2;

$sql = "SELECT a.id, a.userId_1, a.userId_2, a.age 
		FROM gameinvitation a 
		WHERE a.accepted=1 and a.gamedate='".Date("Y-m-d")."' ORDER BY a.id ASC";

echo "$sql<br/>";		
$resdoi = mysqli_query($GLOBALS['con'],$sql);
echo "Generate games...";
$i=0;


while(list($idmeci, $ec1, $ec2, $gr) = mysqli_fetch_row($resdoi)) {
	$gameID=$idmeci;
	
	//check to have at least 7 players in the team
	$nrjuc1 = getTeamComplete($ec1, $gr);
	echo "Team1 has $nrjuc1 players in the line up<br/>";
	$nrjuc2 = getTeamComplete($ec2, $gr);
	echo "Echipa 2 are $nrjuc2 jucatori in echipa de start<br/>";
	$skip=0;
	if($nrjuc1<7) {
		$scor='0:3';
		$skip=1;
	}
	if($nrjuc2<7) {
		$scor='3:0';
		$skip=1;
	}
	if($skip==1) {
		$sscor = "UPDATE gameinvitation
				SET score='".$scor."'
				WHERE id=$gameID";
		echo "$sscor<br/>";
		mysqli_query($GLOBALS['con'],$sscor);
	
		//textul pentru masa verde
		if($nrjuc2<7) $pierz = "oaspetii";
		if($nrjuc1<7) $pierz = "gazdele";
		
		$sql = "INSERT INTO gametext(gameID, text, attacking_team, mminute, goal, realminute)
			VALUES($gameID, 'Meciul a fost castigat la masa verde, pentru ca $pierz nu s-au prezentat la meci!', 0, '12:00', 0, 90)";
		mysqli_query($GLOBALS['con'],$sql);
	}
	if($skip==0) ComputeTheScore($gameID, $_SEASON);
	$i++;
}
echo "<br/>Generated $i games!";
mysqli_free_result($resdoi);


function Changes($team, $minute1, $minute2, $gameID, $which) {
	// minute1 and minute2, the arrays with scored goals.
	//table changes should be checked, to see if the changes are made and take the new players in consideration
	//i keep all the ids of the players that are entering the pitch, not to show then again if they are official
	
	$sch1 = "SELECT a.cminute, a.playerid1, a.playerid2, a.condition1, a.position, b.fname, b.lname, c.fname, c.lname
			FROM changes a
			left join player b
			on a.playerid1=b.id
			left join player c
			on a.playerid2=c.id
			WHERE a.userid=$team
			ORDER BY a.cminute ASC";
	echo "$sch1<br/>";
	$ressch1 = mysqli_query($GLOBALS['con'],$sch1);
	$replacers = array();
	$lineup = array();
	$ri=0;
	$ti=0;
	$mi=0;
	$sch_efect = 0;
	while(list($minut_s, $pid1, $pid2, $cond, $post, $fname1, $lname1, $fname2, $lname2) = mysqli_fetch_row($ressch1)){
		$homescore = 0; $guestscore = 0;
		for($s=0;$s<count($minute1);$s++) {
			if($minut_s>$minute1[$s]) $homescore++;
		}
		for($s=0;$s<count($minute2);$s++) {
			if($minut_s>$minute2[$s]) $guestscore++;
		}

		//inserez in gametext schimbarea doar daca cele doua pid-uri n-au mai fost parte a altei schimbari
		//retin cele doua pid-uri aflate in discutie
		$ex = 0;
		for($t=0;$t<count($replacers);$t++) {
			if($pid1 == $replacers[$t]) {
					$ex=1;
					break;
			}
		}
		for($t=0;$t<count($lineup);$t++) {
			if($pid2 == $replacers[$t]) {
					$ex=1;
					break;
			}
		}
		/*
			"1"=>"no matter what, the score is not important",
			"2"=>"leading with 1 goal",
			"3"=>"Conduc cu doua goluri+",
			"4"=>"Sunt condus cu un goal",
			"5" =>"Sunt condus cu doua goluri+"
		*/
		
		if($ex == 0) {
			//nu a mai fost parte dintr-o schimbare, nici rezerva, nici titularul
			switch($cond) {
				case 1: //no matter what, the score is not important
					//put inside the player ids, to avoid using them for another change
					$replacers[$ri++] = $pid1;
					$lineup[$ti++] = $pid2;
					
					//insert text in gametext
					//to come
					$minreal = $minut_s;
					$m = $minut_s%45;
					if($m<10) $m="0".$m;
					$min = intval($minut_s/45)==1? "13:".$m:"12:".$m; //to know in what part of the game is the game, first or second
					$minute[$mi++] = $min;
					$sql = "INSERT INTO gametext(gameID, text,attacking_team,mminute, goal, realminute)
							VALUES($gameID, 'Se aude fluierul arbitrului! Vom asista la o schimbare: $fname1 $lname1 ii va lua locul lui $fname2 $lname2! Este o schimbare interesanta, sa-l vedem la lucru pe $lname1!', $team, '$min',10, $minreal)";
					mysqli_query($GLOBALS['con'],$sql);
					echo "my change: $sql<br/>";
					$name1 = "$fname1 $lname1";
					$name2 = "$fname2 $lname2";
					$sql = "UPDATE gametext SET text=REPLACE(text,'".$name2."', '".$name1."') WHERE gameID=$gameID AND realminute>$minreal";
					mysqli_query($GLOBALS['con'],$sql);
					echo "CHANGE ::  $sql<br/>";
					$sql = "UPDATE gamedetail SET playerid=$pid1 WHERE gameID=$gameID AND playerid=$pid2 AND mminute>$minreal";
					mysqli_query($GLOBALS['con'],$sql);
					echo "Change ::  $sql<br/>";
					
					$sch_efect++;
					break;
				case 2: //leading with one goal
					//in parameter 'which' first or second team
					//here the goals scored by the home team and guest team, to do the changes set up for each team
					$a_change_is_made=0;
					if($which==1) {
						//so, for home team
						//compare $homescore cu $guestscore
						if($homescore==$guestscore+1) $a_change_is_made=1;
					} else {
						//inseamna ca e echipa oaspete
						if($homescore+1==$guestscore) $a_change_is_made=1;
					}
					if($a_change_is_made==1) {
						$replacers[$ri++] = $pid1;
						$lineup[$ti++] = $pid2;
						
						//insert text in gametext
						//to come
						$minreal = $minut_s;
						$m = $minut_s%45;
						if($m<10) $m="0".$m; // put a leading zero
						$min = intval($minut_s/45)==1? "13:".$m:"12:".$m;
						$minute[$mi++] = $min;
						$sql = "INSERT INTO gametext(gameID, text,attacking_team, mminute, goal, realminute)
								VALUES($gameID, 'Se aude fluierul arbitrului! Vom asista la o schimbare: $fname1 $lname1 ii va lua locul lui $fname2 $lname2! Este o schimbare interesanta, sa-l vedem la lucru pe $lname1!', $team, '$min',10, $minreal)";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCH: $sql<br/>";
						$name1 = "$fname1 $lname1";
						$name2 = "$fname2 $lname2";
						$sql = "UPDATE gametext SET text=REPLACE(text,'".$name2."', '".$name1."') WHERE gameID=$gameID AND realminute>$minreal";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCHIMBARE ::  $sql<br/>";
						$sql = "UPDATE gamedetail SET playerid=$pid1 WHERE gameID=$gameID AND playerid=$pid2 AND mminute>$minreal";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCHIMBARE ::  $sql<br/>";
						
						$sch_efect++;
					}
					break;
				case 3: //daca conduc la mai mult sau egal cu 2 goluri
					//in variabila 'care' se gaseste informatia daca echipa este prima sau a doua
					//trebuie sa iau in calcul cu golurile marcate de gazde si oaspeti
					$a_change_is_made=0;
					if($which==1) {
						//inseamna ca echipa este gazda
						//compar $homescore cu $guestscore
						if($homescore>=$guestscore+2) $a_change_is_made=1;
					} else {
						//inseamna ca e echipa oaspete
						if($homescore+2<=$guestscore) $a_change_is_made=1;
					}
					if($a_change_is_made==1) {
						$replacers[$ri++] = $pid1;
						$lineup[$ti++] = $pid2;
						
						//inserez textul in gametext
						//to come
						$minreal = $minut_s;
						$m = $minut_s%45;
						if($m<10) $m="0".$m;
						$min = intval($minut_s/45)==1? "13:".$m:"12:".$m;
						$minute[$mi++] = $min;
						$sql = "INSERT INTO gametext(gameID, text,attacking_team,mminute, goal, realminute)
								VALUES($gameID, 'Se aude fluierul arbitrului! Vom asista la o schimbare: $fname1 $lname1 ii va lua locul lui $fname2 $lname2! Este o schimbare interesanta, sa-l vedem la lucru pe $lname1!', $team, '$min',10, $minreal)";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCH: $sql<br/>";
						$name1 = "$fname1 $lname1";
						$name2 = "$fname2 $lname2";
						$sql = "UPDATE gametext SET text=REPLACE(text,'".$name2."', '".$name1."') WHERE gameID=$gameID AND realminute>$minreal";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCHIMBARE ::  $sql<br/>";
						$sql = "UPDATE gamedetail SET playerid=$pid1 WHERE gameID=$gameID AND playerid=$pid2 AND mminute>$minreal";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCHIMBARE ::  $sql<br/>";
						
						$sch_efect++;
					}
					break;
				case 4: //daca sunt condus cu un goal
					//in variabila 'care' se gaseste informatia daca echipa este prima sau a doua
					//trebuie sa iau in calcul cu golurile marcate de gazde si oaspeti
					//introduc pid-urile sa nu mai le bag in alta schimbare
					$a_change_is_made=0;
					if($which==1) {
						//inseamna ca echipa este gazda
						//compar $homescore cu $guestscore
						if($homescore+1==$guestscore) $a_change_is_made=1;
					} else {
						//inseamna ca e echipa oaspete
						if($homescore==$guestscore+1) $a_change_is_made=1;
					}
					if($a_change_is_made==1) {
						$replacers[$ri++] = $pid1;
						$lineup[$ti++] = $pid2;
						
						//inserez textul in gametext
						//to come
						$minreal = $minut_s;
						$m = $minut_s%45;
						if($m<10) $m="0".$m;
						$min = intval($minut_s/45)==1? "13:".$m:"12:".$m;
						$minute[$mi++] = $min;
						$sql = "INSERT INTO gametext(gameID, text,attacking_team,mminute, goal, realminute)
								VALUES($gameID, 'Se aude fluierul arbitrului! Vom asista la o schimbare: $fname1 $lname1 ii va lua locul lui $fname2 $lname2! Este o schimbare interesanta, sa-l vedem la lucru pe $lname1!', $team, '$min',10, $minreal)";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCH: $sql<br/>";
						$name1 = "$fname1 $lname1";
						$name2 = "$fname2 $lname2";
						$sql = "UPDATE gametext SET text=REPLACE(text,'".$name2."', '".$name1."') WHERE gameID=$gameID AND realminute>$minreal";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCHIMBARE ::  $sql<br/>";
						$sql = "UPDATE gamedetail SET playerid=$pid1 WHERE gameID=$gameID AND playerid=$pid2 AND mminute>$minreal";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCHIMBARE ::  $sql<br/>";
						
						$sch_efect++;
					}
					break;
				case 5: //daca sunt condus la mai mult sau egal cu 2 goluri
					//in variabila 'care' se gaseste informatia daca echipa este prima sau a doua
					//trebuie sa iau in calcul cu golurile marcate de gazde si oaspeti
					//introduc pid-urile sa nu mai le bag in alta schimbare
					$a_change_is_made=0;
					if($which==1) {
						//inseamna ca echipa este gazda
						//compar $homescore cu $guestscore
						if($homescore+2<=$guestscore) $a_change_is_made=1;
					} else {
						//inseamna ca e echipa oaspete
						if($homescore>=$guestscore+2) $a_change_is_made=1;
					}
					if($a_change_is_made==1) {
						$replacers[$ri++] = $pid1;
						$lineup[$ti++] = $pid2;
						
						//inserez textul in gametext
						//to come
						$minreal = $minut_s;
						$m = $minut_s%45;
						if($m<10) $m="0".$m;
						$min = intval($minut_s/45)==1? "13:".$m:"12:".$m;
						$minute[$mi++] = $min;
						$sql = "INSERT INTO gametext(gameID, text,attacking_team,mminute, goal, realminute)
								VALUES($gameID, 'Se aude fluierul arbitrului! Vom asista la o schimbare: $fname1 $lname1 ii va lua locul lui $fname2 $lname2! Este o schimbare interesanta, sa-l vedem la lucru pe $lname1!', $team, '$min',10, $minreal)";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCH: $sql<br/>";
						$name1 = "$fname1 $lname1";
						$name2 = "$fname2 $lname2";
						$sql = "UPDATE gametext SET text=REPLACE(text,'".$name2."', '".$name1."') WHERE gameID=$gameID AND realminute>$minreal";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCHIMBARE ::  $sql<br/>";
						$sql = "UPDATE gamedetail SET playerid=$pid1 WHERE gameID=$gameID AND playerid=$pid2 AND mminute>$minreal";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCHIMBARE ::  $sql<br/>";
						
						$sch_efect++;
					}
					break;
			}
		}
		if($sch_efect >=3) break;
	}
	mysqli_free_result($ressch1);
	if($which == 1) {
		$lineup1 = implode(":", $lineup);
		$replacers1 = implode(":", $replacers);
		$minute1 = implode(":", $minute);
	} else {
		$lineup2 = implode(":", $lineup);
		$replacers2 = implode(":", $replacers);
		$minute2 = implode(":", $minute);
	}
}

function ChooseScorer($team,$which) {
	//1-portar, 2-dr, 3-dc, 4-dl
	//5-mr, 6-mc, 7-ml, 8-fr, 9-fc, 10-fl
	//there is a higher probability for the attackers and midfielders to score, but it is not impossible for the defenders
	//the probability will be higher according with the value of the players.
	if($which == 1) {
		$pid = $juc;
		$firstname = $fname;
		$name = $lname;
		$posturi = $pst;
	}
	if($which == 2) {
		$pid = $juc2;
		$firstname = $fname2;
		$name = $lname2;
		$posturi = $pst2;
	}
	
	
}


function Calculation($at, $min, $team1, $team2, $gameID, $minreal) {
	global $juc;
	global $juc2;
	global $lname;
	global $lname2;
	global $fname;
	global $fname2;
	global $pst;
	global $pst2;
	global $formula1;
	global $formula2;


	//echo "FORMULA $formula1 :: $formula2<br/>";

	if ($at == 1) {
		//first team attacking
		$idpl1 = (int)mt_rand(0,$formula1-1);
		$idpl2 = (int)mt_rand(0,$formula1-1);
		$idpl3 = (int)mt_rand(0,$formula2-1);
		$idpl4 = (int)mt_rand(0,$formula2-1);
	} else {
		//ataca a doua echipa
		$idpl1 = (int)mt_rand(0,$formula2-1);
		$idpl2 = (int)mt_rand(0,$formula2-1);
		$idpl3 = (int)mt_rand(0,$formula1-1);
		$idpl4 = (int)mt_rand(0,$formula1-1);
	}
	//gtype=0 sau 5 - normal game action or offside
	$sql = "SELECT id, text, gtype FROM tplmessages WHERE gtype=0 or gtype=5";
	$res = mysqli_query($GLOBALS['con'],$sql);

	$texts = array();
	$gtypes = array();
	$i=0;
	while(list($id, $text, $gtype) = mysqli_fetch_row($res)) {
		$texts[$i] = $text;
		$gtypes[$i++] = $gtype;
	}
	mysqli_free_result($res);

	$playid = (int)mt_rand(0,$i-1);
	$txt = $texts[$playid];
	$mytype = $gtypes[$playid];

	if ($at == 1) {
		$pl1 = $fname[$idpl1] . " ". $lname[$idpl1];
		$pl2 = $fname[$idpl2] . " ". $lname[$idpl2];
		$pl3 = $fname2[$idpl3] . " ". $lname2[$idpl3];
		$pl4 = $fname2[$idpl4] . " ". $lname2[$idpl4];
	} else {
		$pl1 = $fname2[$idpl1] . " ". $lname2[$idpl1];
		$pl2 = $fname2[$idpl2] . " ". $lname2[$idpl2];
		$pl3 = $fname[$idpl3] . " ". $lname[$idpl3];
		$pl4 = $fname[$idpl4] . " ". $lname[$idpl4];
	}

	if($at == 1) {
		$ata = $team1;
	} else {
		$ata = $team2;
	}
	echo "PLAYERS in CLACULATION:: :: $pl1  -- $pl2  --- $pl3   ---- $pl4<br/>";
	
	$txt = str_replace("^1E1^", $pl1, $txt);
	$txt = str_replace("^1E2^", $pl2, $txt);
	$txt = str_replace("^2E1^", $pl3, $txt);
	$txt = str_replace("^2E2^", $pl4, $txt);
	
	
	//avem textul, il introducem in bd, in gametext : gameID, text, attacking_team, mminute
	$sql = "INSERT INTO gametext(gameID, text, attacking_team, mminute, goal, realminute)
			VALUES($gameID, '$txt', $ata, '".$min."', $mytype, $minreal)";
	echo "$sql<br/>";
	mysqli_query($GLOBALS['con'],$sql);


} //end function calculation

/****************************************************************************************************
*****************************************************************************************************
****************************************************************************************************/

function ComputeTheScore($gameID, $_SEASON) {
	global $juc;
	global $juc2;
	global $lname;
	global $lname2;
	global $fname;
	global $fname2;
	global $pst;
	global $pst2;
	global $formula1;
	global $formula2;
	
$sql = "SELECT a.userId_1, a.userId_2, b.TeamName, c.TeamName, a.score, d.number, e.number, f.name, a.competitionid, b.Moral, c.Moral, a.age 
		FROM gameinvitation a 
		LEFT OUTER JOIN user b
		ON b.id=a.userId_1
		LEFT OUTER JOIN user c
		ON c.id=a.userId_2
		LEFT OUTER JOIN tribune d
		ON a.userId_1=d.userid
		LEFT OUTER JOIN tribune e
		ON a.userId_2=e.userid
		LEFT JOIN competition f
		ON f.id=a.competitionid
		WHERE a.id=$gameID";
echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
list($team1, $team2, $nameteam1, $nameteam2, $scor, $socios1, $socios2, $compnume, $competitionid, $moral1, $moral2, $grupa) = mysqli_fetch_row($res);
mysqli_free_result($res);

//find system of play: 4-4-2, 4-5-1 s.a.m.d
//get from the lineup how many defenders, midfielders and attackers are in the team
$dcount1 = 0;
$mcount1 = 0;
$acount1 = 0;

$dcount2 = 0;
$mcount2 = 0;
$acount2 = 0;


	//team1
	$sql = "SELECT a.playerId, a.post, b.fname, b.lname, b.strength, b.form 
			FROM lineup a 
			LEFT OUTER JOIN player b
			ON a.playerId=b.id
			WHERE a.userId=$team1 AND a.post<>0 and a.post<>1 and a.pgroup=$grupa"; //for youth and seniors
		
	echo "$sql<br/>";
	$res = mysqli_query($GLOBALS['con'],$sql);
	$juc = array();
	$fname = array();
	$lname = array();
	$st = array();
	$fo = array();
	$j=0;
	while(list($pid, $post, $firstname, $name, $strength, $forma) = mysqli_fetch_row($res)) {
		$fname[$j] = $firstname;
		$lname[$j] = $name;
		$juc[$j] = $pid;
		$st[$j] = $strength;
		$fo[$j] = $forma;
		$pst[$j++] = $post;
		
		switch($post) {
			case 2:
			case 3:
			case 4:
				$dcount1++;
			case 5:
			case 6:
			case 7:
				$mcount1++;
			case 8:
			case 9:
			case 10:			
				$acount1++;
		}
	}
	mysqli_free_result($res);
	$formula1 = $j;

echo "Formula 1: $formula1<br/>";

//team 2
$sql = "SELECT a.playerId, a.post, b.fname, b.lname, b.strength, b.form 
		FROM lineup a 
		LEFT OUTER JOIN player b
		ON a.playerId=b.id
		WHERE a.userId=$team2 AND a.post<>0 and a.post<>1 AND a.pgroup=$grupa";
echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
$juc2 = array();
$fname2 = array();
$lname2 = array();
$st2 = array();
$fo2 = array();
$j=0;
while(list($pid, $post, $firstname, $name, $strength, $forma) = mysqli_fetch_row($res)) {
	$fname2[$j] = $firstname;
	$lname2[$j] = $name;
	$pst2[$j] = $post;
	$st2[$j] = $strength;
	$fo2[$j] = $forma;
	$juc2[$j++] = $pid;
		switch($post) {
			case 2:
			case 3:
			case 4:
				$dcount2++;
			case 5:
			case 6:
			case 7:
				$mcount2++;
			case 8:
			case 9:
			case 10:			
				$acount2++;
		}
}
mysqli_free_result($res);
$formula2 = $j;

//end finding the tactics for each team - values in $formula1 and $formula2

//coefficient home team: 5%
//luck coefficient - mt_random for both teams. who has bigger value, gets 5%, the other, zero.
//team1 is host team
$luck1 = mt_rand(0,100);
$luck2 = mt_rand(0,100);

if ($luck1>$luck2) {
	$_luck1 = 1.05;
	$_luck2 = 1;
} else {
	$_luck1 = 1;
	$_luck2 = 1.05;
}

//get the tactics
if($grupa==1) $grtac=0;
if($grupa==2) $grtac=1;
$tac = array();
$sqltac1 = "SELECT userid, tactics, midfield, atacks, passes 
			FROM tactics 
			WHERE (userid=$team1 or userid=$team2) AND ggroup=$grtac";

echo "TACTIC: $sqltac1<br/>";
$restac1 = mysqli_query($GLOBALS['con'],$sqltac1);
while(list($teamid, $tactics, $midf, $attacks, $passes)=mysqli_fetch_row($restac1)) {
	if($teamid == $team1) {
		$tac1 = $tactics; //1,2,3 - normal, attacking, defensive
		$midf1 = $midf; //1,2,3 - normal, attacking, defensive
		$attacks1 = $attacks; //1,2,3 - both ways, to the sides and central
		$pas1 = $passes; //1,2,3 - combinate, inalte, jos
	} else {
		$tac2 = $tactics;
		$midf2 = $midf;
		$attacks2 = $attacks;
		$pas2 = $passes;
	}
}
mysqli_free_result($restac1);

//involve trainer -> speech and knowhow
//if the team doesn;t have one, i set it to 1
$sql = "SELECT a.userid, b.Speech, b.knowhow
		FROM usertrainer a
		LEFT JOIN trainer b
		ON b.id=a.trainerid
		WHERE a.userid=$team1 OR a.userid=$team2";
echo "TRAINER : $sql<br/>";
$restrai = mysqli_query($GLOBALS['con'],$sql);
while(list($ust, $speech, $knowhow) = mysqli_fetch_row($restrai)) {
	if($ust == $team1) {
		$speech1 = $speech;
		$knowhow1 = $knowhow;
		echo "here, trainer team 1<br/>";
	} else {
		$speech2 = $speech;
		$knowhow2 = $knowhow;
		echo "Here, trainer team 2: $speech2 $knowhow2<br/>";
	}
}
mysqli_free_result($restrai);

if(empty($speech1)) $speech1=1;
if(empty($speech2)) $speech2=1;
if(empty($knowhow1)) $knowhow1=1;
if(empty($knowhow2)) $knowhow2=1;

//team 1
echo "$nameteam1...<br/>";
	$user1 = new User();
	$user1->LoginID($team1);

	$stadionID = $user1->StadiumID;
	echo "Stadium ID is $stadionID<br/>";
	$user1->EchoClub();
	$rat_1 = $user1->GetRating();
	
	$user1->ComputeTeamValues($grupa);
	//$user1->Vfw; $user1->Vmf; $user1->Vdf

	$trainer1  = $speech1*0.13+$knowhow1*.15;
	echo "Profit antrenor 1: $trainer1<br/>";
	

echo "<br/><br/>$nameteam2...<br/>";
	$user2 = new User();
	$user2->LoginID($team2);

	$user2->EchoClub();
	$rat_2 = $user2->GetRating();//for generating the number of spectators
	$user2->ComputeTeamValues($grupa);

	//percent spectators in the stadium
	//if the stadium is full in over 80%, the home team will get a bonus
	$player12th=1;
    $lista = NumberOfSpectators($stadionID, $team1, $rat_1, $rat_2, $competitionid, $_SEASON);
	
	list($number_spect, $capacitate) = explode("::",$lista);
	if($grupa==2) {
		//for youth games, spectators are less (4 times)
		$number_spect=round($number_spect/4);
	}
	$procent = $number_spect/$capacitate;
	if($procent>.79 && $procent<.9) $player12th = 1.05;
	if($procent>=.9) $player12th = 1.1;
	
	echo "Here is the number of spectators: $number_spect<br/>";
	echo "Percent stadium occupied: $procent<br/>";
	//home team gets advantage if the spectators come to the stadium
	//of course, if the stadium is almost full

	if($moral1<50) $moral1=50;

	
	$Vfw1 = ($user1->Vfw * $player12th * $_luck1 +$trainer1)*$moral1/100;
	$Vmf1 = ($user1->Vmf * $player12th * $_luck1 +$trainer1)*$moral1/100;
	$Vdf1 = ($user1->Vdf * $player12th * $_luck1 +$trainer1)*$moral1/100;

	/*
		$tac1 //1,2,3 - normal, attacking, defensive
		$midf1 //1,2,3 - normal, attacking, defensive
		$attacks1 //1,2,3 - both ways, to the sides and central
		$pas1 //1,2,3 - combination, high, low
	*/
	
	switch($tac1) {
		case 1: $Vfw1 = $Vfw1*.9; $Vdf1 = $Vdf1*.9; break;
		case 2: $Vfw1 = $Vfw1*1.1; $Vdf1 = $Vdf1*.7; break;
		case 3: $Vfw1 = $Vfw1*.7; $Vdf1 = $Vdf1*1.1; break;
	}
	switch($midf1) {
		case 1: $Vfw1 = $Vfw1*.93; break;
		case 2: $Vfw1 = $Vfw1*1.1; $Vdf1 = $Vdf1*.87; break;
		case 3: $Vfw1 = $Vfw1*.87; $Vdf1 = $Vdf1*1.03; break;
	}
	
	echo "Values for team 1: $Vfw1 :: $Vmf1 :: $Vdf1<br/>";
	
	
	echo "<br/>Team2!<br/>";
	//Values for team 2
	//$user2->Vfw; $user2->Vmf; $user2->Vdf

	$trainer2  = $speech2*0.13 + $knowhow2*.15;
	echo "Profit antrenor 2: $trainer2<br/>";

	if($moral2<50) $moral2=50;
	
	$Vfw2 = ($user2->Vfw * $_luck2 +$trainer2)*$moral2/100;
	$Vmf2 = ($user2->Vmf * $_luck2 +$trainer2)*$moral2/100;
	$Vdf2 = ($user2->Vdf * $_luck2 +$trainer2)*$moral2/100;

	switch($tac2) {
		case 1: $Vfw2 = $Vfw2*.9; $Vdf2 = $Vdf2*.9; break;
		case 2: $Vfw2 = $Vfw2*1.1; $Vdf2 = $Vdf2*.7; break;
		case 3: $Vfw2 = $Vfw2*.7; $Vdf2 = $Vdf2*1.1; break;
	}
	switch($midf2) {
		case 1: $Vfw2 = $Vfw2*.93; break;
		case 2: $Vfw2 = $Vfw2*1.1; $Vdf2 = $Vdf2*.87; break;
		case 3: $Vfw2 = $Vfw2*.87; $Vdf2 = $Vdf2*1.03; break;
	}

	
	echo "Values for team 2: $Vfw2 :: $Vmf2 :: $Vdf2<br/>";


/************************************************************
IMPORTANT
	//in dcount1, mcount1, acount1 si 2 we have the information how many defenders, midfielders and attackers are used
	//so, a tactic coudl have advantage to another tactic
	//5-4-1 beats 4-4-2, 4-3-3, 4-5-1
	//4-4-2 beats 4-3-3
	//4-5-1 beats 4-3-3, 4-2-2
*************************************************************/		
	$ttac1 = $dcount1+"-"+$mcount1+"-"+$acount1;
	$ttac2 = $dcount2+"-"+$mcount2+"-"+$acount2;

	
	
	//possesion: x/(x+y) * 100 and y/(x+y) * 100

	$pas = 0.07;
	//how many goals scores team 1
	
	$vt1 = $Vfw1+$Vmf1*.71+$Vdf1*.3; // value team 1
	$vt2 = $Vfw2+$Vmf2*.71+$Vdf2*.3; // value team 2

	if($ttac1 == "5-4-1") {
		if($ttac2 == "4-4-2") {
			$vt1 = $vt1*1.12; //increase the value of team 1 and
			$vt2 = $vt2*0.93; //decrease for the opponent
		}
		if($ttac2 == "4-3-3") {
			$vt1 = $vt1*1.22;
			$vt2 = $vt2*0.87;
		}
		if($ttac2 == "4-5-1") {
			$vt1 = $vt1*1.07;
			$vt2 = $vt2*0.96;
		}
	}

	if($ttac1 == "4-5-1") {
		if($ttac2 == "4-4-2") {
			$vt1 = $vt1*1.08;
			$vt2 = $vt2*0.96;
		}
		if($ttac2 == "4-3-3") {
			$vt1 = $vt1*1.17;
			$vt2 = $vt2*0.91;
		}
		if($ttac2 == "5-4-1") {
			$vt1 = $vt1*0.97;
			$vt2 = $vt2*1.06;
		}
	}

	if($ttac1 == "4-4-2") {
		if($ttac2 == "5-4-1") {
			$vt1 = $vt1*0.93;
			$vt2 = $vt2*1.12;
		}
		if($ttac2 == "4-3-3") {
			$vt1 = $vt1*1.07;
			$vt2 = $vt2*0.97;
		}
		if($ttac2 == "4-5-1") {
			$vt1 = $vt1*0.97;
			$vt2 = $vt2*1.06;
		}
	}

	if($ttac1 == "4-3-3") {
		if($ttac2 == "5-4-1") {
			$vt1 = $vt1*0.87;
			$vt2 = $vt2*1.22;
		}
		if($ttac2 == "4-5-1") {
			$vt1 = $vt1*1.16;
			$vt2 = $vt2*0.93;
		}
		if($ttac2 == "4-2-2") {
			$vt1 = $vt1*0.97;
			$vt2 = $vt2*1.06;
		}
	}
	
	
	$bonus1=0;
	$bonus2=0;
	
	$dif=$vt1-$vt2;
	echo "DIF: $dif<br/>";
	switch($dif) {
		case ($dif>=1 && $dif<10): $bonus1=.3;break;
		case ($dif>=10 && $dif<20): $bonus1=.6;break;
		case ($dif>=20 && $dif<30): $bonus1=1.2;break;
		case ($dif>=30 && $dif<40): $bonus1=1.9;break;
		case ($dif>=40 && $dif<50): $bonus1=2.6;break;
		case ($dif>=50 && $dif<100): $bonus1=3.2;break;
		case ($dif>=100 && $dif<200): $bonus1=4.5;break;
		case ($dif>=200 && $dif<300): $bonus1=6.2;break;
		case ($dif>=300 && $dif<500): $bonus1=8.5;break;
		case ($dif>=500): $bonus1=mt_rand(13,20);break;
	}
	$dif=$vt2-$vt1;
	switch($dif) {
		case ($dif>=1 && $dif<10): $bonus2=.3;break;
		case ($dif>=10 && $dif<20): $bonus2=.6;break;
		case ($dif>=20 && $dif<30): $bonus2=1.2;break;
		case ($dif>=30 && $dif<40): $bonus2=1.9;break;
		case ($dif>=40 && $dif<50): $bonus2=2.6;break;
		case ($dif>=50 && $dif<100): $bonus2=3.2;break;
		case ($dif>=100 && $dif<200): $bonus2=4.5;break;
		case ($dif>=200 && $dif<300): $bonus2=6.2;break;
		case ($dif>=300 && $dif<500): $bonus2=8.5;break;
		case ($dif>=500): $bonus2=mt_rand(13,20);break;
	}
	
	$aleator = mt_rand(0,1)/3;
	$prDef = mt_rand(105,119)/100;
	echo "Percent defence: $prDef<br/>";
	
	echo "BONUS1: $bonus1 :::: BINUS2: $bonus2<br/>";
	//goals by team 1
	$goals1 = (int)(($Vfw1+$Vmf1*.53)/$Vdf2*$prDef-$aleator+$bonus1*.37);

	//goals by 2
	$goals2 = (int)(($Vfw2+$Vmf2*.53)/$Vdf1*$prDef-$aleator+$bonus2*.37);

	
	if ($goals1<=0) {
		$goals1=0;
		//to be more fun, i add randomly goals. This way, we will not have a lot of 0-0
		$to_add = mt_rand(0,3);
		$goals2 += $to_add;
		$goals1 += $to_add;
		echo "I;ve added  $to_add goals because we had for goals1  0<br/>";
	}
	echo "GOALS:: ------------------- <br/>$nameteam1 scores $goals1 goals";

	if ($goals2<=0) {
		$goals2=0;
		//to be more fun, i add randomly goals. This way, we will not have a lot of 0-0
		$to_add = mt_rand(0,3);
		$goals2 += $to_add;
		$goals1 += $to_add;
		echo "Added $to_add goals because goals2 was 0<br/>";
	}
	
	//be sure you will not have 7 to 5 goals
	for($i=4;$i<15;$i++) 
		if($goals1-$goals2>$i) $goals1=$goals1-rand($i-2,$i-1);
	for($i=4;$i<15;$i++) 
		if($goals2-$goals1>$i) $goals2=$goals2-rand($i-2,$i-1);
	
	
	//the scorers
	//mostly the attackers and midfielder, there should be the highest probability

	//establish the minutes for the goals
	for($i=0;$i<$goals1;$i++) {
		$minute1[] = (int)mt_rand(0,88)+1;
	}
	asort($minute1);
	echo "<br/>The goals were scored in the following minutes: : ". implode(", ", $minute1).'<br/>';

	echo "<br/>$nameteam2 scores $goals2 goluri";
	
	//minutes with the goals
	for($i=0;$i<$goals2;$i++) {
		$minute2[] = (int)mt_rand(0,88)+1;

	}
	asort($minute2);
	echo "<br/>The goals were scored in the following minutes: : ". implode(", ", $minute2).'<br/>';


	//KO competition
	if(($compnume == 'Cupa Ligii' || $compnume == 'Cupa Romaniei') && $goals1 == $goals2) {
		//generate penalties
	sus:
		$mt_rand1 = mt_rand(0,5);
		$mt_rand2 = mt_rand(0,5);
		if($mt_rand1==$mt_rand2) goto sus;
		if($mt_rand1>2 && $mt_rand2 == 0) $mt_rand1=3;
		if($mt_rand2>2 && $mt_rand1 == 0) $mt_rand2=3;

		if($mt_rand1>3 && $mt_rand2 == 1) $mt_rand1=4;
		if($mt_rand2>3 && $mt_rand1 == 1) $mt_rand2=4;
		
		if($mt_rand1>3 && $mt_rand2 == 2) $mt_rand1=4;
		if($mt_rand2>3 && $mt_rand1 == 2) $mt_rand2=4;
		
		$goals1 = $goals1 + $mt_rand1;
		$goals2 = $goals2 + $mt_rand2;
		
		/*
		for($i=0;$i<$mt_rand1;$i++) {
			$minute1[] = 90;
		}
		for($i=0;$i<$mt_rand2;$i++) {
			$minute2[] = 90;
		}
		*/
		$wins = "";
		if($mt_rand1>$mt_rand2) $castiga = "Host";
		else $wins = "Guest";
		$txt = "$wins wins $mt_rand1:$mt_rand2 after penalties!";
		
		$sql = "INSERT INTO gametext(gameID, text, attacking_team, mminute, goal, realminute)
				VALUES($gameID, '$txt', 0, '13:45', 2, 90)";
		echo "$sql<br/>";
		mysqli_query($GLOBALS['con'],$sql);
	}

	
	//write final result into the table
	$scor = "$goals1:$goals2";
	$sql = "UPDATE gameinvitation
			SET score='".$scor."'
			WHERE id=$gameID";
	mysqli_query($GLOBALS['con'],$sql);

	Moral($scor, $team1, $team2);

	////a formula between strength and tireness
	//if strength has small value, he should decrease his stamina more after the game
	//and the other way 
	//also, if the lineup is not made from 11 players, decrease also more the stamina
	
	//$st2[$j] = $strength;
	//$juc2[$j++] = $pid;
	//modify for team1
	for($i=0;$i<$formula1; $i++) {
		echo "LOOP: ".$juc[$i].'<br/>';
		$forma = round($fo[$i] -100/$formula1- 10/$st[$i],1);
		if($forma<0) $forma=0;
		$sq = "UPDATE player SET form=$forma WHERE id=".$juc[$i];
		echo "$sq<br/>";
		mysqli_query($GLOBALS['con'],$sq);
	}

	//modify for team2
	for($i=0;$i<$formula2; $i++) {
		$forma = round($fo2[$i] -100/$formula2- 10/$st2[$i],1);
		if($forma<0) $forma=0;
		$sq = "UPDATE player SET form=$forma WHERE id=".$juc2[$i];
		echo "$sq<br/>";
		mysqli_query($GLOBALS['con'],$sq);
	}
	
	//generating the live text for the game
	//there are around 60-80 entries here, 50% for each team
	//generate randomly the minutes for the entries, which are taken from table tplmessages (template messages), where the name of the players are changed
	//players are like this ^1E1^, ^1E2^, ^2E1^, ^2E2^, where the number in from says what team is

	//separately, generated the text for start of the game, pause and end of the game, where is the statistic of the game
	//more, for the minutes of the goals, a text is selected from tplmessages
	
	//table tplmessages has a field 'gtype': 
	// 0: not a goal, a miss
	// 1: goal
	// 3: break
	
	// 4: end of the game, which has possesion ^POSSESION_STATS^^


//start of the game
$sql = "SELECT id, text FROM tplmessages WHERE gtype=2";
$res = mysqli_query($GLOBALS['con'],$sql);

$texts = array();
$i=0;
while(list($id, $text) = mysqli_fetch_row($res)) {
	$texts[$i++] = $text;
}
mysqli_free_result($res);
//choosing the text for start, we can have more, so choose one randomly
$playid = (int)mt_rand(0,$i-1);
$txt = $texts[$playid];

//get the lineups and add it to the text for the start of the game

//here we have competition id $competitionid
//if is different from 0, is official game

$formation = "$nameteam1: ";
$formation .= LineUp($team1, $grupa);
$f1 = $formation;
$formation = "$nameteam2: ";
$formation .= LineUp($team2, $grupa);
$f2 = $formation; 

$txt = "$txt<br/> Line-up:<br/><br/>$f1<br/>$f2<br/><br/>$number_spect spectators have come to assist to this interesting game! The host has $socios1 socios, the guests $socios2!"; 
 echo "$txt<br/>";
//insert it in the table, to minute 0, starting hour 12 : gameID, text, attacking_team, mminute
$sql = "INSERT INTO gametext(gameID, text, attacking_team, mminute, goal, realminute)
		VALUES($gameID, '$txt', 0, '12:00', 2,1)";//final
$sql = "SELECT id, text FROM tplmessages WHERE gtype=4";
$res = mysqli_query($GLOBALS['con'],$sql);

$texts = array();
$i=0;
while(list($id, $text) = mysqli_fetch_row($res)) {
	$texts[$i++] = $text;
}
mysqli_free_result($res);
$playid = (int)mt_rand(0,$i-1);
$txt = $texts[$playid];

//possesion added
$possesion1 = round($Vmf1/($Vmf1+$Vmf2) * 100,1);
$possesion2 = round($Vmf2/($Vmf1+$Vmf2) * 100,1);

$posesia = "Possesion. $nameteam1: $possesion1 percents ";
$posesia .= ". $nameteam2: $possesion2 percents ";
$txt = str_replace("^POSSESION_STATS^", $posesia, $txt);
echo "$txt";
$sql = "INSERT INTO gametext(gameID, text, attacking_team, mminute, goal, realminute)
		VALUES($gameID, '$txt', 0, '13:45', 4,90)";
mysqli_query($GLOBALS['con'],$sql);

echo "START OF THE GAME:: $sql<br/>";
//mysqli_query($GLOBALS['con'],$sql);

EndFirstHalf();

//start second half
SecondHalf($gameID);




//generate plays that end with a goal
//in minute1 and minute2, minutes when the goals were scored
//for the moment being, equal chances to score for all the players

GeneratePlaysForGoals($gameID, $minute1, $formula1, 1, $fname, $lname, $juc);
GeneratePlaysForGoals($gameID, $minute2, $formula2, 2, $fname2, $lname2, $juc2);


$counter1 = intval(70*$posesie1/100);
$contor2 = 70-$counter1;

echo "Valori contor: " . $counter1 . "   " . $contor2;

//introducere faze de poarta, una pe mminute pentru fiecare formatie
for($c=1;$c<90;$c++) {
	//$mminute = intval(mt_rand(0,90));
	switch($c) {
	case 0: $mminute=1; break;
	case 45: $mminute=44; break;
	case 90: $mminute=89; break;
	default:
		$mminute = $c; break;
    }
	
	//generate here the hour when the tet is displayed
	//first half starts at 12:00
	
	//second half, at 13:00
	$minreal = $mminute;
	if($mminute >= 45) {
		$rep2min = $mminute % 45;
		if($rep2min <10) $rep2min = "0".$rep2min;
		$mminute = "13:".$rep2min;
	} else {
		if($mminute <10) $mminute = "0".$mminute;
		$mminute = "12:".$mminute;
	}

	$skip1 = $skip2 = 0;
	if($counter1>$contor2) {
	//posesie mai buna pentru prima echipa
	//trebuie sarite citeva actiuni adverse, sa nu aiba minute
		$r2 = mt_rand(0,100);
		if($r2>77) $skip2 = 1;
	} else {
		$r1 = mt_rand(0,100);
		if($r1>77) $skip1 = 1;
	}
	
	for($i=0;$i<count($fname);$i++) {
		echo "FIRST NAMES :: ".$fname[$i]."<br/>";
	}
	
	if($skip1 == 0)
		Calculation(1, $mminute, $team1, $team2, $gameID, $minreal);
	if($skip2 == 0)
		Calculation(2, $mminute, $team1, $team2, $gameID, $minreal);
  }

  	Changes($team1, $minute1, $minute2, $gameID, 1);
	Changes($team2, $minute1, $minute2, $gameID, 2);
  
} //end function ComputeTheScore


function getTeamComplete($teamid, $gr) {
	$s = "SELECT id FROM lineup WHERE pgroup=$gr AND userid=$teamid and post<>0";
	echo "$s<br/>";
	$r = mysqli_query($GLOBALS['con'],$s);
	$nrjuc = mysqli_num_rows($r);
	mysqli_free_result($r);
	return $nrjuc;
}

function NumberOfSpectators($stadionID, $team1, $rat_1, $rat_2, $competitionid,$season) {
	//update bani incasati dupa bilete
	//trebuie stabilit si numarul de spectators care asista la meci
	//trebuie in functie de ratingurile echipelor implicate, vremea de afara si pretul la bilete.

	echo "<br/>Calculate spectators for this ID....$stadionID<br/>";
	$stad = new Stadium($stadionID);
	$capacitate = $stad->AvailableSeats();
	$pretb = $stad->Price;
	echo "<br/>Capacity is $capacitate and this price: $pretb<br/>";
	
	
	//am $rat_1 si $rat_2, rating-urile celor doua echipe
	//am si $pretb, trebuie sa gasesc o formula care sa le lege pe toate 3, sa dea o proportie de ocupare a tribunelor
	$pretok = 9+$rat_1/25+($rat_2-$rat_1)/25;
	echo "Price for the game is $pretok EURO (Rating 1 is $rat_1, rating 2 is $rat_2<br/>";
	//pt amical (competitionid==0), pretul agreat sa fie jumate
	if($competitionid==0) $pretok = intval($pretok/2);
	if($pretb==0)$pretb=1;
	$number_spect = intval($pretok*$pretok*$capacitate/($pretb*$pretb));
	if($number_spect > $capacitate) $number_spect=$capacitate;
	//$nrspect = mt_rand(1,$capacitate); //trebuie luata capacitatea stadionului primei echipe
	
	$sumabilete = $number_spect*$pretb;
	echo "No. of Spectators: $number_spect/$capacitate CompetitionID: $competitionid :: $sumabilete<br/>";
	mysqli_free_result($res);


	$sql = "INSERT INTO account (userid, reason, amount, season)
		VALUES($team1, 'Bilete', $sumabilete, $season)";
	echo "$sql<br/>";
	mysqli_query($GLOBALS['con'],$sql);
	
	$sql = "UPDATE user
			SET Funds = Funds+$sumabilete 
			WHERE id=$team1";
	mysqli_query($GLOBALS['con'],$sql);

	
	return "$number_spect::$capacitate";
}

function Moral($scor, $team1, $team2) {
	
	//check moral of the players 
	
	list($sc1,$sc2) = explode(':', $scor);
	//decrease/inscrease moral, if the team looses or wins with more than 5 goals
	$moral1 = $moral2 = 0;
	if($sc1-$sc2>=5) {
		$moral1 = 2;
		$moral2 = -2;
	}
	if($sc1-$sc2<=-5) {
		$moral2 = 2;
		$moral1 = -2;
	}
	if($moral1<>0) {
		$s = "UPDATE player SET Moral=CASE
									WHEN Moral+$moral1>100 THEN 100
									WHEN Moral+$moral1<=0 THEN 1
									ELSE Moral+$moral1
									  END
			  WHERE id IN (SELECT playerid FROM userplayer WHERE userid=$team1)";
		echo "$s<br/>";
		mysqli_query($GLOBALS['con'],$s);
		$s = "UPDATE player SET Moral=CASE
									WHEN Moral+$moral2>100 THEN 100
									WHEN Moral+$moral2<=0 THEN 1
									ELSE Moral+$moral2
									  END
			  WHERE id IN (SELECT playerid FROM userplayer WHERE userid=$team2)";
		echo "$s<br/>";
		mysqli_query($GLOBALS['con'],$s);
	}
}

function LineUp($team1, $grupa) {

//with teamid and if it's senior or youth
$sql = "SELECT a.playerId, a.post, LEFT(b.fname,1), LEFT(b.lname,7) 
		FROM lineup a
		LEFT OUTER JOIN player b
		ON a.playerId=b.id
		WHERE a.pgroup=$grupa AND a.userId=$team1 ORDER BY a.post ASC";
echo "LINEUP function :: $sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
$pozitii = array();
$pidi = array();
$i=0;
$findex=0;
$mindex=0;
$aindex=0;
$fs=$fd=$f1=$f2=$f3=$ml=$mc=$mr=$m1=$m2=$m3=$a1=$a2=$a3="";
while(list($e_pid, $e_post, $player_fname, $player_lname)=mysqli_fetch_row($res)) {
	if($e_post<>0) {
		//pentru moral, a fost titular, plus resetez contorul de meciuri oficiale
		$s = "UPDATE morale SET counter1=0 WHERE playerid=$e_pid";
		mysqli_query($GLOBALS['con'],$s);
		if($competitionid==0)$adun=7;
		else $adun=10;
		$s = "UPDATE player SET moral=CASE
									WHEN moral+$adun>100 THEN 100
									ELSE moral+$adun
									  END
			  where id=$e_pid";
		//echo "$s<br/>";
		mysqli_query($GLOBALS['con'],$s);

		$pozitii[$e_pid] = $e_post;
		$pidi[$e_pid] = $player_fname.'.'.$player_lname;
		switch ($e_post) {
			case 1: $pos = "GK"; $portar = $player_lname; break;
			case 2: $pos = "DR"; $fd = $player_lname;break;
			case 3: 
				$pos = "DC"; 
				switch($findex) {
					case 0: $f1=$player_lname; break;
					case 1: $f2=$player_lname; break;
					case 2: $f3=$player_lname; break;
				}
				$findex++;
				break;
			case 4: $pos = "DL"; $fs = $player_lname; break;
			case 5: $pos = "MR"; $mr = $player_lname; break;
			case 6: 
				$pos = "MC"; 
				switch($mindex) {
					case 0: $m1=$player_lname; break;
					case 1: $m2=$player_lname; break;
					case 2: $m3=$player_lname; break;
				}
				$mindex++;
				
				break;
			case 7: $pos = "ML"; $ml = $player_lname; break;
			case 8: 
				$pos = "FR"; 
				switch($aindex) {
					case 0: $a1=$player_lname; break;
					case 1: $a2=$player_lname; break;
					case 2: $a3=$player_lname; break;
				}
				$aindex++;
				break;
			case 9: 
				$pos = "FC"; 
				switch($aindex) {
					case 0: $a1=$player_lname; break;
					case 1: $a2=$player_lname; break;
					case 2: $a3=$player_lname; break;
				}
				$aindex++;
				
				break;
			case 10: 
				$pos = "FL"; 
				switch($aindex) {
					case 0: $a1=$player_lname; break;
					case 1: $a2=$player_lname; break;
					case 2: $a3=$player_lname; break;
				}
				$aindex++;
				
				break;
			}
		$formation .= "$player_fname.$player_lname($pos)";
		if($i<10) $formation .= "-";
		$i++;
	} else {
		//e_post==0, so he is not in the lineup
		//add one to the counter
		if($competitionid<>0) {
			//it is game from one of the leagues, so i count it
			$s = "UPDATE morale SET counter1=counter1+1 WHERE playerid=$e_pid";
			echo "added to not played::      $s<br/>";
			mysqli_query($GLOBALS['con'],$s);
		} 
		
	}
}
mysqli_free_result($res);
$formation .= '<br/>'; 
return $formation;
}

function SecondHalf($gameID) {
$sql = "SELECT id, text FROM tplmessages WHERE gtype=6";
$res = mysqli_query($GLOBALS['con'],$sql);

$texts = array();
$i=0;
while(list($id, $text) = mysqli_fetch_row($res)) {
	$texts[$i++] = $text;
	echo "$text<br/>";
}
mysqli_free_result($res);
//alegerea fazei pe care o punem la inceputul reprizei II
$playid = (int)mt_rand(0,$i-1);
$txt = $texts[$playid];
//avem textul, il introducem in bd, in gametext : gameID, text, attacking_team, mminute
$sql = "INSERT INTO gametext(gameID, text, attacking_team, mminute, goal, realminute)
		VALUES($gameID, '$txt', 0, '13:00', 2,46)";
echo "SECOND HALF:: $sql<br/>";
mysqli_query($GLOBALS['con'],$sql);


}


function GeneratePlaysForGoals($gameID, $minute, $formula, $team, $fname, $lname, $players) {
//in minute, the minutes when the goals were scored
echo "MINUTE::: ".count($minute).'<br/>';

	for ($ii=0; $ii<count($minute); $ii++) {
		
		$idplayer = (int)mt_rand(0,$formula-1);
		//$players[$idplayer] -id
		//take one template message
		$sql = "SELECT id, text FROM tplmessages WHERE gtype=1";
		echo "GOAL PLAY :: $sql<br/>";
		$res = mysqli_query($GLOBALS['con'],$sql);

		$texts = array();
		$i=0;
		while(list($id, $text) = mysqli_fetch_row($res)) {
			$texts[$i++] = $text;
		}
		mysqli_free_result($res);

		$playid = (int)mt_rand(0,$i-1);
		$txt = $texts[$playid];
		$scorer = $fname[$idplayer] . " ". $lname[$idplayer];
		echo "The scorer name is $scorer and $idplayer and the play is:::: $txt<br/>";
		$txt = str_replace("^1E1^", $scorer, $txt);
		echo "TEXT OF PLAY AFTER REPLACE :: $txt<br/>";

		if($minute[$ii] >= 45) {
			$m = $minute[$ii] % 45;
			if($m<10) $m = "0".$m;
			$mintrodus = "13:$m";
		} else {
			if($minute[$ii]<10) $m = "0".$minute[$ii];
			else $m = $minute[$ii];
			$mintrodus = "12:".$m;
		}
		//for goals, action has value 1
		$sql = "INSERT INTO gamedetail(gameID, playerid, mminute, team, action)
				VALUES($gameID, ".$players[$idplayer].", ".$minute[$ii].", $team,1)";
		echo "SCORER: $sql<br/>";
		mysqli_query($GLOBALS['con'],$sql);
		
		
		//avem textul, il introducem in bd, in gametext : gameID, text, attacking_team, mminute
		$sql = "INSERT INTO gametext(gameID, text, attacking_team, mminute, goal, realminute)
				VALUES($gameID, '$txt', $team, '".$mintrodus."', 1, ".$minute[$ii].")";
		echo "$sql<br/>";
		mysqli_query($GLOBALS['con'],$sql);
	}
}

function EndFirstHalf() {


//pauza
$sql = "SELECT id, text FROM tplmessages WHERE gtype=3";
$res = mysqli_query($GLOBALS['con'],$sql);

$texts = array();
$i=0;
while(list($id, $text) = mysqli_fetch_row($res)) {
	$texts[$i++] = $text;
}
mysqli_free_result($res);
//the play we choose for the start of the match ( choose one from many)
$playid = (int)mt_rand(0,$i-1);
$txt = $texts[$playid];
//insert in gametext : gameID, text, attacking_team, mminute
$sql = "INSERT INTO gametext(gameID, text, attacking_team, mminute, goal, realminute)
		VALUES($gameID, '$txt', 0, '12:45', 3,45)";
mysqli_query($GLOBALS['con'],$sql);

}


?>