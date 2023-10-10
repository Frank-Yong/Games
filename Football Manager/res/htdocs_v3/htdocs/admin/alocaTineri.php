<?php
//creare jucatori liber la transfer
//cind se asociaza in UserPlayer, la userid se pune 0
//in lista de jucatori transferabili, in loc de echipa se pune "Liber la transfer

//trebuie adaugat un cimp nou in userplayer, -> saptamini (integer)
//daca jucatorul este la club de mai putin de 10 saptamini, sa nu poata fi pus pe lista de transfer

//se genereaza cu userid=0
//cind userid=0, trebuie sa fie pus automat transfer=1 si transfersuma=0
 

include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

include('admin.head.php');


$sql = "SELECT DISTINCT(a.country), b.name FROM firstname a LEFT JOIN country b ON a.country=b.id";
$res = mysqli_query($GLOBALS['con'],$sql);
$countries = array();
$cnume = array();
$i=0;
while(list($c,$cn) = mysqli_fetch_row($res)) {
	$cnume[$i] = $cn;
	$countries[$i++] = $c;
}
mysqli_free_result($res);
//Definire juc liberi


if(!empty($_REQUEST['Trimite'])) {

			$country = 3;
			$young = 1;
			$coeficient_liga = 0.71;

			//portari
			for($i=0;$i<3;$i++) {
				$poz = 1;
				//aici urmeaza definirea de var
				$den = "juc".$i;
				$$den = new Player($_REQUEST['useri'], 0, $country, $young, $poz, $coeficient_liga);
				$$den->EchoPlayer();
			}

			//fundasi
			for($i=0;$i<5;$i++) {
				$poz = rand(2,4);
				//aici urmeaza definirea de var
				$den = "juc".$i;
				$$den = new Player($_REQUEST['useri'], 0, $country, $young, $poz, $coeficient_liga);
				$$den->EchoPlayer();
			}

			//mijlocasi
			for($i=0;$i<6;$i++) {
				$poz = rand(5,7);
				//aici urmeaza definirea de var
				$den = "juc".$i;
				$$den = new Player($_REQUEST['useri'], 0, $country, $young, $poz, $coeficient_liga);
				$$den->EchoPlayer();
			}

			//atacanti
			for($i=0;$i<4;$i++) {
				$poz = rand(8,10);
				//aici urmeaza definirea de var
				$den = "juc".$i;
				$$den = new Player($_REQUEST['useri'], 0, $country, $young, $poz, $coeficient_liga);
				$$den->EchoPlayer();
			}

	
		
}

?>
<h1>Give youth players to... (allocates full team - 18 players)...</h1>
<form action="alocaTineri.php" method="POST">
<?php
$sql = "SELECT id, username, teamname FROM user WHERE activated=1 ORDER BY LastActive DESC";
$ruser = mysqli_query($GLOBALS['con'],$sql);
echo "<select name=\"useri\">";
while(list($uid,$uname,$tname) = mysqli_fetch_row($ruser)) {
	echo "<option value=\"$uid\">$tname - $uname";
}
echo "</select>";
mysqli_free_result($ruser);
?>
<input type="Submit" name="Trimite" value="Allocate">
</form>
