<?php
error_reporting(63);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

//job care ruleaza zilnic
//verifica daca in ziua urmatoare este meci oficial. Daca este, trimit mesaj cu sponsor pentru tricou.
//mesajului i se paote rapsunde pina la ora de incepere a partidei
//se primesc bani pentru acceptarea sponsorului

$sql = "SELECT a.userId_1, a.userId_2, b.TeamName, c.TeamName, a.datameci, b.rating, c.rating
		FROM invitatiemeci a 
		LEFT JOIN user b
		ON a.userId_1=b.id
		LEFT JOIN user c
		ON a.userId_2=c.id
		WHERE a.competitieid>0 and a.datameci='".date('Y-m-d', strtotime(' +1 day'))."'";
echo "$sql<br/>";
$res = mysql_query($sql);
while(list($u1, $u2, $team1, $team2, $datameci, $rat1, $rat2) = mysql_fetch_row($res)) {
		$bani = intval((9+$rat1/15+($rat2-$rat1)/25)*1300);

		//$bani = rand($rat1*2-10, $rat1*2+10)*600;
		$data = $datameci.' 12:00:00';
		$txt = "Salut!<br/><br/>Sunt consilierul tau financiar! Pentru partida pe care o vom disputa cu echipa $team2, am primit o oferta de sponsorizare pentru afisat pe tricou, valabila doar pentru acest meci! Oferta este in valoare de ".number_format($bani)." &euro; si o putem accepta pana la ora jocului, altfel expira!<br/><br/><script type=\"text/javascript\" src=\"//profitshare.ro/j/7oIg\"></script>";
		$sql = "INSERT INTO messages(fromID, toID, subject, data, body, meciID, tricou, tricousuma, tricoudisponibil, hideQuestion, sponsor)
				VALUES(0,$u1, 'Oferta sponsori tricou','".Date("Y-m-d")."', '$txt', 0, 1, $bani, '$data', 0, 1)"; 
		mysql_query($sql);
		//echo "$sql<br/>";

		$bani = intval((9+$rat2/15+($rat1-$rat2)/25)*1300);

		$txt = "Salut!<br/><br/>Sunt consilierul tau financiar! Pentru partida pe care o vom disputa cu echipa $team1, am primit o oferta de sponsorizare pentru afisat pe tricou, valabila doar pentru acest meci! Oferta este in valoare de ".number_format($bani)." &euro; si o putem accepta pana la ora jocului, altfel expira!<br/><br/><script type=\"text/javascript\" src=\"//profitshare.ro/j/7oIg\"></script>";
		$sql = "INSERT INTO messages(fromID, toID, subject, data, body, meciID, tricou, tricousuma, tricoudisponibil, hideQuestion, sponsor)
				VALUES(0,$u2, 'Oferta sponsori tricou','".Date("Y-m-d")."', '$txt', 0, 1, $bani, '$data', 0, 1)"; 
		mysql_query($sql);
		//echo "$sql<br/>";
}
mysql_free_result($res);


?>