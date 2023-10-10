<h2>Management</h2>
<br/><br/>
<?php
include('management.head.php');

$sql = "SELECT reason, amount
		FROM account
		WHERE season=$_SEASON AND userid=".$_SESSION['USERID'];
$res = mysqli_query($GLOBALS['con'],$sql);
$sum = array();
while(list($motiv, $suma) = mysqli_fetch_row($res)) {
	switch($motiv) {
		case 'Antrenor': $sum['A'] += $suma; break;
		case 'Jucator': $sum['P'] += $suma; break;
		case 'Bilete': $sum['B'] += $suma; break;
		case 'Sponsori': $sum['S'] += $suma; break;
	}
}
mysqli_free_result($res);
?>
<br/>
<table class="tftable"  width="800" cellpadding="1">
<tr>
	<th>Weekly Outcome/Income</th>
</tr>
<tr>
	<td>
	<?php
	$sql = "SELECT SUM(a.Wage) 
			FROM player a
			LEFT OUTER JOIN userplayer b
			ON a.id=b.PlayerID
			WHERE b.UserID = ". $_SESSION['USERID'];
	$res = mysqli_query($GLOBALS['con'],$sql);
	list($salarii) = mysqli_fetch_row($res);
	mysqli_free_result($res);
	echo "Players wage: ".number_format($sum['P'])." &euro;".'(Actual: '.number_format($salarii)." &euro;/week)";
	?>
	</td>
</tr>
<tr>
	<td>
	<?php
	$sql = "SELECT a.Wage 
			FROM trainer a
			LEFT OUTER JOIN usertrainer b
			ON a.id=b.TrainerID
			WHERE b.UserID = ". $_SESSION['USERID'];
	$res = mysqli_query($GLOBALS['con'],$sql);
	list($salarii) = mysqli_fetch_row($res);
	mysqli_free_result($res);
	
	
	echo "Trainers wage: ".number_format($sum['A'])." &euro;".'(Actual: '.number_format($salarii)." &euro;/week)";
	?>
	</td>
</tr>
<tr>
	<td>
	Construction: 0 &euro;
	</td>
<tr>
	<td>
	Pitch care: 0 &euro;
	</td>
</tr>
<tr>
	<td>
	<?php echo "From tickets: ".number_format($sum['B'])." &euro;"; ?>
	</td>
</tr>
<tr>
	<td>
	<?php echo "Sponsorship: ".number_format($sum['S'])." &euro;"; ?>
	</td>
</tr>
<tr>
	<th>
	Available funds:
	<?php
	$fonduri = $user->Fonduri();
	echo number_format($fonduri)." &euro;";
	?>
	</th>
</tr>

</table>