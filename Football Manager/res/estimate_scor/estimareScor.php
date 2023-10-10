<?php
//estimate score, without any changes

/*
include('app.conf.php');
include('player.php');
include('UserStadium.php');
include('trainer.php');
*/


/****************************************************************************************************
*****************************************************************************************************
****************************************************************************************************/
function ComputeTeamValues($uid, $grupa) {
		//attacking value
		//it computes like this
		// = Maxim(Forwards)+ 0.7*Average(Forwards) + Midfielders_attacking_value * .4 + Defenders_Attacking_value * .4
		$Vof = 0;
		$Vdef = 0;
		$Vmijloc = 0;
		$fw_max = 0;
		$fw_medie = 0;
		$fw_index=0;

		$sql = "SELECT b.PlayerId, c.Position, b.post, d.tactics, d.midfield, d.atacks, d.passes
				FROM user a
				LEFT OUTER JOIN lineup b 
				ON b.UserId = a.id
				LEFT OUTER JOIN player c 
				ON c.id = b.PlayerId
				LEFT OUTER JOIN tactics d
				ON a.id=d.userid
				WHERE b.pgroup=$grupa AND b.post<>0 AND a.id = $uid";
		//echo "$sql<br/>";
				$res = mysqli_query($GLOBALS['con'],$sql);

		$fundasi = 1;
		$mijlocasi = 1;
		$atacanti = 1;
		
		while(list($p_id, $p_position, $efolositca, $tactica, $mijlocul, $atacuri, $pase) = mysqli_fetch_row($res)) {
			//echo "Player $p_id<br/>";
			$pl = new Player($uid, $p_id);
			//$pl->EchoPlayer();
			//take in consideration the position of the player and what position is he set to play on
			//if he is DC and he plays as DL, decrease 10% from his value
			//if he is DC and he plays as MC, decrease 30%
			//if he is DC and he plays s FC, decrease 60%
			//he is DC and plays as GK, decrease 60%
			$df = $pl->GetDFWork();
			$mf = $pl->GetMFWork();
			$fw = $pl->GetFWWork();
			if(!$tactica) $tactica=1;
			if(!$mijlocul) $mijlocul=1;
			if(!$atacuri) $atacuri=1;
			if(!$pase) $pase=1;
			
			if($mijlocul == 1) {
				//normal, no change
			}
			if($mijlocul == 2) {
				//attacking
				//decrease deffensive values and increase attacking values
				$df = $df *.92;
				$mf = $mf * 1.08;
				$fw = $fw * 1.13;
			}
			if($mijlocul == 3) {
				//deffensive tactic
				//decrease the values for attacking  and increase the ones for defense
				$df = $df *1.17;
				$mf = $mf * 0.98;
				$fw = $fw * 0.93;
			}
			
			if($p_position != $efolositca) {
				//he is not on his position
				switch($p_position) {
					case 1: 
						//he is GK and he is used on the field
						if($efolositca>1) {
						$df = $df*.3;
						$mf = $mf*.15;
						$fw = $fw*.1;
						}
						break;
					case 2:
						//he is DL or DR
						switch($efolositca) {
							case 1: $df = $df*.3;$mf=0;$fw=0;break;
							case 3: $df = $df*.85; break;
							case 4: $df = $df*.9; break;
							case 5: 
							case 6:
							case 7:
							$df = $df*.4; $mf = $mf*1.25; $fw = $fw *1.15; break;
							case 8:
							case 9:
							case 10:
							$df = $df*.2; $mf = $mf*1.1; $fw = $fw * 1.25; break;
						}					
						break;
					case 3:
						//he is DC
						switch($efolositca) {
							case 1: $df = $df*.3;$mf=0;$fw=0;break;
							case 2: $df = $df*.9; break;
							case 4: $df = $df*.9; break;
							case 5: 
							case 6:
							case 7:
							$df = $df*.4; $mf = $mf*1.25; $fw = $fw *1.15; break;
							case 8:
							case 9:
							case 10:
							$df = $df*.2; $mf = $mf*1.1; $fw = $fw * 1.25; break;
						}					
						break;
					case 4:
						//he is DL or DR
						switch($efolositca) {
							case 1: $df = $df*.3;$mf=0;$fw=0;break;
							case 2: $df = $df*.9; break;
							case 3: $df = $df*.85; break;
							case 5: 
							case 6:
							case 7:
							$df = $df*.4; $mf = $mf*1.25; $fw = $fw *1.15; break;
							case 8:
							case 9:
							case 10:
							$df = $df*.2; $mf = $mf*1.1; $fw = $fw * 1.25; break;
						}					
						break;
					case 5:
						//he is ML or MR
						switch($efolositca) {
							case 1: $df = $df*.2;$mf=0;$fw=0;break;
							case 2: 
							case 3: 
							case 4:
									$df = $df * 1.25; $mf = $mf*.85; $fw = $fw * .9; break;
							case 6:
									$df = $df *.95; $mf = $mf * .9; break;
							case 7:
									$df = $df*.95; $mf = $mf *.95; break;
							case 8:
							case 9:
							case 10:
								$df = $df*.6; $mf = $mf*0.7; $fw = $fw * 1.25; break;
						}					
						break;
					case 6:
						//he is MC
						switch($efolositca) {
							case 1: $df = $df*.2;$mf=0;$fw=0;break;
							case 2: 
							case 3: 
							case 4:
									$df = $df * 1.25; $mf = $mf*.85; $fw = $fw * .9; break;
							case 5:
									$df = $df *.95; $mf = $mf * .95; break;
							case 7:
									$df = $df*.95; $mf = $mf *.95; break;
							case 8:
							case 9:
							case 10:
								$df = $df*.6; $mf = $mf*0.7; $fw = $fw * 1.25; break;
						}					
						break;
					case 7:
						//he is ML or MR
						switch($efolositca) {
							case 1: $df = $df*.2;$mf=0;$fw=0;break;
							case 2: 
							case 3: 
							case 4:
									$df = $df * 1.25; $mf = $mf*.85; $fw = $fw * .9; break;
							case 6:
									$df = $df *.95; $mf = $mf * .9; break;
							case 5:
									$df = $df*.95; $mf = $mf *.95; break;
							case 8:
							case 9:
							case 10:
								$df = $df*.6; $mf = $mf*0.7; $fw = $fw * 1.25; break;
						}					
						break;
					case 8:
						//he is FL or FR
						switch($efolositca) {
							case 1: $df = $df*.2;$mf=0;$fw=0;break;
							case 2: 
							case 3: 
							case 4:
									$df = $df * 1.25; $mf = $mf*.65; $fw = $fw * .35; break;
							case 5:
							case 6:
							case 7:
									$df = $df*1.15; $mf = $mf *1.35; $fw = $fw *.5; break;
							case 9:
							case 10:
									$df = $df*.95; $mf = $mf*0.95; $fw = $fw * 0.9; break;
						}					
						break;
					case 9:
						//he is FC
						switch($efolositca) {
							case 1: $df = $df*.2;$mf=0;$fw=0;break;
							case 2: 
							case 3: 
							case 4:
									$df = $df * 1.25; $mf = $mf*.65; $fw = $fw * .35; break;
							case 5:
							case 6:
							case 7:
									$df = $df*1.15; $mf = $mf *1.35; $fw = $fw *.5; break;
							case 8:
							case 10:
									$df = $df*.95; $mf = $mf*0.95; $fw = $fw * 0.9; break;
						}					
						break;
					case 10:
						//he is FR or FL
						switch($efolositca) {
							case 1: $df = $df*.2;$mf=0;$fw=0;break;
							case 2: 
							case 3: 
							case 4:
									$df = $df * 1.25; $mf = $mf*.65; $fw = $fw * .35; break;
							case 5:
							case 6:
							case 7:
									$df = $df*1.15; $mf = $mf *1.35; $fw = $fw *.5; break;
							case 8:
							case 9:
									$df = $df*.95; $mf = $mf*0.95; $fw = $fw * 0.9; break;
						}					
						break;
				}
				
			}
			
			$Vdef = $Vdef + $df;
			$Vmijloc = $Vmijloc + $mf;
			//echo "val<br/>";
			//if he is a striker, take the maximum and make the average
			if ($p_position == 8 or $p_position == 9 or $p_position == 10) {
				$fw_max = max($fw_max,$fw);
				$fw_medie = $fw_medie+$fw;
				$fw_index++;
			} else {
				$Vof = $Vof + $fw*.4;
			}
				
		}
		$fw_medie = $fw_medie/$fw_index;
		$Vof = 1.95*($Vof + $fw_max + 0.7*$fw_medie);

		//echo "Total attacking value: $Vof";
		//echo "<br/>The midfiled value is: $Vmijloc";
		//echo "<br/>Defensive value is: $Vdef";
		mysqli_free_result($res);

		return "$Vof::$Vmijloc::$Vdef";

}




function calculareScor($meciID, $_SEZON) {
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
	
$sql = "SELECT a.userId_1, a.userId_2, b.TeamName, c.TeamName, a.score, d.numar, e.numar, f.name, a.competitionid, a.id, b.Moral, c.Moral, a.age 
		FROM gameinvitation a 
		LEFT OUTER JOIN user b
		ON b.id=a.userId_1
		LEFT OUTER JOIN user c
		ON c.id=a.userId_2
		LEFT OUTER JOIN tribuna d
		ON a.userId_1=d.userid
		LEFT OUTER JOIN tribuna e
		ON a.userId_2=e.userid
		LEFT JOIN competition f
		ON f.id=a.competitionid
		WHERE a.id=$meciID";
//echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
list($echipa1, $echipa2, $numeechipa1, $numeechipa2, $scor, $socios1, $socios2, $compnume, $competitieid, $meciid, $moral1, $moral2, $grupa) = mysqli_fetch_row($res);
mysqli_free_result($res);


//coeficient host: 5%
//coeficient "luck" - mt_random for both teams. Who has more, takes 5%, the other - nothing
//team 1 - the host
	$_noroc2 = 1;
	$_noroc1 = 1;

//get the tactics
$tac = array();
$sqltac1 = "SELECT userid, tactics, midfield, atacks, passes 
			FROM tactics 
			WHERE (userid=$echipa1 or userid=$echipa2) and ggroup=$grupa";
//echo "$sqltac1<br/>";			
$restac1 = mysqli_query($GLOBALS['con'],$sqltac1);
while(list($ec, $tactica, $mij, $atacuri, $pasare)=mysqli_fetch_row($restac1)) {
	if($ec == $echipa1) {
		$tac1 = $tactica;
		$mij1 = $mij;
		$atacuri1 = $atacuri;
		$pas1 = $pasare;
	} else {
		$tac2 = $tactica;
		$mij2 = $mij;
		$atacuri2 = $atacuri;
		$pas2 = $pasare;
	}
}
mysqli_free_result($restac1);

//trainer involvement  -> speech and knowhow
//if the team doesn't have = set to 1
$sql = "SELECT a.userid, b.speech, b.knowhow
		FROM usertrainer a
		LEFT JOIN trainer b
		ON b.id=a.trainerid
		WHERE a.userid=$echipa1 OR a.userid=$echipa2";
$restrai = mysqli_query($GLOBALS['con'],$sql);
while(list($ust, $mot, $tacti) = mysqli_fetch_row($restrai)) {
	if($ust == $echipa1) {
		$mot1 = $mot;
		$tacti1 = $tacti;
		//echo "aici ant 1<br/>";
	} else {
		$mot2 = $mot;
		$tacti2 = $tacti;
		//echo "aici ant 2: $mot2 $tacti2<br/>";
	}
}
mysqli_free_result($restrai);

if(empty($mot1)) $mot1=1;
if(empty($mot2)) $mot2=1;
if(empty($tacti1)) $tacti1=1;
if(empty($tacti2)) $tacti2=1;

//echipa 1
//echo "First team: $numeechipa1...<br/>";

	$prostii1 = ComputeTeamValues($echipa1, $grupa);
	//Valorile pentru echipa 1
	//$user1->Vfw; $user1->Vmf; $user1->Vdf
	list($vfw1,$vmf1,$vdf1)=explode("::",$prostii1);
	//echo "Echipa 1: $prostii1 :::::: $echipa1 -----> $vfw1 -- $vmf1 -- $vdf1<br/>";
	
	
	$ant1  = $mot1*0.06+$tacti1*.08;
	//echo "Profit antrenor 1: $ant1<br/>";
	

	//echo "<br/><br/>$numeechipa2...<br/>";
	$prostii2 = ComputeTeamValues($echipa2, $grupa);
	list($vfw2,$vmf2,$vdf2)=explode("::",$prostii2);
	//echo "Echipa 2: $prostii2 :::::: $echipa2 -----> $vfw2 -- $vmf2 -- $vdf2<br/>";

	//percentage tribune occupation
	//in case the stadium is full more than 80%, the 12th player to be awarded to the home team
	$jucator12=1;

	if($moral1<50) $moral1=50;
	
	$Vfw1 = ($vfw1 * $jucator12 * $_noroc1 +$ant1)*$moral1/100;
	$Vmf1 = ($vmf1 * $jucator12 * $_noroc1 +$ant1)*$moral1/100;
	$Vdf1 = ($vdf1 * $jucator12 * $_noroc1 +$ant1)*$moral1/100;

	//echo "Valori echipa 1: $Vfw1 :: $Vmf1 :: $Vdf1<br/>";
	
	
	//echo "Sunt aici!";
	//Valorile pentru echipa 2
	//$user2->Vfw; $user2->Vmf; $user2->Vdf

	
	
	$ant2  = $mot2*0.06 + $tacti2*.08;
	//echo "Profit antrenor 2: $ant2<br/>";

	if($moral2<50) $moral2=50;

	
	$Vfw2 = ($vfw2 * $_noroc2 +$ant2)*$moral2/100;
	$Vmf2 = ($vmf2 * $_noroc2 +$ant2)*$moral2/100;
	$Vdf2 = ($vdf2 * $_noroc2 +$ant2)*$moral2/100;

	//echo "Valori echipa 2: $Vfw2 :: $Vmf2 :: $Vdf2<br/>";

	//posesia: x/(x+y) * 100 si y/(x+y) * 100

	$pas = 0.07;
	//how many goals scores first team
	
	$vt1 = $Vfw1+$Vmf1*.61+$Vdf1*.22;
	$vt2 = $Vfw2+$Vmf2*.61+$Vdf2*.22;
	$bonus1=0;
	$bonus2=0;
	
	$dif=$vt1-$vt2;
	//echo "DIF: $dif<br/>";
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

	//team 1 scores...
	$goluri1 = (int)(($Vfw1/$Vdf2 - 1)/$pas);
	$goluri1 = (int)(($Vfw1+$Vmf1*.53)/$Vdf2*$prDef-$aleator+$bonus1*.37);

	//team 2 scores...
	$goluri2 = (int)(($Vfw2/$Vdf1 - 1)/$pas);
	$goluri2 = (int)(($Vfw2+$Vmf2*.53)/$Vdf1*$prDef-$aleator+$bonus2*.37);

	
	if ($goluri1<=0) {
		$goluri1=0;
		//in case goals scores by team 1 is zero, i add goals to both teams, to be more exciting (goluri1=0)
		//the same if goluri2=0;
		$deadaugat = mt_rand(1,3);
		$goluri2 += $deadaugat;
		$goluri1 += $deadaugat;
		//echo "Am aduagat $deadaugat pentru ca goluri1 era 0<br/>";
	}
	//echo "<br/>$numeechipa1 inscrie $goluri1 goluri";

	if ($goluri2<=0) {
		$goluri2=0;
		$deadaugat = mt_rand(1,3);
		$goluri2 += $deadaugat;
		$goluri1 += $deadaugat;
		//echo "Am aduagat $deadaugat pentru ca goluri2 era 0<br/>";
	}
	
		//descrease the score, if it is too high
	for($i=4;$i<15;$i++) 
		if($goluri1-$goluri2>$i) $goluri1=$goluri1-rand($i-2,$i-1);
	//mai diminuez din scoruri
	for($i=4;$i<15;$i++) 
		if($goluri2-$goluri1>$i) $goluri2=$goluri2-rand($i-2,$i-1);

	
	
	//echo "<br/>$numeechipa2 inscrie $goluri2 goluri";
	$sc= "$goluri1:$goluri2";
	$s1 = "INSERT INTO estimatescore(gameid,userid,scor,data)
		VALUES($meciid, ".$_SESSION['USERID'].",'$sc','".Date('Y-m-d H:i:s')."')";
	mysqli_query($GLOBALS['con'],$s1);
	//echo "<h3>Score estimation: $sc</h3>";
	
	
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
		
	}

	
  
} //end function calculareScor

global $formula1;
global $formula2;

$nextday = date('Y-m-d',strtotime("+1 day"));

$sql = "SELECT a.id, a.userId_1, a.userId_2 
		FROM gameinvitation a 
		WHERE a.accepted=1 and (a.userId_1=".$_SESSION['USERID']." or a.userId_2=".$_SESSION['USERID']. ") and a.gamedate='".$nextday."' ORDER BY a.id ASC";

		//echo "$sql<br/>";
$resdoi = mysqli_query($GLOBALS['con'],$sql);
$i=0;


while(list($idmeci, $ec1, $ec2) = mysqli_fetch_row($resdoi)) {
	$meciID=$idmeci;
	
	//checking to have at least 7 players
	$nrjuc1 = getTeamComplete($ec1);
	//echo "Team 1 has $nrjuc1 players in the line-up<br/>";
	$nrjuc2 = getTeamComplete($ec2);
	//echo "Team 2 has $nrjuc2  in the line-up<br/>";
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
	
		//textul pentru masa verde
		if($nrjuc2<7) $pierz = "guest";
		if($nrjuc1<7) $pierz = "host";
		
	}
	if($skip==0) calculareScor($meciID, $_SEASON);
	else {
		//echo "<h3>Score estimation: $scor</h3>";
		$s1 = "INSERT INTO estimarescor(meciid,userid,scor,data)
			VALUES($meciID, ".$_SESSION['USERID'].",'$scor','".Date('Y-m-d H:i:s')."')";
		//echo "$s1<br/>";
		mysqli_query($GLOBALS['con'],$s1);
	}
	$i++;
}
mysqli_free_result($resdoi);



function getTeamComplete($echipaid) {
	$s = "SELECT id FROM lineup WHERE userid=$echipaid and post<>0";
	$r = mysqli_query($GLOBALS['con'],$s);
	$nrjuc = mysqli_num_rows($r);
	mysqli_free_result($r);
	return $nrjuc;
}


function Moral($scor, $echipa1, $echipa2) {
	
}

?>