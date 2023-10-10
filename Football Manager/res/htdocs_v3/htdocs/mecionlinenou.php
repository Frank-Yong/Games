<?php 
/*
include('app.conf.php');
include('player.php');
include('UserStadium.php');
include('trainer.php');


include('app.head.php'); 
*/
error_reporting(63);
?>
<meta http-equiv="refresh" content="60">



<table class="tftable">

<?php
$sql = "SELECT c.fname, c.lname, a.mminute, a.team, b.gamedate 
		FROM gamedetail a
		LEFT OUTER JOIN gameinvitation b
		ON a.gameid=b.id
		LEFT OUTER JOIN player c
		ON a.playerid = c.id
		WHERE a.gameid=".$_REQUEST['meciID']." AND a.action=1 ORDER BY a.mminute ASC";
//echo "$sql<br/>";		
$deafisat1 = "";
$scor1 = 0;
$scor2 = 0;
$deafisat2 = "";
$res = mysqli_query($GLOBALS['con'],$sql);
while(list($fnume, $lnume, $minut, $nrechipa, $datameci)=mysqli_fetch_row($res)) {
	if(Date("Y-m-d")>$datameci) {
		//echo "sunt aici<br/>";
		//afisez marcatorii, ca s-a terminat meciul
		if($minut>45) {
			$rest = $minut % 45;
			$rest = ($rest<10)? " 13:0".$rest:" 13:".$rest;
				if($nrechipa == 1) {
					$deafisat1 .= "<img src=\"images/minge.png\" width=\"11\">&nbsp;$fnume $lnume $minut<br/>";
					$scor1++;
				} else {
					$deafisat2 .= "<img src=\"images/minge.png\" width=\"11\">&nbsp;$fnume $lnume $minut<br/>";
					$scor2++;
				}
			
		} else {
			$rest = ($minut<10)? " 12:0".$minut:" 12:".$minut;
				if($nrechipa == 1) {
					$deafisat1 .= "<img src=\"images/minge.png\" width=\"11\">&nbsp;$fnume $lnume $minut<br/>";
					$scor1++;
				} else {
					$deafisat2 .= "<img src=\"images/minge.png\" width=\"11\">&nbsp;$fnume $lnume $minut<br/>";
					$scor2++;
				}
		}
	}
	if(Date("Y-m-d") == $datameci) {
		if($minut>45) {
			$rest = $minut % 45;
			$rest = ($rest<10)? " 13:0".$rest:" 13:".$rest;
			if(Date("Y-m-d").$rest<Date("Y-m-d H:i")) 
				if($nrechipa == 1) {
					$deafisat1 .= "<img src=\"images/minge.png\" width=\"11\">&nbsp;$fnume $lnume $minut<br/>";
					$scor1++;
				} else {
					$deafisat2 .= "<img src=\"images/minge.png\" width=\"11\">&nbsp;$fnume $lnume $minut<br/>";
					$scor2++;
				}
			
		} else {
			$rest = ($minut<10)? " 12:0".$minut:" 12:".$minut;
			if(Date("Y-m-d").$rest<Date("Y-m-d H:i"))
				if($nrechipa == 1) {
					$deafisat1 .= "<img src=\"images/minge.png\" width=\"11\">&nbsp;$fnume $lnume $minut<br/>";
					$scor1++;
				} else {
					$deafisat2 .= "<img src=\"images/minge.png\" width=\"11\">&nbsp;$fnume $lnume $minut<br/>";
					$scor2++;
				}
		}
	}
}
mysqli_free_result($res);
?>
<?php
//echo "Current date:" .Date("Y-m-d H:i").'<br/>';
$sql = "SELECT a.mminute, a.goal, a.text, a.attacking_team, b.TeamName, d.TeamName, e.TeamName, d.id, e.id, a.goal, c.gamedate, c.score, d.rating, e.rating, c.competitionid, a.realminute
		FROM gametext a
		LEFT OUTER JOIN user b
		ON a.attacking_team=b.id
		LEFT OUTER JOIN gameinvitation c
		ON a.gameid=c.id
		LEFT OUTER JOIN user d
		ON d.id=c.userId_1
		LEFT OUTER JOIN user e
		ON e.id=c.userId_2
		WHERE a.gameid=".$_REQUEST['meciID']." AND CONCAT(c.gamedate,' ',a.mminute)<'".Date("Y-m-d H:i")."' ORDER BY a.mminute DESC";
//echo "$sql<br/>";		
$res = mysqli_query($GLOBALS['con'],$sql);

//returnez numarul de linii cu comentariu
//il inmultesc cu 2, pentru ca fac afisarea pe 2 linii
//$numar il folosesc cu un rowspan, pt a afisa imaginea cu actiunea pe prima coloana
$numar = mysqli_num_rows($res);
$numar = $numar*2;
$rand = rand(1,5);

$i=0;



while (list($minut, $gol, $text, $atacatorid, $echipa, $echipa1, $echipa2, $userid1, $userid2, $gol, $datameci, $scor, $rat1, $rat2, $competitieid, $minreal) = mysqli_fetch_row($res)) {
	if($i == 0) {
		//nu se afiseaza scorul daca nu s-a terminat meciul
		if($datameci.' '.'13:45' > Date("Y-m-d H:i")) $scor = "<h1 align=\"center\">$scor1:$scor2</h1>";
		//echo "$echipa1:$rat1:$rat2:$competitieid";
		//$nrspect = NumarSpectatori($userid1, $rat1, $rat2, $competitieid);
		
		echo "<tr><th colspan=\"4\"><h2>$datameci</h2>";
		//echo "<br/>Numar spectatori: $nrspect";
		echo "</th></tr><tr><th>$echipa1</th><th colspan=\"2\"><h1 align=\"center\">$scor</h1></th><th><p align=\"right\">$echipa2</p></h2></th></tr>";
		if($deafisat1 <> "" || $deafisat2 <> "") {
		?>
			<tr>
				<td colspan="2">
				<?php echo $deafisat1; ?>
				</td>
				<td colspan="2">
				<?php echo $deafisat2; ?>
				</td>
			</tr>

		<?php
		}
	}
	$uid = ($echipa == $echipa1)?$userid1:$userid2;
	$imagine2 = ($gol == 1)?"<img src=\"images/minge.png\" width=\"20\" align=\"right\">":"";
	if ($gol == 10) $imagine2 = "<img src=\"images/schimbare.png\" width=\"20\" align=\"right\">";
	if ($gol == 5) $imagine2 = "<img src=\"images/fanion.png\" width=\"20\" align=\"right\">";
	if ($gol == 2 || $gol == 3 || $gol == 4) $imagine2 = "<img src=\"images/fluier.png\" width=\"20\" align=\"right\">";
	switch($rand) {
		case 1:	$imagine = "online1.jpg"; break;
		case 2:	$imagine = "online2.jpg"; break;
		case 3:	$imagine = "online3.jpg"; break;
		case 4:	$imagine = "online4.jpg"; break;
		case 5:	$imagine = "online5.jpg"; break;
		case 6:	$imagine = "online6.jpg"; break;
	}
	echo "<tr>";
	if($i==0) {
		echo "<td rowspan=\"$numar\"  style=\"vertical-align: top;\"><img src=\"images/$imagine\" class=\"img-1\" width=\"250\"></td>";
	}
	echo "<td>min.$minreal&nbsp;</td><td><a href=\"index.php?option=viewclub&club_id=$uid\" class=\"link-2\">$echipa</a></td><td></td></tr><tr><td></td>";
	echo "<td>$imagine2</td><th>$text</th>";
	echo "</tr>";
	$i++;
}
if($i==0) {
	echo "<tr><th colspan=\"4\"><h2>The game isn't started yet!</h2></th></tr>";
}
mysqli_free_result($res);
?>
</table>

<?php
function NumarSpectatori($echipa1, $rat_1, $rat_2, $competitieid) {
	
	$sql = "SELECT b.pret, b.capacity
			FROM user a
			LEFT OUTER JOIN stadium b
			ON a.stadiumid=b.id
			WHERE a.id=$echipa1";
			
	$res = mysqli_query($GLOBALS['con'],$sql);
	list($pretb, $capacitate)=mysqli_fetch_row($res);
	
	//am $rat_1 si $rat_2, rating-urile celor doua echipe
	//am si $pretb, trebuie sa gasesc o formula care sa le lege pe toate 3, sa dea o proportie de ocupare a tribunelor
	//greetings to lulu
	$pretok = 9+$rat_1/25+($rat_2-$rat_1)/25;
	//pt amical (competitieid==0), pretul agreat sa fie jumate
	if($competitieid==0) $pretok = intval($pretok/2);
	if($pretb==0)$pretb=1;
	$nrspect = intval($pretok*$pretok*$capacitate/($pretb*$pretb));
	if($nrspect > $capacitate) $nrspect=$capacitate;
	//$nrspect = rand(1,$capacitate); //trebuie luata capacitatea stadionului primei echipe
	
	mysqli_free_result($res);

	return $nrspect;
}

?>
