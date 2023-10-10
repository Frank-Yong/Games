
<?php
//pick the first line-up

function ColorIti($color_value) 
{
if($color_value<=20) 
	return "$color_value";

if($color_value>20 && $color_value<=35) 
	return "<font color=\"orange\">$color_value</font>";
if($color_value>35) 
	return "<font color=\"green\">$color_value</font>";
return;

}

/*-----------------------------------------------
Alegere titulari
Se introduuc in tabelul echipastart. 
Exista pentru fiecare echipa o singura echipa de start, nu cite una pentru fiecare etapa.
Cind un jucator este transferat, trebuie scos si din echipa de start.
Trebuie verificat, cind isi alege echipa, sa nu poata alege mai multi portari si sa nu existe mai mult de 11 jucatori in teren.
Daca un jucator nu are echipa de start, pierde meciul la masa verde cu 3-0.


-------------------------------------------------*/

/*
if(isset($_REQUEST['userid'])) {
	//vine id-ul inregistrarii prin GET
	$user = new user();
	$user->LoginID($_REQUEST['userid']);
	
	$user->EchoClub();

}
*/

$sql = "SELECT tactics, midfield, atacks, passes from tactics WHERE userid=".$_SESSION['USERID'];
$res = mysqli_query($GLOBALS['con'],$sql);
list($tactica, $mijlocul, $atacuri, $pase) = mysqli_fetch_row($res);
mysqli_free_result($res);

$cfg_abordare = array(
				"1" => "Normala", 
				"2" => "Ofensiva", 
				"3" => "Defensiva" 

);

$cfg_abordare2 = array(
				"1" => "Normal", 
				"2" => "Mijlocasi ofensivi", 
				"3" => "Mijlocasi defensivi" 

);

$cfg_abordare3 = array(
				"1" => "Atacuri mixte", 
				"2" => "Atacuri pe benzi", 
				"3" => "Atacuri pe centru" 

);

$cfg_pase = array(
				"1" => "Pase combinate", 
				"2" => "Pase inalte", 
				"3" => "Pase pe jos" 

);

$cfg_conditie = array(
			"1"=>"Indiferent de scor",
			"2"=>"Conduc cu un gol",
			"3"=>"Conduc cu doua goluri+",
			"4"=>"Sunt condus cu un gol",
			"5" =>"Sunt condus cu doua goluri+"
);



?>
<h2><?php echo translate('Pick first team!'); ?></h2>

<?php
	echo "<A href=\"#\" class=\"link1\"><img src=\"images/seniori.png\" width=\"30\" title=\"Seniori\"></a> <A href=\"#\" class=\"link2\" title=\"Juniori\"><img src=\"images/juniori.png\" width=\"30\"></a><br/>";
	echo "<div class=\"div1\">";
	AlegeGrupa(0);
	echo "</div>";
	echo "<div class=\"div2\">";
	AlegeGrupa(1);
	echo "</div>";
	
?>


<?php
function AlegeGrupa($grupa) {

$sql = "SELECT tactics, midfield, atacks, passes from tactics WHERE ggroup=$grupa AND userid=".$_SESSION['USERID'];
//echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
list($tactica, $mijlocul, $atacuri, $pase) = mysqli_fetch_row($res);
mysqli_free_result($res);

$cfg_abordare = array(
				"1" => "Normal", 
				"2" => "Offensive", 
				"3" => "Deffensive" 

);

$cfg_abordare2 = array(
				"1" => "Normal", 
				"2" => "Attacking midfielders", 
				"3" => "Defensive midfielders" 

);

$cfg_abordare3 = array(
				"1" => "Mixed attacks", 
				"2" => "Sides attacks", 
				"3" => "Central attacks" 

);

$cfg_pase = array(
				"1" => "Combined passes", 
				"2" => "High passes", 
				"3" => "Low passes" 

);

$cfg_conditie = array(
			"1"=>"No mather what",
			"2"=>"Leading by one goal",
			"3"=>"Leading with 2+ goals",
			"4"=>"I'm behind with one gol",
			"5" =>"I'm behind with 2+ goals"
);

if($grupa==0) $grupastart=1;
if($grupa==1) $grupastart=2;

$sql = "SELECT a.playerId, a.post, LEFT(b.fname,1), LEFT(b.lname,7) 
		FROM lineup a
		LEFT OUTER JOIN player b
		ON a.playerId=b.id
		WHERE a.pgroup=$grupastart AND a.post<>0 AND a.userId=".$_SESSION['USERID']. " ORDER BY a.post ASC";
//echo "$sql<br/>";		
$res = mysqli_query($GLOBALS['con'],$sql);
$pozitii = array();
$jucatori = array();
$i=0;
$findex=0;
$mindex=0;
$aindex=0;
$fs=$fd=$f1=$f2=$f3=$ml=$mc=$mr=$m1=$m2=$m3=$a1=$a2=$a3="";
while(list($e_pid, $e_post, $juc_fname, $juc_lname)=mysqli_fetch_row($res)) {
	$pozitii[$e_pid] = $e_post;
	$jucatori[$e_pid] = $juc_fname.'.'.$juc_lname;
	switch ($e_post) {
		case 1: $pos = "GK"; $portar = $juc_lname; break;
		case 2: $pos = "DR"; $fd = $juc_lname;break;
		case 3: 
			$pos = "DC"; 
			switch($findex) {
				case 0: $f1=$juc_lname; break;
				case 1: $f2=$juc_lname; break;
				case 2: $f3=$juc_lname; break;
			}
			$findex++;
			break;
		case 4: $pos = "DL"; $fs = $juc_lname; break;
		case 5: $pos = "MR"; $mr = $juc_lname; break;
		case 6: 
			$pos = "MC"; 
			switch($mindex) {
				case 0: $m1=$juc_lname; break;
				case 1: $m2=$juc_lname; break;
				case 2: $m3=$juc_lname; break;
			}
			$mindex++;
			
			break;
		case 7: $pos = "ML"; $ml = $juc_lname; break;
		case 8: 
			$pos = "FR"; 
			switch($aindex) {
				case 0: $a1=$juc_lname; break;
				case 1: $a2=$juc_lname; break;
				case 2: $a3=$juc_lname; break;
			}
			$aindex++;
			break;
		case 9: 
			$pos = "FC"; 
			switch($aindex) {
				case 0: $a1=$juc_lname; break;
				case 1: $a2=$juc_lname; break;
				case 2: $a3=$juc_lname; break;
			}
			$aindex++;
			
			break;
		case 10: 
			$pos = "FL"; 
			switch($aindex) {
				case 0: $a1=$juc_lname; break;
				case 1: $a2=$juc_lname; break;
				case 2: $a3=$juc_lname; break;
			}
			$aindex++;
			
			break;
}
	echo "$juc_fname.$juc_lname($pos)";
	if($i<10) echo "-";
	$i++;
}
mysqli_free_result($res);


$sql = "SELECT p.*, p.Training , up.Number
		FROM user u 
		LEFT OUTER JOIN userplayer up
		ON up.UserID=u.id
		LEFT OUTER JOIN player p
		ON up.PlayerID=p.id
		WHERE p.youth=$grupa AND u.id=" . $_SESSION['USERID'] . " ORDER BY p.Position ASC";

//echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);


$portari = 1;
$fundasi = 1;
$mijlocasi = 1;
$atacanti = 1;

?>


<table class="tftable">
<tr>
	<td valign="top">
		<table width="200">
		<tr>
			<th class="testtd">
			<table height="100%" class="transparent">
			<tr>
				<td><font class="numar-accesari"></font></td>
				<td><font class="numar-accesari"><?php echo $a2; ?></font></td>
				<td><font class="numar-accesari"><?php echo $a3; ?></font></td>
				<td><font class="numar-accesari"><?php echo $a1; ?></font></td>
				<td><font class="numar-accesari"></font></td>
				
			</tr>
			<tr>
				<td><font class="numar-accesari"><?php echo $ml; ?></font></td>
				<td><font class="numar-accesari"><?php echo $m2; ?></font></td>
				<td><font class="numar-accesari"><?php echo $m3; ?></font></td>
				<td><font class="numar-accesari"><?php echo $m1; ?></font></td>
				<td><font class="numar-accesari"><?php echo $mr; ?></font></td>
			
			</tr>
			<tr>
				<td><font class="numar-accesari"><?php echo $fs; ?></font></td>
				<td><font class="numar-accesari"><?php echo $f2; ?></font></td>
				<td><font class="numar-accesari"><?php echo $f3; ?></font></td>
				<td><font class="numar-accesari"><?php echo $f1; ?></font></td>
				<td><font class="numar-accesari"><?php echo $fd; ?></font></td>

			</tr>
			<tr>
				<td></td>
				<td></td>
				<td><font class="numar-accesari"><?php echo $portar; ?></font></td>
				<td></td>
				<td></td>

			</tr>
			</table>
			</th>
		</tr>
		<tr>
			<td>
			<form action="index.php" method="POST">
			<input type="hidden" name="grupa" value="<?php echo $grupa; ?>">
			<table class="tf2">
			<tr>
			<th>
			<?php echo translate('Approach'); ?>:</th>
			<td>
			<select name="tactica" class="select-2">
				<?php 
				foreach($cfg_abordare as $k=>$v) {
					$selected = $k==$tactica? 'selected': '';
					echo "<option value=\"$k\" $selected>$v";
				}
				?>
			</select>
			</td>
			</tr>
			<tr>
			<th>
			<?php echo translate('Midfield'); ?>:</th>
			<td>
			<select name="mijlocul" class="select-2">
				<?php 
				foreach($cfg_abordare2 as $k=>$v) {
					$selected = $k==$mijlocul? 'selected': '';
					echo "<option value=\"$k\" $selected>$v";
				}
				?>
			</select>
			</td>
			</tr>
			<tr>
			<th>
			<?php echo translate('Attacking style'); ?>:</th>
			<td>
			<select name="atacuri" class="select-2">
				<?php 
				foreach($cfg_abordare3 as $k=>$v) {
					$selected = $k==$atacuri? 'selected': '';
					echo "<option value=\"$k\" $selected>$v";
				}
				?>
			</select>
			</td>
			</tr>
			<tr>
			<th>
			<?php echo translate('Passing mode'); ?>:</th>
			<td>
			<select name="pase" class="select-2">
				<?php 
				foreach($cfg_pase as $k=>$v) {
					$selected = $k==$pase? 'selected': '';
					echo "<option value=\"$k\" $selected>$v";
				}
				?>
			</select>
			</td>
			</tr>

			<tr>
			<th colspan="2">
			<input type="Submit" name="SendAbordare" value="<?php echo  translate('Choose'); ?>" class="button-2">
			</th>
			</tr>
			<tr>
			<style>
ul {
	margin: 2em; 
}
ul li {
	padding-left: 1em; 
	margin-bottom: .5em; 
}
			</style>
			<td colspan="2">
				<ul>
				<li>
				An offensive approach of the game makes the contribution of midfielders and attackers diminish defensively. On the other hand, the team will have a stronger attack, which can translate into more goals scored than usual. At the same time, playing defensively can lead to fewer goals scored, because both the midfielders and the attackers will play lower and will help the defense more.
				<li>
				The attacks on the bands are suitable for the team with very good lateral midfielders. The attack on the center is good to use if the central midfielders have very good offensive qualities (distance sutures, header or shot).				
				<li>
				The way of passing is important and also related to the players you have. If you have strikers with a very good header, from an offensive point of view it is better to play with high passes. If the shot is better for the attackers, maybe it's better to play with low passes. Of course, (soon) the game will also depend on the weather conditions, and then it is important to choose the passing mode corresponding to the type of terrain.</ul>
			</td>
			</tr>
			</table>
			</form>
			</td>
		</tr>
	</table>
	</td>
	<td valign="top">
	<form action="index.php" method="POST">
	<input type="hidden" name="grupa" value="<?php echo $grupa; ?>">
	<table cellspacing="1">
	<?php
			
		while($p_array = mysqli_fetch_assoc($res)) {
			switch ($p_array['Position']) {
					case 1: $pos = "GK"; break;
					case 2: $pos = "DR"; break;
					case 3: $pos = "DC"; break;
					case 4: $pos = "DL"; break;
					case 5: $pos = "MR"; break;
					case 6: $pos = "MC"; break;
					case 7: $pos = "ML"; break;
					case 8: $pos = "FR"; break;
					case 9: $pos = "FC"; break;
					case 10: $pos = "FL"; break;
			}
			$pid = $p_array['id'];

			if ($pos == 'GK' and $portari==1) {
				echo "<tr>";
				echo "<th colspan=\"3\">&nbsp;</th>";
				echo "<th>Reflexe</th><th>Unu la unu</th><th>Manevr.</th><th>Com.</th>";
				echo "<th></th>";
				echo "<th>Forma</th>";
				echo "</tr>";
				$portari=0;
			}

			if (($pos == 'DL' or $pos == 'DC' or $pos == 'DR') and $fundasi==1) {
				echo "<tr>";
				echo "<th colspan=\"3\">&nbsp;</th>";
				echo "<th>Deposed.</th><th>Marcaj</th><th>Joc de cap</th><th>Poz.</th>";
				echo "<th></th>";
				echo "<th>Forma</th>";
				echo "</tr>";
				$fundasi=0;
			}

			if (($pos == 'ML' or $pos == 'MC' or $pos == 'MR') and $mijlocasi==1) {
				echo "<tr>";
				echo "<th colspan=\"3\">&nbsp;</th>";
				echo "<th>Creativ</th><th>Lansari</th><th>Pase</th><th>Sut distanta</th>";
				echo "<th></th>";
				echo "<th>Forma</th>";
				echo "</tr>";
				$mijlocasi=0;
			}

			if (($pos == 'FL' or $pos == 'FC' or $pos == 'FR') and $atacanti==1) {
				echo "<tr>";
				echo "<th colspan=\"3\">&nbsp;</th>";
				echo "<th>Sut</th><th>Joc de cap</th><th>Prima atingere</th><th>Poz.</th>";
				echo "<th></th>";
				echo "<th>Forma</th>";
				echo "</tr>";
				$atacanti=0;
			}
			$td="";
			if($p_array['Number'] <> 0) {
				$td = "class=\"numar\"";
			}

			echo "<tr>";
			echo "<td $td><div class=\"div-33\">".$p_array['fname']."&nbsp;".$p_array['lname']."</div></td>";
			echo "<td>".$p_array['Age']."&nbsp;ani</td>";// - ".$p_array['Talent']."</td>";
			echo "<td>".$pos."</td>";
			if ($pos == 'GK') {		
				echo "<td>".ColorIti($p_array['reflexes'])."</td>";
				echo "<td>".ColorIti($p_array['OneOnOne'])."</td>";
				echo "<td>".ColorIti($p_array['Handling'])."</td>";
				echo "<td>".ColorIti($p_array['Communication'])."</td>";
			}
			if ($pos == 'DL' or $pos == 'DC' or $pos == 'DR') {		
				echo "<td>".ColorIti($p_array['Tackling'])."</td>";
				echo "<td>".ColorIti($p_array['Marking'])."</td>";
				echo "<td>".ColorIti($p_array['Heading'])."</td>";
				echo "<td>".ColorIti($p_array['Positioning'])."</td>";
			}
			if ($pos == 'FL' or $pos == 'FC' or $pos == 'FR') {		
				echo "<td>".ColorIti($p_array['Shooting'])."</td>";
				echo "<td>".ColorIti($p_array['Heading'])."</td>";
				echo "<td>".ColorIti($p_array['FirstTouch'])."</td>";
				echo "<td>".ColorIti($p_array['Positioning'])."</td>";
			}
			if ($pos == 'ML' or $pos == 'MC' or $pos == 'MR') {		
				echo "<td>".ColorIti($p_array['Creativity'])."</td>";
				echo "<td>".ColorIti($p_array['Crossing'])."</td>";
				echo "<td>".ColorIti($p_array['Passing'])."</td>";
				echo "<td>".ColorIti($p_array['LongShot'])."</td>";
			}
			if($p_array['training']==0 && $p_array['injured']==0) {
				echo "<td><select name=\"player_$pid\">";
				for($i=0;$i<11; $i++) {
					$selected = "";
					if ($pozitii[$p_array['id']] == $i) {
						$selected = " SELECTED";
					} 

					switch($i) {
						case 0: $pos = "-"; break;
						case 1: $pos = "GK"; break;
						case 2: $pos = "DR"; break;
						case 3: $pos = "DC"; break;
						case 4: $pos = "DL"; break;
						case 5: $pos = "MR"; break;
						case 6: $pos = "MC"; break;
						case 7: $pos = "ML"; break;
						case 8: $pos = "FR"; break;
						case 9: $pos = "FC"; break;
						case 10: $pos = "FL"; break;
					}
					echo "<option value=\"$i\" $selected>$pos</option>";
				}
				echo "</select></td>";
			} else {
				if($p_array['training']==1)
					echo "<td><img src=\"images/t_camp.png\" width=\"20\" title=\"Training Camp\"></td>";
				if($p_array['accidentat']==1)
					echo "<td><img src=\"images/injured.png\" width=\"20\" title=\"Accidentat\"></td>";
				
			}
			echo "<td>";
				echo "<img width=\"33\" height=\"5\" src=\"baragrafica.php?percentage=".$p_array['Form']."\"><br/><br/>";
				//echo "<img width=\"33\" height=\"5\" src=\"baragrafica.php?percentage=".$p_array['Moral']."\">";
			echo "</td>";

			echo "</tr>";
		}
		mysqli_free_result($res);


	?>
	<tr>
		<td colspan="9">
		<input type="Submit" name="Alege11" value="<?php echo translate('Pick first squad'); ?>" class="button-2">
		</td>
	</tr>
	</table>
	</form>
	<br/>
	<h1><?php echo translate('CHANGES'); ?></h1>
	<form action="index.php" method="POST">
	<input type="hidden" name="grupa" value="<?php echo $grupa; ?>">
	<table cellspacing="1" width="100%">
	<tr>
		<th><?php echo translate('Minute'); ?></th>
		<th><?php echo translate('Plays as'); ?></th>
		<th><?php echo translate('Replacer'); ?></th>
		<th><?php echo translate('Replaced'); ?></th>
	</tr>
	<tr>
		<td>
		<select name="minut">
		<?php
		for($i=0;$i<91;$i=$i+10) {
			echo "<option value=\"$i\">$i";
		}
		?>
		</select>
		</td>
		<td>
		<select name="post">
		<?php
			for($i=1;$i<11; $i++) {
				switch($i) {
					case 1: $pos = "GK"; break;
					case 2: $pos = "DR"; break;
					case 3: $pos = "DC"; break;
					case 4: $pos = "DL"; break;
					case 5: $pos = "MR"; break;
					case 6: $pos = "MC"; break;
					case 7: $pos = "ML"; break;
					case 8: $pos = "FR"; break;
					case 9: $pos = "FC"; break;
					case 10: $pos = "FL"; break;
				}
				echo "<option value=\"$i\">$pos";
			}
			?>
		</select>
		</td>
		<td>
		<?php 
		$sql = "SELECT a.playerid, b.fname, b.lname, a.post
				FROM lineup a
				LEFT JOIN player b
				ON a.playerid=b.id
				WHERE a.pgroup=$grupastart AND a.userid=".$_SESSION['USERID'];
		//echo "$sql<br/>";
		$res = mysqli_query($GLOBALS['con'],$sql);
		$i=0;
		$j=0;
		while(list($pid, $fname, $lname, $post) = mysqli_fetch_row($res)) {
			if($post == 0) 
				$rezerve[$i++] = "<option value=\"$pid\">$fname $lname";
			else 
				$titulari[$j++] = "<option value=\"$pid\">$fname $lname";
			
		}	
		mysqli_free_result($res);
		?>
		<select name="rezerva">
		<?php
			for($k=0;$k<$i;$k++) 
				echo $rezerve[$k];
		?>
		</select>
		</td>
		<td>
		<select name="titular">
		<?php
			for($k=0;$k<$j;$k++) 
				echo $titulari[$k];
		?>
		</select>
		</td>
	</tr>
	<tr>
		<th colspan="4">
		<?php echo translate('Condition'); ?>: 
		<select name="conditie">
			<?php
				foreach($cfg_conditie as $k=>$v) {
					echo "<option value=\"$k\" $selected>$v";
				}
			?>
		</select>
		</th>
	</tr>
	<tr>
		<td colspan="4"><input type="Submit" name="AlegeSchimbari" value="<?php echo translate('Add change'); ?>" class="button-2"></td>
	</tr>
	</table>
	</form>
	<br/>
	<table width="100%">
	<tr>
		<th>Action</th>
		<th>Exchange</th>
	</tr>
	<?php
	$sql = "SELECT a.id, b.fname, b.lname, c.fname, c.lname, a.minut, a.conditie1, a.post
			FROM schimbari a
			LEFT JOIN player b
			ON a.playerid1=b.id
			LEFT JOIN player c
			ON a.playerid2=c.id
			WHERE a.grupa=$grupa AND a.userid=".$_SESSION['USERID'];
	//echo "$sql<br/>";
	$res = mysqli_query($GLOBALS['con'],$sql);
	while(list($sch_id, $fname1, $lname1, $fname2, $lname2, $sch_minut, $sch_conditie, $sch_post)=mysqli_fetch_row($res)) {
		switch($sch_post) {
			case 1: $pos = "GK"; break;
			case 2: $pos = "DR"; break;
			case 3: $pos = "DC"; break;
			case 4: $pos = "DL"; break;
			case 5: $pos = "MR"; break;
			case 6: $pos = "MC"; break;
			case 7: $pos = "ML"; break;
			case 8: $pos = "FR"; break;
			case 9: $pos = "FC"; break;
			case 10: $pos = "FL"; break;
		}	
	?>
	<tr>
		<td><a href="index.php?delSchimbare=<?php echo $sch_id; ?>"> <img src="images/delete.png" border="0" width="25"></a></td>
		<td><?php echo "Min. $sch_minut: [$pos] $fname1 $lname1"; ?><img src="images/schimbare.png" border="0" width="15"><?php echo "$fname2 $lname2 (".$cfg_conditie[$sch_conditie].")"; ?></td>
	</tr>
	<?php 
	} 
	mysqli_free_result($res);
	?>
	</table>
	
	</td>
</tr>
</table>
<?php
}
?>

