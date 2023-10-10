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

$young = 0;
$coeficient_liga = 1;

if(!empty($_REQUEST['Trimite'])) {
	$coef = $_REQUEST['coeficient'];
	$grupa = $_REQUEST['grupa'];
	echo "Age: $grupa<br/>";
	$ptmesaj = "";
	for($i=0;$i<$_REQUEST['nrjuc'];$i++) {
		//country=24 - brazilia
		$country = $_REQUEST['natjuc'];
		//random la pozitia din teren sa genereze din fiecare
		$poz = rand(1,10);
		//aici urmeaza definirea de var
		$den = "juc".$i;
		echo "team: ".$_REQUEST['useri'].'<br/>';
		$$den = new Player($_REQUEST['useri'], 0, $country, $grupa, $poz, $coef);
		$$den->EchoPlayer();

		$ptmesaj .= $$den->EchoPlayerPtMesaj();
		
		
		
	}
		
	//echo "Aici e mesajul:    $ptmesaj<br/>";	
	$mes = "Hi, i am your assistant for youth team! These players have joined the team, and they already started the training:<br/><br/>$ptmesaj";
	$sql = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
	VALUES(0, ".$_REQUEST['useri'].", 'Youth: players joined', '$mes' , '".Date("Y-m-d H:i:s")."', 0, 0)";
	mysqli_query($GLOBALS['con'],$sql);

}

?>
<h1>Players awarded</h1>
<form action="jucLiberiPremiu.php" method="POST">
Coeficient <input type="text" name="coeficient" size="10" value="1">(1, 1.2, 1.4)
<br/>
Age <select name="grupa">
<option value="0">first team
<option value="1">youth
</select>
<br/>
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
<br/>
No of Players <input type="text" name="nrjuc" size="10">
<br/>
Country:
<select name="natjuc">
	<?php
	for($i=0;$i<count($countries);$i++)
		echo "<option value=\"".$countries[$i]."\">".$cnume[$i];
	?>
</select>
<br/>
<input type="Submit" name="Trimite" value="Allocate">
</form>
