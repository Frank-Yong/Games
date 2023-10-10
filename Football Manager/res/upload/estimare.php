<?php
$nextday = date('Y-m-d',strtotime("+1 day"));

$eid = -1;
$sql2 = "SELECT a.id, a.scor, a.userid
		FROM estimatescore a
		WHERE a.meciid=".$meciid2 ." AND a.userid=".$_SESSION['USERID'];
$res2 = mysqli_query($GLOBALS['con'], $sql2);
list($eid, $estimare, $user_id) = mysqli_fetch_row($res2);

//echo "$eid, $estimare, $user_id<br/>";

if(is_null($eid))$eid=-1;		
		
if($eid<0) {
	
	//just one day in front
	if($mecidata2==$nextday) { 
		if(!empty($_REQUEST['EstimareScor'])) {
			$sql = "UPDATE user SET Funds=Funds-100000 WHERE id=".$_SESSION['USERID'];
			mysqli_query($GLOBALS['con'], $sql);
			include('estimareScor.php');
		} else {
	?>
		<form action="index.php" method="POST" onSubmit="return estimareScor(this);">
		<input type="Submit" name="EstimareScor" value="Cere estimare!">
		</form>
	<?php
		}
	}
} else {
	if($_SESSION['USERID']==$user_id) {
		echo "<h3>Estimated result: $estimare!</h3>";
	} else {
		?>
		<form action="index.php" method="POST" onSubmit="return estimareScor(this);">
		<input type="Submit" name="EstimareScor" value="Ask for estimation!">
		</form>
		<?php
	}
}
?>