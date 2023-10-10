<?php

error_reporting(63);
//comes $key for activation, from email
//if the key is correct, set activated=1

$sql = "SELECT id FROM user WHERE activationkey='".$_REQUEST['key']."' AND activated=0";
$res = mysql_query($sql);
list($user_de_activat) = mysql_fetch_row($res);
mysql_free_result($res);
if($user_de_activat>0) {
	$sql="UPDATE user SET activated=1 WHERE id=$user_de_activat";
	mysql_query($sql);

	$user = new User();
	$user->LoginID($user_de_activat);

		
			//mail('fcbrasov@yahoo.com', 'new team in the game!', 'Team:'.$teamname);
			
			//$user->EchoClub();



			//Lotul initial
			//3 portari
			//5 fundasi - orice fel (pot fi toti DL)
			//6 mijlocasi - orice fel (pot fi toti MC)
			//4 atacanti -orice fel (pot fi toti FR)
			//5 antrenori nealocati echipei

			$young = 0;
			$coeficient_liga = 1.02;

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


			$young = 1;
			$coeficient_liga = 0.61;

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
				$user->ComputeTeamValues(1);
				$rating = ($user->Vfw + $user->Vdf+ $user->Vmf)/30;
				$sql = "UPDATE user
						SET rating = $rating
						WHERE id=". $user->ReturnID();
				//mysql_query($sql);

				//trimitere mesaj de bun venit
				$sql = "INSERT INTO messages(fromID, toID, subject, data, body, meciID)
						VALUES(0,".$user->ReturnID().", 'Welcome!','".Date("Y-m-d")."', 'Welcome to my football manager!<br/><br/>Scurt training: dispui de un lot de 18 jucatori, cu calitati specifice fiecarui post. De exemplu, pentru portar este importata manevrarea balonului, reflexele sau pozitionarea. Trebuie sa-ti faci formula de start in functie de aceste calitati, sa alegi cei mai buni 11 jucatori. Daca ai nevoie de jucatori noi, poti cauta jucatori pusi pe lista de transfer sau liberi de contract. De asemenea, poti vinde jucatori, daca nu se pliaza pe sistemul tau de joc sau nu au calitati suficient de bune.<br/><br/>Este important sa ai grija de banii pe care ii ai in club, pentru ca trebuie sa-ti ajunga pe tot parcursul sezonului, pentru a plati salariile jucatorilor si antrenorului. Angajeaza un antrenor pentru ca jucatorii tai sa-si imbunatateasca calitatile cat mai repede. <br/><br/>Mai multe informatii gasesti pe site ( http://www.CupaLigii.ro/index.php?option=despre ).<br/><br/>Mult succes!', 0)";
				mysql_query($sql);
		

	
	echo "<h2>Contul a fost activat cu succes! <a href=\"index.php?option=despre\">Afla aici mai multe detalii despre joc!</a></h2>";
} else {
	echo "<h2>Contul nu s-a putut activa sau este deja activ! Verificati din nou cheia de acces.</h2>";
}



?>