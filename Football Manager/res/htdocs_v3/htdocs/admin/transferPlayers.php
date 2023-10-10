<?php
error_reporting(63);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');



//preiau toti jucatorii care au avut deadline in ziua precedenta scanarii scriptului
//pt toti acestia, trebuie sa fac mutarea la noul club, in functie de cine a cistigat licitatia
//datele cu licitatia se gasesc in playerbid si folosesc o functia care returneaza cine a facut pariul maxim
//in userplayer modific inregistrarea cu apartenenta jucatorului
//totodata, modific pe 0 cimpurile Transfer si TransferSuma si pe '0000-00-00 00:00:00' TransferDeadline din tabelul player
//in user se modifica suma din cont pentru ambii implicati: vinzator si cumparator

//pt jucatorii liber de contract, trebuie ca suma pariata sa fie trecuta la salariu si sa nu se scada din banii din cont


$sql = "SELECT a.id, MaxBid(a.id), b.UserID
		FROM player a
		LEFT OUTER JOIN userplayer b
		ON a.id=b.PlayerID
		WHERE CAST(a.TransferDeadline AS DATE) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
while(list($player_id, $licitatie, $vinzator_id) = mysqli_fetch_row($res)) {
	//il scot de pe lista de transfer
	//echo "$player_id :: $licitatie<br/>";
	list($suma,,$team_id) = explode(";",$licitatie);
	
	//inserare in tabelul transferuri
	$sql = "INSERT INTO transferuri (proprietarid, cumparatorid, playerid, suma, data)
			VALUES($vinzator_id, $team_id, $player_id, $suma, '".Date("Y-m-d")."')";
	
	echo "$sql<br/>";
	mysqli_query($GLOBALS['con'],$sql);
	
	$sql = "UPDATE player SET Transfer=0, TransferSuma=0, TransferDeadline='0000-00-00 00:00:00', Contract=2 WHERE id=$player_id";
	mysqli_query($GLOBALS['con'],$sql);
	//il trec la noua echipa si ii pun 0 saptamini la club
	$sql = "UPDATE userplayer SET UserID=$team_id, weeks=0, number=0, data='".Date("Y-m-d")."' WHERE PlayerID=$player_id";
	mysqli_query($GLOBALS['con'],$sql);
	$sql = "UPDATE playerbid SET activ=0 WHERE playerid=$player_id";
	mysqli_query($GLOBALS['con'],$sql);
	//cumparatorului i se scad banii din cont doar daca nu este liber de contract
	if($vinzator_id <> 0) {
		$sql = "UPDATE user SET Funds=Funds-$suma WHERE id=$team_id";
		mysqli_query($GLOBALS['con'],$sql);
		//vinzatorul primeste banii
		$sql = "UPDATE user SET Funds=Funds+$suma WHERE id=$vinzator_id";
		mysqli_query($GLOBALS['con'],$sql);
	
		//se scoate din echipa de start jucatorul
		$sql = "DELETE FROM lineup
			WHERE playerid=$player_id";
		mysqli_query($GLOBALS['con'],$sql);
	} else {
		//jucator liber de transfer
		$sql = "UPDATE player SET wage=$suma WHERE id=$player_id";
		//echo "$sql<br/>";
		mysqli_query($GLOBALS['con'],$sql);
	}
}
mysqli_free_result($res);