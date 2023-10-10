<?php
error_reporting(63);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

//procesare pentru faliment
//verificare daca fondurile sunt sub 0
//daca sunt: trimit mesaj de avertizare si introduc informatia in tabelul insolventa
//insolventa: userid, data, procesat, informat

$sql = "SELECT a.id, a.Funds, b.informat, b.procesat 
		FROM user a
		LEFT JOIN insolventa b
		ON a.id=b.userid
		WHERE a.Funds<0 and a.botteam=0 and a.activated=1";
echo "$sql<br/>";
$res = mysql_query($sql);
while(list($uid, $fonduri, $informat, $procesat) = mysql_fetch_row($res)) {
	if($informat == 0 && $procesat == 0) {
		$datalimita = date('Y-m-d', strtotime(' +14 days'));
		$mes_1 = "Salut!
				<br/><br/>
				Sunt responsabilul pe probleme financiare din club. Vreau sa te anunt ca suntem pe minus cu bugetul si nu mai avem bani de salarii!
				<br/><br/>
				In cazul in care in 2 saptamani nu reusim sa strangem fondurile necesare (prin vanzare/concediere de jucatori, accesare prime zilnice de la sponsori), riscam sa intram in faliment!
				<br/><br/>
				Aceasta inseamna ca vei avea clubul resetat, iar echipa va fi depunctata in clasament! Avem timp pana in $datalimita sa remediem problema!";
		$s1 = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
				VALUES(0, $uid, 'Depasire buget!', '$mes_1', '".Date("Y-m-d")."', 0, 0)";
		mysql_query($s1);
		echo "$sql<br/>";

		$s2 = "INSERT INTO insolventa(userid, data, procesat, informat)
				VALUES($uid, '$datalimita', 0, 1)";
		mysql_query($s2);
		echo "$sql<br/>";

	}
}
mysql_free_result($res);	


//trecere in insolventa a clublui
//verificare in tabel insolventa daca la data curenta are fondurile in regula

$sql = "SELECT b.id, b.Funds, a.informat, b.stadiumid, b.leagueid 
		FROM insolventa a
		LEFT JOIN user b
		ON b.id=a.userid
		WHERE a.procesat=0 and data='".Date('Y-m-d')."'";
echo "$sql<br/>";
$res = mysql_query($sql);
while(list($uid, $fonduri, $informat, $stadionid, $compid) = mysql_fetch_row($res)) {
	if($fonduri<0) {

		echo "<br/>Resetez clubul $uid...<br/>";
	
		$s8 = "DELETE FROM messages WHERE toid=$uid";
		mysql_query($s8);

		$s8 = "DELETE FROM evenimente WHERE userid=$uid";
		mysql_query($s8);

		$s8 = "DELETE FROM playerbid WHERE userid=$uid";
		mysql_query($s8);

		
		$s8 = "DELETE FROM balanta WHERE userid=$uid";
		mysql_query($s8);

		$mes_1 = "Salut!
				<br/><br/>
				Sunt responsabilul pe probleme financiare din club. Din pacate, nu s-au gasit posibilitati sa putem sa ne redresam financiar!
				<br/><br/>
				Ca urmare, jucatorii au fost declarati liberi de contract si alti jucatori s-au alaturat clubului. Si cum o veste proasta nu vine niciodata singura, clubul a fost depunctat cu 15 unitati, iar ratingul a fost resetat la cel initial!
				<br/><br/>";
		$s1 = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
				VALUES(0, $uid, 'Resetare club!', '$mes_1', '".Date("Y-m-d")."', 0, 0)";
		mysql_query($s1);

		$s2 = "UPDATE insolventa SET procesat = 1 WHERE userid=$uid";
		mysql_query($s2);
		
		//resetare club
		$s3 = "UPDATE user SET rating=10, funds=1000000 where id=$uid";
		mysql_query($s3);
		
		//punere pe transfer a jucatorilor, inainte de a-i scoate din club
		$s5 = "SELECT playerid FROM player WHERE userid=$uid";
		$r5 = mysql_query($s5);
		while(list($pid) = mysql_fetch_row($r5)) {
			$s6 = "UPDATE player SET transfer=1, transfersuma=wage+1000 WHERE id=$pid";
			mysql_query($s6);
			
			$s6 = "DELETE FROM playerbid WHERE playerid=$pid";
			mysql_query($s6);
		}
		mysql_free_result($r5);

		
		//scadere clasament cu 15 puncte
		//pun -15 la etapa=0 si competitia in care se afla echipa
		$s4 = "UPDATE clasament SET puncte=-15 WHERE userid=$uid AND etapa=0 AND competitieid=$compid";
		mysql_query($s4);
		
		
		//scoatere jucatori de la club
		$s4 = "UPDATE userplayer SET userid=0 where userid=$uid";
		mysql_query($s4);
		
		$s4 = "DELETE FROM echipastart WHERE userid=$uid";
		mysql_query($s4);
		
		//scoatere antrenori
		$s7 = "UPDATE usertrainer SET userid=0 where userid=$uid";
		mysql_query($s7);
		
		//scoatere sponsori
		$s8 = "DELETE FROM sponsoriuser WHERE userid=$uid";
		mysql_query($s8);
		$s8 = "DELETE FROM sponsoribuffer WHERE userid=$uid";
		mysql_query($s8);
		$s8 = "DELETE FROM sponsoriclick WHERE userid=$uid";
		mysql_query($s8);
		$s9 = "DELETE FROM requests WHERE userid=$uid";
		mysql_query($s9);
		//resetare stadion
		$s9 = "UPDATE stadium SET capacity=1400, sector1=50, sector2=400, sector3=50, sector4=200, sector5=50, sector7=50, sector8=200
				sector1c=1, sector2c=1, sector3c=1, sector4c=1, sector5c=1, sector6c=1, sector7c=1, sector8c=1,
				construction1=0, construction2=0, construction3=0, construction4=0, construction5=0, construction6=0, construction7=0, construction8=0,
				data1='0000-00-00',
				data2='0000-00-00',
				data3='0000-00-00',
				data4='0000-00-00',
				data5='0000-00-00',
				data6='0000-00-00',
				data7='0000-00-00',
				data8='0000-00-00'		
				WHERE id=$stadionid";
		mysql_query($s9);
		
		//generare jucatori
		
			$young = 0;
			$coeficient_liga = 0.61;
			$country = 0;
			
			//portari
			for($i=0;$i<3;$i++) {
				$poz = 1;
				//aici urmeaza definirea de var
				$den = "juc".$i;
				$$den = new Player($uid, 0, $country, $young, $poz, $coeficient_liga);
				//$$den->EchoPlayer();
			}

			//fundasi
			for($i=0;$i<5;$i++) {
				$poz = rand(2,4);
				//aici urmeaza definirea de var
				$den = "juc".$i;
				$$den = new Player($uid, 0, $country, $young, $poz, $coeficient_liga);
				//$$den->EchoPlayer();
			}

			//mijlocasi
			for($i=0;$i<6;$i++) {
				$poz = rand(5,7);
				//aici urmeaza definirea de var
				$den = "juc".$i;
				$$den = new Player($uid, 0, $country, $young, $poz, $coeficient_liga);
				//$$den->EchoPlayer();
			}

			//atacanti
			for($i=0;$i<4;$i++) {
				$poz = rand(8,10);
				//aici urmeaza definirea de var
				$den = "juc".$i;
				$$den = new Player($uid, 0, $country, $young, $poz, $coeficient_liga);
				//$$den->EchoPlayer();
			}


			for($i=0;$i<5;$i++) {
				//aici urmeaza definirea de var
				$den = "antrenor".$i;
				$Liga = 1 - $i/10;
				$$den = new Trainer($Liga);
				//$$den->EchoTrainer();
			}

		$s9 = "DELETE FROM insolventa WHERE userid=$uid";
		mysql_query($s9);


		
		
	}
}
mysql_free_result($res);	

?>