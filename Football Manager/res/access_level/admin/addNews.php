<?php
//phpinfo();
error_reporting(E_ALL);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');


$result = access_level("helper");
if ($result == 0) {
	$err_msg .= "Permission denied<br>";
	include('admin.head.php');
	exit;
}

include('admin.head.php');


if(!empty($_REQUEST['Insert'])) {
		$data = Date("Y-m-d");
		$ti = $_REQUEST['title'];
		$te = $_REQUEST['text'];
		$sql2 = "INSERT INTO news (title, text, data)
				 VALUES('$ti','$te','$data')";
		echo "$sql2<br/>";
		mysqli_query($GLOBALS['con'],$sql2);

}

if(!empty($_REQUEST['Delete'])) {
		$idu = $_REQUEST['Delete'];
		$sql2 = "DELETE FROM news WHERE id=$idu";
		echo "$sql2<br/>";
		mysqli_query($GLOBALS['con'],$sql2);

}


?>
<h1>News section</h1>
<form action="addNews.php" method="POST">
<table>
<tr>
	<td>Title</td>
	<td>
	<input type="text" name="title" size="30">
	</td>
</tr>
<tr>
	<td>Stire</td>
	<td>
	<textarea name="text" rows="3" cols="30"></textarea>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="Submit" name="Insert" value="Add news"></td>
</tr>
</table>
</form>
<?php
$sql = "SELECT id, title, text, data
		FROM news
		ORDER BY id DESC";
$res = mysqli_query($GLOBALS['con'],$sql);
$i=0;
while(list($n_id, $n_titlu, $n_text, $n_data) = mysqli_fetch_row($res)) {
	echo "$n_titlu ($n_data)<br/>$n_text<br/><a href=\"addNews.php?Delete=$n_id\">Delete</a><br/><br/>";
	$i++;
	if($i>20) break;
}
mysqli_free_result($res);
?>