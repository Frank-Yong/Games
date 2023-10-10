<?php

//vine $key pentru activare, din mail
//in urma acesteia, daca este cheia corecta, se seteaza activated=1

$sql = "SELECT id FROM user WHERE activationkey='".$_REQUEST['key']."'";// AND activated=0";
echo "MMMMMM:: $sql<br/><br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
list($user_de_activat) = mysqli_fetch_row($res);
echo "USERUL este $user_de_activat<br/>";
mysqli_free_result($res);
if($user_de_activat>0) {
	$sql="UPDATE user SET activated=1 WHERE id=$user_de_activat";
	mysqli_query($GLOBALS['con'],$sql);

	$user = new User();
	$user->LoginID($user_de_activat);

		
			//mail('fcbrasov@yahoo.com', 'New team in the soccer manager', 'Team name :'.$teamname);
			
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
				$user->ComputeTeamValues();
				$rating = ($user->Vfw + $user->Vdf+ $user->Vmf)/30;
				$sql = "UPDATE user
						SET rating = $rating
						WHERE id=". $user->ReturnID();
				//mysqli_query($GLOBALS['con'],$sql);

				//trimitere mesaj de bun venit
				$sql = "INSERT INTO messages(fromID, toID, subject, data, body, meciID)
						VALUES(0,".$user->ReturnID().", 'Welcome!','".Date("Y-m-d")."', 'Welcome to the online soccer manager, myFM.com!<br/><br/>Short training: you have 18 players, with skills for each position. For example, for the goalkeeper, ball handling, reflexes or positioning are imported. You have to make your starting formula based on these qualities, to choose the best 11 players. If you need new players, you can look for players on the transfer list or free of contract. You can also sell players if they do not fit on your game system or do not have good enough qualities. <br/> <br/> It is important to take care of the money you have in the club, because you have to -you get enough throughout the season, to pay the salaries of the players and the coach. Hire a coach so that your players can improve their qualities as soon as possible. <br/> <br/> You can find more information on the site (http://www.CupaLigii.ro/index.php?option=despre). <br/> <br/> Good luck! ', 0)";
				mysqli_query($GLOBALS['con'],$sql);
		

	
	echo "<h2>The account was activated! <a href=\"index.php?option=despre\">Find out here more about the game!</a></h2>";
} else {
	echo "<h2>The account wasn't activated or it is already activated! Check again the activation key.</h2>";
}



?>