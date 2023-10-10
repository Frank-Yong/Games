<?php

include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');
error_reporting(63);
//process the records from table requests
//se preiau inregistrarile egale sau mai vechi decit data curenta

//generez si mesajele de antrenament, prin verificarea tabelului logcresteri pe ziua precedenta


$sql = "SELECT id, userid, data, categorie, detaliu
		FROM requests
		WHERE data<='".Date("Y-m-d"). "' AND procesat = 0
		ORDER BY id ASC";
echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
while(list($reqid, $userid, $data, $categorie, $detaliu) = mysqli_fetch_row($res)) {
	switch ($categorie) {
		case "Eveniment":
				//trebuie implementata partea cu zi libera pentru jucatori
				if ($detaliu == 1) {
					//day off for the players
					$s1 = "SELECT a.playerid, b.form
						   FROM userplayer a
						   LEFT JOIN player b
						   ON a.playerid=b.id
						   WHERE a.userid=$userid";
					$r1 = mysqli_query($GLOBALS['con'],$s1);
					while(list($plid, $forma) = mysqli_fetch_row($r1)) {
						$fo = ($forma+10>100)? 100 : $forma+10;
						$s2 = "UPDATE player SET Form=$fo WHERE id=$plid";
						mysqli_query($GLOBALS['con'],$s2);
					}
					mysqli_free_result($r1);
					
					//set as processed
					$sql = "UPDATE requests SET procesat=1 WHERE id=$reqid";
					mysqli_query($GLOBALS['con'],$sql);

				}
				
				if ($detaliu == 3) {
					//tournament to find young player
					$rnd = rand(0,100);
					$bani = rand(5000,8000);
					
					if($rnd>30) {
						echo "Am gasit jucator<br/>";
						//a gasit jucator tinar, trebuie generat
						//trebuie retrasi si bani din cont
						//trimis mail
						$sql = "UPDATE user SET Funds=Funds-$bani WHERE id=$userid";
						mysqli_query($GLOBALS['con'],$sql);
						//echo "$sql<br/>";
						
						$young = 1;//il fac tinar
						$coeficient_liga = 1;

						$country=3; //Este roman
						//random la pozitia din teren
						$poz = rand(1,10);
						//aici urmeaza definirea de var
						$den = "juc".$i;
						$$den = new Player($userid, 0, $country, $young, $poz, $coeficient_liga);
						$$den->EchoPlayer();

						$txt = "Salut!<br/>Sunt adjunctul tau in ceea ce priveste recrutarea de noi talente. Cu bucurie iti spun ca am gasit un pusti care merita sa-l luam langa noi, are reale calitati. Numele lui este ".$$den->FirstName." ".$$den->LastName." si s-a alaturat lotului mare! Costurile totale de organizare a turneului au fost de $bani &euro;.";
						$sql = "INSERT INTO messages(fromID, toID, subject, data, body, meciID)
								VALUES(0,$userid, 'Turneu cautare talente','".Date("Y-m-d")."', '$txt', 0)"; 
						mysqli_query($GLOBALS['con'],$sql);
						echo "$sql<br/>";
					} else {
						$sql = "UPDATE user SET Funds=Funds-$bani WHERE id=$userid";
						mysqli_query($GLOBALS['con'],$sql);
						echo "$sql<br/>";
						
						
						$txt = "Salut!<br/>Sunt adjunctul tau in ceea ce priveste recrutarea de noi talente. Din pacate nu am gasit nici un tanar jucator care sa indeplineasca cerintele... Mai cautam! Costurile totale de organizare a turneului au fost de $bani &euro;";
						$sql = "INSERT INTO messages(fromID, toID, subject, data, body, meciID)
								VALUES(0,$userid, 'Turneu cautare talente','".Date("Y-m-d")."', '$txt', 0)"; 
						mysqli_query($GLOBALS['con'],$sql);
						//echo "$sql<br/>";
					}
					//procesat trece in 1
					$sql = "UPDATE requests SET procesat=1 WHERE id=$reqid";
					mysqli_query($GLOBALS['con'],$sql);
					echo "$sql<br/>";
				}
				break;
		case "sponsori":
				echo "Request sponsori: $userid - $data<br/>";
				//inserare in sponsoribuffer 
				//se insereaza si in messages daca reuseste sa scrie in sponsoribuffer
				$perioada = rand(1,3);
					
					$user = new user();
					$user->LoginID($userid);
					$rati = $user->GetRating();

				$valoare = 80000+$rati/25*100000;
				$valoaresponsor = rand($valoare*.8,$valoare*1.2);
				$sql = "INSERT INTO sponsoribuffer(sponsorid, userid, data, pozitie, perioada, pret, sezon)
						VALUES(1,$userid, '".DATE("Y-m-d")."', $detaliu,$perioada,$valoaresponsor,$_SEASON)";
				echo "$sql<br/>";
				mysqli_query($GLOBALS['con'],$sql);
				
				
				$sbid = mysqli_insert_id($GLOBALS['con']);
				echo "ID-ul inreg din sponsoribuffer: $sbid<br/>";
				
				$sql = "SELECT id, pret, perioada FROM sponsoribuffer
						WHERE id=$sbid";
				$res2 = mysqli_query($GLOBALS['con'],$sql);
				list($u_id, $u_pret, $u_perioada) = mysqli_fetch_row($res2);
				mysqli_free_result($res2);
				
				if($u_id>0) {
					$sql = "INSERT INTO messages(fromID, toID, subject, body, data, citit, meciID, sponsor)
							VALUES(0, $userid, 'Sponsorship offer!', 'You have received a sponsorship offer for ".number_format($u_pret)." &euro;/season, for $u_perioada seasons!', '".Date("Y-m-d H:i:s")."',0,0,$sbid)";
					echo "$sql<br/>";
					mysqli_query($GLOBALS['con'],$sql);
					$sql = "UPDATE requests SET spbuffer=$sbid WHERE id=$reqid";
					echo "$sql<br/>";
					mysqli_query($GLOBALS['con'],$sql);
				}
				break;
	}
}
mysqli_free_result($res);


/////////////////////////////////////////////////////
//generare mesaj cu antrenamentul din ziua precedenta
/////////////////////////////////////////////////////

$sql = "SELECT a.playerid, c.fname, c.lname, a.caracteristica, a.data, b.userid
		FROM logcresteri a
		LEFT JOIN userplayer b
		ON a.playerid=b.playerid
		LEFT JOIN player c
		ON a.playerid=c.id
		WHERE b.userid is not null AND a.data='".date('Y-m-d', strtotime(' -1 day'))."' ORDER BY b.userid ASC";
echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
$uid = 0;
while(list($plid, $fname, $lname, $caracteristica, $data, $userid) = mysqli_fetch_row($res)) {		
	if($uid<>$userid) {
		if($uid<>0) {
			//trimit mesajul
			$mes_1 = "Salut,<br/>Sunt asistentul tau responsabil pentru antrenamente! Te anunt ca urmatorii jucatori s-au pregatit foarte bine si au crescut la urmatoarele calitati:<br/>$lista";
			$sql = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
					VALUES(0, $uid, 'Antrenament', '$mes_1', '".Date("Y-m-d")."', 0, 0)";
			mysqli_query($GLOBALS['con'],$sql);
			echo "$sql<br/>";
			$lista = "";

		}
		$uid=$userid;
	}
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
	$lista .= "<a href=\"index.php?option=viewplayer&pid=$plid&uid=$userid\" class=\"link-2\">$fname $lname</a> ($deafisat)<br/>";
	
}
mysqli_free_result($res);

//trimit mesajul
if($uid<>0) {
	$mes_1 = "Salut,<br/>Sunt asistentul tau responsabil pentru antrenamente! Te anunt ca urmatorii jucatori s-au pregatit foarte bine si au crescut la urmatoarele calitati:<br/>$lista";
	$sql = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
			VALUES(0, $userID, 'Antrenament', '$mes_1', '".Date("Y-m-d")."', 0, 0)";
	mysqli_query($GLOBALS['con'],$sql);
	$lista = "";
}



function generareOfertaSponsori($userid, $detaliu) {
//se genereaza oferta in functie de user rating, pozitionare banner
//1,2,3 - bannere mari
//4,5,6,7 - mici
//8,9,10 - mari
}

?>