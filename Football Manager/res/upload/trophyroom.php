<table class="tftable">
<tr>
	<th>Prophy room</td>
</tr>
</table>
<table class="tftable">
<?php
if(!empty($_REQUEST['club_id'])) $clubid=$_REQUEST['club_id'];
else $clubid=$_SESSION['USERID'];
$tr = "SELECT b.nume, b.poza, a.sezon
	   FROM trofee a
	   LEFT JOIN trofeutip b
	   ON a.trofeuid=b.id
	   WHERE a.userid=$clubid ORDER BY a.id ASC";//$_REQUEST['club_id'].;
//echo "$tr<br/>";
$restr = mysqli_query($GLOBALS['con'],$tr);
$i=0;
while(list($trofeunume, $trofeupoza, $trofeusezon) = mysqli_fetch_row($restr)) {
	if($i==0) echo "<tr><td>";
	echo "<img src=\"$trofeupoza\" height=\"60\" border=\"0\" title=\"$trofeunume\" class=\"img-11\">";
	if($i%10==0 && $i<>0) echo "</td></tr>";
	$i++;
}
mysqli_free_result($restr);
?>
</table>