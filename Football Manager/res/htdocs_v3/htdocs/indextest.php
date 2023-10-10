<?php 
include('app.conf.php');
include('player.php');
include('UserStadium.php');
include('trainer.php');


if(!empty($_REQUEST['SetBilete'])) {
		//echo "sunt aici in setbilete<br/>";
		$stad = new Stadium($_SESSION['STADIUMID']);
		$stad->Pret = $_REQUEST['PretB'];
		header('Location: stadion.php');
}

if(!empty($_REQUEST['Inscriere'])) {
	require_once 'securimage.php';
		$stadiumname = $_REQUEST['stadiumName'];
		$teamname = $_REQUEST['teamName'];
		$username = $_REQUEST['userName'];
		$password = $_REQUEST['password'];
		$email = $_REQUEST['email'];

	$sql = "SELECT id FROM user WHERE teamname='".$_REQUEST['teamName']."'";
	$res = mysql_query($sql);
	$acelasi_nume = mysql_num_rows($res);
	mysql_free_result($res);
	//echo "ACELASI NUME: $acelasi_nume<br/>";
	if($acelasi_nume>0) {
      		$_MESSAGE = 'O echipa cu acelasi nume deja exista!';
			header("Location: index.php?option=register&stadiumName=$stadiumname&teamName=$teamname&userName=$username&email=$email&mes=$_MESSAGE");
	
	}
	
	$image = new Securimage();
	if ($image->check($_REQUEST['captcha_code']) == false) {
			
      		$_MESSAGE = 'Codul captcha nu a fost corect introdus!';
			header("Location: index.php?option=register&stadiumName=$stadiumname&teamName=$teamname&userName=$username&email=$email&mes=$_MESSAGE");
	} else {


		$activationkey = md5(microtime().rand());
		$user = new user();
		
		$user->CreateTeam($teamname, $stadiumname, $username, md5($password), $email, $activationkey);
		
		//$user->EchoClub();



		//Lotul initial
		//3 portari
		//5 fundasi - orice fel (pot fi toti DL)
		//6 mijlocasi - orice fel (pot fi toti MC)
		//4 atacanti -orice fel (pot fi toti FR)
		//5 antrenori nealocati echipei

		$young = 0;
		$coeficient_liga = 1;

		//portari
		for($i=0;$i<3;$i++) {
			$poz = 1;
			//aici urmeaza definirea de var
			$den = "juc".$i;
			$$den = new Player($user->ReturnID(), 0, $country, $young, $poz, $coeficient_liga);
			//$$den->EchoPlayer();
		}

		//fundasi
		for($i=0;$i<5;$i++) {
			$poz = rand(2,4);
			//aici urmeaza definirea de var
			$den = "juc".$i;
			$$den = new Player($user->ReturnID(), 0, $country, $young, $poz, $coeficient_liga);
			//$$den->EchoPlayer();
		}

		//mijlocasi
		for($i=0;$i<6;$i++) {
			$poz = rand(5,7);
			//aici urmeaza definirea de var
			$den = "juc".$i;
			$$den = new Player($user->ReturnID(), 0, $country, $young, $poz, $coeficient_liga);
			//$$den->EchoPlayer();
		}

		//atacanti
		for($i=0;$i<4;$i++) {
			$poz = rand(8,10);
			//aici urmeaza definirea de var
			$den = "juc".$i;
			$$den = new Player($user->ReturnID(), 0, $country, $young, $poz, $coeficient_liga);
			//$$den->EchoPlayer();
		}


		for($i=0;$i<5;$i++) {
			//aici urmeaza definirea de var
			$den = "antrenor".$i;
			$Liga = 1 - $i/10;
			$$den = new Trainer($Liga);
			//$$den->EchoTrainer();
		}

			//calculare rating
			$user->ComputeTeamValues();
			$rating = ($user->Vfw + $user->Vdf+ $user->Vmf)/30;
			$sql = "UPDATE user
					SET rating = $rating
					WHERE id=". $user->ReturnID();
			mysql_query($sql);

			//trimitere mesaj de bun venit
			$sql = "INSERT INTO messages(fromID, toID, subject, data, body, meciID)
					VALUES(0,".$user->ReturnID().", 'Bine ai venit!','".Date("Y-m-d")."', 'Bine ai venit in managerul de fotbal CupaLigii.ro!', 0)";
			mysql_query($sql);
			
			$_SESSION['_MESSAGE'] = "Inscrierea s-a incheiat cu succes! Verifica-ti adresa de email pentru a activa contul! Este posibil ca mailul sa fie in alta locatie decit Inbox (Spam/Junk s.a.m.d.). Bucura-te de managerul de fotbal CupaLigii.ro!";
	}
}


if(isset($_REQUEST['option'])) {
	if($_REQUEST['option'] == "logoff") {
		$sql = "UPDATE useronline SET online=0 WHERE userid=".$_SESSION['USERID'];
		mysql_query($sql);

		$_SESSION['USERID'] = 0;
	}
	if($_REQUEST['option'] == "messages") {
		if(isset($_REQUEST['accept'])) {
			//s-a primit accept pt un amical
			$sql = "UPDATE invitatiemeci SET accepted=1 WHERE id=".$_REQUEST['accept'];
			mysql_query($sql);
			//cind se seteaza cimpul accepted pe 1 (s-a dat ok la invitatia de amical)
			//se trece aceasta inregistrare in tabela 'etapa', cu numar=0, sezon=0 si idcompetitie=-5000 (amical)
			//default in bd, aceste cimpuri au aceste valori - setat pt amical default
			$sql = "INSERT INTO etapa(iduser1, iduser2, data, idstadium)
					SELECT userId_1, userId_2, datameci, stadium FROM invitatiemeci
					WHERE id=".$_REQUEST['accept'];
			//echo "$sql";
			mysql_query($sql);
		}
		if(isset($_REQUEST['deny'])) {
			//s-a primit accept pt un amical
			$sql = "UPDATE invitatiemeci SET accepted=0 WHERE id=".$_REQUEST['deny'];
			mysql_query($sql);
		}
	}

}

if(isset($_REQUEST['TrimiteMesaj'])) {
	$subject = $_REQUEST['subiect'];
	$body = $_REQUEST['mesaj'];
	$data_curenta = Date("Y-m-d");
	
	$sql = "INSERT INTO messages (fromID, toID, subject, body, data, meciID)
			VALUES (".$_SESSION['USERID'].", ".$_REQUEST['to']. ", '$subject', '$body', '$data_curenta', 0)";
	mysql_query($sql);

}

if(!empty($_REQUEST['SetEveniment'])) {
	$data_curenta = Date("Y-m-d");
	$data = date("Y-m-d",strtotime($_REQUEST['date5']));
	//REQUEST[ev]
	//cauta daca exista meci in ziua aceea
	//daca exista, nu introduce
	$num=0;
	$sql = "SELECT id FROM invitatiemeci WHERE datameci='$data' AND (userId_1=".$_SESSION['USERID'] . " OR userId_2=". $_SESSION['USERID']. ")";
	$res = mysql_query($sql);
	$num = mysql_num_rows($res);
	mysql_free_result($res);
	
	if($num==0) { 
		$sql = "INSERT INTO evenimente (tip, data, userid)
				VALUES(".$_REQUEST['ev'].", '$data',".$_SESSION['USERID'].")";
		$res = mysql_query($sql);
		//echo "$sql<br/>";
		
		//inserez si in requests, sa fie procesat inziua aceea
		$sql = "INSERT INTO requests (userid, data, categorie, detaliu, procesat)
				VALUES(".$_SESSION['USERID'].",'$data', 'Eveniment', ".$_REQUEST['ev'].", 0)";
		//echo "$sql<br/>";
		$res = mysql_query($sql);
	} else $_SESSION['_MESSAGE'] = 'Eveniment neinregistrat (exista meci in acea zi)';
	header('Location:index.php?option=calendar');
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

	if($data_curenta>$data) {
		$_SESSION['_MESSAGE'] = 'Invitatia nu a fost trimisa, meciul nu se poate disputa in acea data!';
	} else {
		$sql = "SELECT email, botteam, stadiumid FROM user WHERE id=".$_REQUEST['club_id'];
		$res = mysql_query($sql);
		list($emailadv, $estebot, $stadionadv) = mysql_fetch_row($res);
		mysql_free_result($res);

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
	

		$res = mysql_query($sql);
		$meci_id = mysql_insert_id();
		

		
		$sql = "SELECT name FROM stadium WHERE id=".$_REQUEST['stadion'];
		$res = mysql_query($sql);
		list($nume_stadion) = mysql_fetch_row($res);
		mysql_free_result($res);

		$sql = "SELECT TeamName FROM user WHERE id=".$_SESSION['USERID'];
		$res = mysql_query($sql);
		list($nume_adversar) = mysql_fetch_row($res);
		mysql_free_result($res);

		$subject = "Invitatie amical cu $nume_adversar";
		$body = "Clubul <b>$nume_adversar</b> te provoaca la un amical, pe data de $data, pe stadionul $nume_stadion. Pentru a accepta acest meci, apasa mai jos!";

		$sql = "INSERT INTO messages (fromID, toID, subject, body, data, meciID)
				VALUES (".$_SESSION['USERID'].", ".$_REQUEST['club_id']. ", '$subject', '$body', '$data_curenta', $meci_id)";
		mysql_query($sql);
		
		$mes = "Salut!\r\n\r\nAi primit o invitatie pentru un amical in managerul de fotbal CupaLigii.ro! Vezi daca o poti onora!\r\nhttp://www.CupaLigii.ro";
		$mes = wordwrap($mes, 70, "\r\n");
		if($estebot) {
		} else {
			mail($emailadv, 'Invitatie amical CupaLigii.ro!', $mes);
			mail('fcbrasov@yahoo.com', 'Invitatie amical CupaLigii.ro!', $mes);
		}

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

if (isset($_REQUEST['Alege11'])) {
	//stergere titulari pentru a introduce altii
	$sql = "DELETE FROM echipastart
			WHERE userId=".$_SESSION['USERID'];
	mysql_query($sql);

	//aflare tactica
	/*
	$sql = "SELECT tactica FROM tactica WHERE userid=".$_SESSION['USERID'];
	$restac = mysql_query($sql);
	list($bdtac) = mysql_fetch_row($restac);
	mysql_free_result($restac);
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
			WHERE  u.id=" . $_SESSION['USERID'] . " ORDER BY p.Position ASC";

	$res = mysql_query($sql);

	$nrjuc=0;
	$gk = $fs = $fd = $fc = $ml = $mr = $mc = $at = 0;
	$eroare = 0;
	while(list($pid) = mysql_fetch_row($res)) {
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
			$_SESSION['_MESSAGE'] = "Tactica nepermisa: 2 portari!";
			$eroare = 1;
		}
	
		
		if($eroare == 1) {
			//sterg ce s-a introdus
			$sql = "DELETE FROM echipastart
					WHERE userId=".$_SESSION['USERID'];
			mysql_query($sql);
			
		}
		
		//se modifica echipa (cei care nu au fost selectati, se modifica in 0)
		$insert = "INSERT INTO echipastart(post, playerId, userId)
				   VALUES(".$_REQUEST['player_'.$pid].", $pid, ".$_SESSION['USERID'].")";
		mysql_query($insert);
		//echo $insert.'<br/>';
		
		$update = "UPDATE echipastart SET post = ". $_REQUEST['player_'.$pid] . " WHERE playerId=$pid AND userId=".$_SESSION['USERID'];
		mysql_query($update);

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


	mysql_free_result($res);
	
}

include('app.head.php'); 
?>

	<div id="content">
		<div id="content-left">
			<div class="container-1">
			<?php include('news.php'); ?>
			Aici mesaj
				<div class="container-3d">
				<h3>Mesaj</h3>
				<div class="container-3d-text">
					<?php
					echo "<h2>".$_SESSION['_MESSAGE']."</h2>";
					$_SESSION['_MESSAGE'] = '';
					?>
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
