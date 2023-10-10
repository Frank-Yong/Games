<?php
//error_reporting(63);
include('app.conf.php');
include('player.php');
include('UserStadium.php');
include('trainer.php');

//coeficient echipa gazda: 5%
//coeficient de noroc - random pt ambele echipe. cine are mai mare valoarea, ia 5%, cealalta echipa nu ia nimic
//echipa 1 este echipa gazda
$noroc1 = rand(0,100);
$noroc2 = rand(0,100);

if ($noroc1>$noroc2) {
	$_noroc1 = 1.05;
	$_noroc2 = 1;
} else {
	$_noroc1 = 1;
	$_noroc2 = 1.05;
}

//echipa 1
echo "Echipa 1...<br/>";
	$user1 = new User();
	$user1->LoginID($_REQUEST['id1']);

	$user1->EchoClub();
	
	$user1->ComputeTeamValues();

	//Valorile pentru echipa 1
	//$user1->Vfw; $user1->Vmf; $user1->Vdf

	$Vfw1 = $user1->Vfw * 1.05 * $_noroc1;
	$Vmf1 = $user1->Vmf * 1.05 * $_noroc1;
	$Vdf1 = $user1->Vdf * 1.05 * $_noroc1;


echo "<br/><br/>Echipa 2...<br/>";
	$user2 = new User();
	$user2->LoginID($_REQUEST['id2']);

	$user2->EchoClub();
	
	$user2->ComputeTeamValues();

	//Valorile pentru echipa 2
	//$user2->Vfw; $user2->Vmf; $user2->Vdf

	$Vfw2 = $user2->Vfw * $_noroc2;
	$Vmf2 = $user2->Vmf * $_noroc2;
	$Vdf2 = $user2->Vdf * $_noroc2;


	//posesia: x/(x+y) * 100 si y/(x+y) * 100
	echo "Posesia jocului. <br/>Echipa 1: " . round($Vmf1/($Vmf1+$Vmf2) * 100,1) . "%";
	echo "<br/>Echipa 2: " . round($Vmf2/($Vmf1+$Vmf2) * 100,1) . "%";

	$pas = 0.15;
	//cite goluri inscrie echipa 1
	$goluri1 = (int)(($Vfw1/$Vdf2 - 1)/$pas);
	if ($goluri1<0) $goluri1=0;
	echo "<br/>Echipa 1 inscrie $goluri1 goluri";

	//minutele in care s-a marcat
	for($i=0;$i<$goluri1;$i++) {
		$minute1[] = (int)rand(0,91)+1;
	}
	asort($minute1);
	echo "<br/>Golurile s-au inscris in minutele: ". implode(", ", $minute1).'<br/>';

	//cite goluri inscrie echipa 2
	$goluri2 = (int)(($Vfw2/$Vdf1 - 1)/$pas);
	if ($goluri2<0) $goluri2=0;
	echo "<br/>Echipa 2 inscrie $goluri2 goluri";

	//minutele in care s-a marcat
	for($i=0;$i<$goluri2;$i++) {
		$minute2[] = (int)rand(0,91)+1;
	}
	asort($minute2);
	echo "<br/>Golurile s-au inscris in minutele: ". implode(", ", $minute2).'<br/>';

?>