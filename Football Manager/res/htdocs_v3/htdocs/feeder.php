<?php
include('app.conf.php');


$orastart = 2;

$ora = Date("h");
echo "Ora este: $ora<br/>";

$minutes = Date("i");
//echo "$minutes<br/>";

$sql = "SELECT b.TeamName, c.TeamName, a.datameci
		FROM invitatiemeci a
		LEFT JOIN user b
		ON b.id=a.userId_1
		LEFT JOIN user c
		ON c.id=a.userId_2
		WHERE a.id=".$_SESSION['meciId'];
$res = mysql_query($sql);
while(list($e1,$e2, $datameci) = mysql_fetch_row($res)) {
	$ec1 = $e1;
	$ec2 = $e2;
	$dmeci = $datameci;
}
mysql_free_result($res);


$sql = "SELECT a.text, b.TeamName, a.minut, a.gol
		FROM mecitext a
		LEFT JOIN user b
		ON a.atacator=b.id
		WHERE a.meciID=".$_SESSION['meciId'].
		" ORDER BY a.minut ASC";


$res = mysql_query($sql);
$varTxt = "";
$unu = 0;
echo "Data meciului: $dmeci!<br/>";
$gazde=0;
$oaspeti=0;
while(list($text, $echipa, $minut, $gol) = mysql_fetch_row($res)) {
    if($dmeci<Date("Y-m-d")) {
		//meciul s-a desfasurat deja
		$ultimulminut= 90;
	} elseif($dmeci==Date("Y-m-d")) {
		//suntem in ziua meciului
		if($gol==1) $gazde++;
		if($gol==2) $oaspeti++;
		if($ora==$orastart and $minutes>45 and $minutes<59) {
			$ultima = "Pauza. Echipele sunt la cabine";
			$minutes=45;
		}
		if($ora<>$orastart and $unu==0) {
			$minutes = $minutes+60-15;
			$unu=1;
		}
		if($ora<> $orastart and $ora<>$orastart+1) {
			$ultima = "Final";
			$minutes=90;

			$scor = "$gazde:$oaspeti";
			//se scrie in baza de date la final meciul
			$sql = "UPDATE invitatiemeci
					SET scor='$scor'
					WHERE meciID=".$_SESSION['meciId'];
			mysql_query($sql);
			//break;
		}
		if($minut>$minutes) break;
	} else {
	//nu s-a ajuns la data meciului
		$ultima = "Meciul are loc in data de $dmeci!";
		$ultimulminut = 0;
		break;
	}
	$varTxt = "Min.: $minut<br>$echipa: $text<br/>".$varTxt;
	$ultima = $echipa.": ".$text;
	$ultimulminut = $minut;
}
$valoare=$ultima;
$minut=$ultimulminut;
$scor = "$gazde:$oaspeti";
mysql_free_result($res);
?>

&valoare=<?php echo $valoare; ?>&echipa1=<?php echo $ec1; ?>&echipa2=<?php echo $ec2; ?>&scor=<?php echo $scor; ?>&minut=<?php echo $minut; ?>&varTxt=<?php echo $varTxt; ?>