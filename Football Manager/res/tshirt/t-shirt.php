<h1>Pick a number:</h1>
<?php
$sql = "SELECT p.id, p.fname, p.lname, p.position, up.number 
		FROM user u
		LEFT JOIN userplayer up
		ON u.id=up.userid
		LEFT JOIN player p
		ON up.PlayerID=p.id  
		WHERE u.id=" . $_SESSION['USERID'] . " ORDER BY p.Position ASC";

$res22 = mysql_query($sql);
echo "<form action=\"index.php?option=club\" method=\"post\"><table class=\"tftable\">";
echo "<tr><th>It will be the t-shirt of:</th><td><select name=\"juc\" class=\"select-train2\">";
while(list($p_id, $fname, $lname, $position, $numar) = mysql_fetch_row($res22)) {
		switch ($position) {
			case 1: $pos = "GK"; $gk="green";$md="white";$fw="white";$dffw="white"; $df="white"; break;
			case 2: $pos = "DR";  $gk="white";$md="white";$fw="white";$dffw="green"; $df="green"; break;
			case 3: $pos = "DC"; $gk="white";$md="white";$fw="white";$dffw="green"; $df="green";break;
			case 4: $pos = "DL"; $gk="white";$md="white";$fw="white";$dffw="green"; $df="green";break;
			case 5: $pos = "MR"; $gk="white";$md="green";$fw="white";$dffw="white"; $df="white";break;
			case 6: $pos = "MC"; $gk="white";$md="green";$fw="white";$dffw="white"; $df="white";break;
			case 7: $pos = "ML"; $gk="white";$md="green";$fw="white";$dffw="white"; $df="white";break;
			case 8: $pos = "FR"; $gk="white";$md="white";$fw="green";$dffw="green"; $df="white";break;
			case 9: $pos = "FC"; $gk="white";$md="white";$fw="green";$dffw="green"; $df="white";break;
			case 10: $pos = "FL"; $gk="white";$md="white";$fw="green";$dffw="green"; $df="white";break;
	}
	if($numar == 0) $numar="";
	else $numar="[$numar]";
	echo "<option value=\"$p_id\">$pos $numar $fname $lname";
}
echo "</select></td>";
echo "<td><select name=\"numar\" class=\"select-note\">";
for($i=0;$i<100;$i++) {
	echo "<option>$i";
}
echo "</select></td>";
echo "<td><input type=\"Submit\" name=\"PickTShirt\" value=\"Set the number\" class=\"button-2\"></td>";
echo "</tr>";
echo "<tr><th colspan=\"4\">*Two players cannot have the same number! If you want to cancel the number, set it to 0.</th></tr></table>";
echo "</form>";
mysql_free_result($res22);
		
?>