<?php
$sql = "SELECT a.proprietarid, a.cumparatorid, a.playerid, a.suma, a.data, b.TeamName, c.fname, c.lname 
		FROM transferuri a
		LEFT JOIN user b
		ON a.proprietarid=b.id
		LEFT JOIN player c
		ON a.playerid=c.id
		WHERE a.cumparatorid=".$_SESSION['USERID']. " ORDER BY a.data DESC";
$res = mysqli_query($GLOBALS['con'],$sql);
echo "<h1>Players bought</h1>";
echo "<table class=\"tftable\">";
echo "<tr><th>Player</th><th>Team</th><th>Amount</th><th>Date</th>";
while(list($propid, $cumpid, $pid, $suma, $data, $teamname, $fname, $lname) = mysqli_fetch_row($res)) {
		echo "<tr>";
		echo "<td><a class=\"link-5\" href=\"echipa.php?id=$pid\">$fname $lname</a></td>";
		if($propid==0) echo "<td>Liber de transfer</td>";
		else echo "<td>$teamname</td>";
		echo "<td>".number_format($suma)." &euro;</td>";
		echo "<td>$data</td>";
		echo "</tr>";
}
echo "</table>";
mysqli_free_result($res);


//sold

$sql = "SELECT a.proprietarid, a.cumparatorid, a.playerid, a.suma, a.data, b.TeamName, c.fname, c.lname 
		FROM transferuri a
		LEFT JOIN user b
		ON a.cumparatorid=b.id
		LEFT JOIN player c
		ON a.playerid=c.id
		WHERE a.proprietarid=".$_SESSION['USERID']. " ORDER BY a.data DESC";
$res = mysqli_query($GLOBALS['con'],$sql);
echo "<h1>Players sold</h1>";
echo "<table class=\"tftable\">";
echo "<tr><th>Player</th><th>Team</th><th>Amount</th><th>Date</th>";
while(list($propid, $cumpid, $pid, $suma, $data, $teamname, $fname, $lname) = mysqli_fetch_row($res)) {
		echo "<tr>";
		echo "<td><a class=\"link-5\" href=\"index.php?option=viewplayer&pid=$pid\">$fname $lname</a></td>";
		echo "<td>$teamname</td>";
		echo "<td>".number_format($suma)." &euro;</td>";
		echo "<td>$data</td>";
		echo "</tr>";
}
echo "</table>";
mysqli_free_result($res);


?>