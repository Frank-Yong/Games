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
$res = mysql_query($sql);
$countries = array();
$cnume = array();
$i=0;
while(list($c,$cn) = mysql_fetch_row($res)) {
	$cnume[$i] = $cn;
	$countries[$i++] = $c;
}
mysql_free_result($res);
//Definire juc liberi

$young = 0;
$coeficient_liga = 2.5;

if(!empty($_REQUEST['Trimite'])) {
	
	for($i=0;$i<$_REQUEST['nrjuc'];$i++) {
		//country=24 - brazilia
		$country = $_REQUEST['natjuc'];
		//random la pozitia din teren sa genereze din fiecare
		$poz = rand(1,10);
		//aici urmeaza definirea de var
		$den = "juc".$i;
		$$den = new Player(0, 0, $country, $young, $poz, $coeficient_liga);
		$$den->EchoPlayer();

	}
		
}

?>
<h1>Jucatori liberi de contract</h1>
<form action="jucLiberi.php" method="POST">
Nr jucatori <input type="text" name="nrjuc" size="10">
<br/>
Tara:
<select name="natjuc">
	<?php
	for($i=0;$i<count($countries);$i++)
		echo "<option value=\"".$countries[$i]."\">".$cnume[$i];
	?>
</select>
<br/>
<input type="Submit" name="Trimite" value="Trimite">
</form>
