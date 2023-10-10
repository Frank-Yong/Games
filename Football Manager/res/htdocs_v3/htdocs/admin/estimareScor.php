<?php
//estimare scor brut, fara schimbari


include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

//vine meciID
//$meciID = 13;


/****************************************************************************************************
*****************************************************************************************************
****************************************************************************************************/
function ComputeTeamValues($uid) {
		//valoarea ofensiva
		//se calculeaxa astfel
		// = Maxim(Atacanti)+ 0.7*Medie(Atacanti) + ValoareOfensivaMijlocasi * .4 + VOfensivaFundasi * .4
		$Vof = 0;
		$Vdef = 0;
		$Vmijloc = 0;
		$fw_max = 0;
		$fw_medie = 0;
		$fw_index=0;

		$sql = "SELECT b.PlayerId, c.Position, b.post, d.tactica, d.mijlocul, d.atacuri, d.pase
				FROM user a
				LEFT OUTER JOIN echipastart b 
				ON b.UserId = a.id
				LEFT OUTER JOIN player c 
				ON c.id = b.PlayerId
				LEFT OUTER JOIN tactica d
				ON a.id=d.userid
				WHERE b.post<>0 AND a.id = $uid";
		//echo "$sql<br/>";
				$res = mysql_query($sql);

		$fundasi = 1;
		$mijlocasi = 1;
		$atacanti = 1;
		
		while(list($p_id, $p_position, $efolositca, $tactica, $mijlocul, $atacuri, $pase) = mysql_fetch_row($res)) {
			//echo "Player $p_id<br/>";
			$pl = new Player($uid, $p_id);
			//$pl->EchoPlayer();
			//trebuie sa iau in considerare si pozitia jucatorului si pe ce post e pus sa joace
			//daca este DC si e pus pe DL, sa scad 10% din eficienta lui pe acel post
			//daca e DC si e pus MC, sa scad 30% din eficienta
			//daca e DC si e pus FC, sa scad 60% din valori
			//daca e DC si e pus GK, sa scad 60%
			$df = $pl->GetDFWork();
			$mf = $pl->GetMFWork();
			$fw = $pl->GetFWWork();
			if(!$tactica) $tactica=1;
			if(!$mijlocul) $mijlocul=1;
			if(!$atacuri) $atacuri=1;
			if(!$pase) $pase=1;
			
			if($mijlocul == 1) {
				//abordare normala, ramine neschimbat
			}
			if($mijlocul == 2) {
				//abordare ofensiva
				//scad valorile pentru aparare si le cred pe cele de atac
				$df = $df *.92;
				$mf = $mf * 1.08;
				$fw = $fw * 1.13;
			}
			if($mijlocul == 3) {
				//abordare defensiva
				//scad valorile pentru atac si le cred pe cele de aparare
				$df = $df *1.17;
				$mf = $mf * 0.98;
				$fw = $fw * 0.93;
			}
			
			if($p_position != $efolositca) {
				//nu este folosit pe postul lui
				switch($p_position) {
					case 1: 
						//e portar si e folosit in teren
						if($efolositca>1) {
						$df = $df*.3;
						$mf = $mf*.15;
						$fw = $fw*.1;
						}
						break;
					case 2:
						//e fundas lateral
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
						//e fundas central
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
						//e fundas lateral
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
						//e mijlocas lateral
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
						//e mijlocas central
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
						//e mijlocas lateral
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
						//e atacant lateral
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
						//e atacant central
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
						//e atacant lateral
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
			//dc este atacant, sa ia maximul si sa faca media
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

		//echo "Valoarea ofensiva totala este: $Vof";
		//echo "<br/>Valoarea mijloc totala este: $Vmijloc";
		//echo "<br/>Valoarea defensiva totala este: $Vdef";
		mysql_free_result($res);

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
	
$sql = "SELECT a.userId_1, a.userId_2, b.TeamName, c.TeamName, a.scor, d.numar, e.numar, f.nume, a.competitieid, a.id 
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
		WHERE a.id=$meciID";
echo "$sql<br/>";
$res = mysql_query($sql);
list($echipa1, $echipa2, $numeechipa1, $numeechipa2, $scor, $socios1, $socios2, $compnume, $competitieid, $meciid) = mysql_fetch_row($res);
mysql_free_result($res);


//coeficient echipa gazda: 5%
//coeficient de noroc - mt_random pt ambele echipe. cine are mai mare valoarea, ia 5%, cealalta echipa nu ia nimic
//echipa 1 este echipa gazda
	$_noroc2 = 1;
	$_noroc1 = 1;

//preluare tactica pentru cele doua formatii
$tac = array();
$sqltac1 = "SELECT userid, tactica, mijlocul, atacuri, pasare 
			FROM tactica 
			WHERE userid=$echipa1 or userid=$echipa2";
$restac1 = mysql_query($sqltac1);
while(list($ec, $tactica, $mij, $atacuri, $pasare)=mysql_fetch_row($restac1)) {
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
mysql_free_result($restac1);

//implicare antrenor -> tactic si motivare
//daca nu are, pun implicit pe 1
$sql = "SELECT a.userid, b.Motivation, b.Tactical
		FROM usertrainer a
		LEFT JOIN trainer b
		ON b.id=a.trainerid
		WHERE a.userid=$echipa1 OR a.userid=$echipa2";
$restrai = mysql_query($sql);
while(list($ust, $mot, $tacti) = mysql_fetch_row($restrai)) {
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
mysql_free_result($restrai);

if(empty($mot1)) $mot1=1;
if(empty($mot2)) $mot2=1;
if(empty($tacti1)) $tacti1=1;
if(empty($tacti2)) $tacti2=1;

//echipa 1
//echo "$numeechipa1...<br/>";

	$prostii1 = ComputeTeamValues($echipa1, 1);
	//Valorile pentru echipa 1
	//$user1->Vfw; $user1->Vmf; $user1->Vdf
	list($vfw1,$vmf1,$vdf1)=split("::",$prostii1);
	//echo "Echipa 1: $prostii1 :::::: $echipa1 -----> $vfw1 -- $vmf1 -- $vdf1<br/>";
	
	
	$ant1  = $mot1*0.06+$tacti1*.08;
	//echo "Profit antrenor 1: $ant1<br/>";
	

	//echo "<br/><br/>$numeechipa2...<br/>";
	$prostii2 = ComputeTeamValues($echipa2, 2);
	list($vfw2,$vmf2,$vdf2)=split("::",$prostii2);
	//echo "Echipa 2: $prostii2 :::::: $echipa2 -----> $vfw2 -- $vmf2 -- $vdf2<br/>";

	//procent ocupare tribune
	//in cazul in care se umple stadionul peste 80%, sa se ofere
	$jucator12=1;
	
	$Vfw1 = $vfw1 * $jucator12 * $_noroc1 +$ant1;
	$Vmf1 = $vmf1 * $jucator12 * $_noroc1 +$ant1;
	$Vdf1 = $vdf1 * $jucator12 * $_noroc1 +$ant1;

	//echo "Valori echipa 1: $Vfw1 :: $Vmf1 :: $Vdf1<br/>";
	
	
	//echo "Sunt aici!";
	//Valorile pentru echipa 2
	//$user2->Vfw; $user2->Vmf; $user2->Vdf

	
	
	$ant2  = $mot2*0.06 + $tacti2*.08;
	//echo "Profit antrenor 2: $ant2<br/>";

	
	$Vfw2 = $vfw2 * $_noroc2 +$ant2;
	$Vmf2 = $vmf2 * $_noroc2 +$ant2;
	$Vdf2 = $vdf2 * $_noroc2 +$ant2;

	//echo "Valori echipa 2: $Vfw2 :: $Vmf2 :: $Vdf2<br/>";

	//posesia: x/(x+y) * 100 si y/(x+y) * 100

	$pas = 0.07;
	//cite goluri inscrie echipa 1
	
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
	//echo "PROCENT APARARE: $prDef<br/>";
	
	//echo "BONUS1: $bonus1 :::: BINUS2: $bonus2<br/>";
	//cite goluri inscrie echipa 1
	$goluri1 = (int)(($Vfw1/$Vdf2 - 1)/$pas);
	$goluri1 = (int)(($Vfw1+$Vmf1*.53)/$Vdf2*$prDef-$aleator+$bonus1*.37);

	//cite goluri inscrie echipa 2
	$goluri2 = (int)(($Vfw2/$Vdf1 - 1)/$pas);
	$goluri2 = (int)(($Vfw2+$Vmf2*.53)/$Vdf1*$prDef-$aleator+$bonus2*.37);

	
	if ($goluri1<=0) {
		$goluri1=0;
		//sa fie mai palpitant adaug goluri atit la gazde, cit si la oaspeti, incaz de goluri1=0
		//la fel si daca goluri2=0;
		$deadaugat = mt_rand(1,3);
		$goluri2 += $deadaugat;
		$goluri1 += $deadaugat;
		//echo "Am aduagat $deadaugat pentru ca goluri1 era 0<br/>";
	}
	echo "<br/>$numeechipa1 inscrie $goluri1 goluri";

	if ($goluri2<=0) {
		$goluri2=0;
		//sa fie mai palpitant adaug goluri atit la gazde, cit si la oaspeti, incaz de goluri2=0
		//la fel si daca goluri1=0;
		$deadaugat = mt_rand(1,3);
		$goluri2 += $deadaugat;
		$goluri1 += $deadaugat;
		//echo "Am aduagat $deadaugat pentru ca goluri2 era 0<br/>";
	}
	
		//stabilire marcatori
	//ar trebui luati dintre atacanti si mijlocasi, acolo este probabilitatea cea mai mare de a marca

	//minutele in care s-a marcat

	echo "<br/>$numeechipa2 inscrie $goluri2 goluri";
	$sc= "$goluri1:$goluri2";
	$s1 = "INSERT INTO estimarescor(meciid,userid,scor,data)
		VALUES($meciid, ".$_SESSION['USERID'].",'$sc','".Date('Y-m-d H:i:s')."')";
	mysql_query($s1);

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

$nextday = date('Y-m-d',strtotime("+2 days"));

$eid = -1;
$sql = "SELECT id, scor
		FROM estimarescor
		WHERE userid=".$_SESSION['USERID'];
echo "$sql<br/>";
$res = mysql_query($sql);
list($eid, $estimare) = mysql_fetch_row($res);

if(is_null($eid))$eid=-1;		
echo "EID $eid<br/>";		
		
if($eid<0) {
	$sql = "SELECT a.id, a.userId_1, a.userId_2 
			FROM invitatiemeci a 
			WHERE a.accepted=1 and (a.userId_1=".$_SESSION['USERID']." or a.userId_2=".$_SESSION['USERID']. ") and a.datameci='".$nextday."' ORDER BY a.id ASC";

	echo "$sql<br/>";		
	$resdoi = mysql_query($sql);
	//echo "Generare meciuri...";
	$i=0;


	while(list($idmeci, $ec1, $ec2) = mysql_fetch_row($resdoi)) {
		$meciID=$idmeci;
		
		//verific daca are macar 7 jucatori
		$nrjuc1 = getTeamComplete($ec1);
		//echo "Echipa 1 are $nrjuc1 jucatori in echipa de start<br/>";
		$nrjuc2 = getTeamComplete($ec2);
		//echo "Echipa 2 are $nrjuc2  in echipa de start<br/>";
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
			if($nrjuc2<7) $pierz = "oaspetii";
			if($nrjuc1<7) $pierz = "gazdele";
			
		}
		if($skip==0) calculareScor($meciID, $_SEZON);
		$i++;
	}
	//echo "<br/>Generate $i meciuri!";
	mysql_free_result($resdoi);
} else {
	echo "Scor estimat: $estimare!<br/>";
}

function getTeamComplete($echipaid) {
	$s = "SELECT id FROM echipastart WHERE userid=$echipaid and post<>0";
	$r = mysql_query($s);
	$nrjuc = mysql_num_rows($r);
	mysql_free_result($r);
	return $nrjuc;
}


function Moral($scor, $echipa1, $echipa2) {
	
}

?>