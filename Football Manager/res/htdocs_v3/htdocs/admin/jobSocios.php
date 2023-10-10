<?php
error_reporting(E_ALL);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

//actualizare socios -> ruleaza in fiecare zi, sa faca update la numarul de like-uri 
//preia fiecare utilizator si citeste nr de like-uri

$sql = "SELECT id from user WHERE activated=1 and botteam=0 ORDER BY id ASC";
$res = mysql_query($sql);
while(list($userid) = mysql_fetch_row($res)) {
	$url = "https://api.facebook.com/method/fql.query?query=select%20%20like_count%20from%20link_stat%20where%20url=%22http://www.cupaligii.ro/index.php?fbclub=$userid%22";

	if (($response_xml_data = file_get_contents($url))===false){
		echo "Error fetching XML\n";
	} else {
		libxml_use_internal_errors(true);
		$data = simplexml_load_string($response_xml_data);
		if (!$data) {
			echo "Error loading XML\n";
			foreach(libxml_get_errors() as $error) {
				echo "\t", $error->message;
			}
		} else {
			$sql = "INSERT INTO tribuna (userid, numar) VALUES($userid, ".$data->link_stat->like_count.")";
			mysql_query($sql);

			$sql = "UPDATE tribuna SET numar = ".$data->link_stat->like_count. " WHERE userid=$userid";
			mysql_query($sql);
			echo "$sql<br/>";
		}
	}
}
mysql_free_result($res);
?>

