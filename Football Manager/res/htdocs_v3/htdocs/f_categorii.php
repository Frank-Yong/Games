<!--
Afisare categorii forum
-->

<table class="tftable">
<?php
$sql = "SELECT id, nume, descriere
		FROM f_categorii
		ORDER BY id ASC";
$res = mysql_query($sql);
$i=0;
while(list($fid, $fnume, $fdescriere) = mysql_fetch_row($res)) {

	$s = "SELECT a.id, a.userid, a.data, c.TeamName, c.UserName, a.topicid
		  FROM f_post a
		  LEFT JOIN user c
		  ON c.id=a.userid
		  WHERE a.topicid IN (SELECT b.id FROM f_topic b WHERE b.categorieid = $fid) ORDER BY a.id DESC";
	//echo "$s<br/>";
	$r = mysql_query($s);
	
	$_postid=0;
	$_userid=0;
	$_data = '-';
	$_user = "";
	$_team = "";
	while(list($pid, $puid, $pdata, $pteam, $puser, $tid) = mysql_fetch_row($r)) {
		$_postid=$tid;
		$_userid=$puid;
		$_data = $pdata;
		$_user = $puser;
		$_team = $pteam;
		break;
	}
	

	$i++;
	echo "<tr>";
	echo "<th><a href=\"f_index.php?catid=$fid\" class=\"link-3\"><h1>$fnume</h1></a></th>";
	echo "</tr>";
	echo "<tr>";
	if($_postid<>0)
		echo "<td>$fdescriere (Ultima postare: <a href=\"f_index.php?topid=$_postid\">$_user $_data</a>)</td>";
	else 
		echo "<td>$fdescriere</td>";
	echo "</tr>";
	mysql_free_result($r);
	
}

mysql_free_result($res);
?>
</table>
