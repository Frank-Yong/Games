<h2>Tactica curenta</h2>

<?php
$sql = "SELECT a.playerid, b.fname, b.lname, a.post FROM echipastart a
		LEFT OUTER JOIN player b
		ON a.playerid=b.id
		WHERE a.userid=".$_SESSION['USERID']." ORDER BY a.post ASC";

$res = mysql_query($sql);
$i=0;
while(list($playerid, $fname, $lname, $pos) = mysql_fetch_row($res)) {
	echo "$fname $lname";
	if($i<10) {
	if($pos == 1) echo " - ";
	else echo ", ";
	}
	$i++;
}
mysql_free_result($res);


if (isset($_REQUEST['Alege11'])) {
	$sql = "SELECT p.id FROM User u, userplayer up, Player p
			WHERE up.PlayerID=p.id AND up.UserID=u.id AND u.id=" . $_SESSION['USERID'] . " ORDER BY p.Position ASC";
	$res = mysql_query($sql);

	while(list($pid) = mysql_fetch_row($res)) {
		//se modifica echipa (cei care nu au fost selectati, se modifica in 0)
		$insert = "INSERT INTO echipastart(post, meciId, playerId, userId)
				   VALUES(".$_REQUEST['player_'.$pid].", 1, $pid, ".$_SESSION['USERID'].")";
		mysql_query($insert);
		echo $insert.'<br/>';

		$update = "UPDATE echipastart SET post = ". $_REQUEST['player_'.$pid] . " WHERE meciId=1 AND playerId=$pid AND userId=".$_SESSION['USERID'];
		echo $update.'<br/>';
		
		mysql_query($update);
	}


	mysql_free_result($res);
	
}


$sql = "SELECT playerId, post 
		FROM echipastart
		WHERE userId=".$_SESSION['USERID'];
$res = mysql_query($sql);
$pozitii = array();
while(list($e_pid, $e_post)=mysql_fetch_row($res)) {
	$pozitii[$e_pid] = $e_post;
}
mysql_free_result($res);


	$sql = "SELECT p.* FROM User u, userplayer up, Player p
			WHERE up.PlayerID=p.id AND up.UserID=u.id AND u.id=" . $_SESSION['USERID'] . " ORDER BY p.Position ASC";
	$res = mysql_query($sql);


	$portari = 1;
	$fundasi = 1;
	$mijlocasi = 1;
	$atacanti = 1;

?>
		<script type="text/javascript" src="drag.js"></script>

		<!-- tables inside this DIV could have draggable content -->
<h2>Alege tactica</h2>


		<div id="drag">
<?php 
$cel = "u".$_SESSION['USERID'];
?>

<table id="table21">
<tr>
	<td><div id="<?php echo $cel; ?>"></div></td>
</tr>
</table>

<table id="table2" width="1100">
		<tr>
						<td width="75">GK</td>
						<td width="75">DR</td>
						<td width="75">DC</td>
						<td width="75">DC</td>
						<td width="75">DL</td>
						<td width="75">MR</td>
						<td width="75">MC</td>
						<td width="75">MC</td>
						<td width="75">ML</td>
						<td width="75">FC</td>
						<td width="75">FC</td>
					</tr>
</table>


			<table id="table1" width="530">
<?php
		
	while($p_array = mysql_fetch_assoc($res)) {
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
		$celulaid="d".$pid;
		if ($pos == 'GK' and $portari==1) {
			echo "<tr>";
			echo "<td colspan=\"3\" class=\"mark\">&nbsp;</td>";
			echo "<td class=\"mark\">Reflexes</td><td class=\"mark\">One on One</td><td class=\"mark\">Handling</td><td class=\"mark\">Communication</td>";
			echo "</tr>";
			$portari=0;
		}

		if (($pos == 'DL' or $pos == 'DC' or $pos == 'DR') and $fundasi==1) {
			echo "<tr>";
			echo "<td colspan=\"3\" class=\"mark\">&nbsp;</td>";
			echo "<td class=\"mark\">Tackling</td><td class=\"mark\">Marking</td><td class=\"mark\">Heading</td><td class=\"mark\">Positioning</td>";
			echo "</tr>";
			$fundasi=0;
		}

		if (($pos == 'ML' or $pos == 'MC' or $pos == 'MR') and $mijlocasi==1) {
			echo "<tr>";
			echo "<td colspan=\"3\" class=\"mark\">&nbsp;</td>";
			echo "<td class=\"mark\">Creativity</td><td class=\"mark\">Crossing</td><td class=\"mark\">Passing</td><td class=\"mark\">Long Shots</td>";
			echo "</tr>";
			$mijlocasi=0;
		}

		if (($pos == 'FL' or $pos == 'FC' or $pos == 'FR') and $atacanti==1) {
			echo "<tr>";
			echo "<td colspan=\"3\" class=\"mark\">&nbsp;</td>";
			echo "<td class=\"mark\">Shooting</td><td class=\"mark\">Heading</td><td class=\"mark\">First Touch</td><td class=\"mark\">Positioning</td>";
			echo "</tr>";
			$atacanti=0;
		}

		echo "<tr>";
		echo "<td><div id=\"".$celulaid."\" class=\"drag t1\">".$p_array['fname']." ".$p_array['lname']."</div></td>";
		echo "<td class=\"mark\">".$p_array['Age']." ani - ".$p_array['Talent']."</td>";
		echo "<td class=\"mark\">".$pos."</td>";
		if ($pos == 'GK') {		
			echo "<td class=\"mark\">".$p_array['reflexes']."</td>";
			echo "<td class=\"mark\">".$p_array['OneOnOne']."</td>";
			echo "<td class=\"mark\">".$p_array['Handling']."</td>";
			echo "<td class=\"mark\">".$p_array['Communication']."</td>";
		}
		if ($pos == 'DL' or $pos == 'DC' or $pos == 'DR') {		
			echo "<td class=\"mark\">".$p_array['Tackling']."</td>";
			echo "<td class=\"mark\">".$p_array['Marking']."</td>";
			echo "<td class=\"mark\">".$p_array['Heading']."</td>";
			echo "<td class=\"mark\">".$p_array['Positioning']."</td>";
		}
		if ($pos == 'FL' or $pos == 'FC' or $pos == 'FR') {		
			echo "<td class=\"mark\">".$p_array['Shooting']."</td>";
			echo "<td class=\"mark\">".$p_array['Heading']."</td>";
			echo "<td class=\"mark\">".$p_array['FirstTouch']."</td>";
			echo "<td class=\"mark\">".$p_array['Positioning']."</td>";
		}
		if ($pos == 'ML' or $pos == 'MC' or $pos == 'MR') {		
			echo "<td class=\"mark\">".$p_array['Creativity']."</td>";
			echo "<td class=\"mark\">".$p_array['Crossing']."</td>";
			echo "<td class=\"mark\">".$p_array['Passing']."</td>";
			echo "<td class=\"mark\">".$p_array['LongShot']."</td>";
		}
		echo "</tr>";
	}
	mysql_free_result($res);
?>
</table>

			<table id="table3">
				<colgroup><col width="100"/><col width="100"/><col width="100"/><col width="100"/><col width="100"/></colgroup>
				<tr style="background-color: #eee">
					<td id="message" class="mark" title="<->">Steagu.ro</td>
				</tr>
			</table>
			<div><input type="button" value="Save" class="button" onclick="REDIPS.drag.save_content()" title="Salvare echipa pe server!"/><span class="message_line">Salvare echipa pe server!</span></div>


		</div>
