<?php
error_reporting(63);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

//in fiecare zi, care nu e zi de meci, jcuatorul isi revine la forma
//daca la zi libera isi revine cu 10% pe zi, la zi normala cu antrenament isi revine cu 6.5%

//job si pentru verificarea finalizarii constructiei la tribunele stadionului
//daca se termina constructia in ziua curenta, se face upgrade la stadion si se trimite mesaj


$s1 = "SELECT a.id, a.form
	   FROM player a
	   WHERE a.form<100";
$r1 = mysql_query($s1);
while(list($plid, $forma) = mysql_fetch_row($r1)) {
	$fo = ($forma+6.5>100)? 100 : $forma+6.5;
	$s2 = "UPDATE player SET Form=$fo WHERE id=$plid";
	mysql_query($s2);
}
mysql_free_result($r1);

//verific si daca exista constructie la stadion si daca s-a terminat in ziua respectiva
$dc = Date("Y-m-d");
$sql = "SELECT a.id, a.construction1, a.construction2, a.construction3, a.construction4, a.construction5, a.construction6, a.construction7, a.construction8
			,a.data1, a.data2, a.data3, a.data4, a.data5, a.data6, a.data7, a.data8, b.id
		FROM stadium a
		LEFT JOIN user b
		ON a.id=b.stadiumid
		WHERE a.data1='$dc' OR a.data2='$dc' or a.data3='$dc' or a.data4='$dc' or 
				a.data5='$dc' OR a.data6='$dc' or a.data7='$dc' or a.data8='$dc'";
echo "$sql<br/>";
$rcon = mysql_query($sql);
while(list($id, $c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $d1, $d2, $d3, $d4, $d5, $d6, $d7, $d8, $uid) = mysql_fetch_row($rcon)) {
	if($d1==$dc) {
		$s = "UPDATE stadium
			 SET capacity=capacity+$c1, sector1=sector1+$c1, data1='0000-00-00', construction1=0
			 WHERE id=$id";
		mysql_query($s);
		
		$mes_1 = "Salut!
		<br/><br/>
		Sunt responsabilul pe constructii din cadrul  clubului! Te anunt cu bucurie ca lucrarile la Sectorul 1 al stadionului au fost terminate cu succes! Inca $c1 locuri sunt disponibile pentru suporteri!";
		$s1 = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
				VALUES(0, $uid, 'Constructie finalizata!', '$mes_1', '".Date("Y-m-d")."', 0, 0)";
		mysql_query($s1);
		echo "$s1<br/>";

	}
	if($d2==$dc) {
		$s = "UPDATE stadium
			 SET capacity=capacity+$c2, sector2=sector2+$c2, data2='0000-00-00', construction2=0
			 WHERE id=$id";
		mysql_query($s);
		$mes_1 = "Salut!
		<br/><br/>
		Sunt responsabilul pe constructii din cadrul  clubului! Te anunt cu bucurie ca lucrarile la Sectorul 2 al stadionului au fost terminate cu succes! Inca $c2 locuri sunt disponibile pentru suporteri!";
		$s1 = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
				VALUES(0, $uid, 'Constructie finalizata!', '$mes_1', '".Date("Y-m-d")."', 0, 0)";
		mysql_query($s1);
		echo "$sql<br/>";
	}
	if($d3==$dc) {
		$s = "UPDATE stadium
			 SET capacity=capacity+$c3, sector3=sector3+$c3, data3='0000-00-00', construction3=0
			 WHERE id=$id";
		mysql_query($s);
		$mes_1 = "Salut!
		<br/><br/>
		Sunt responsabilul pe constructii din cadrul  clubului! Te anunt cu bucurie ca lucrarile la Sectorul 3 al stadionului au fost terminate cu succes! Inca $c3 locuri sunt disponibile pentru suporteri!";
		$s1 = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
				VALUES(0, $uid, 'Constructie finalizata!', '$mes_1', '".Date("Y-m-d")."', 0, 0)";
		mysql_query($s1);
		echo "$sql<br/>";
	}
	if($d4==$dc) {
		$s = "UPDATE stadium
			 SET capacity=capacity+$c4, sector4=sector4+$c4, data4='0000-00-00', construction4=0
			 WHERE id=$id";
		mysql_query($s);
		$mes_1 = "Salut!
		<br/><br/>
		Sunt responsabilul pe constructii din cadrul  clubului! Te anunt cu bucurie ca lucrarile la Sectorul 4 al stadionului au fost terminate cu succes! Inca $c4 locuri sunt disponibile pentru suporteri!";
		$s1 = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
				VALUES(0, $uid, 'Constructie finalizata!', '$mes_1', '".Date("Y-m-d")."', 0, 0)";
		mysql_query($s1);
		echo "$sql<br/>";
	}
	if($d5==$dc) {
		$s = "UPDATE stadium
			 SET capacity=capacity+$c5, sector5=sector5+$c5, data5='0000-00-00', construction5=0
			 WHERE id=$id";
		mysql_query($s);
		$mes_1 = "Salut!
		<br/><br/>
		Sunt responsabilul pe constructii din cadrul  clubului! Te anunt cu bucurie ca lucrarile la Sectorul 5 al stadionului au fost terminate cu succes! Inca $c5 locuri sunt disponibile pentru suporteri!";
		$s1 = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
				VALUES(0, $uid, 'Constructie finalizata!', '$mes_1', '".Date("Y-m-d")."', 0, 0)";
		mysql_query($s1);
		echo "$sql<br/>";
	}
	if($d6==$dc) {
		$s = "UPDATE stadium
			 SET capacity=capacity+$c6, sector6=sector6+$c6, data6='0000-00-00', construction6=0
			 WHERE id=$id";
		mysql_query($s);
		$mes_1 = "Salut!
		<br/><br/>
		Sunt responsabilul pe constructii din cadrul  clubului! Te anunt cu bucurie ca lucrarile la Sectorul 6 al stadionului au fost terminate cu succes! Inca $c6 locuri sunt disponibile pentru suporteri!";
		$s1 = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
				VALUES(0, $uid, 'Constructie finalizata!', '$mes_1', '".Date("Y-m-d")."', 0, 0)";
		mysql_query($s1);
		echo "$sql<br/>";
	}
	if($d7==$dc) {
		$s = "UPDATE stadium
			 SET capacity=capacity+$c7, sector7=sector7+$c7, data7='0000-00-00', construction7=0
			 WHERE id=$id";
		mysql_query($s);
		$mes_1 = "Salut!
		<br/><br/>
		Sunt responsabilul pe constructii din cadrul  clubului! Te anunt cu bucurie ca lucrarile la Sectorul 7 al stadionului au fost terminate cu succes! Inca $c7 locuri sunt disponibile pentru suporteri!";
		$s1 = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
				VALUES(0, $uid, 'Constructie finalizata!', '$mes_1', '".Date("Y-m-d")."', 0, 0)";
		mysql_query($s1);
		echo "$sql<br/>";
	}
	if($d8==$dc) {
		$s = "UPDATE stadium
			 SET capacity=capacity+$c8, sector8=sector8+$c8, data8='0000-00-00', construction8=0
			 WHERE id=$id";
		mysql_query($s);
		$mes_1 = "Salut!
		<br/><br/>
		Sunt responsabilul pe constructii din cadrul  clubului! Te anunt cu bucurie ca lucrarile la Sectorul 8 al stadionului au fost terminate cu succes! Inca $c8 locuri sunt disponibile pentru suporteri!";
		$s1 = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
				VALUES(0, $uid, 'Constructie finalizata!', '$mes_1', '".Date("Y-m-d")."', 0, 0)";
		mysql_query($s1);
		echo "$sql<br/>";
	}
	echo "$s<br/>";
}
mysql_free_result($rcon);
?>