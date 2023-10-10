<?php
$cantitate = 10;
if(empty($_REQUEST['page'])) $start = 0;
else $start = ($_REQUEST['page']-1)*10;
?>
<h2>Cautare</h2>
<br/>
<table>
<tr>
<TD>
<a href= "index.php?option=searchteam"><img src="images/echipe.png" width="65"></a>
</TD>
<td>
<a href= "index.php?option=searchtrainer"><img src="images/trainer2.png" width="65"></a> 
</td>
<td>
<a href= "index.php?option=searchplayers"><img src="images/jucatori.png" width="90"></a>
</td>
<td>
<a href= "index.php?option=searchbids"><img src="images/licitatii.png" width="80"></a> 
</td>
</tr>
<tr>
<td align="center">Teams</td>
<td align="center">Trainers</td>
<td align="center">Players</td>
<td align="center">Bids</td>
</tr>
</table>

<form action="" method="POST">

<table class="tftable" width="100%" cellpadding="1">
<tr>
	<th colspan="7">Search team</th>
</tr>		
<tr>
	<td colspan="7">Name of the team contains: <input type="text" name="NumeEchipa" class="input-nou" value="<?php echo $_REQUEST['NumeEchipa']; ?>"></th>
</tr>		

<tr>
	<td colspan="7">
	<input type="Submit" name="SearchTeam" value="Display" class="button-2">
	</td>
</tr>

</table>

</form>
<?php

if($_SESSION['query'] == NULL) {
	include('searchteam.php');
}

?>
