<?php
ob_start();

if(isset($_REQUEST['Inscriere'])) {
	$teamname = $_REQUEST['teamName'];
	$stadiumname = $_REQUEST['stadiumName'];
	$managername = $_REQUEST['managerName'];
	$username = $_REQUEST['userName'];
	$password = $_REQUEST['password'];
	$firstname = $_REQUEST['firstName'];
	$lastname = $_REQUEST['lastName'];
	$address = $_REQUEST['address'];
	$city = $_REQUEST['city'];
	$country = $_REQUEST['country'];

	$user = new User();
	$user->Create($managername, $teamname, $stadiumname, $username, $password, $firstname, $lastname, $address, $city, $country);
	
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

	Header('Location: home.php?userid='.$user->ReturnID());
	ob_end_flush();

}

?>
