<?php

include('../app.conf.php');

include('../user.php');
include('../Player.php');
include('../trainer.php');

$data = Date("Y-m-d");
$sql = "SELECT id, userid, tip, existent, nou, data
		FROM facilitati
		WHERE data='$data' AND nou>0 AND tip='parcare'";
$res = mysql_query($sql);
while(list($id, $userid, $tip, $existent, $nou, $data) = mysql_fetch_row($res)) {
	$existent = $existent+$nou;
	$s = "UPDATE facilitati SET existent=$existent, nou=0, data='0000-00-00' WHERE id=$id";
	mysql_query($s);
	echo "$s<br/>";
}
mysql_free_result($res);

?>