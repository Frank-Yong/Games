<?php 

include('app.conf.php');
include('player.php');
include('UserStadium.php');
include('trainer.php');

$sql = "SELECT 
			a.userid,
			b.fname, b.lname, b.avatar,
			c.fname as fname1, c.lname as lname1, c.avatar as avatar1,
			d.fname as fname2, d.lname as lname2, d.avatar as avatar2,
			e.fname as fname3, e.lname as lname3, e.avatar as avatar3
			
		FROM testonline a
		LEFT JOIN player b
		ON a.pid11=b.id
		LEFT JOIN player c
		ON a.pid12=c.id
		LEFT JOIN player d
		ON a.pid21=d.id
		LEFT JOIN player e
		ON a.pid22=e.id
		WHERE a.meciid=2 AND a.minut=".$_REQUEST['minut'];

$res = mysql_query($sql);

/*
list($tn) = mysql_fetch_row($res);
mysql_free_result($res);

echo "User $tn";
*/
list($u, $f1, $l1, $a1,$f2, $l2, $a2,$f3, $l3, $a3,$f4, $l4, $a4)  = mysql_fetch_row($res);
mysql_free_result($res);

echo "$u, $f1, $l1, $a1,$f2, $l2, $a2,$f3, $l3, $a3,$f4, $l4, $a4";
?>


