<?php
//training partea a II-a
//pentru toti jucatorii care au echipa, verific nivelul de crestere.
//ar trebui cumva sa fac, ca dupa ce are cresterea la acea caracteristica, sa-i micsorez procentul de crestere, fara sa-l redistribui
//totodata, daca a ajuns la maxim cu o caracteristica, procentul sa devina 0 pt ea.


//corectie pentru mai sus: daca a ajuns la maxim pentru caracteristica, are o sansa din 8 sa creasca la respectiva.

include('../app.conf.php');

include('../user.php');
include('../Player.php');
include('../trainer.php');
include('../definitions.inc');


//se incrementeaza zilele de la salt la fiecare antrenament
$sql = "UPDATE salt SET delasalt=delasalt+1 WHERE playerID>0";
mysql_query($sql);

$sql = "SELECT a.playerID, a.userID, b.delasalt, b.cstsalt 
		FROM userplayer a
		LEFT JOIN salt b
		ON a.playerid=b.playerid
		WHERE a.userID<>0";
$res = mysql_query($sql);
while (list($playerID, $userID, $deLaSalt, $cstSalt) = mysql_fetch_row($res)) {

	if ($deLaSalt >= constant($cstSalt)) {
		echo "------------------------------------------------>Este zi de salt!!!!!<br/>";
		//se preiau valorile din tabela cresteri
		$sql = "SELECT @val:=GREATEST(reflexes, OneOnOne, Handling, Communication, Tackling, Passing, LongShot, Shooting, Heading, Creativity, Crossing, Marking, TeamWork, 	FirstTouch, Strength, Speed, Aggresivity, Injury, Dribbling, Positioning, Rating) as 'maxim',
				CASE @val WHEN reflexes THEN 'reflexes'
						  WHEN oneonone THEN 'OneOnOne'
						  WHEN handling THEN 'Handling'
						  WHEN communication THEN 'Communication'
						  WHEN tackling THEN 'Tackling'
						  WHEN passing THEN 'Passing'
						  WHEN longshot THEN 'LongShot'
						  WHEN shooting THEN 'Shooting'
						  WHEN heading THEN 'Heading'
						  WHEN creativity THEN 'Creativity'
						  WHEN crossing THEN 'Crossing'
						  WHEN marking THEN 'Marking'
						  WHEN teamwork THEN 'TeamWork'
						  WHEN firsttouch THEN 'FirstTouch'
						  WHEN strength THEN 'Strength'
						  WHEN speed THEN 'Speed'
						  WHEN aggresivity THEN 'Aggresivity'
						  WHEN injury THEN 'Injury'
						  WHEN dribbling THEN 'Dribbling'
						  WHEN positioning THEN 'Positioning'
						  WHEN rating THEN 'Rating'
				END as columna
				FROM cresteri WHERE playerId = $playerID";
		echo "$sql<br/>";
		$res2 = mysql_query($sql);
		list($valoare, $caracteristica) = mysql_fetch_row($res2);
		echo "$valoare ===== $caracteristica<br/>";
		if($valoare > 2.5) {
			echo "---------------------------------------------------------------------->Aici este: $caracteristica :: $valoare<br/>";

			//preiau valoarea maxima la care poate ajunge si valoarea actuala 
			//daca valoarea actuala este mai mare decit maximul, are o sansa din 8 sa creasca.
			//altfel, creste normal
			
			$svmax = "SELECT a.$caracteristica , b.$caracteristica
					  FROM vmaxpos a
					  LEFT JOIN player b
					  ON a.playerid=b.id
					  WHERE a.playerid=$playerID";
			$resvmax = mysql_query($svmax);
			list($carvmax, $valact)=mysql_fetch_row($resvmax);
			echo "$carvmax - $valact :: $svmax<br/>";
			mysql_free_result($resvmax);
			
			$intrare = true;
			$crestereRandom=7;
			if($valact>=$carvmax) {
				//s-a ajuns la maxim pentru caracteristica
				//va avea sanse de 1 la 8 sa intre in crestere
				//in caz ca nu s-a ajuns, $intrare este pe true deja
				$intrare = false;
				$crestereRandom = rand(1,8);
			}
			
			echo "CrestereRandom este: $crestereRandom (daca este 7 intra in crestere)<br/>";
			if($crestereRandom==7 || $intrare==true) {
				//se face cresterea pentru acea caracteristica
				//am facut, prin randomul de mai sus, sa aiba doar o sansa (am ales eu 7 ca valoare, sa fie egal)
				$valoare = 0;
				
		
				if($valact<50) {
					//resetez cresterea pt caracteristica
					$sql = "UPDATE cresteri SET $caracteristica = 0
							WHERE playerId=$playerID";
					echo "$sql<br/>";
					mysql_query($sql);
					//modific valoarea
					//dupa fiecare crestere, ii maresc valoarea, dar si salariul.
					//ulterior, daca are 3-4 cresteri in luna precedenta, sa-i cresc si rating-ul cu un punct
					//asta sa se faca intr-un job separat, care se ruleaza o data pe luna
					$sql = "UPDATE player SET $caracteristica=CASE
																WHEN $caracteristica<=49  THEN $caracteristica+1 
																WHEN $caracteristica = 50 THEN 50
															  END,
											Value=Value*1.05, Wage = Wage*1.05				  													  
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
				$valoare = $valoare/6;
				//daca nu face saltul ca nu se indeplineste conditia de random, ii scad mult valoare
				$sql = "UPDATE cresteri SET $caracteristica = $valoare WHERE playerId=$playerID";
				mysql_query($sql);
			}
			
			
			//iau caracteristica cu procentul cel mai mic, ca sa-l maresc
			$sql = "SELECT caracteristica 
					FROM procente 
					where playerid=$playerid
						and procent=(select min(procent) from procente where procent>0 and playerid=$playerid)";
			$rmin = mysql_query($sql);
			list($carmin) = mysql_fetch_row($rmin);
			
			//indiferent daca creste sau nu, ii scad procentul de crestere
			$sql = "UPDATE procente SET procent=procent*0.9 WHERE caracteristica=$caracteristica AND playerid=$playerID";
			mysql_query($sql);
			mysql_free_result($rmin);
			
			//ii cresc procentul celei mai mici calitati
			$sql = "UPDATE procente SET procent=procent*1.1 WHERE caracteristica=$carmin AND playerid=$playerID";
			mysql_query($sql);

		}
 



		mysql_free_result($res2);

		//aici urmeaza setarile tabelelor
	} else {
		//????
		//se muta ziua de salt pe ziua urmatore
		//se decrementeaza valoarea din tabela salt
	}



}
mysql_free_result($res);

$mes = 'Creste si valoarea jucatorului: '.Date("Y-m-d H:i:s");
$sql = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
		VALUES(0, 23, 'Fin train. nou3 -crestere valoare', '$mes' , '".Date("Y-m-d H:i:s")."', 0, 0)";
//mysql_query($sql);
echo "$sql<br/>";

?>