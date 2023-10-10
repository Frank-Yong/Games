<?php

include('../app.conf.php');

include('../user.php');
include('../Player.php');
include('../trainer.php');


$sql = "INSERT INTO messages(fromID, toID, subject, body, data, meciID, sponsor)
		VALUES(0, 23, 'Ora test 11:00', 'ora test', '".Date("Y-m-d H:i:s")."', 0, 0)";
$resmes = mysql_query($sql);
echo "$sql<br/>";





?>