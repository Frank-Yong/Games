<?php 
include('app.conf.php');
include('player.php');
include('UserStadium.php');
include('trainer.php');



if(!empty($_REQUEST['BuyParking'])) {
	$sql = "SELECT id, data, existent, nou FROM facilitati
			WHERE tip='parcare' AND userid=".$_SESSION['USERID']. " ORDER BY id DESC";
	$res = mysqli_query($GLOBALS['con'], $sql);
	list($f_id, $f_data, $f_existent, $f_nou) = mysqli_fetch_row($res);
	
	$total = $_REQUEST['locuri'];
	$pretmarire = 200*$total;

	$user = new user();
	$user->LoginID($_SESSION['USERID']);
	$fonduri = $user->Fonduri();
	$sepoate = 1;
	if($fonduri*.67<$pretmarire) $sepoate = 0;
	$d = Date('Y-m-d', strtotime("+7 days"));
	
	if(is_null($f_id) ) {
		//nu exista inregistrare
		
		$sql = "INSERT INTO facilitati(data, existent, nou, userid, tip)
				VALUES('$d', 0, $total,".$_SESSION['USERID'].", 'parcare')";
		mysqli_query($GLOBALS['con'], $sql);
		
		$sql = "UPDATE user SET Funds=Funds-$pretmarire WHERE id=".$_SESSION['USERID'];
		mysqli_query($GLOBALS['con'], $sql);
		
		
	} else {
		if($f_data == '0000-00-00') {
			//este terminata constructia altor locuri, deci pot face altele
			$sql = "INSERT INTO facilitati(data, existent, nou, userid, tip)
					VALUES('$d', $f_existent, $total, ".$_SESSION['USERID'].", 'parcare')";
			//echo "$sql<br/>";
			mysqli_query($GLOBALS['con'], $sql);
			
			$sql = "UPDATE user SET Funds=Funds-$pretmarire WHERE id=".$_SESSION['USERID'];
			mysqli_query($GLOBALS['con'], $sql);

		} else {
			$_SESSION['_MESSAGE'] =  "<br/><h3>Nu se executa actiunea pentru ca exista deja in constructie altele!</h3>";
		}
	}
	mysqli_free_result($res);
}

if(!empty($_REQUEST['SearchTeam'])) {
	$whereNume = "";
	if(!empty($_REQUEST['NumeEchipa'])) {
		$whereNume = "AND a.TeamName LIKE '%".$_REQUEST['NumeEchipa']."%'";
		$sql = "SELECT a.id, a.TeamName, a.Username, b.name, a.LeagueID, a.Rating, a.LastActive 
				FROM user a
				LEFT JOIN country b
				ON a.CountryID=b.id
				WHERE a.activated=1 $whereNume 
				ORDER BY a.LastActive DESC";
		$res = mysqli_query($GLOBALS['con'], $sql);
		//echo "$sql<br/>";
		while($row = mysqli_fetch_array($res)) {
			$result[] = $row;
			//echo $row['Username']."<br/>";
		}
		$_SESSION['query'] = $result;
		//var_dump($_SESSION['query']);
		mysqli_free_result($res);
		//echo "inainte de header";
		header("Location: index.php?option=searchteam2");
		exit;
	}	
}

if(!empty($_REQUEST['SearchPl'])) {
	$whereGrupa = "";
	$wherePozitie = "";
	$whereReflexe = "";
	$whereUnulaunu = "";
	$whereManevrare = "";
	$whereMarcaj = "";
	$whereDeposedare = "";
	$whereHeading = "";
	$whereLong = "";
	$wherePozitionare = "";
	$whereSut = "";
	$whereAtingere = "";
	$whereCreativitate = "";
	$whereLansari = "";
	$wherePase = "";
	if($_REQUEST['grupa']>0)
		$whereGrupa = "AND b.youth=".$_REQUEST['grupa'];
	if($_REQUEST['pozitie']>0)
		$wherePozitie = "AND b.Position=".$_REQUEST['pozitie'];
	if($_REQUEST['reflexeLow']<>1 || $_REQUEST['reflexeHigh']<>50)
		$whereReflexe = "AND (b.Reflexes between ".$_REQUEST['reflexeLow']." AND ".$_REQUEST['reflexeHigh'].")";
	if($_REQUEST['unulaunuLow']<>1 || $_REQUEST['unulaunuHigh']<>50)
		$whereUnulaunu = "AND (b.OneOnOne between ".$_REQUEST['unulaunuLow']." AND ".$_REQUEST['unulaunuHigh'].")";
	if($_REQUEST['manevrareLow']<>1 || $_REQUEST['manevrareHigh']<>50)
		$whereManevrare = "AND (b.Handling between ".$_REQUEST['manevrareLow']." AND ".$_REQUEST['manevrareHigh'].")";
	if($_REQUEST['deposedareLow']<>1 || $_REQUEST['deposedareHigh']<>50)
		$whereDeposedare = "AND (b.Tackling between ".$_REQUEST['deposedareLow']." AND ".$_REQUEST['deposedareHigh'].")";
	if($_REQUEST['marcajLow']<>1 || $_REQUEST['marcajHigh']<>50)
		$whereMarcaj = "AND (b.Marking between ".$_REQUEST['marcajLow']." AND ".$_REQUEST['marcajHigh'].")";
	if($_REQUEST['headingLow']<>1 || $_REQUEST['headingHigh']<>50)
		$whereHeading = "AND (b.Heading between ".$_REQUEST['headingLow']." AND ".$_REQUEST['headingHigh'].")";
	if($_REQUEST['longLow']<>1 || $_REQUEST['longHigh']<>50)
		$whereLong = "AND (b.LongShots between ".$_REQUEST['longLow']." AND ".$_REQUEST['longHigh'].")";
	if($_REQUEST['pozitionareLow']<>1 || $_REQUEST['pozitionareHigh']<>50)
		$wherePozitionare = "AND (b.Positioning between ".$_REQUEST['pozitionareLow']." AND ".$_REQUEST['pozitionareHigh'].")";
	if($_REQUEST['sutLow']<>1 || $_REQUEST['sutHigh']<>50)
		$whereSut = "AND (b.Shooting between ".$_REQUEST['sutLow']." AND ".$_REQUEST['sutHigh'].")";
	if($_REQUEST['atingereLow']<>1 || $_REQUEST['atingereHigh']<>50)
		$whereAtigere = "AND (b.FirstTouch between ".$_REQUEST['atingereLow']." AND ".$_REQUEST['atingereHigh'].")";
	if($_REQUEST['creativitateLow']<>1 || $_REQUEST['creativitateHigh']<>50)
		$whereCreativitate = "AND (b.Creativity between ".$_REQUEST['creativitateLow']." AND ".$_REQUEST['creativitateHigh'].")";
	if($_REQUEST['lansariLow']<>1 || $_REQUEST['lansariHigh']<>50)
		$whereLansari = "AND (b.Crossing between ".$_REQUEST['lansariLow']." AND ".$_REQUEST['lansariHigh'].")";
	if($_REQUEST['paseLow']<>1 || $_REQUEST['paseHigh']<>50)
		$wherePase = "AND (b.Passing between ".$_REQUEST['paseLow']." AND ".$_REQUEST['paseHigh'].")";



	//cauta toti jucatorii care sunt pe lista de transfer si a caror pariere nu a inceput
	$sql = "SELECT b.id, b.fname, b.lname, b.TransferDeadline, c.name, b.Rating, b.Age, b.Value, f.TeamName, b.TransferSuma, f.id, b.Position
		FROM player b
		LEFT OUTER JOIN userplayer e
		ON e.PlayerID=b.id
		LEFT OUTER JOIN user f
		ON f.id=e.UserID
		LEFT OUTER JOIN country c 
		ON c.id=b.Nationality
		WHERE b.TransferDeadline='0000-00-00 00:00:00' AND b.Transfer=1 $whereGrupa $wherePozitie $whereReflexe $whereUnulaunu $whereManevrare $whereMarcaj $whereDeposedare
				$whereHeading $whereLong $wherePozitionare $whereSut $whereAtingere $whereCreativitate $whereLansari $wherePase
		ORDER BY b.TransferDeadline ASC";
		//echo "$sql<br/>";
		$res = mysqli_query($GLOBALS['con'], $sql);

		while($row = mysqli_fetch_array($res)) {
			$result[] = $row;
		}
		//var_dump($result);
		$_SESSION['qplayers'] = $result;
		mysqli_free_result($res);
		header("Location: index.php?option=searchplayers2");
		exit;
}

if(!empty($_REQUEST['MaresteCapacitate'])) {
	//verifica mai intii daca are fonduri disponibile
	
	$s1 = $_REQUEST['s1_build'];
	$s2 = $_REQUEST['s2_build'];
	$s3 = $_REQUEST['s3_build'];
	$s4 = $_REQUEST['s4_build'];
	$s5 = $_REQUEST['s5_build'];
	$s6 = $_REQUEST['s6_build'];
	$s7 = $_REQUEST['s7_build'];
	$s8 = $_REQUEST['s8_build'];
	
	$total = $s1+$s2+$s3+$s4+$s5+$s6+$s7+$s8;
	//1 loc = 500 euro
	$pretmarire = 500*$total;

	$user = new user();
	$user->LoginID($_SESSION['USERID']);
	$fonduri = $user->Fonduri();
	$stadionID = $user->StadiumID;
	$sepoate = 1;
	if($fonduri*.67<$pretmarire) $sepoate = 0;
	
	if($sepoate==1) {
		//daca se dau mai multe sectoare la marit, maresc si datele de la un sector la altul
		//nu se vor termina toate in acelasi timp
		//daca exista deja sectoare la marit, sa tina seama de ele si sa mareasca timpul de executie
		$stad = new Stadium($stadionID);
		$co = 0;
		$days = 21;
		//echo "In constructie sunt ".$stad->Construction1.'<br/>';
		if($stad->Construction1>0) $co++;
		if($stad->Construction2>0) $co++;
		if($stad->Construction3>0) $co++;
		if($stad->Construction4>0) $co++;
		if($stad->Construction5>0) $co++;
		if($stad->Construction6>0) $co++;
		if($stad->Construction7>0) $co++;
		if($stad->Construction8>0) $co++;
		$days += $co*7;
		
		$d = Date('Y-m-d', strtotime("+$days days"));
		$i=0;
		if($s1>0) { 
			$update[$i++] = "construction1 = $s1, data1='$d'";
			$d = date('Y-m-d', strtotime($d. ' + 7 days'));
		}
		if($s2>0) { 
			$update[$i++] = "construction2 = $s2, data2='$d'";
			$d = date('Y-m-d', strtotime($d. ' + 7 days'));
		}
		if($s3>0) { 
			$update[$i++] = "construction3 = $s3, data3='$d'";
			$d = date('Y-m-d', strtotime($d. ' + 7 days'));
		}
		if($s4>0) { 
			$update[$i++] = "construction4 = $s4, data4='$d'";
			$d = date('Y-m-d', strtotime($d. ' + 7 days'));
		}
		if($s5>0) { 
			$update[$i++] = "construction5 = $s5, data5='$d'";
			$d = date('Y-m-d', strtotime($d. ' + 7 days'));
		}
		if($s6>0) { 
			$update[$i++] = "construction6 = $s6, data6='$d'";
			$d = date('Y-m-d', strtotime($d. ' + 7 days'));
		}
		if($s7>0) { 
			$update[$i++] = "construction7 = $s7, data7='$d'";
			$d = date('Y-m-d', strtotime($d. ' + 7 days'));
		}
		if($s8>0) { 
			$update[$i++] = "construction8 = $s8, data8='$d'";
			$d = date('Y-m-d', strtotime($d. ' + 7 days'));
		}
		$up = implode(', ', $update);
		$sql = "UPDATE stadium SET $up WHERE id=".$user->StadiumID;
		mysqli_query($GLOBALS['con'], $sql);
		
		$sql = "UPDATE user SET Funds=Funds-$pretmarire WHERE id=".$_SESSION['USERID'];
		mysqli_query($GLOBALS['con'], $sql);
	}
	
	header("Location: stadium.php");
	exit;
	
}

if(!empty($_REQUEST['delSchimbare'])) {
	$sql = "DELETE FROM schimbari WHERE userid=".$_SESSION['USERID']." AND id=".$_REQUEST['delSchimbare'];
	mysqli_query($GLOBALS['con'], $sql);
	header("Location: index.php?option=tactics");
	exit;
}

if(!empty($_REQUEST['Respecializare'])) {
	//trimiti jucatorul la trainingul de respecializare post
	//va fi indisponibil 2 saptamani si costa 100.000
	if($_SESSION['USERID'] == $_REQUEST['uid']) {
		$sql = "SELECT id FROM respecializare WHERE userid=".$_SESSION['USERID'];
		$rr = mysqli_query($GLOBALS['con'], $sql);
		$nr = mysqli_num_rows($rr);
		mysqli_free_result($rr);
		//echo "RESPECIALIZATI :: $nr<br/>";
		//doar doi jucatori are voie in acelasi timp sa trimita la respecializare
		if($nr<=1) {
			$sql = "UPDATE user SET Funds=Funds-100000 WHERE id=".$_SESSION['USERID'];
			mysqli_query($GLOBALS['con'], $sql);
			$disponibil = date('Y-m-d 00:00:00', strtotime("+2 weeks"));
			$sql = "INSERT INTO respecializare (userid, playerid, post, data)
					VALUES(".$_SESSION['USERID'].",".$_REQUEST['pid'].", ".$_REQUEST['postnou'].",'$disponibil')";
			mysqli_query($GLOBALS['con'], $sql);
			$sql = "UPDATE echipastart SET post=0 WHERE playerid=".$_REQUEST['pid'];
			mysqli_query($GLOBALS['con'], $sql);
			$sql = "UPDATE player SET training=1 WHERE id=".$_REQUEST['pid'];
			mysqli_query($GLOBALS['con'], $sql);
			header("Location: index.php?option=club");
			exit;
		} else {
			$_SESSION['_MESSAGE'] = 'Doar 2 jucatori pot fi respecializati in acelasi timp!';		
		}
	}
}


if(!empty($_REQUEST['ConcediazaAntrenor'])) {
	$tr = new Trainer(1,$_REQUEST['trainerid']);
	$tr->Fire();
	header("Location: index.php?option=club");
	exit;
}


if(!empty($_REQUEST['AlegeSchimbari'])) {
	//$_REQUEST['rezerva'], $_REQUEST['titular'], $_REQUEST['minut'], $_REQUEST['conditie'], $_REQUEST['post']
	$sql = "INSERT INTO schimbari (playerid1, playerid2, minut, conditie1, post, userid, grupa)
			VALUES(".$_REQUEST['rezerva'].",".$_REQUEST['titular'].",".$_REQUEST['minut'].",".$_REQUEST['conditie'].",".$_REQUEST['post'].", ".$_SESSION['USERID'].", ".$_REQUEST['grupa'].")";
	mysqli_query($GLOBALS['con'], $sql);
	header("Location: index.php?option=tactics");
	exit;
}

if(!empty($_REQUEST['SetParola'])) {
	//$_REQUEST['cod'] = codul trimis pe mail
	//cu el aflu userid pt care se reseteaza parola
	if($_REQUEST['password'] <> $_REQUEST['repassword']) {
		$_SESSION['_MESSAGE'] = 'Nu ati introdus aceeasi parola!';
		header("Location: index.php?option=lostpass&key=".$_REQUEST['cod']);
		exit;

	} else {
		$uti=0;
		$sql = "SELECT userid FROM parolapierduta WHERE cod='".$_REQUEST['cod']."'";
		$res = mysqli_query($GLOBALS['con'], $sql);
		list($uti) = mysqli_fetch_row($res);
		if($uti>0) {
			$s2 = "UPDATE user SET password='".md5($_REQUEST['password'])."' where id=$uti";
			mysqli_query($GLOBALS['con'], $s2);
			
			$_SESSION['_MESSAGE'] = 'Parola a fost schimbata, incercati sa va logati!';
		}
		mysqli_free_result($res);
	}
	
}

if(!empty($_REQUEST['ResetParola'])) {
	//caut in bd daca exista emailul
	//daca exista, trimit mail ca sa apese pe un link, care sa-l duca in forma cu introducere noua parola
	//in functie de acel cod, stiu ce user este. cind introduc in bd, introduc intr-un tabel de cereri, cu user, cod si data (parolapierduta este tabelul)
	$utilizator = "";
	$sql = "SELECT id, teamname, username FROM user WHERE email = '".$_REQUEST['email']."'";
	$res = mysqli_query($GLOBALS['con'], $sql);
	list($utilizator, $teamname, $username) = mysqli_fetch_row($res);
	if($utilizator<> "") {
		$cod = md5(microtime().rand());
		$s2 = "INSERT INTO parolapierduta (userid, cod, data)
			   VALUES($utilizator, '$cod', '".Date("Y-m-d H:i:s")."')";
		mysqli_query($GLOBALS['con'], $s2);
		
		$_mes = "Salut!\r\nAm primit o cerere pentru schimbare parola pentru utilizatorul $username (echipa $teamname). Daca tu ai facut cererea, continua prin apasarea link-ului urmator, altfel ignora mesajul!\r\nhttp://www.CupaLigii.ro/index.php?option=lostpass&key=".$cod;
		$_mes = wordwrap($_mes, 70, "\r\n");
		//echo "Trimitere mail de confirmare<br/>";
		mail($_REQUEST['email'], 'Schimbare parola CupaLigii.ro!', $_mes);
		mail('fcbrasov@yahoo.com', 'Schimbare parola CupaLigii.ro!', $_mes);
		$_SESSION['_MESSAGE'] = 'Un email a fost trimis cu detaliile pentru resetarea parolei!';
		}
	mysqli_free_result($res);
	
}

if(!empty($_REQUEST['SendSigla'])) {
	$file_upload="true";
	$file_up_size=$_FILES['file_up']['size'];
	//echo 'Nume fisier: '.$_FILES['file_up']['name'];
	if ($_FILES['file_up']['size']>150000){
		$_SESSION['_MESSAGE'] = 'Dimensiune prea mare. Trebuie sa fie <150KB!';		
		$file_upload="false";
	}

	//echo 'Tip fisier: '.$_FILES['file_up']['type'].'<br/>';
	
	if (!($_FILES['file_up']['type'] =="image/jpg" || $_FILES['file_up']['type'] =="image/jpeg" || $_FILES['file_up']['type'] =="image/gif")){
		$_SESSION['_MESSAGE'] = 'Tipul de fisier nu este corect!';
		$file_upload="false";
	}

	$file_name=$_FILES['file_up']['name'];
	$add="upload/".$_SESSION['USERID'].'.jpg'; // the path with the file name where the file will be stored

	if($file_upload=="true"){
		unlink($add);
		if(move_uploaded_file ($_FILES['file_up']['tmp_name'], $add)){
			$sql = "UPDATE user SET poza='$add' WHERE id=".$_SESSION['USERID'];
			mysqli_query($GLOBALS['con'], $sql);
			
			header("Location:index.php?option=modificaCont");
			exit;

		}else{
					$_SESSION['_MESSAGE'] = 'A intervenit o problema si nu s-a putut procesa comanda!';
		}
	}else{
		echo $msg;
	}  
}

if(!empty($_REQUEST['AlegeAntrenament'])) {
	//echo "sunt aici";
	$sql = "SELECT p.id 
		FROM user u 
		LEFT OUTER JOIN userplayer up
		ON up.UserID=u.id
		LEFT OUTER JOIN player p
		ON up.PlayerID=p.id
		WHERE  u.id=" . $_SESSION['USERID'] . " ORDER BY p.Position ASC";

	$res = mysqli_query($GLOBALS['con'], $sql);
	while(list($pid) = mysqli_fetch_row($res)) {
		$update = "UPDATE trainerplayer SET post = ". $_REQUEST['post_'.$pid] . ", trainerid=".$_REQUEST['antrenor_'.$pid].", tip=".$_REQUEST['tip_'.$pid]." WHERE playerId=$pid";
		//echo "$update<br/>";
		mysqli_query($GLOBALS['con'], $update);

	}
	mysqli_free_result($res);

}

if(!empty($_REQUEST['SetBilete'])) {
		//echo "sunt aici in setbilete<br/>";
		$stad = new Stadium($_SESSION['STADIUMID']);
		$stad->Pret = $_REQUEST['PretB'];
		header('Location: stadium.php');
		exit;
}

if(!empty($_REQUEST['Angajeaza'])) {
	//scaderea bonusului
	$trainerid = $_REQUEST['trainerid'];
	$bonus = $_REQUEST['bonus'];
	$salariu = $_REQUEST['salariu'];
	//verificare mai intii daca are bani suficienti in cont
	$user = new user();
	$user->LoginID($_SESSION['USERID']);
	$fonduri = $user->Fonduri();
	$insuficientibani = 0;
	$existaantrenor = 0;
	$sql = "SELECT id FROM usertrainer WHERE userid=".$_SESSION['USERID'];
	$res = mysqli_query($GLOBALS['con'], $sql);
	$nrantrenori = mysqli_num_rows($res);
	if($nrantrenori>0) $existaantrenor = 1;
	mysqli_free_result($res);
	
	if($existaantrenor == 1) {
		$_SESSION['_MESSAGE'] = 'Deja ai antrenor!';
		
	}
	
	if($fonduri<$bonus+$salariu) {
		//nu poate paria pentru ca fondurile nu sunt suficiente
		$insuficientibani = 1;
		$_SESSION['_MESSAGE'] = 'Bani insuficienti in cont!';
	}
	if($insuficientibani==0 && $existaantrenor==0) {
		//angajeaza antrenor
		$sql = "INSERT INTO usertrainer (userid, trainerid)
				VALUES(".$_SESSION['USERID'].", $trainerid)";
		mysqli_query($GLOBALS['con'], $sql);
		$id = mysqli_insert_id($GLOBALS['con']);
		
		$sql = "SELECT id FROM usertrainer WHERE id=$id";
		$res = mysqli_query($GLOBALS['con'], $sql);
		$nr = mysqli_num_rows($res);
		mysqli_free_result($res);
		
		if($nr>0) {
			//scade bonusul
			$sql = "UPDATE user SET Funds=Funds-$bonus WHERE id=".$_SESSION['USERID'];
			mysqli_query($GLOBALS['con'], $sql);
			
			//il pun ca fiind sub contract
			$sql = "UPDATE trainer SET Contract=1 WHERE id=$trainerid";
			mysqli_query($GLOBALS['con'], $sql);
			
			
			
			
			//inserare in tabel pentru cheltuieli
			$sql = "INSERT INTO balanta(userid, suma, motiv, sezon)
					VALUES(".$_SESSION['USERID'].", -$bonus, 'Antrenor', ".$_SEZON.")";
			mysqli_query($GLOBALS['con'], $sql);
		}
	}	

}
//join
if(!empty($_REQUEST['Inscriere'])) {
	//require_once 'securimage.php';
		$stadiumname = $_REQUEST['stadiumName'];
		$teamname = $_REQUEST['teamName'];
		$username = $_REQUEST['userName'];
		$password = $_REQUEST['password'];
		$email = $_REQUEST['email'];
	//check if the same name exists
	$sql = "SELECT id FROM user WHERE teamname='".$_REQUEST['teamName']."' OR email='$email' OR username='$username'";
	$res = mysqli_query($GLOBALS['con'], $sql);
	$acelasi_nume = mysqli_num_rows($res);
	mysqli_free_result($res);
	//echo "ACELASI NUME: $acelasi_nume<br/>";
	if($acelasi_nume>0) {
      		$_MESSAGE = 'Same name or same team already exists!!!';
			header("Location: index.php?option=register&stadiumName=$stadiumname&teamName=$teamname&userName=$username&email=$email&mes=$_MESSAGE");
			exit;
	}
	
				$activationkey = md5(microtime().rand());
				$user = new user();
				
				$user->CreateTeam($teamname, $stadiumname, $username, md5($password), $email, $activationkey);

				$idcreat = 0;
				$idcreat = $user->ReturnID();

				//CreareEchipa($idcreat);

							
				$_SESSION['_MESSAGE'] = "Register succesfully! Check your email for activation key! Look for it also in Spam/Junk. Enjoy the game!";

				mail($email, "Register to myfm.com", "Hi, <br/>Please activate account by pressing the following link (user name $username, team name $teamname):<br/>http://www.CupaLigii.ro/index.php?option=activare&key=$activationkey");
			
	}


if(isset($_REQUEST['option'])) {
	if($_REQUEST['option'] == "logoff") {
		$sql = "UPDATE useronline SET online=0 WHERE userid=".$_SESSION['USERID'];
		mysqli_query($GLOBALS['con'], $sql);

		$_SESSION['USERID'] = 0;
	}
	if($_REQUEST['option'] == "messages") {
		if(isset($_REQUEST['accept'])) {
			//s-a primit accept pt un amical
			$sql = "UPDATE invitatiemeci SET accepted=1 WHERE id=".$_REQUEST['accept'];
			mysqli_query($GLOBALS['con'], $sql);
			
			$sql = "SELECT userId_1, userId_2, datameci FROM invitatiemeci WHERE id=".$_REQUEST['accept'];
			$res = mysqli_query($GLOBALS['con'], $sql);
			list($ech1, $ech2, $dmeci) = mysqli_fetch_row($res);
			if($ech1 == $_SESSION['USERID']) $to = $ech2;
			else $to = $ech1;
				$data_curenta = Date("Y-m-d");
				$body = 'Invitatia ta pentru meciul din data '.$dmeci.' a fost acceptata!';
				$sql = "INSERT INTO messages (fromID, toID, subject, body, data, meciID)
						VALUES (0, $to, 'Invitatie la amical, acceptata!', '$body', '$data_curenta', 0)";
				//echo "$sql<br/>";
				mysqli_query($GLOBALS['con'], $sql);			
			mysqli_free_result($res);
			
			//cind se seteaza cimpul accepted pe 1 (s-a dat ok la invitatia de amical)
			//se trece aceasta inregistrare in tabela 'etapa', cu numar=0, sezon=0 si idcompetitie=-5000 (amical)
			//default in bd, aceste cimpuri au aceste valori - setat pt amical default
			$sql = "INSERT INTO etapa(iduser1, iduser2, data, idstadium)
					SELECT userId_1, userId_2, datameci, stadium FROM invitatiemeci
					WHERE id=".$_REQUEST['accept'];
			//echo "$sql";
			mysqli_query($GLOBALS['con'], $sql);
		}
		if(isset($_REQUEST['deny'])) {
			//s-a primit accept pt un amical
			$sql = "UPDATE invitatiemeci SET accepted=0 WHERE id=".$_REQUEST['deny'];
			mysqli_query($GLOBALS['con'], $sql);
		}
	}

}

if(isset($_REQUEST['TrimiteMesaj'])) {
	$subject = $_REQUEST['subiect'];
	$body = $_REQUEST['mesaj'];
	$data_curenta = Date("Y-m-d");
	
	$sql = "INSERT INTO messages (fromID, toID, subject, body, data, meciID)
			VALUES (".$_SESSION['USERID'].", ".$_REQUEST['to']. ", '$subject', '$body', '$data_curenta', 0)";
	mysqli_query($GLOBALS['con'], $sql);

}

if(!empty($_REQUEST['SetEveniment'])) {
	$data_curenta = Date("Y-m-d");
	$data = date("Y-m-d",strtotime($_REQUEST['date5']));
	//REQUEST[ev]
	//cauta daca exista meci in ziua aceea
	//daca exista, nu introduce
	$num=0;
	$sql = "SELECT id FROM invitatiemeci WHERE accepted=1 AND datameci='$data' AND (userId_1=".$_SESSION['USERID'] . " OR userId_2=". $_SESSION['USERID']. ")";
	$res = mysqli_query($GLOBALS['con'], $sql);
	$num = mysqli_num_rows($res);
	mysqli_free_result($res);
	
	if($num==0) { 
		$sql = "INSERT INTO evenimente (tip, data, userid)
				VALUES(".$_REQUEST['ev'].", '$data',".$_SESSION['USERID'].")";
		$res = mysqli_query($GLOBALS['con'], $sql);
		//echo "$sql<br/>";
		
		//inserez si in requests, sa fie procesat inziua aceea
		$sql = "INSERT INTO requests (userid, data, categorie, detaliu, procesat)
				VALUES(".$_SESSION['USERID'].",'$data', 'Eveniment', ".$_REQUEST['ev'].", 0)";
		//echo "$sql<br/>";
		$res = mysqli_query($GLOBALS['con'], $sql);
	} else $_SESSION['_MESSAGE'] = 'Eveniment neinregistrat (exista meci in acea zi)';
	header('Location:index.php?option=calendar');
	exit;
}

if(isset($_REQUEST['SetAmical'])) {
	//se baga in tabela 'meci' invitatia de meci, cu cimpul accepted=-1 (default)
	//se baga in tabela 'messages' invitatia
	//daca apasa pe 'accept', se seteaza in cimpul din tabela meci accepted=1
	//daca se apasa pe 'deny', se seteaza cimpul accepted = 0 (ca sa se stie ca s-a apasat pe buton, sa nu mai poti da inapoi)
	//in tablea 'messgaes', in cazul in care exista o invitatie la meci, se pune id-ul meciului pe cimpul meciID
	//daca mesajul este fara invitatie la meci, cimpul acesta ramine necompletat
	$data_curenta = Date("Y-m-d H:i:s");
	$data = date("Y-m-d 13:00:00",strtotime($_REQUEST['date5']));

	//echo "DATA:   $data<br/>";

	if($data_curenta>$data) {
		$_SESSION['_MESSAGE'] = 'Invitatia nu a fost trimisa, meciul nu se poate disputa in acea data!';
	} else {
		$sql = "SELECT email, botteam, stadiumid FROM user WHERE id=".$_REQUEST['club_id'];
		//echo "$sql<br>";
		$res = mysqli_query($GLOBALS['con'], $sql);
		list($emailadv, $estebot, $stadionadv) = mysqli_fetch_row($res);
		mysqli_free_result($res);

		if($estebot) $accepted=1;
		else $accepted=-1;
	
		if($_REQUEST['stadion'] == $stadionadv) {
			//se joaca la adversari
			$sql = "INSERT INTO invitatiemeci (UserId_1, UserId_2, tipMeci, datameci, grupaVirsta, stadium, accepted)
					VALUES(".$_REQUEST['club_id'].", ".$_SESSION['USERID'].", 10, '".$data."', 1, ".$_REQUEST['stadion'].", $accepted)";

		} else {
			//se joaca la mine acasa
			$sql = "INSERT INTO invitatiemeci (UserId_1, UserId_2, tipMeci, datameci, grupaVirsta, stadium, accepted)
					VALUES(".$_SESSION['USERID'].", ".$_REQUEST['club_id'].", 10, '".$data."', 1, ".$_REQUEST['stadion'].", $accepted)";
		}
	

		$res = mysqli_query($GLOBALS['con'], $sql);
		$meci_id = mysqli_insert_id($GLOBALS['con']);
		

		
		$sql = "SELECT name FROM stadium WHERE id=".$_REQUEST['stadion'];
		$res = mysqli_query($GLOBALS['con'], $sql);
		list($nume_stadion) = mysqli_fetch_row($res);
		mysqli_free_result($res);

		$sql = "SELECT TeamName FROM user WHERE id=".$_SESSION['USERID'];
		$res = mysqli_query($GLOBALS['con'], $sql);
		list($nume_adversar) = mysqli_fetch_row($res);
		mysqli_free_result($res);

		$subject = "Invitatie amical cu $nume_adversar";
		$body = "Clubul <b>$nume_adversar</b> te provoaca la un amical, pe data de $data, pe stadionul $nume_stadion. Pentru a accepta acest meci, apasa mai jos!";

		$sql = "INSERT INTO messages (fromID, toID, subject, body, data, meciID)
				VALUES (".$_SESSION['USERID'].", ".$_REQUEST['club_id']. ", '$subject', '$body', '$data_curenta', $meci_id)";
		mysqli_query($GLOBALS['con'], $sql);
		
		$mes = "Salut!\r\n\r\nAi primit o invitatie pentru un amical in managerul de fotbal CupaLigii.ro! Vezi daca o poti onora!\r\nhttp://www.CupaLigii.ro";
		$mes = wordwrap($mes, 70, "\r\n");
		if($estebot) {
		} else {
			mail($emailadv, 'Invitatie amical CupaLigii.ro!', $mes);
			mail('fcbrasov@yahoo.com', 'Invitatie amical CupaLigii.ro!', $mes);
		}
		//header("Location: index.php?option=searchteam");
		//exit;
		}
}

if(isset($_REQUEST['Login'])) {
	//dupa login normal
	$username = $_REQUEST['userName'];
	$password = $_REQUEST['password'];

	$user = new user();
	$user->Login($username, md5($password));

//	$user->EchoClub();

//	$user->ComputeTeamValues();

//	$user->EchoTeam();

}
if(isset($_REQUEST['userid'])) {
	//dupa registration_step1
	//vine id-ul inregistrarii prin GET, dupa home.php?id=...
	$user = new user();
	$user->LoginID($_REQUEST['userid']);
	
	$user->EchoClub();
	$user->EchoTeam();

}

if(!empty($_REQUEST['SendAbordare'])) {
		
		//tactica-mijlocul-atacuri-pase
		$tactica = $_REQUEST['tactica'];
		$mijloc = $_REQUEST['mijlocul'];
		$atacuri = $_REQUEST['atacuri'];
		$pase = $_REQUEST['pase'];
		$grupa = $_REQUEST['grupa'];
		
		$sql = "INSERT INTO tactica (tactica, mijlocul, atacuri, pase, userid, grupa)
				VALUES($tactica, $mijloc, $atacuri, $pase, ".$_SESSION['USERID'].", $grupa)";
		mysqli_query($GLOBALS['con'], $sql);
		
		$sql = "UPDATE tactica SET tactica=$tactica, mijlocul=$mijloc, atacuri=$atacuri, pase=$pase, grupa=$grupa WHERE grupa=$grupa AND userid=".$_SESSION['USERID'];
		mysqli_query($GLOBALS['con'], $sql);
		
		header("Location: index.php?option=tactics");
		exit;

}

if (isset($_REQUEST['Alege11'])) {
	//delete the players already in the first squad
	if($_REQUEST['grupa']==1) $grupastart=2;
	if($_REQUEST['grupa']==0) $grupastart=1;
	$sql = "DELETE FROM lineup
			WHERE pgroup=$grupastart AND userId=".$_SESSION['USERID'];
	mysqli_query($GLOBALS['con'], $sql);

	//aflare tactica
	/*
	$sql = "SELECT tactica FROM tactica WHERE userid=".$_SESSION['USERID'];
	$restac = mysqli_query($GLOBALS['con'], $sql);
	list($bdtac) = mysqli_fetch_row($restac);
	mysqli_free_result($restac);
	*/
//daca tactica e 4-4-2, atunci a stabilit 2 DC si un DL si un DR
//plus 2 MC si un ML si un MR
// si 2 atacanti FC

//daca a pus 5-4-1 : 3DC-DL-DR+2MC-ML-MR+FC s.a.m..d


/*
	switch($bdtac) {
		//4-4-2
	case 1: $f1=2;$f2=3;$f3=3;$f4=4;$f5=0; $m1=5;$m2=6;$m3=6;$m4=7;$m5=0; $a1=9;$a2=9;$a3=0;break;
		//5-3-2
	case 2: $f1=2;$f2=3;$f3=3;$f4=3;$f5=4; $m1=5;$m2=6;$m3=7;$m4=0;$m5=0; $a1=9;$a2=9;$a3=0;break;
		//3-5-2
	case 3: $f1=3;$f2=3;$f3=3;$f4=0;$f5=0; $m1=5;$m2=6;$m3=6;$m4=6;$m5=7; $a1=9;$a2=9;$a3=0;break;
		//5-4-1
	case 4: $f1=2;$f2=3;$f3=3;$f4=3;$f5=4; $m1=5;$m2=6;$m3=6;$m4=7;$m5=0; $a1=9;$a2=0;$a3=0;break;
		//4-5-1
	case 5: $f1=2;$f2=3;$f3=3;$f4=4;$f5=0; $m1=5;$m2=6;$m3=6;$m4=6;$m5=7; $a1=9;$a2=0;$a3=0;break;
		//4-3-3
	case 6: $f1=2;$f2=3;$f3=3;$f4=4;$f5=0; $m1=5;$m2=6;$m3=7;$m4=0;$m5=0; $a1=8;$a2=9;$a3=10;break;
	}
	
*/

	$sql = "SELECT p.id 
			FROM user u 
			LEFT OUTER JOIN userplayer up
			ON up.UserID=u.id
			LEFT OUTER JOIN player p
			ON up.PlayerID=p.id
			WHERE p.youth=".$_REQUEST['grupa']." AND u.id=" . $_SESSION['USERID'] . " ORDER BY p.Position ASC";
	//echo "$sql<br/>";
	$res = mysqli_query($GLOBALS['con'], $sql);

	$nrjuc=0;
	$gk = $fs = $fd = $fc = $ml = $mr = $mc = $at = 0;
	$eroare = 0;
	while(list($pid) = mysqli_fetch_row($res)) {
		//verificare echipa
		/*
				case 1: $pos = "GK"; break;
				case 2: $pos = "DR"; break;
				case 3: $pos = "DC"; break;
				case 4: $pos = "DL"; break;
				case 5: $pos = "MR"; break;
				case 6: $pos = "MC"; break;
				case 7: $pos = "ML"; break;
				case 8: $pos = "FR"; break;
				case 9: $pos = "FC"; break;
				case 10: $pos = "FL"; break;
		*/
		
		//find out how many of each, are in the team
		//not allowed to have 2 GKs inside
		switch($_REQUEST['player_'.$pid]) {
			case 1: $gk++;break;
			case 2: $fd++;break;
			case 3: $fc++;break;
			case 4: $fs++; break;
			case 5: $mr++; break;
			case 6: $mc++; break;
			case 7: $ml++; break;
			case 8:
			case 9:
			case 10: $at++; break;
		}
		if($gk == 2) {
			$_SESSION['_MESSAGE'] =  translate('Two GKs in the squad!');
			$eroare = 1;
		}
	
		
		if($eroare == 1) {
			//sterg ce s-a introdus
			$sql = "DELETE FROM lineup
					WHERE pgroup=$grupastart AND userId=".$_SESSION['USERID'];
			mysqli_query($GLOBALS['con'], $sql);
			
		}
		
		//se modifica echipa (cei care nu au fost selectati, se modifica in 0)
		$insert = "INSERT INTO lineup(post, playerId, userId, pgroup)
				   VALUES(".$_REQUEST['player_'.$pid].", $pid, ".$_SESSION['USERID'].", $grupastart)";
		mysqli_query($GLOBALS['con'], $insert);
		//echo $insert.'<br/>';
		
		$update = "UPDATE lineup SET post = ". $_REQUEST['player_'.$pid] . " WHERE playerId=$pid AND userId=".$_SESSION['USERID'];
		mysqli_query($GLOBALS['con'], $update);
		//echo $update.'<br/>';
		
		if($_REQUEST['player_'.$pid] >0) {
			//daca are alta valoare, inseamna ca a fost selectat
			//sa nu se permita mai mult de 11 salvari
			$nrjuc++;
		}
		if($nrjuc == 11) {
			break;
		}
		
		$_SESSION['_MESSAGE'] = "Echipa de start este actualizata!";
	}


	mysqli_free_result($res);
	
}
include('app.head.php'); 
?>

	<div id="content">
		<div id="content-left">

			<div class="container-1">
<?php
			if(!empty($_SESSION['_MESSAGE'])) {
?>
			<h1>CupaLigii.ro</h1>
			<div class="clear"></div>
			<div class="container-3d">
				<h3>Mesaj</h3>
				<div class="container-3d-text">
					<?php
					echo "<h2>".$_SESSION['_MESSAGE']."</h2>";
					$_SESSION['_MESSAGE'] = '';
					?>
				</div>
			</div>
<?php 
} 
	if(!empty($_SESSION['badlogin'])) {
		include('lostpassword.php');
		$_SESSION['badlogin'] = 0;
	}

	
IF(!empty($_SESSION['USERID'])) {
	if(isset($_REQUEST['fbclub'])) {
		$_REQUEST['club_id'] = $_REQUEST['fbclub'];
		include('viewclub.php');//?club_id='.$_REQUEST['fbclub']);		
	}
	if(isset($_REQUEST['option'])) {
		if($_REQUEST['option'] == 'activare')
			include('activare.php');
		if($_REQUEST['option'] == 'tactics')
			include('alege11.php');
		if($_REQUEST['option'] == 'management')
			include('management.php');
		if($_REQUEST['option'] == 'calendar')
			include('managementcalendar.php');
		if($_REQUEST['option'] == 'club')
			include('club.php');
		if($_REQUEST['option'] == 't-shirt')
			include('t-shirt.php');
		if($_REQUEST['option'] == 'search')
			include('search.php');
		if($_REQUEST['option'] == 'searchteam') 
			include('search1.php');
		if($_REQUEST['option'] == 'searchteam2')
			include('searchteam3.php');
		if($_REQUEST['option'] == 'searchtrainer')
			include('searchtrainer.php');
		if($_REQUEST['option'] == 'searchbids')
			include('searchbids.php');
		if($_REQUEST['option'] == 'searchplayers')
			include('search2.php');
		if($_REQUEST['option'] == 'searchplayers2')
			include('searchplayers3.php');
		if($_REQUEST['option'] == 'meciuri')
			include('meciuri.php');
		if($_REQUEST['option'] == 'viewclub')
			include('viewclub.php');
		if($_REQUEST['option'] == 'viewplayer') 
			include('viewplayer.php');
		if($_REQUEST['option'] == 'transferuri') 
			include('transferuri.php');
		if($_REQUEST['option'] == 'viewtrainer') 
			include('viewtrainer.php');
		if($_REQUEST['option'] == 'facilitati') 
			include('facilitati.php');
		if($_REQUEST['option'] == 'competitii') 
			include('competitii.php');
		if($_REQUEST['option'] == 'amical')
			include('setamical.php');
		if($_REQUEST['option'] == 'messages')
			include('messages.php');
		if($_REQUEST['option'] == 'sendmess')
			include('sendmess.php');
		if($_REQUEST['option'] == 'modificaCont')
			include('managementcont.php');
		if($_REQUEST['option'] == 'despre') {
//			echo "am intrat la det";
			include('about.php');
		}
		if($_REQUEST['option'] == 'mecionline') {
			$_SESSION['meciId'] = $_REQUEST['meciID'];
			include('mecionlinenou.php'); //mecionlinenou.php
			//include('mecionlinenou.php'); //mecionlinenou.php
		}
	} else {
		 include('news.php');
	}
} else {
	if(isset($_REQUEST['fbclub'])) {
		$_REQUEST['club_id'] = $_REQUEST['fbclub'];
		include('viewclub.php');//?club_id='.$_REQUEST['fbclub']);		
	}

	if(isset($_REQUEST['option'])) {
			if($_REQUEST['option'] == 'lostpass' || $_REQUEST['option'] == 'parola')
				include('lostpassword.php');
	
			if($_REQUEST['option'] == 'register')
				include('register.php');
			if($_REQUEST['option'] == 'despre')
				include('about.php');
			if($_REQUEST['option'] == 'activare')
				include('activare.php');
			//sa se poata vedea clubul chiar daca nu e logat
			//trebuie sa existe si conditia sa nu poata face amicale sau trimite mesaje sau orice altceva
			if($_REQUEST['option'] == 'viewclub')
				include('viewclub.php');
			if($_REQUEST['option'] == 'mecionline') {
				$_SESSION['meciId'] = $_REQUEST['meciID'];
				//include('_mecionline.php');
				include('mecionlinenou.php');
			}
	} else  include('news.php');
}

?>
			</div>

			<div class="clear"></div>
			
			<h1>CupaLigii.ro</h1>
			<div class="clear"></div>
			<div class="container-3">
				<h3>Ponturi...</h3>
				<div class="containet-3-text">
					<?php include('ponturi.php'); ?>
					<!--
					<a href="#" class="link-1">mai multe detalii &raquo;</a>
					-->
				</div>
			</div>
			<div class="container-3 right">
				<h3>Contact</h3>
				<div class="containet-3-text">
					Ne puteti contacta la adresa de email contact@CupaLigii.ro .
					<div align="center">
					<script type="text/javascript" src="//profitshare.ro/j/VLJf"></script>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>

		<div id="content-right">
			<?php include('right.php'); ?>
                        
		</div>

		<div class="clear"></div>
<?php include('app.foot.php'); ?>
