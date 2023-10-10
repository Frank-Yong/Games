<?php

$sql = "SELECT id, nume, descriere 
		FROM tips";
$res = mysqli_query($GLOBALS['con'], $sql);
$rows = mysqli_num_rows($res);
$rind = rand(2, $rows);
$i=0;
while(list($id, $nume, $descriere) = mysqli_fetch_row($res)) {
	if($i == $rind) {
		echo "<h2>$nume</h2><br/><br/>$descriere";
	}
	$i++;
}
mysqli_free_result($res);
?>