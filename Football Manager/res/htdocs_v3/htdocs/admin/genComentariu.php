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

error_reporting(63);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

//it comes gameID
//$gameID = 13;

function Changes($team, $minute1, $minute2, $gameID, $which) {
	//am minute1 si minute2, array-uri cu golurile.
	//trebuie sa parcurg tabelul schimbari, sa vad daca se fac schimbarile
	//iau la rind toate, in functie cu minutul schimbarii si conditie.
	//totodata, tin minte si id-urile jucatorilor care intra la schimbare, sa nu mai apara inca o data
	
	$sch1 = "SELECT a.minut, a.playerid1, a.playerid2, a.conditie1, a.post, b.fname, b.lname, c.fname, c.lname
			FROM schimbari a
			left join player b
			on a.playerid1=b.id
			left join player c
			on a.playerid2=c.id
			WHERE a.userid=$team
			ORDER BY a.minut ASC";
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
			"2"=>"Conduc cu un gol",
			"3"=>"Conduc cu doua goluri+",
			"4"=>"Sunt condus cu un gol",
			"5" =>"Sunt condus cu doua goluri+"
		*/
		
		if($ex == 0) {
			//nu a mai fost parte dintr-o schimbare, nici rezerva, nici titularul
			switch($cond) {
				case 1: //no matter what, the score is not important
					//introduc pid-urile sa nu mai le bag in alta schimbare
					$replacers[$ri++] = $pid1;
					$lineup[$ti++] = $pid2;
					
					//insert text in gametext
					//to come
					$minreal = $minut_s;
					$m = $minut_s%45;
					if($m<10) $m="0".$m;
					$min = intval($minut_s/45)==1? "13:".$m:"12:".$m; //to know in what part of the game is the game, first or second
					$minute[$mi++] = $min;
					$sql = "INSERT INTO gametext(gameID, text,atacator,minut, gol, minutreal)
							VALUES($gameID, 'Se aude fluierul arbitrului! Vom asista la o schimbare: $fname1 $lname1 ii va lua locul lui $fname2 $lname2! Este o schimbare interesanta, sa-l vedem la lucru pe $lname1!', $team, '$min',10, $minreal)";
					mysqli_query($GLOBALS['con'],$sql);
					echo "my change: $sql<br/>";
					$name1 = "$fname1 $lname1";
					$name2 = "$fname2 $lname2";
					$sql = "UPDATE gametext SET text=REPLACE(text,'".$name2."', '".$name1."') WHERE gameID=$gameID AND minutreal>$minreal";
					mysqli_query($GLOBALS['con'],$sql);
					echo "SCHIMBARE ::  $sql<br/>";
					$sql = "UPDATE gamedetail SET playerid=$pid1 WHERE gameID=$gameID AND playerid=$pid2 AND minut>$minreal";
					mysqli_query($GLOBALS['con'],$sql);
					echo "Change ::  $sql<br/>";
					
					$sch_efect++;
					break;
				case 2: //leading with one goal
					//in parameter 'which' first or second team
					//here the goals scored by the home team and guest team, to do the changes set up for each team
					//introduc pid-urile sa nu mai le bag in alta schimbare
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
						
						//inserez textul in gametext
						//to come
						$minreal = $minut_s;
						$m = $minut_s%45;
						if($m<10) $m="0".$m;
						$min = intval($minut_s/45)==1? "13:".$m:"12:".$m;
						$minute[$mi++] = $min;
						$sql = "INSERT INTO gametext(gameID, text,atacator,minut, gol, minutreal)
								VALUES($gameID, 'Se aude fluierul arbitrului! Vom asista la o schimbare: $fname1 $lname1 ii va lua locul lui $fname2 $lname2! Este o schimbare interesanta, sa-l vedem la lucru pe $lname1!', $team, '$min',10, $minreal)";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCH: $sql<br/>";
						$name1 = "$fname1 $lname1";
						$name2 = "$fname2 $lname2";
						$sql = "UPDATE gametext SET text=REPLACE(text,'".$name2."', '".$name1."') WHERE gameID=$gameID AND minutreal>$minreal";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCHIMBARE ::  $sql<br/>";
						$sql = "UPDATE gamedetail SET playerid=$pid1 WHERE gameID=$gameID AND playerid=$pid2 AND minut>$minreal";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCHIMBARE ::  $sql<br/>";
						
						$sch_efect++;
					}
					break;
				case 3: //daca conduc la mai mult sau egal cu 2 goluri
					//in variabila 'care' se gaseste informatia daca echipa este prima sau a doua
					//trebuie sa iau in calcul cu golurile marcate de gazde si oaspeti
					//introduc pid-urile sa nu mai le bag in alta schimbare
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
						$sql = "INSERT INTO gametext(gameID, text,atacator,minut, gol, minutreal)
								VALUES($gameID, 'Se aude fluierul arbitrului! Vom asista la o schimbare: $fname1 $lname1 ii va lua locul lui $fname2 $lname2! Este o schimbare interesanta, sa-l vedem la lucru pe $lname1!', $team, '$min',10, $minreal)";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCH: $sql<br/>";
						$name1 = "$fname1 $lname1";
						$name2 = "$fname2 $lname2";
						$sql = "UPDATE gametext SET text=REPLACE(text,'".$name2."', '".$name1."') WHERE gameID=$gameID AND minutreal>$minreal";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCHIMBARE ::  $sql<br/>";
						$sql = "UPDATE gamedetail SET playerid=$pid1 WHERE gameID=$gameID AND playerid=$pid2 AND minut>$minreal";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCHIMBARE ::  $sql<br/>";
						
						$sch_efect++;
					}
					break;
				case 4: //daca sunt condus cu un gol
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
						$sql = "INSERT INTO gametext(gameID, text,atacator,minut, gol, minutreal)
								VALUES($gameID, 'Se aude fluierul arbitrului! Vom asista la o schimbare: $fname1 $lname1 ii va lua locul lui $fname2 $lname2! Este o schimbare interesanta, sa-l vedem la lucru pe $lname1!', $team, '$min',10, $minreal)";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCH: $sql<br/>";
						$name1 = "$fname1 $lname1";
						$name2 = "$fname2 $lname2";
						$sql = "UPDATE gametext SET text=REPLACE(text,'".$name2."', '".$name1."') WHERE gameID=$gameID AND minutreal>$minreal";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCHIMBARE ::  $sql<br/>";
						$sql = "UPDATE gamedetail SET playerid=$pid1 WHERE gameID=$gameID AND playerid=$pid2 AND minut>$minreal";
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
						$sql = "INSERT INTO gametext(gameID, text,atacator,minut, gol, minutreal)
								VALUES($gameID, 'Se aude fluierul arbitrului! Vom asista la o schimbare: $fname1 $lname1 ii va lua locul lui $fname2 $lname2! Este o schimbare interesanta, sa-l vedem la lucru pe $lname1!', $team, '$min',10, $minreal)";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCH: $sql<br/>";
						$name1 = "$fname1 $lname1";
						$name2 = "$fname2 $lname2";
						$sql = "UPDATE gametext SET text=REPLACE(text,'".$name2."', '".$name1."') WHERE gameID=$gameID AND minutreal>$minreal";
						mysqli_query($GLOBALS['con'],$sql);
						echo "SCHIMBARE ::  $sql<br/>";
						$sql = "UPDATE gamedetail SET playerid=$pid1 WHERE gameID=$gameID AND playerid=$pid2 AND minut>$minreal";
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
	//probabiltate mai mare pentru atacanti si mijlocasi sa inscrie. nu este imposibil insa pentru fundasi
	//totodata, in functie de valoare sa se stabileasca cine inscrie
	if($which == 1) {
		$jucator = $juc;
		$prenume = $pren;
		$name = $nu;
		$posturi = $pst;
	}
	if($which == 2) {
		$jucator = $juc2;
		$prenume = $pren2;
		$name = $nu2;
		$posturi = $pst2;
	}
	
	
}


function calcul($at, $min, $team1, $team2, $gameID, $minreal) {
	global $juc;
	global $juc2;
	global $nu;
	global $nu2;
	global $pren;
	global $pren2;
	global $pst;
	global $pst2;
		global $formula1;
	global $formula2;

	//echo "FORMULA $formula1 :: $formula2<br/>";

	if ($at == 1) {
		//ataca prima ecgipa
		$idjuc1 = (int)mt_rand(0,$formula1-1);
		$idjuc2 = (int)mt_rand(0,$formula1-1);
		$idjuc3 = (int)mt_rand(0,$formula2-1);
		$idjuc4 = (int)mt_rand(0,$formula2-1);
	} else {
		//ataca a doua echipa
		$idjuc1 = (int)mt_rand(0,$formula2-1);
		$idjuc2 = (int)mt_rand(0,$formula2-1);
		$idjuc3 = (int)mt_rand(0,$formula1-1);
		$idjuc4 = (int)mt_rand(0,$formula1-1);
	}
	//tip=0 sau 5 - faze normale sau offside
	$sql = "SELECT id, text, tip FROM tplmessages WHERE tip=0 or tip=5";
	$res = mysqli_query($GLOBALS['con'],$sql);

	$texts = array();
	$tips = array();
	$i=0;
	while(list($id, $text, $tip) = mysqli_fetch_row($res)) {
		$texts[$i] = $text;
		$tips[$i++] = $tip;
	}
	mysqli_free_result($res);

	$fazaid = (int)mt_rand(0,$i-1);
	$txt = $texts[$fazaid];
	$tipul = $tips[$fazaid];

	if ($at == 1) {
		$juc1 = $pren[$idjuc1] . " ". $nu[$idjuc1];
		$juc2 = $pren[$idjuc2] . " ". $nu[$idjuc2];
		$juc3 = $pren2[$idjuc3] . " ". $nu2[$idjuc3];
		$juc4 = $pren2[$idjuc4] . " ". $nu2[$idjuc4];
	} else {
		$juc1 = $pren2[$idjuc1] . " ". $nu2[$idjuc1];
		$juc2 = $pren2[$idjuc2] . " ". $nu2[$idjuc2];
		$juc3 = $pren[$idjuc3] . " ". $nu[$idjuc3];
		$juc4 = $pren[$idjuc4] . " ". $nu[$idjuc4];
	}

	if($at == 1) {
		$ata = $team1;
	} else {
		$ata = $team2;
	}
	$txt = str_replace("^1E1^", $juc1, $txt);
	$txt = str_replace("^1E2^", $juc2, $txt);
	$txt = str_replace("^2E1^", $juc3, $txt);
	$txt = str_replace("^2E2^", $juc4, $txt);
	
	
	//avem textul, il introducem in bd, in gametext : gameID, text, atacator, minut
	$sql = "INSERT INTO gametext(gameID, text, atacator, minut, gol, minutreal)
			VALUES($gameID, '$txt', $ata, '".$min."', $tipul, $minreal)";
	echo "$sql<br/>";
	mysqli_query($GLOBALS['con'],$sql);


} //end function calcul

/****************************************************************************************************
*****************************************************************************************************
****************************************************************************************************/

function ComputeTheScore($gameID, $_SEZON) {
	global $juc;
	global $juc2;
	global $nu;
	global $nu2;
	global $pren;
	global $pren2;
	global $pst;
	global $pst2;
	global $formula1;
	global $formula2;
	
$sql = "SELECT a.userId_1, a.userId_2, b.TeamName, c.TeamName, a.scor, d.numar, e.numar, f.nume, a.competitieid, b.Moral, c.Moral, a.grupavirsta 
		FROM invitatiemeci a 
		LEFT OUTER JOIN user b
		ON b.id=a.userId_1
		LEFT OUTER JOIN user c
		ON c.id=a.userId_2
		LEFT OUTER JOIN tribuna d
		ON a.userId_1=d.userid
		LEFT OUTER JOIN tribuna e
		ON a.userId_2=e.userid
		LEFT JOIN competitie f
		ON f.id=a.competitieid
		WHERE a.id=$gameID";
echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
list($team1, $team2, $nameechipa1, $nameechipa2, $scor, $socios1, $socios2, $compnume, $competitieid, $moral1, $moral2, $grupa) = mysqli_fetch_row($res);
mysqli_free_result($res);

//sa aflu sistemul de joc: 4-42, 4-5-1 s.a.m.d
$fcount1 = 0;
$mcount1 = 0;
$acount1 = 0;

$fcount2 = 0;
$mcount2 = 0;
$acount2 = 0;


	//echipa1
	$sql = "SELECT a.playerId, a.post, b.fname, b.lname, b.strength, b.form 
			FROM echipastart a 
			LEFT OUTER JOIN player b
			ON a.playerId=b.id
			WHERE a.userId=$team1 AND a.post<>0 and a.post<>1 and a.grupa=$grupa"; //am adugat grupa pentru tineret si seniori
		
	echo "$sql<br/>";
	$res = mysqli_query($GLOBALS['con'],$sql);
	$juc = array();
	$pren = array();
	$nu = array();
	$st = array();
	$fo = array();
	$j=0;
	while(list($jucator, $post, $prenume, $name, $strength, $forma) = mysqli_fetch_row($res)) {
		$pren[$j] = $prenume;
		$nu[$j] = $name;
		$juc[$j] = $jucator;
		$st[$j] = $strength;
		$fo[$j] = $forma;
		$pst[$j++] = $post;
		
		switch($post) {
			case 2:
			case 3:
			case 4:
				$fcount1++;
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

//echipa 2
$sql = "SELECT a.playerId, a.post, b.fname, b.lname, b.strength, b.form 
		FROM echipastart a 
		LEFT OUTER JOIN player b
		ON a.playerId=b.id
		WHERE a.userId=$team2 AND a.post<>0 and a.post<>1 AND a.grupa=$grupa";
echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
$juc2 = array();
$pren2 = array();
$nu2 = array();
$st2 = array();
$fo2 = array();
$j=0;
while(list($jucator, $post, $prenume, $name, $strength, $forma) = mysqli_fetch_row($res)) {
	$pren2[$j] = $prenume;
	$nu2[$j] = $name;
	$pst2[$j] = $post;
	$st2[$j] = $strength;
	$fo2[$j] = $forma;
	$juc2[$j++] = $jucator;
		switch($post) {
			case 2:
			case 3:
			case 4:
				$fcount2++;
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
$sqltac1 = "SELECT userid, tactica, mijlocul, atacuri, pasare 
			FROM tactica 
			WHERE (userid=$team1 or userid=$team2) AND grupa=$grtac";
$restac1 = mysqli_query($GLOBALS['con'],$sqltac1);
while(list($ec, $tactica, $mij, $atacuri, $pasare)=mysqli_fetch_row($restac1)) {
	if($ec == $team1) {
		$tac1 = $tactica; //1,2,3 - normal, attacking, defensive
		$mij1 = $mij; //1,2,3 - normal, attacking, defensive
		$atacuri1 = $atacuri; //1,2,3 - mixte, pe benzi, centrale
		$pas1 = $pasare; //1,2,3 - combinate, inalte, jos
	} else {
		$tac2 = $tactica;
		$mij2 = $mij;
		$atacuri2 = $atacuri;
		$pas2 = $pasare;
	}
}
mysqli_free_result($restac1);

//implicare antrenor -> tactic si motivare
//daca nu are, pun implicit pe 1
$sql = "SELECT a.userid, b.Motivation, b.Tactical
		FROM usertrainer a
		LEFT JOIN trainer b
		ON b.id=a.trainerid
		WHERE a.userid=$team1 OR a.userid=$team2";
$restrai = mysqli_query($GLOBALS['con'],$sql);
while(list($ust, $mot, $tacti) = mysqli_fetch_row($restrai)) {
	if($ust == $team1) {
		$mot1 = $mot;
		$tacti1 = $tacti;
		echo "aici ant 1<br/>";
	} else {
		$mot2 = $mot;
		$tacti2 = $tacti;
		echo "aici ant 2: $mot2 $tacti2<br/>";
	}
}
mysqli_free_result($restrai);

if(empty($mot1)) $mot1=1;
if(empty($mot2)) $mot2=1;
if(empty($tacti1)) $tacti1=1;
if(empty($tacti2)) $tacti2=1;

//echipa 1
echo "$nameechipa1...<br/>";
	$user1 = new User();
	$user1->LoginID($team1);

	$stadionID = $user1->StadiumID;
	
	$user1->EchoClub();
	$rat_1 = $user1->GetRating();
	
	$user1->ComputeTeamValues($grupa);

	//Valorile pentru echipa 1
	//$user1->Vfw; $user1->Vmf; $user1->Vdf

	$ant1  = $mot1*0.13+$tacti1*.15;
	echo "Profit antrenor 1: $ant1<br/>";
	

echo "<br/><br/>$nameechipa2...<br/>";
	$user2 = new User();
	$user2->LoginID($team2);

	$user2->EchoClub();
	$rat_2 = $user2->GetRating();//pt a genera numar de spectatori
	$user2->ComputeTeamValues($grupa);

	//pecent fans in the stadium
	//if the stadium is full in over 80%, the home team will get a bonus
	$jucator12=1;
    $lista = NumarSpectatori($stadionID, $team1, $rat_1, $rat_2, $competitieid, $_SEZON);
	
	list($nrspect, $capacitate) = explode("::",$lista);
	if($grupa==2) {
		//la tineret, spectatorii sunt mai putini (de 4 ori)
		$nrspect=round($nrspect/4);
	}
	$procent = $nrspect/$capacitate;
	if($procent>.79 && $procent<.9) $jucator12 = 1.05;
	if($procent>=.9) $jucator12 = 1.1;
	
	echo "Percent stadium occupied: $procent<br/>";
	//in functie de numarul de spectatori, ar trebui sa primeasca echipa gazda un avantaj
	//desigur, doar daca vine stadion aproape de plin

	if($moral1<50) $moral1=50;

	
	$Vfw1 = ($user1->Vfw * $jucator12 * $_luck1 +$ant1)*$moral1/100;
	$Vmf1 = ($user1->Vmf * $jucator12 * $_luck1 +$ant1)*$moral1/100;
	$Vdf1 = ($user1->Vdf * $jucator12 * $_luck1 +$ant1)*$moral1/100;

	/*
		$tac1 //1,2,3 - normal, attacking, defensive
		$mij1 //1,2,3 - normal, attacking, defensive
		$atacuri1 //1,2,3 - mixte, pe benzi, centrale
		$pas1 //1,2,3 - combinate, inalte, jos
	*/
	
	switch($tac1) {
		case 1: $Vfw1 = $Vfw1*.9; $Vdf1 = $Vdf1*.9; break;
		case 2: $Vfw1 = $Vfw1*1.1; $Vdf1 = $Vdf1*.7; break;
		case 3: $Vfw1 = $Vfw1*.7; $Vdf1 = $Vdf1*1.1; break;
	}
	switch($mij1) {
		case 1: $Vfw1 = $Vfw1*.93; break;
		case 2: $Vfw1 = $Vfw1*1.1; $Vdf1 = $Vdf1*.87; break;
		case 3: $Vfw1 = $Vfw1*.87; $Vdf1 = $Vdf1*1.03; break;
	}
	
	echo "Valori echipa 1: $Vfw1 :: $Vmf1 :: $Vdf1<br/>";
	
	
	echo "Team2!";
	//Values for team 2
	//$user2->Vfw; $user2->Vmf; $user2->Vdf

	$ant2  = $mot2*0.13 + $tacti2*.15;
	echo "Profit antrenor 2: $ant2<br/>";

	if($moral2<50) $moral2=50;
	
	$Vfw2 = ($user2->Vfw * $_luck2 +$ant2)*$moral2/100;
	$Vmf2 = ($user2->Vmf * $_luck2 +$ant2)*$moral2/100;
	$Vdf2 = ($user2->Vdf * $_luck2 +$ant2)*$moral2/100;

	switch($tac2) {
		case 1: $Vfw2 = $Vfw2*.9; $Vdf2 = $Vdf2*.9; break;
		case 2: $Vfw2 = $Vfw2*1.1; $Vdf2 = $Vdf2*.7; break;
		case 3: $Vfw2 = $Vfw2*.7; $Vdf2 = $Vdf2*1.1; break;
	}
	switch($mij2) {
		case 1: $Vfw2 = $Vfw2*.93; break;
		case 2: $Vfw2 = $Vfw2*1.1; $Vdf2 = $Vdf2*.87; break;
		case 3: $Vfw2 = $Vfw2*.87; $Vdf2 = $Vdf2*1.03; break;
	}

	
	echo "Valori echipa 2: $Vfw2 :: $Vmf2 :: $Vdf2<br/>";

	//in fcount1, mcount1, acount1 si 2 exista citi fundsi, mijl si atacanti sunt folositi
	//vor exista tactici mai bune in functie de tactica folosita de adversar.
	//5-4-1 bate 4-4-2, 4-3-3, 4-5-1
	//4-4-2 bate 4-3-3
	//4-5-1 bate 4-3-3, 4-2-2
	
	$ttac1 = $fcount1+"-"+$mcount1+"-"+$acount1;
	$ttac2 = $fcount2+"-"+$mcount2+"-"+$acount2;
	
	
	
	//possesion: x/(x+y) * 100 si y/(x+y) * 100

	$pas = 0.07;
	//cite goluri inscrie echipa 1
	
	$vt1 = $Vfw1+$Vmf1*.71+$Vdf1*.3;
	$vt2 = $Vfw2+$Vmf2*.71+$Vdf2*.3;

	if($ttac1 == "5-4-1") {
		if($ttac2 == "4-4-2") {
			$vt1 = $vt1*1.12;
			$vt2 = $vt2*0.93;
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
	echo "PROCENT APARARE: $prDef<br/>";
	
	echo "BONUS1: $bonus1 :::: BINUS2: $bonus2<br/>";
	//cite goluri inscrie echipa 1
	$goluri1 = (int)(($Vfw1/$Vdf2 - 1)/$pas);
	$goluri1 = (int)(($Vfw1+$Vmf1*.53)/$Vdf2*$prDef-$aleator+$bonus1*.37);

	//cite goluri inscrie echipa 2
	$goluri2 = (int)(($Vfw2/$Vdf1 - 1)/$pas);
	$goluri2 = (int)(($Vfw2+$Vmf2*.53)/$Vdf1*$prDef-$aleator+$bonus2*.37);

	
	if ($goluri1<=0) {
		$goluri1=0;
		//to be more fun, i add randomly goals. This way, we will not have a lot of 0-0
		$deadaugat = mt_rand(0,3);
		$goluri2 += $deadaugat;
		$goluri1 += $deadaugat;
		echo "I;ve added  $deadaugat goals because we had for goals1  0<br/>";
	}
	echo "<br/>$nameechipa1 scores $goluri1 goals";

	if ($goluri2<=0) {
		$goluri2=0;
		//to be more fun, i add randomly goals. This way, we will not have a lot of 0-0
		$deadaugat = mt_rand(0,3);
		$goluri2 += $deadaugat;
		$goluri1 += $deadaugat;
		echo "Added $deadaugat goals because goals2 was 0<br/>";
	}
	
	//be sure you will not have 7 to 5 goals
	for($i=4;$i<15;$i++) 
		if($goluri1-$goluri2>$i) $goluri1=$goluri1-rand($i-2,$i-1);
	for($i=4;$i<15;$i++) 
		if($goluri2-$goluri1>$i) $goluri2=$goluri2-rand($i-2,$i-1);
	
	
	//the scorers
	//mostly the attackers and midfielder, there should be the highest probability

	//establish the minutes for the goals
	for($i=0;$i<$goluri1;$i++) {
		$minute1[] = (int)mt_rand(0,88)+1;
		ChooseScorer($team1);
	}
	asort($minute1);
	echo "<br/>The goals were scored in the following minutes: : ". implode(", ", $minute1).'<br/>';

	echo "<br/>$nameechipa2 inscrie $goluri2 goluri";
	
	//minutele in care s-a marcat
	for($i=0;$i<$goluri2;$i++) {
		$minute2[] = (int)mt_rand(0,88)+1;
		ChooseScorer($team2);
	}
	asort($minute2);
	echo "<br/>The goals were scored in the following minutes: : ". implode(", ", $minute2).'<br/>';


	//KO competition
	if(($compnume == 'Cupa Ligii' || $compnume == 'Cupa Romaniei') && $goluri1 == $goluri2) {
		//meciul nu se poate termina la egalitate!!!
		//se executa direct penalty-uri
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
		
		$goluri1 = $goluri1 + $mt_rand1;
		$goluri2 = $goluri2 + $mt_rand2;
		
		/*
		for($i=0;$i<$mt_rand1;$i++) {
			$minute1[] = 90;
		}
		for($i=0;$i<$mt_rand2;$i++) {
			$minute2[] = 90;
		}
		*/
		$castiga = "";
		if($mt_rand1>$mt_rand2) $castiga = "Gazdele";
		else $castiga = "Oaspetii";
		$txt = "$castiga partida cu $mt_rand1:$mt_rand2 dupa executarea loviturilor de la 11m!";
		
		//avem textul, il introducem in bd, in gametext : gameID, text, atacator, minut
		$sql = "INSERT INTO gametext(gameID, text, atacator, minut, gol, minutreal)
				VALUES($gameID, '$txt', 0, '13:45', 2, 90)";
		echo "$sql<br/>";
		mysqli_query($GLOBALS['con'],$sql);
	}

	
	//scriere rezultat final in tabel
	$scor = "$goluri1:$goluri2";
	$sql = "UPDATE invitatiemeci
			SET scor='".$scor."'
			WHERE id=$gameID";
	mysqli_query($GLOBALS['con'],$sql);

	Moral($scor, $team1, $team2);

	////trebuie gasita o formula intre strength si scaderea de forma (oboseala)
	//daca are strength mic, trebuie sa scada mai mult in forma fizica dupa meci
	//asadar, invers proportionala descresterea
	//totodata, scade si in functie de numarul de jucatori din formatie -  o sa le adun
	
	//$st2[$j] = $strength;
	//$juc2[$j++] = $jucator;
	//modificare forma echipa1
	for($i=0;$i<$formula1; $i++) {
		$forma = round($fo[$i] -100/$formula1- 10/$st[$i],1);
		if($forma<0) $forma=0;
		$sq = "UPDATE player SET form=$forma WHERE id=".$juc[$i];
		echo "$sq<br/>";
		mysqli_query($GLOBALS['con'],$sq);
	}

	//modificare forma echipa2
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
	
	//table tplmessages has a field 'tip': 
	// 0: not a goal, a miss
	// 1: goal
	// 3: break
	
	// 4: end of the game, which has possesion ^STATISTICA_POSESIE^


//start of the game
$sql = "SELECT id, text FROM tplmessages WHERE tip=2";
$res = mysqli_query($GLOBALS['con'],$sql);

$texts = array();
$i=0;
while(list($id, $text) = mysqli_fetch_row($res)) {
	$texts[$i++] = $text;
}
mysqli_free_result($res);
//choosing the text for start, we can have more, so choose one randomly
$fazaid = (int)mt_rand(0,$i-1);
$txt = $texts[$fazaid];


//add lineups

//here we have competition id $competitieid
//if is different from 0, is official game

$formatia1 = "$nameechipa1: ";
//echipa1
$sql = "SELECT a.playerId, a.post, LEFT(b.fname,1), LEFT(b.lname,7) 
		FROM echipastart a
		LEFT OUTER JOIN player b
		ON a.playerId=b.id
		WHERE a.grupa=$grupa AND a.userId=$team1 ORDER BY a.post ASC";
$res = mysqli_query($GLOBALS['con'],$sql);
$pozitii = array();
$jucatori = array();
$i=0;
$findex=0;
$mindex=0;
$aindex=0;
$fs=$fd=$f1=$f2=$f3=$ml=$mc=$mr=$m1=$m2=$m3=$a1=$a2=$a3="";
while(list($e_pid, $e_post, $juc_fname, $juc_lname)=mysqli_fetch_row($res)) {
	if($e_post<>0) {
		//pentru moral, a fost titular, plus resetez contorul de meciuri oficiale
		$s = "UPDATE moral SET contor1=0 WHERE playerid=$e_pid";
		mysqli_query($GLOBALS['con'],$s);
		if($competitieid==0)$adun=7;
		else $adun=10;
		$s = "UPDATE player SET moral=CASE
									WHEN moral+$adun>100 THEN 100
									ELSE moral+$adun
									  END
			  where id=$e_pid";
		echo "$s<br/>";
		mysqli_query($GLOBALS['con'],$s);

		$pozitii[$e_pid] = $e_post;
		$jucatori[$e_pid] = $juc_fname.'.'.$juc_lname;
		switch ($e_post) {
			case 1: $pos = "GK"; $portar = $juc_lname; break;
			case 2: $pos = "DR"; $fd = $juc_lname;break;
			case 3: 
				$pos = "DC"; 
				switch($findex) {
					case 0: $f1=$juc_lname; break;
					case 1: $f2=$juc_lname; break;
					case 2: $f3=$juc_lname; break;
				}
				$findex++;
				break;
			case 4: $pos = "DL"; $fs = $juc_lname; break;
			case 5: $pos = "MR"; $mr = $juc_lname; break;
			case 6: 
				$pos = "MC"; 
				switch($mindex) {
					case 0: $m1=$juc_lname; break;
					case 1: $m2=$juc_lname; break;
					case 2: $m3=$juc_lname; break;
				}
				$mindex++;
				
				break;
			case 7: $pos = "ML"; $ml = $juc_lname; break;
			case 8: 
				$pos = "FR"; 
				switch($aindex) {
					case 0: $a1=$juc_lname; break;
					case 1: $a2=$juc_lname; break;
					case 2: $a3=$juc_lname; break;
				}
				$aindex++;
				break;
			case 9: 
				$pos = "FC"; 
				switch($aindex) {
					case 0: $a1=$juc_lname; break;
					case 1: $a2=$juc_lname; break;
					case 2: $a3=$juc_lname; break;
				}
				$aindex++;
				
				break;
			case 10: 
				$pos = "FL"; 
				switch($aindex) {
					case 0: $a1=$juc_lname; break;
					case 1: $a2=$juc_lname; break;
					case 2: $a3=$juc_lname; break;
				}
				$aindex++;
				
				break;
			}
		$formatia1 .= "$juc_fname.$juc_lname($pos)";
		if($i<10) $formatia1 .= "-";
		$i++;
	} else {
		//e_post==0, adica nu apare in formatia de start
		//trebuie sa-i adaug la contor
		if($competitieid<>0) {
			//e meci oficial, bag la contor
			$s = "UPDATE moral SET contor1=contor1+1 WHERE playerid=$e_pid";
			echo "ADAUG la nejucate::      $s<br/>";
			mysqli_query($GLOBALS['con'],$s);
		} 
		
	}
}
mysqli_free_result($res);
$formatia1 .= '<br/>'; 
 
//echipa2
$formatia2 = "$nameechipa2: ";
$sql = "SELECT a.playerId, a.post, LEFT(b.fname,1), LEFT(b.lname,7) 
		FROM echipastart a
		LEFT OUTER JOIN player b
		ON a.playerId=b.id
		WHERE a.grupa=$grupa AND a.post<>0 AND a.userId=$team2 ORDER BY a.post ASC";
echo "Echipa 2: $sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
$pozitii = array();
$jucatori = array();
$i=0;
$findex=0;
$mindex=0;
$aindex=0;
$fs=$fd=$f1=$f2=$f3=$ml=$mc=$mr=$m1=$m2=$m3=$a1=$a2=$a3="";
while(list($e_pid, $e_post, $juc_fname, $juc_lname)=mysqli_fetch_row($res)) {
	$pozitii[$e_pid] = $e_post;
	$jucatori[$e_pid] = $juc_fname.'.'.$juc_lname;
	switch ($e_post) {
		case 1: $pos = "GK"; $portar = $juc_lname; break;
		case 2: $pos = "DR"; $fd = $juc_lname;break;
		case 3: 
			$pos = "DC"; 
			switch($findex) {
				case 0: $f1=$juc_lname; break;
				case 1: $f2=$juc_lname; break;
				case 2: $f3=$juc_lname; break;
			}
			$findex++;
			break;
		case 4: $pos = "DL"; $fs = $juc_lname; break;
		case 5: $pos = "MR"; $mr = $juc_lname; break;
		case 6: 
			$pos = "MC"; 
			switch($mindex) {
				case 0: $m1=$juc_lname; break;
				case 1: $m2=$juc_lname; break;
				case 2: $m3=$juc_lname; break;
			}
			$mindex++;
			
			break;
		case 7: $pos = "ML"; $ml = $juc_lname; break;
		case 8: 
			$pos = "FR"; 
			switch($aindex) {
				case 0: $a1=$juc_lname; break;
				case 1: $a2=$juc_lname; break;
				case 2: $a3=$juc_lname; break;
			}
			$aindex++;
			break;
		case 9: 
			$pos = "FC"; 
			switch($aindex) {
				case 0: $a1=$juc_lname; break;
				case 1: $a2=$juc_lname; break;
				case 2: $a3=$juc_lname; break;
			}
			$aindex++;
			
			break;
		case 10: 
			$pos = "FL"; 
			switch($aindex) {
				case 0: $a1=$juc_lname; break;
				case 1: $a2=$juc_lname; break;
				case 2: $a3=$juc_lname; break;
			}
			$aindex++;
			
			break;
}
	$formatia2 .= "$juc_fname.$juc_lname($pos)";
	if($i<10) $formatia2 .=  "-";
	$i++;
}
mysqli_free_result($res);
$formatia2 .= '<br/>'; 

$txt = "$txt<br/> Formatii de start:<br/><br/>$formatia1<br/>$formatia2<br/><br/>Un numar de $nrspect spectatori au decis sa vina la stadion sa urmareasca aceasta partida! Gazdele au $socios1 socios, iar oaspetii $socios2!"; 
 echo "$txt<br/>";
//avem textul, il introducem in bd, in gametext : gameID, text, atacator, minut
$sql = "INSERT INTO gametext(gameID, text, atacator, minut, gol, minutreal)
		VALUES($gameID, '$txt', 0, '12:00', 2,1)";
echo "$sql<br/>";
mysqli_query($GLOBALS['con'],$sql);


//pauza
$sql = "SELECT id, text FROM tplmessages WHERE tip=3";
$res = mysqli_query($GLOBALS['con'],$sql);

$texts = array();
$i=0;
while(list($id, $text) = mysqli_fetch_row($res)) {
	$texts[$i++] = $text;
}
mysqli_free_result($res);
//alegerea fazei pe care o punem la inceputul partidei ( pot fi mai multe texte pt inceput de partida - se alege doar unul)
$fazaid = (int)mt_rand(0,$i-1);
$txt = $texts[$fazaid];
//avem textul, il introducem in bd, in gametext : gameID, text, atacator, minut
$sql = "INSERT INTO gametext(gameID, text, atacator, minut, gol, minutreal)
		VALUES($gameID, '$txt', 0, '12:45', 3,45)";
mysqli_query($GLOBALS['con'],$sql);

//start repriza secunda
$sql = "SELECT id, text FROM tplmessages WHERE tip=6";
$res = mysqli_query($GLOBALS['con'],$sql);

$texts = array();
$i=0;
while(list($id, $text) = mysqli_fetch_row($res)) {
	$texts[$i++] = $text;
}
mysqli_free_result($res);
//alegerea fazei pe care o punem la inceputul reprizei II
$fazaid = (int)mt_rand(0,$i-1);
$txt = $texts[$fazaid];
//avem textul, il introducem in bd, in gametext : gameID, text, atacator, minut
$sql = "INSERT INTO gametext(gameID, text, atacator, minut, gol, minutreal)
		VALUES($gameID, '$txt', 0, '13:00', 2,46)";
echo "$sql<br/>";
mysqli_query($GLOBALS['con'],$sql);


//final
$sql = "SELECT id, text FROM tplmessages WHERE tip=4";
$res = mysqli_query($GLOBALS['con'],$sql);

$texts = array();
$i=0;
while(list($id, $text) = mysqli_fetch_row($res)) {
	$texts[$i++] = $text;
}
mysqli_free_result($res);
//alegerea fazei pe care o punem la inceputul partidei ( pot fi mai multe texte pt inceput de partida - se alege doar unul)
$fazaid = (int)mt_rand(0,$i-1);
$txt = $texts[$fazaid];


//trebuie sa am in vedere si numarul de jucatori din echipa
$posesie1 = round($Vmf1/($Vmf1+$Vmf2) * 100,1);
$posesie2 = round($Vmf2/($Vmf1+$Vmf2) * 100,1);

$posesia = "Posesia jocului. $nameechipa1: " . round($Vmf1/($Vmf1+$Vmf2) * 100,1) . " procente ";
$posesia .= ". $nameechipa2: " . round($Vmf2/($Vmf1+$Vmf2) * 100,1) . " procente ";
echo "$posesia";
$txt = str_replace("^STATISTICA_POSESIE^", $posesia, $txt);
echo "$txt";
//avem textul, il introducem in bd, in gametext : gameID, text, atacator, minut
$sql = "INSERT INTO gametext(gameID, text, atacator, minut, gol, minutreal)
		VALUES($gameID, '$txt', 0, '13:45', 4,90)";
mysqli_query($GLOBALS['con'],$sql);


//generare faze care se incheie cu gol
//in minute1 si minute2 se afla minutele golurilor
//momentan, sanse pentru gol, egale pentru toti jucatorii, cu exceptia portarului

//marcatori echipa 1
for ($ii=0; $ii<count($minute1); $ii++) {
	
	$idjuc = (int)mt_rand(0,$formula1-1);
	//$juc[$idjuc] -id-ul
	
	$sql = "SELECT id, text FROM tplmessages WHERE tip=1";
	$res = mysqli_query($GLOBALS['con'],$sql);

	$texts = array();
	$i=0;
	while(list($id, $text) = mysqli_fetch_row($res)) {
		$texts[$i++] = $text;
	}
	mysqli_free_result($res);

	$fazaid = (int)mt_rand(0,$i-1);
	$txt = $texts[$fazaid];
//aici e cu marcatorul
	$marcator = $pren[$idjuc] . " ". $nu[$idjuc];
	$txt = str_replace("^1E1^", $marcator, $txt);

	if($minute1[$ii] >= 45) {
		$m = $minute1[$ii] % 45;
		if($m<10) $m = "0".$m;
		$mintrodus = "13:$m";
	} else {
		if($minute1[$ii]<10) $m = "0".$minute1[$ii];
		else $m = $minute1[$ii];
		$mintrodus = "12:".$m;
	}
	$sql = "INSERT INTO gamedetail(gameID, playerid, minut, echipa, actiune)
			VALUES($gameID, ".$juc[$idjuc].", ".$minute1[$ii].",1,1)";
	echo "MARCATOR: $sql<br/>";
	mysqli_query($GLOBALS['con'],$sql);
	
	
	//avem textul, il introducem in bd, in gametext : gameID, text, atacator, minut
	$sql = "INSERT INTO gametext(gameID, text, atacator, minut, gol, minutreal)
			VALUES($gameID, '$txt', $team1, '".$mintrodus."', 1, ".$minute1[$ii].")";
	mysqli_query($GLOBALS['con'],$sql);
}


//marcatori echipa 2
for ($ii=0; $ii<count($minute2); $ii++) {
	$idjuc = (int)mt_rand(0,$formula2-1);

	$sql = "SELECT id, text FROM tplmessages WHERE tip=1";
	$res = mysqli_query($GLOBALS['con'],$sql);

	$texts = array();
	$i=0;
	while(list($id, $text) = mysqli_fetch_row($res)) {
		$texts[$i++] = $text;
	}
	mysqli_free_result($res);

	$fazaid = (int)mt_rand(0,$i-1);
	$txt = $texts[$fazaid];

	$marcator = $pren2[$idjuc] . " ". $nu2[$idjuc];


	$txt = str_replace("^1E1^", $marcator, $txt);
	
	if($minute2[$ii] >= 45) {
		$m = $minute2[$ii] % 45;
		if($m<10) $m = "0".$m;
		$mintrodus = "13:$m";
	} else {
		if($minute2[$ii]<10) $m = "0".$minute2[$ii];
		else $m = $minute2[$ii];
		$mintrodus = "12:".$m;
	}
	$sql = "INSERT INTO gamedetail(gameID, playerid, minut, echipa, actiune)
			VALUES($gameID, ".$juc2[$idjuc].", ".$minute2[$ii].",2,1)";
	echo "MARCATOR: $sql<br/>";
	mysqli_query($GLOBALS['con'],$sql);


	//avem textul, il introducem in bd, in gametext : gameID, text, atacator, minut
	$sql = "INSERT INTO gametext(gameID, text, atacator, minut, gol, minutreal)
			VALUES($gameID, '$txt', $team2, '".$mintrodus."', 1,".$minute2[$ii].")";
	mysqli_query($GLOBALS['con'],$sql);
}

$contor1 = intval(70*$posesie1/100);
$contor2 = 70-$contor1;

echo "Valori contor: " . $contor1 . "   " . $contor2;

//introducere faze de poarta, una pe minut pentru fiecare formatie
for($c=1;$c<90;$c++) {
	//$minut = intval(mt_rand(0,90));
	switch($c) {
	case 0: $minut=1; break;
	case 45: $minut=44; break;
	case 90: $minut=89; break;
	default:
		$minut = $c; break;
    }
	
	//generez direct ora la care se afiseaza comentariu
	//prima repriza incepe la ora 12
	//a doua, la ora 13
	$minreal = $minut;
	if($minut >= 45) {
		$rep2min = $minut % 45;
		if($rep2min <10) $rep2min = "0".$rep2min;
		$minut = "13:".$rep2min;
	} else {
		if($minut <10) $minut = "0".$minut;
		$minut = "12:".$minut;
	}

	$skip1 = $skip2 = 0;
	if($contor1>$contor2) {
	//posesie mai buna pentru prima echipa
	//trebuie sarite citeva actiuni adverse, sa nu aiba minute
		$r2 = mt_rand(0,100);
		if($r2>77) $skip2 = 1;
	} else {
		$r1 = mt_rand(0,100);
		if($r1>77) $skip1 = 1;
	}
	if($skip1 == 0)
		calcul(1, $minut, $team1, $team2, $gameID, $minreal);
	if($skip2 == 0)
		calcul(2, $minut, $team1, $team2, $gameID, $minreal);
  }

  	Changes($team1, $minute1, $minute2, $gameID, 1);
	Changes($team2, $minute1, $minute2, $gameID, 2);
  
} //end function ComputeTheScore

global $formula1;
global $formula2;


$sql = "SELECT a.id, a.userId_1, a.userId_2, a.grupavirsta 
		FROM invitatiemeci a 
		WHERE a.accepted=1 and a.datameci='".Date("Y-m-d")."' ORDER BY a.id ASC";

echo "$sql<br/>";		
$resdoi = mysqli_query($GLOBALS['con'],$sql);
echo "Generare meciuri...";
$i=0;


while(list($idmeci, $ec1, $ec2, $gr) = mysqli_fetch_row($resdoi)) {
	$gameID=$idmeci;
	
	//verific daca are macar 7 jucatori
	$nrjuc1 = getTeamComplete($ec1, $gr);
	echo "Echipa 1 are $nrjuc1 jucatori in echipa de start<br/>";
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
		$sscor = "UPDATE invitatiemeci
				SET scor='".$scor."'
				WHERE id=$gameID";
		echo "$sscor<br/>";
		mysqli_query($GLOBALS['con'],$sscor);
	
		//textul pentru masa verde
		if($nrjuc2<7) $pierz = "oaspetii";
		if($nrjuc1<7) $pierz = "gazdele";
		
		$sql = "INSERT INTO gametext(gameID, text, atacator, minut, gol, minutreal)
			VALUES($gameID, 'Meciul a fost castigat la masa verde, pentru ca $pierz nu s-au prezentat la meci!', 0, '12:00', 0, 90)";
		mysqli_query($GLOBALS['con'],$sql);
	}
	if($skip==0) ComputeTheScore($gameID, $_SEZON);
	$i++;
}
echo "<br/>Generate $i meciuri!";
mysqli_free_result($resdoi);

function getTeamComplete($teamid, $gr) {
	$s = "SELECT id FROM echipastart WHERE grupa=$gr AND userid=$teamid and post<>0";
	$r = mysqli_query($GLOBALS['con'],$s);
	$nrjuc = mysql_num_rows($r);
	mysqli_free_result($r);
	return $nrjuc;
}

function NumarSpectatori($stadionID, $team1, $rat_1, $rat_2, $competitieid,$sezon) {
	//update bani incasati dupa bilete
	//trebuie stabilit si numarul de spectatori care asista la meci
	//trebuie in functie de ratingurile echipelor implicate, vremea de afara si pretul la bilete.
	
	$stad = new Stadium($stadionID);
	$capacitate = $stad->Disponibil();
	$pretb = $stad->Pret;
	
	/*
	$sql = "SELECT b.pret, b.capacity
			FROM user a
			LEFT OUTER JOIN stadium b
			ON a.stadiumid=b.id
			WHERE a.id=$team1";
			
	echo "$sql<br/>";
	$res = mysqli_query($GLOBALS['con'],$sql);
	list($pretb, $capacitate)=mysqli_fetch_row($res);
	*/
	
	//am $rat_1 si $rat_2, rating-urile celor doua echipe
	//am si $pretb, trebuie sa gasesc o formula care sa le lege pe toate 3, sa dea o proportie de ocupare a tribunelor
	//greetings to lulu
	$pretok = 9+$rat_1/25+($rat_2-$rat_1)/25;
	//pt amical (competitieid==0), pretul agreat sa fie jumate
	if($competitieid==0) $pretok = intval($pretok/2);
	if($pretb==0)$pretb=1;
	$nrspect = intval($pretok*$pretok*$capacitate/($pretb*$pretb));
	if($nrspect > $capacitate) $nrspect=$capacitate;
	//$nrspect = mt_rand(1,$capacitate); //trebuie luata capacitatea stadionului primei echipe
	
	$sumabilete = $nrspect*$pretb;
	echo "NUMAR SPECT: $nrspect/$capacitate COMPETITIE: $competitieid :: $sumabilete<br/>";
	mysqli_free_result($res);


	$sql = "INSERT INTO balanta (userid, motiv, suma, sezon)
		VALUES($team1, 'Bilete', $sumabilete, $sezon)";
	echo "$sql<br/>";
	mysqli_query($GLOBALS['con'],$sql);
	
	$sql = "UPDATE user
			SET Funds = Funds+$sumabilete 
			WHERE id=$team1";
	mysqli_query($GLOBALS['con'],$sql);

	
	return "$nrspect::$capacitate";
}

function Moral($scor, $team1, $team2) {
	
	//modific moralul in functie de scorul partidei
	
	list($sc1,$sc2) = split(':', $scor);
	//partea cu moralul, sa creasca/scada in functie de victorie/infringere la mai mult de 5 goluri
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

?>