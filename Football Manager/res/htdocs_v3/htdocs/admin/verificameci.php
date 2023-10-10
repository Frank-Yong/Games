<?php
error_reporting(63);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

//vine meciID
//$meciID = 13;


function AlegeMarcator($echipa,$care) {
	//1-portar, 2-dr, 3-dc, 4-dl
	//5-mr, 6-mc, 7-ml, 8-fr, 9-fc, 10-fl
	//probabiltate mai mare pentru atacanti si mijlocasi sa inscrie. nu este imposibil insa pentru fundasi
	//totodata, in functie de valoare sa se stabileasca cine inscrie
	if($care == 1) {
		$jucator = $juc;
		$prenume = $pren;
		$nume = $nu;
		$posturi = $pst;
	}
	if($care == 2) {
		$jucator = $juc2;
		$prenume = $pren2;
		$nume = $nu2;
		$posturi = $pst2;
	}
	
	
}


function calcul($at, $min, $echipa1, $echipa2, $meciID) {
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
		$idjuc1 = (int)rand(0,$formula1-1);
		$idjuc2 = (int)rand(0,$formula1-1);
		$idjuc3 = (int)rand(0,$formula2-1);
		$idjuc4 = (int)rand(0,$formula2-1);
	} else {
		//ataca a doua echipa
		$idjuc1 = (int)rand(0,$formula2-1);
		$idjuc2 = (int)rand(0,$formula2-1);
		$idjuc3 = (int)rand(0,$formula1-1);
		$idjuc4 = (int)rand(0,$formula1-1);
	}
	//tip=0 sau 5 - faze normale sau offside
	$sql = "SELECT id, text, tip FROM tplmessages WHERE tip=0 or tip=5";
	$res = mysql_query($sql);

	$texts = array();
	$tips = array();
	$i=0;
	while(list($id, $text, $tip) = mysql_fetch_row($res)) {
		$texts[$i] = $text;
		$tips[$i++] = $tip;
	}
	mysql_free_result($res);

	$fazaid = (int)rand(0,$i-1);
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
		$ata = $echipa1;
	} else {
		$ata = $echipa2;
	}
	$txt = str_replace("^1E1^", $juc1, $txt);
	$txt = str_replace("^1E2^", $juc2, $txt);
	$txt = str_replace("^2E1^", $juc3, $txt);
	$txt = str_replace("^2E2^", $juc4, $txt);
	
	if($min >= 45) {
		$m = $min % 45;
		if($m<10) $m = "0".$m;
		$mintrodus = "13:$m";
	} else {
		if($min<10) $min = "0".$min;
		$mintrodus = "12:".$min;
	}

	
	//avem textul, il introducem in bd, in mecitext : meciID, text, atacator, minut
	$sql = "INSERT INTO mecitext(meciID, text, atacator, minut, gol)
			VALUES($meciID, '$txt', $ata, '".$min."', $tipul)";
//	echo "$sql<br/>";
//	mysql_query($sql);


} //end function calcul

/****************************************************************************************************
*****************************************************************************************************
****************************************************************************************************/

function calculareScor($meciID) {
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
	
$sql = "SELECT a.userId_1, a.userId_2, b.TeamName, c.TeamName, a.scor 
		FROM invitatiemeci a 
		LEFT OUTER JOIN user b
		ON b.id=a.userId_1
		LEFT OUTER JOIN user c
		ON c.id=a.userId_2
		WHERE a.id=$meciID";
echo "$sql<br/>";
$res = mysql_query($sql);
list($echipa1, $echipa2, $numeechipa1, $numeechipa2, $scor) = mysql_fetch_row($res);
mysql_free_result($res);


	//echipa1
	$sql = "SELECT a.playerId, a.post, b.fname, b.lname, b.strength, b.form 
			FROM echipastart a 
			LEFT OUTER JOIN player b
			ON a.playerId=b.id
			WHERE a.userId=$echipa1 AND a.post<>0 and a.post<>1";
		
	echo "$sql<br/>";
	$res = mysql_query($sql);
	$juc = array();
	$pren = array();
	$nu = array();
	$st = array();
	$fo = array();
	$j=0;
	while(list($jucator, $post, $prenume, $nume, $strength, $forma) = mysql_fetch_row($res)) {
		$pren[$j] = $prenume;
		$nu[$j] = $nume;
		$juc[$j] = $jucator;
		$st[$j] = $strength;
		$fo[$j] = $forma;
		$pst[$j++] = $post;
	}
	mysql_free_result($res);
	$formula1 = $j;

//echipa 2
$sql = "SELECT a.playerId, a.post, b.fname, b.lname, b.strength, b.form 
		FROM echipastart a 
		LEFT OUTER JOIN player b
		ON a.playerId=b.id
		WHERE a.userId=$echipa2 AND a.post<>0 and a.post<>1";
echo "$sql<br/>";
$res = mysql_query($sql);
$juc2 = array();
$pren2 = array();
$nu2 = array();
$st2 = array();
$fo2 = array();
$j=0;
while(list($jucator, $post, $prenume, $nume, $strength, $forma) = mysql_fetch_row($res)) {
	$pren2[$j] = $prenume;
	$nu2[$j] = $nume;
	$pst2[$j] = $post;
	$st2[$j] = $strength;
	$fo2[$j] = $forma;
	$juc2[$j++] = $jucator;
}
mysql_free_result($res);
$formula2 = $j;



//coeficient echipa gazda: 5%
//coeficient de noroc - random pt ambele echipe. cine are mai mare valoarea, ia 5%, cealalta echipa nu ia nimic
//echipa 1 este echipa gazda
$noroc1 = rand(0,100);
$noroc2 = rand(0,100);

if ($noroc1>$noroc2) {
	$_noroc1 = 1;
	$_noroc2 = 1;
} else {
	$_noroc1 = 1;
	$_noroc2 = 1;
}

//echipa 1
echo "$numeechipa1...<br/>";
	$user1 = new User();
	$user1->LoginID($echipa1);

	$user1->EchoClub();
	
	$user1->ComputeTeamValues();

	//Valorile pentru echipa 1
	//$user1->Vfw; $user1->Vmf; $user1->Vdf

	$Vfw1 = $user1->Vfw * 1.05 * $_noroc1;
	$Vmf1 = $user1->Vmf * 1.05 * $_noroc1;
	$Vdf1 = $user1->Vdf * 1.05 * $_noroc1;

	echo "Valori echipa 1: $Vfw1 :: $Vmf1 :: $Vdf1<br/>";


echo "<br/><br/>$numeechipa2...<br/>";
	$user2 = new User();
	$user2->LoginID($echipa2);

	$user2->EchoClub();
	
	$user2->ComputeTeamValues();

	//Valorile pentru echipa 2
	//$user2->Vfw; $user2->Vmf; $user2->Vdf

	$Vfw2 = $user2->Vfw * $_noroc2;
	$Vmf2 = $user2->Vmf * $_noroc2;
	$Vdf2 = $user2->Vdf * $_noroc2;

	echo "Valori echipa 2: $Vfw2 :: $Vmf2 :: $Vdf2<br/>";

	//posesia: x/(x+y) * 100 si y/(x+y) * 100

	$pas = 0.07;
	
	$vt1 = $Vfw1+$Vmf1*.71+$Vdf1*.3;
	$vt2 = $Vfw2+$Vmf2*.71+$Vdf2*.3;
	$bonus1=0;
	$bonus2=0;
	
	$dif=$vt1-$vt2;
	echo "DIF: $dif<br/>";
	switch($dif) {
		case ($dif>=1 && $dif<10): $bonus1=.5;break;
		case ($dif>=10 && $dif<20): $bonus1=.9;break;
		case ($dif>=20 && $dif<30): $bonus1=1.7;break;
		case ($dif>=30 && $dif<40): $bonus1=2.4;break;
		case ($dif>=40 && $dif<50): $bonus1=3.5;break;
		case ($dif>=50 && $dif<100): $bonus1=4.7;break;
		case ($dif>=100 && $dif<200): $bonus1=6.1;break;
		case ($dif>=200 && $dif<300): $bonus1=8.2;break;
		case ($dif>=300 && $dif<500): $bonus1=10.7;break;
		case ($dif>=500): $bonus1=rand(13,20);break;
	}
	$dif=$vt2-$vt1;
	switch($dif) {
		case ($dif>=1 && $dif<10): $bonus2=.5;break;
		case ($dif>=10 && $dif<20): $bonus2=.9;break;
		case ($dif>=20 && $dif<30): $bonus2=1.7;break;
		case ($dif>=30 && $dif<40): $bonus2=2.4;break;
		case ($dif>=40 && $dif<50): $bonus2=3.5;break;
		case ($dif>=50 && $dif<100): $bonus2=4.7;break;
		case ($dif>=100 && $dif<200): $bonus2=6.1;break;
		case ($dif>=200 && $dif<300): $bonus2=8.2;break;
		case ($dif>=300 && $dif<500): $bonus2=10.7;break;
		case ($dif>=500): $bonus2=rand(13,20);break;
	}
	
	$aleator = rand(0,1)/3;
	$prDef = rand(105,119)/100;
	echo "PROCENT APARARE: $prDef<br/>";
	
	echo "BONUS1: $bonus1 :::: BINUS2: $bonus2<br/>";
	//cite goluri inscrie echipa 1
	$goluri1 = (int)(($Vfw1/$Vdf2 - 1)/$pas);
	$goluri1 = (int)(($Vfw1+$Vmf1*.53)/$Vdf2*$prDef-$aleator+$bonus1*.37);
	
	/*
	if(rand(1,20)>13) {
		if($goluri1 % 2 == 0 && $goluri2 % 2 == 0) {
			$goluri1 = $goluri1/2;
			$goluri2 = $goluri2/2;
		}
	}
	*/
	
	if ($goluri1<0) $goluri1=0;
	echo "<br/>$numeechipa1 inscrie $goluri1 goluri";

	//stabilire marcatori
	//ar trebui luati dintre atacanti si mijlocasi, acolo este probabilitatea cea mai mare de a marca

	//minutele in care s-a marcat
	for($i=0;$i<$goluri1;$i++) {
		$minute1[] = (int)rand(0,88)+1;
		AlegeMarcator($echipa1);
	}
	asort($minute1);
	echo "<br/>Golurile s-au inscris in minutele: ". implode(", ", $minute1).'<br/>';
	
	//cite goluri inscrie echipa 2
	$goluri2 = (int)(($Vfw2/$Vdf1 - 1)/$pas);
	$goluri2 = (int)(($Vfw2+$Vmf2*.53)/$Vdf1*$prDef-$aleator+$bonus2*.37);

	if ($goluri2<0) $goluri2=0;
	echo "<br/>$numeechipa2 inscrie $goluri2 goluri";

	//minutele in care s-a marcat
	for($i=0;$i<$goluri2;$i++) {
		$minute2[] = (int)rand(0,88)+1;
		AlegeMarcator($echipa2);
	}
	asort($minute2);
	echo "<br/>Golurile s-au inscris in minutele: ". implode(", ", $minute2).'<br/>';

	//scriere rezultat final in tabel
	$scor = "$goluri1:$goluri2";
	$sql = "UPDATE invitatiemeci
			SET scor='".$scor."'
			WHERE id=$meciID";
	//mysql_query($sql);

	//update bani incasati dupa bilete
	//trebuie stabilit si numarul de spectatori care asista la meci
	//trebuie in functie de ratingurile echipelor implicate, vremea de afara si pretul la bilete.
	//acum, fac doar random :)
	
	
	$sql = "SELECT b.pret, b.capacity
			FROM user a
			LEFT OUTER JOIN stadium b
			ON a.stadiumid=b.id
			WHERE a.id=$echipa1";
			
	echo "$sql<br/>";
	$res = mysql_query($sql);
	list($pretb, $capacitate)=mysql_fetch_row($res);
	mysql_free_result($res);
	
	$nrspect = rand(1,$capacitate); //trebuie luata capacitatea stadionului primei echipe
	$sumabilete = $nrspect*$pretb;
	$sql = "UPDATE user
			SET Funds = Funds+$sumabilete 
			WHERE id=$echipa1";
	//mysql_query($sql);

	////trebuie gasita o formula intre strength si scaderea de forma (oboseala)
	//daca are strength mic, trebuie sa scada mai mult in forma fizica dupa meci
	//asadar, invers proportionala descresterea
	//totodata, scade si in functie de numarul de jucatori din formatie -  o sa le adun
	
	//$st2[$j] = $strength;
	//$juc2[$j++] = $jucator;
	//modificare forma echipa1
	for($i=0;$i<$formula1; $i++) {
		$forma = round($fo[$i] -100/$formula1- 10/$st[$i],1);
		$sq = "UPDATE player SET form=$forma WHERE id=".$juc[$i];
		echo "$sq<br/>";
		//mysql_query($sq);
	}

	//modificare forma echipa2
	for($i=0;$i<$formula2; $i++) {
		$forma = round($fo2[$i] -100/$formula2- 10/$st2[$i],1);
		$sq = "UPDATE player SET form=$forma WHERE id=".$juc2[$i];
		echo "$sq<br/>";
		//mysql_query($sq);
	}
	
	
	//partea cu generatul de comentariu al meciului
	//sa fie cam 60-80 de intrari in acest comentariu, daca se poate, impartite egal intre echipe
	//aleator se genereaza 60-80 de minute pentru care se preiau mesaje din tplmessages in care se inlocuiesc jucatorii
	//jucatorii sunt de forma ^1E1^, ^1E2^, ^2E1^, ^2E2^, unde cifra din fata repr nr echipei

	//mai trebuie generat startul partidei, pauza si finalul, in care se afla statistica partidei.
	//in plus, pentru minutele alocate golurilor, se selecteaza un text din tplmessages

	//tabela tplmessages are un cimp numit 'tip': 
	// 0: faza cu ratare
	// 1: gol
	// 3: pauza
	
	// 4: final, care contine in text ^STATISTICA_POSESIE^


//startul
$sql = "SELECT id, text FROM tplmessages WHERE tip=2";
$res = mysql_query($sql);

$texts = array();
$i=0;
while(list($id, $text) = mysql_fetch_row($res)) {
	$texts[$i++] = $text;
}
mysql_free_result($res);
//alegerea fazei pe care o punem la inceputul partidei ( pot fi mai multe texte pt inceput de partida - se alege doar unul)
$fazaid = (int)rand(0,$i-1);
$txt = $texts[$fazaid];


//se adauga si formatiile de start pentru cele doua echipe

$formatia1 = "$numeechipa1: ";
//echipa1
$sql = "SELECT a.playerId, a.post, LEFT(b.fname,1), LEFT(b.lname,7) 
		FROM echipastart a
		LEFT OUTER JOIN player b
		ON a.playerId=b.id
		WHERE a.post<>0 AND a.userId=$echipa1 ORDER BY a.post ASC";
$res = mysql_query($sql);
$pozitii = array();
$jucatori = array();
$i=0;
$findex=0;
$mindex=0;
$aindex=0;
$fs=$fd=$f1=$f2=$f3=$ml=$mc=$mr=$m1=$m2=$m3=$a1=$a2=$a3="";
while(list($e_pid, $e_post, $juc_fname, $juc_lname)=mysql_fetch_row($res)) {
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
}
mysql_free_result($res);
$formatia1 .= '<br/>'; 
 
//echipa2
$formatia2 = "$numeechipa2: ";
$sql = "SELECT a.playerId, a.post, LEFT(b.fname,1), LEFT(b.lname,7) 
		FROM echipastart a
		LEFT OUTER JOIN player b
		ON a.playerId=b.id
		WHERE a.post<>0 AND a.userId=$echipa2 ORDER BY a.post ASC";
$res = mysql_query($sql);
$pozitii = array();
$jucatori = array();
$i=0;
$findex=0;
$mindex=0;
$aindex=0;
$fs=$fd=$f1=$f2=$f3=$ml=$mc=$mr=$m1=$m2=$m3=$a1=$a2=$a3="";
while(list($e_pid, $e_post, $juc_fname, $juc_lname)=mysql_fetch_row($res)) {
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
mysql_free_result($res);
$formatia2 .= '<br/>'; 

$txt = "$txt<br/> Formatii de start:<br/><br/>$formatia1<br/>$formatia2<br/><br/>Un numar de $nrspect spectatori au decis sa vina la stadion sa urmareasca aceasta partida!"; 
 
//avem textul, il introducem in bd, in mecitext : meciID, text, atacator, minut
$sql = "INSERT INTO mecitext(meciID, text, atacator, minut, gol)
		VALUES($meciID, '$txt', 0, '12:00', 2)";
//echo "$sql<br/>";
//mysql_query($sql);


//pauza
$sql = "SELECT id, text FROM tplmessages WHERE tip=3";
$res = mysql_query($sql);

$texts = array();
$i=0;
while(list($id, $text) = mysql_fetch_row($res)) {
	$texts[$i++] = $text;
}
mysql_free_result($res);
//alegerea fazei pe care o punem la inceputul partidei ( pot fi mai multe texte pt inceput de partida - se alege doar unul)
$fazaid = (int)rand(0,$i-1);
$txt = $texts[$fazaid];
//avem textul, il introducem in bd, in mecitext : meciID, text, atacator, minut
$sql = "INSERT INTO mecitext(meciID, text, atacator, minut, gol)
		VALUES($meciID, '$txt', 0, '12:45', 3)";
//mysql_query($sql);

//start repriza secunda
$sql = "SELECT id, text FROM tplmessages WHERE tip=6";
$res = mysql_query($sql);

$texts = array();
$i=0;
while(list($id, $text) = mysql_fetch_row($res)) {
	$texts[$i++] = $text;
}
mysql_free_result($res);
//alegerea fazei pe care o punem la inceputul reprizei II
$fazaid = (int)rand(0,$i-1);
$txt = $texts[$fazaid];
//avem textul, il introducem in bd, in mecitext : meciID, text, atacator, minut
$sql = "INSERT INTO mecitext(meciID, text, atacator, minut, gol)
		VALUES($meciID, '$txt', 0, '13:00', 2)";
//echo "$sql<br/>";
//mysql_query($sql);


//final
$sql = "SELECT id, text FROM tplmessages WHERE tip=4";
$res = mysql_query($sql);

$texts = array();
$i=0;
while(list($id, $text) = mysql_fetch_row($res)) {
	$texts[$i++] = $text;
}
mysql_free_result($res);
//alegerea fazei pe care o punem la inceputul partidei ( pot fi mai multe texte pt inceput de partida - se alege doar unul)
$fazaid = (int)rand(0,$i-1);
$txt = $texts[$fazaid];


//trebuie sa am in vedere si numarul de jucatori din echipa
$posesie1 = round($Vmf1/($Vmf1+$Vmf2) * 100,1);
$posesie2 = round($Vmf2/($Vmf1+$Vmf2) * 100,1);

$posesia = "Posesia jocului. $numeechipa1: " . round($Vmf1/($Vmf1+$Vmf2) * 100,1) . " procente ";
$posesia .= ". $numeechipa2: " . round($Vmf2/($Vmf1+$Vmf2) * 100,1) . " procente ";
echo "$posesia";
$txt = str_replace("^STATISTICA_POSESIE^", $posesia, $txt);
echo "$txt";
//avem textul, il introducem in bd, in mecitext : meciID, text, atacator, minut
$sql = "INSERT INTO mecitext(meciID, text, atacator, minut, gol)
		VALUES($meciID, '$txt', 0, '13:45', 4)";
//mysql_query($sql);


//generare faze care se incheie cu gol
//in minute1 si minute2 se afla minutele golurilor
//momentan, sanse pentru gol, egale pentru toti jucatorii, cu exceptia portarului

//marcatori echipa 1
for ($ii=0; $ii<count($minute1); $ii++) {
	
	$idjuc = (int)rand(0,$formula1-1);
	//$juc[$idjuc] -id-ul
	
	$sql = "SELECT id, text FROM tplmessages WHERE tip=1";
	$res = mysql_query($sql);

	$texts = array();
	$i=0;
	while(list($id, $text) = mysql_fetch_row($res)) {
		$texts[$i++] = $text;
	}
	mysql_free_result($res);

	$fazaid = (int)rand(0,$i-1);
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
	$sql = "INSERT INTO detaliumeci(meciid, playerid, minut, echipa, actiune)
			VALUES($meciID, ".$juc[$idjuc].", ".$minute1[$ii].",1,1)";
//	echo "MARCATOR: $sql<br/>";
//	mysql_query($sql);
	
	
	//avem textul, il introducem in bd, in mecitext : meciID, text, atacator, minut
	$sql = "INSERT INTO mecitext(meciID, text, atacator, minut, gol)
			VALUES($meciID, '$txt', $echipa1, '".$mintrodus."', 1)";
//	mysql_query($sql);
}


//marcatori echipa 2
for ($ii=0; $ii<count($minute2); $ii++) {
	$idjuc = (int)rand(0,$formula2-1);

	$sql = "SELECT id, text FROM tplmessages WHERE tip=1";
	$res = mysql_query($sql);

	$texts = array();
	$i=0;
	while(list($id, $text) = mysql_fetch_row($res)) {
		$texts[$i++] = $text;
	}
	mysql_free_result($res);

	$fazaid = (int)rand(0,$i-1);
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
	$sql = "INSERT INTO detaliumeci(meciid, playerid, minut, echipa, actiune)
			VALUES($meciID, ".$juc2[$idjuc].", ".$minute2[$ii].",2,1)";
	echo "MARCATOR: $sql<br/>";
	//mysql_query($sql);


	//avem textul, il introducem in bd, in mecitext : meciID, text, atacator, minut
	$sql = "INSERT INTO mecitext(meciID, text, atacator, minut, gol)
			VALUES($meciID, '$txt', $echipa2, '".$mintrodus."', 1)";
	//mysql_query($sql);
}

$contor1 = intval(70*$posesie1/100);
$contor2 = 70-$contor1;

echo "Valori contor: " . $contor1 . "   " . $contor2;

$c1 = 0; //asta merge pina la $contor1
$c2 = 0; //merge pina la maxim $contor2
//introducere faze de poarta, inca 70
for($c=0;$c<70;$c++) {
	$minut = intval(rand(0,90));
	if($minut == 0) $minut=1;
	if($minut == 45) $minut=44;
	if($minut == 90) $minut=89;

	
	//generez direct ora la care se afiseaza comentariu
	//prima repriza incepe la ora 12
	//a doua, la ora 13
	if($minut >= 45) {
		$rep2min = $minut % 45;
		if($rep2min <10) $rep2min = "0".$rep2min;
		$minut = "13:".$rep2min;
	} else {
		if($minut <10) $minut = "0".$minut;
		$minut = "12:".$minut;
	}
	
	$atacator = intval(rand(1,2));
	if($atacator == 1){
	//echipa atacatoare se stabileste si in functie de posesie $posesie1 si $posesie2
	//din cele 70 de faze, un procent de $posesie1 va fi alocat primei echipe, restul celei de a doua
	//ataca prima echipa
		$c1++;
		if($c1<=$contor1) {
			//aici pt prima echipa
			echo "Faza echipa1: $c1<br>";
			calcul($atacator, $minut, $echipa1, $echipa2, $meciID);
		} else {
			//s-au epuizat toate fazele pt echipa1
			$c2++;
			echo "Trecere echipa2: $c2<br>";
			calcul(2, $minut, $echipa1, $echipa2, $meciID);
		}
	}


	if($atacator == 2){
	//din cele 70 de faze, un procent de $posesie1 va fi alocat primei echipe, restul celei de a doua
	//ataca echipa 2
		$c2++;
		if($c2<=$contor2) {
			//aici pt echipa 2
			echo "Faza echipa2: $c2<br>";
			calcul($atacator, $minut, $echipa1, $echipa2, $meciID);

		} else {
			//s-au epuizat toate fazele pt echipa2
			$c1++;
			echo "Trecere echipa1: $c1<br>";
			calcul(1, $minut, $echipa1, $echipa2, $meciID);
		}
	}
  }

} //end function calculareScor

global $formula1;
global $formula2;

$sql = "SELECT a.id, a.userId_1, a.userId_2 
		FROM invitatiemeci a 
		WHERE a.accepted=1 and a.id=".$_REQUEST['meciid'];

echo "$sql<br/>";		
$resdoi = mysql_query($sql);
echo "Generare meciuri...";
$i=0;


while(list($idmeci, $ec1, $ec2) = mysql_fetch_row($resdoi)) {
	$meciID=$idmeci;
	
	//verific daca are macar 7 jucatori
	$nrjuc1 = getTeamComplete($ec1);
	echo "Echipa 1 are $nrjuc1 jucatori in echipa de start<br/>";
	$nrjuc2 = getTeamComplete($ec2);
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
				WHERE id=$meciID";
		echo "$sscor<br/>";
		//mysql_query($sscor);
	
		//textul pentru masa verde
		if($nrjuc1<7) $winner = "oaspetii";
		if($nrjuc2<7) $winner = "gazdele";
		
		$sql = "INSERT INTO mecitext(meciID, text, atacator, minut, gol)
			VALUES($meciID, 'Meciul a fost castigat la masa verde, pentru ca $winner nu s-au prezentat la meci!', 0, '12:00', 0)";
		//mysql_query($sql);
	}
	if($skip==0) calculareScor($meciID);
	$i++;
}
echo "<br/>Generate $i meciuri!";
mysql_free_result($resdoi);

function getTeamComplete($echipaid) {
	$s = "SELECT id FROM echipastart WHERE userid=$echipaid and post<>0";
	$r = mysql_query($s);
	$nrjuc = mysql_num_rows($r);
	mysql_free_result($r);
	return $nrjuc;
}

?>