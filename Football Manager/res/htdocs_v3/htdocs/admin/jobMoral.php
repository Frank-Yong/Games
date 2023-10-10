<?php
error_reporting(E_ALL);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

//trebuie sa ruleze in fiecare zi, sa verifice daca este zi de meci
//ora: 22:30, in feicare zi

//verific in invitatiemeci daca exista etapa in acea zi, prin interogarea cimpurilor competitieid si datameci

/*
//Moralul
//moralul maxim este 100%
//daca un jucator este nefolosit timp de 3 meciuri oficiale, sa scada la moral cu 10%. Pentru fiecare meci in plus fata de cele trei, sa scada cu cite 7%
		//tabel cu toti jucatorii din lot, iar dupa fiecare meci, adaug sau scad contorul asociat
		//tabel: moral (id, playerid, contor1, contor2) -> contor1-pt meciuri, contor2 pentru zile libere
		-contorizez cite meciuri oficiale lipseste
		-daca a fost introdus, resetez contorul -> jucatorul ramine la moral la fel ca in acel moment pina se intimpla altceva 
		
//daca este scos din teren pina in minutul 45, sa scada cu 7% la moral
//daca este introdus in teren ca titular, sa creasca cu 10% -> resetez contor
//daca este introdus in teren ca rezerva sa creasca cu 5% -> resetez contor
//daca trec mai mult de 10 zile fara zi libera, moralul sa scada cu 10%. pentru fiecare zi in plus fata de aceasta, sa scada cu inca 5%
//daca se da zi libera jucatorilor, moralul sa creasca cu 7% -> resetez contor zile libere
//daca se merge la restaurant, sa creasca moralul cu 7%
//daca echipa pierde cu mai mult de 5 goluri, moralul titularilor si rezervelor sa scada cu 2%
//daca echipa cistiga cu mai mult de 5 goluri, moralul titularilor si rezervelor utilizate sa creasca cu 2%
//moralul sa nu scada sub 0
//in functie de valoare, sa fie afisat moralul cu o culoare sau cu o bara


//in caz de moral mai mic, modul in care se antreneaza sa fie afectat
//de asemenea, si jocul sa fie afectat

//prin acestea, jucatorii trebuie rulati in echipa, echipa de start trebuie sa fie modificata frecvent

*/

$sql = "SELECT a.userId_1, a.userId_2, a.competitieid, a.etapa, a.scor, b.nume 
		FROM invitatiemeci a
		LEFT OUTER JOIN competitie b
		ON a.competitieid=b.id
		WHERE a.datameci='".Date("Y-m-d")."'";
echo "$sql<br/>";
$res = mysql_query($sql);
while(list($id1, $id2, $cid, $et, $scor, $numecomp) = mysql_fetch_row($res)) {
	if($cid<>0) {
		//verific daca au trecut 3 meciuri oficiale fara sa fie in teren
		//daca au trecut 3, scad din moral 10
		//daca au trecut mai multe, scad 7 pentru fiecare meci care trece si nu e titular
		//este oficial
		$s = "SELECT a.playerid, a.contor1
			  FROM moral a
			  LEFT JOIN userplayer b
			  ON a.playerid=b.playerid
			  WHERE b.userid=$id1 or b.userid=$id2";
	  echo "$s<br/>";
	  $r = mysql_query($s);
		while(list($pid, $contor1) = mysql_fetch_row($r)) {
			$adun=0;
			echo "CoNTOR 1: $contor1<br/>";
			switch(true) {
				case $contor1==3: $adun = 10;
					break;
				case $contor1>3: $adun = 7;
					break;
			}
			//echo "ADUN : $adun<br/>";
			if($adun>0) {
				$ss = "UPDATE player SET Moral=CASE
									WHEN Moral-$adun<=0 THEN 1
									ELSE Moral-$adun
									  END
					   WHERE id=$pid";
			  echo "$ss<br/>";
			  mysql_query($ss);
			}
		}
		mysql_free_result($r);
	}
	
}
mysql_free_result($res);

$mes = 'Am rulat job Moral la ora '.Date("Y-m-d H:i:s");
$sql = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor, citit)
		VALUES(0, 23, 'Am rulat job Moral', '$mes' , '".Date("Y-m-d H:i:s")."', 0, 0, 1)";
$resmes = mysql_query($sql);
//echo "$sql<br/>";
?>