<html>
<head>

	<style>body{font-family:Verdana,Arial,Helvetica,sans-serif;}</style>
</head>
<body>
<b>Echipa salvata:</b>
<br>
<?php

include('app.conf');

// accept parameters (p is array)
$arr = $_REQUEST['p'];
// open loop through each array element
foreach ($arr as $p){
	//in tabelul 0 se afla id-ul utilizatorului
	//in tabelul 1 se afla jucatorii
	// detach values from each parameter
	list($id, $table, $row, $column) = explode('_', $p);
	// instead of print, you can store accepted parameteres to the database
	if($table == 0){
		//aici este id-ul
		$iduser = ereg_replace("u", "", $id);
		//stergere titulari
		$sql = "DELETE FROM echipastart WHERE userId = $iduser";
		mysql_query($sql);
	}
	if($table == 1) {
		//postul este dat de coloana
		if($column == 0) $post = 1;
		if($column == 1) $post = 2;
		if($column == 2) $post = 3;
		if($column == 3) $post = 3;
		if($column == 4) $post = 4;
		if($column == 5) $post = 5;
		if($column == 6) $post = 6;
		if($column == 7) $post = 6;
		if($column == 8) $post = 7;
		if($column == 9) $post = 9;
		if($column == 10) $post = 9;
		
		$idjuc = ereg_replace("d","", $id);
		
		$sql = "INSERT INTO echipastart (playerId, post, meciId, userId)
				VALUES ($idjuc, $post, 1, $iduser)";
		echo "$sql<br/>";
		mysql_query($sql);
	}
	//print "Id=$id Table=$table Column=$column Row=$row Post=$post<br>";
}
?>
</body>
</html>