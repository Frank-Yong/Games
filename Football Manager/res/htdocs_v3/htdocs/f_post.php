<!--
Afisare topicuri din categorie
-->
<?php add(); ?>
<br/>

<table class="tftable">
<?php
$sql = "SELECT a.id, a.userid, a.data, a.comentariu, c.TeamName, c.UserName, b.nume, d.nume, b.id, d.id
		FROM f_post a
		LEFT JOIN f_topic b
		ON a.topicid=b.id
		LEFT JOIN f_categorii d
		ON b.categorieid=d.id
		LEFT JOIN user c
		ON a.userid=c.id
		
		WHERE a.topicid=".$_REQUEST['topid']. "
		ORDER BY a.data DESC";
//echo "$sql<br/>";
$res = mysql_query($sql);
$i=0;
while(list($tid, $tuserid, $tdata, $tcomentariu, $team, $manager, $topicnume, $categorienume, $topicid, $catid) = mysql_fetch_row($res)) {
	if($i==0) echo "<a href=\"f_index.php\">Forum</a> >> <a href=\"f_index.php?catid=$catid\">$categorienume</a> >> <a href=\"f_index.php?topid=$topicid\">$topicnume</a><br/>";
	$i++;
	//f_post` (`id`, `userid`, `topicid`, `nume`, `comentariu`, `data`
	echo "<tr>";
	echo "<th><a href=\"index.php?option=viewclub&club_id=$tuserid\" class=\"link-3\">$manager ($team)</a><br/>Data: $tdata</th>";
	echo "</tr><tr>";
	echo "<td colspan=\"2\">".nl2br($tcomentariu)."</td>";
	echo "</tr>";
	mysql_free_result($r);
	
}
if($i==0) {
	//nu sunt rezultate
	echo "<a href=\"f_index.php\">Forum</a> >> <a href=\"f_index.php?catid=$catid\">$categorienume</a> >> <a href=\"f_index.php?topid=$topicid\">$topicnume</a><br/>";
	echo "<tr><td>Nu sunt postari in aceasta categorie!</td></tr>";
}
mysql_free_result($res);
?>
</table>

<?php

add();

function add() {
?>
<a onclick="showComment();" href="javascript:;" class="button-2">&nbsp;Adauga raspuns&nbsp;</a>
<div class="1" id="comentariu" style="display:none">
	<form action="" method="post">
	<input type="hidden" name="topicid" value="<?php echo $_REQUEST['topid']; ?>">
	<input type="hidden" name="userid" value="<?php echo $_SESSION['USERID']; ?>">
	<table class="tftable">
	<tr>
		<th>Text</th>
		<td>
			<textarea name="descriere" cols="40" rows="5"></textarea>
		</td>
	</tr>
	<tr>
	<td colspan="2" align="right">
	<input type="Submit" name="AdaugaRaspuns" value="Trimite" class="button-2"/>
	</td>
	</tr>
	</table>
	</form>
</div>
<?php
}
?>