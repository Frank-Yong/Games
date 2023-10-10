<?php
include('../app.conf.php');

$sql = "SELECT UserName, TeamName, email FROM user WHERE botteam=0 AND activated=1
		ORDER BY id ASC";
$res = mysql_query($sql);
//$titlu = 
$i=0;
	$headers = "From: webmaster@CupaLigii.ro\r\n";
	$headers .= "Reply-To: contact@CupaLigii.ro\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

while(list($username, $teamname, $email) = mysql_fetch_row($res)) {
			$mes = "<img src=\"http://www.CupaLigii.ro/images/p2.png\" align=\"left\" width=\"300\">Salut $username!<br/><br/>
			Luni, 29 august, are loc migrarea la sezonul 5 din Managerul de Fotbal Cupa Ligii!<br/><br/>
			Asta inseamna ca jucatorii isi vor mai termina un sezon din contractul pe care-l au cu clubul tau. Daca in acest moment mai au doar un singur sezon contract, in urma trecerii la sezonul 5, vor deveni jucatori liberi! Asadar, daca inca ii vrei in echipa, prelungeste-le contractul!<br/>	
			<br/>
			Ca noutate, jucatorii care implinesc 34 de ani vor ajunge liberi de contract, indiferent de anii de contract ramasi. Pentru sezonul care urmeaza, antrenorii pot fi imbunatatiti, prin trimiterea la specializare.
			<br/><br/>
			Intra acum in joc si vezi care este stadiul echipei tale!
			<br/><br/><a href=\"http://www.CupaLigii.ro\">CupaLigii.ro</a>";
			$mes = wordwrap($mes, 110, "\r\n");
			//mail('fcbrasov@yahoo.com', 'CupaLigii.ro - Pune-ti sigla! Marti, zi de meci!', $mes, $headers);
			mail($email, 'CupaLigii.ro - Trecerea la sezonul 5', $mes, $headers);
			echo "INACTIV :: $mes<br/><br/>";
			$i++;
//			if($i>0) break;
}
mysql_free_result($res);
?>