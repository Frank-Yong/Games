<script>
function showComment()
{
	if (document.getElementById("comentariu").style.display == "")
		document.getElementById("comentariu").style.display = "none";
	else 
		document.getElementById("comentariu").style.display = "";
}
</script>

<?php
error_reporting(63);


//vine $player sub forma unui nume
$_CHECK_LOGIN=0;

if (isset($_REQUEST['id'])) {
	//afisare jucator
	$user->EchoPlayerSolo($_REQUEST['id']);
} else {
	//aici se intra fara id
	echo "<h1>".$user->TeamName()."";
	$v1 = $user->ShowMoral();
	$v2 = 100 - $v1;
	echo "&nbsp;&nbsp;&nbsp;<img width=\"21\" src=\"pie.php?n1=$v1&n2=$v2\"></h1>";
	if($user->Imagine() != "") $poza = $user->Imagine();
	else $poza =  "images/manager.jpg";

	$stad = new Stadium($_SESSION['STADIUMID']);
	
	$nrl = $stad->AvailableSeats();
//$nrl = 25000;

	switch($nrl) {
		case ($nrl<3000): $img = "images/stadion-1.jpg";break;
		case ($nrl>=3000 && $nrl<6000): $img = "images/stadion-2.jpg";break;
		case ($nrl>=6000 && $nrl<10000): $img = "images/stadion-3.jpg";break;
		case ($nrl>=10000 && $nrl<15000): $img = "images/stadion-4.jpg";break;
		case ($nrl>=15000 && $nrl<25000): $img = "images/stadion-5.jpg";break;
		case ($nrl>=25000 && $nrl<35000): $img = "images/stadion-6.jpg";break;
		case ($nrl>=35000 && $nrl<50000): $img = "images/stadion-7.jpg";break;
		case ($nrl>=50000 && $nrl<60000): $img = "images/stadion-8.jpg";break;
		case ($nrl>=60000 && $nrl<80000): $img = "images/stadion-9.jpg";break;
		case ($nrl>=80000): $img = "images/stadion-10.jpg";break;
	}

	
//	echo "<img src=\"suprapuse.php?id=".$_SESSION['USERID']."&img=$poza&imgstad=$img\" width=\"310\" class=\"img-1\">";

echo "before echoclub<br/>";
	$user->EchoClub();
?>

						<table>
							<tr>
							<TD>
							<a href= "index.php?option=meciuri"><img src="images/meciuri.png" width="70"></a>
							</TD>
							<td>
							<a href= "index.php?option=club&players=1"><img src="images/jucatori.png" width="90"></a>
							</td>
							<td>
							<a href= "sponsoriclub.php"><img src="images/sponsori.png" width="80"></a> 
							</td>
							</tr>
							<tr>
							<td align="center">Meciuri</td>
							<td align="center">Jucatori</td>
							<td align="center">Sponsori</td>
							</tr>
							<tr>
							<td>
							<a href= "index.php?option=club&antrenor=1"><img src="images/trainer.png" width="80"></a> 
							</td>
							<td>
							<a href= "index.php?option=tricouri"><img src="images/number.png" width="80"></a> 
							</td>
							<td>
							<a href= "index.php?option=transferuri"><img src="images/transferuri.png" width="80"></a> 
							</td>
							<td></td>
							</tr>
							<tr>
							<td align="center">Antrenament</td>
							<td align="center">Nr.tricouri</td>
							<td align="center">Transferuri</td>
							<td></td>
							</tr>
							<tr>
							<td>
							<a href= "stadion.php"><img src="images/stadion.jpg" width="80"></a> 
							</td>
							<td>
							<a href= "index.php?option=facilitati"><img src="images/facilitati.png" width="70"></a> 
							</td>
							<td><a href= "index.php?option=competitii"><img src="images/competitii.png" width="70"></a> 
							</td>
							</tr>
							<tr>
							<td align="center">Stadion</td>
							
							<td align="center">Facilitati</td>
							<td align="center">Competitii</td>
							</tr>
							</table>
<br/>
<?php

	
	echo "<A href=\"#\" class=\"link1\"><img src=\"images/seniori.png\" width=\"30\" title=\"Seniori\"></a> <A href=\"#\" class=\"link2\" title=\"Juniori\"><img src=\"images/juniori.png\" width=\"30\"></a><br/>";
	echo "<div class=\"div1\">";
	$user->EchoTeamNou(0);
	echo "</div>";
	echo "<div class=\"div2\">";
	$user->EchoTeamNou(1);


}

?>

