<h2>Training</h2>
<?php
//error_reporting(63);

//2 reguli de implementat:
// REZOLVAT --- 1. nu poti paria pe jucatorul propriu
// REZOLVAT --- 2. trebuie verificati banii din cont la pariere
// REZOLVAT --- mai trebuie verificat daca timpul nu a expirat cumva, iar daca se pariaza in ultimele doua minute, sa se modifice data transf. = ora parierii+2 minute.

//REZOLVAT ---- trebuie pus un T in dreptul jucatorului, in cadran de culoare
//REZOLVAT ---- nu trebuie sa mai poti scoate jucatorul de pe transfer cind deja s-a pariat pe el
//trebuie implementat ca dupa ce cineva cistiga licitatia, sa se duca informatia in tabelul requests, ca sa fie procesat ziua urmatoare

//sa nu se poata paria mai putin decit suma maxima existenta + 1000;
if($ant==1) {
	$p = new Trainer(1,$_REQUEST['pid'],1);		
	$p->EchoTrainer();
	
	$trid = $p->ReturnID();
	$numeantrenor = $p->ReturnName();
	//afisare jucatori
	$sql = "SELECT a.playerid, b.fname, b.lname, b.Position, c.trainerid, c.post, c.ttype
			FROM userplayer a
			LEFT JOIN player b
			ON a.playerid=b.id
			LEFT JOIN trainerplayer c
			ON a.playerid=c.playerid
			WHERE a.userid=".$_SESSION['USERID'] . " ORDER BY b.Position ASC";
	$res = mysqli_query($GLOBALS['con'],$sql);
	echo "<br/>";
	echo "<form action=\"index.php?option=club&antrenor=1\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"trainerid\" value=\"$trid\">";
	echo "<table class=\"tftable\"><tr><th colspan=\"3\">Choose training</th><th>Intensity</th></tr>";
	while(list($pid, $fname, $lname, $pos ,$trainerid, $post, $tip) = mysqli_fetch_row($res)) {
		switch ($pos) {
				case 1: $posi = "GK"; break;
				case 2: $posi = "DR"; break;
				case 3: $posi = "DC"; break;
				case 4: $posi = "DL"; break;
				case 5: $posi = "MR"; break;
				case 6: $posi = "MC"; break;
				case 7: $posi = "ML"; break;
				case 8: $posi = "FR"; break;
				case 9: $posi = "FC"; break;
				case 10: $posi = "FL"; break;
		}		
				echo "<tr><td><span class=\"numar-tricou\">$posi</span> $fname $lname </td>";
				$tr = array(0=>"-", 1 => "GK", 2 => "Defender", 3 => "Midfielder", 4 => "Forward");
				$trtip = array(0=>"-", 1 => "Lite", 2 => "Normal", 3 => "Intense");
				echo "<td><select name=\"post_$pid\" class=\"select-train\">";
				while(list($k,$v) = each($tr)) {
					$selected = "";
					if($k==$post) $selected="selected";
					echo "<option value=\"$k\" $selected>$v";
				}
				
				echo "</select></td>";
				if($trid !="")
					$arttrainer = array(0,$trid);
				else 
					$arttrainer = array(0); 
				echo "<td><select name=\"antrenor_$pid\" class=\"select-train2\">";
				while(list(,$v) = each($arttrainer)) {
					
					$selected = $v==$trainerid ? 'selected':'';
					if($v==$trid && $v!=0) $nume= $numeantrenor;
					else $nume = "-";
					echo "<option value=\"$v\" $selected>$nume";
				}
				echo "</select>";
				echo "</td>";
				
				echo "<td><select name=\"tip_$pid\" class=\"select-train\">";
				while(list($k,$v) = each($trtip)) {
					$selected = "";
					if($k==$tip) $selected="selected";
					echo "<option value=\"$k\" $selected>$v";
				}
				
				echo "</select></td></tr>";
	}
	echo "<tr><th colspan=\"5\"><input type=\"Submit\" name=\"AlegeAntrenament\" class=\"button-2\" value=\"Train them!\">";
	echo "</th></tr>";
	echo "</table></form>";
	mysqli_free_result($res);
} else {
	//echo "aiiiiiiiciiiii<br/>";
	$p = new Trainer(1,$_REQUEST['pid']);		
	$p->EchoTrainer();

}

?>
<a href="##" onClick="history.go(-1); return false;"><span class="button-2">&nbsp;Back&nbsp;</span></a>