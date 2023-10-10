<script>
function showComment()
{
	if (document.getElementById("comentariu").style.display == "")
		document.getElementById("comentariu").style.display = "none";
	else 
		document.getElementById("comentariu").style.display = "";
}
</script>
<div class="container-1">
<?php
//vine $player sub forma unui nume
$_CHECK_LOGIN=0;

if (isset($_REQUEST['id'])) {

	//asta vine de la cautare

//	include('echipa_meniu.php');

	//afisare jucator
	if($pariereinceputa == 1) {
		?>
		<table class="tftable">
		<tr>
		<th>
		La acest jucator, parierea a inceput! Nu poate fi scos de pe lista de transfer!
		</th>
		</tr>
		</table>
		<?php
	}
	$user->EchoPlayerSolo($_REQUEST['id']);
	?>
	<br/>
	<h3><a onclick="showComment();" href="javascript:;" class="link-3">Can be transfered - Press for details</a></h3>
	<div class="1" id="comentariu" style="display:none">
		<form action="" method="post">
		<input type="hidden" name="playerid" value="<?php echo $_REQUEST['id']; ?>">
		<table class="tftable">
		<tr>
		<th>
		Put him on the transfer list:
		</th>
		<th>
		Price
		</th>
		</tr>
		<tr>
		<td>
		<input type="radio" name="transfer" value="1">YES
		<input type="radio" name="transfer" value="0">NO
		</td>
		<td>
		<input type="text" name="pret" size="4" class="input-1"/> &euro;
		</td>
		</tr>
		<tr>
		<td colspan="2" align="right">
		<input type="Submit" name="SetPrice" value="Set transfer status" class="button-2"/>
		</td>
		</tr>
		</table>
		</form>
	</div>
	<?php	
} else {
	//aici se intra fara id
	echo "<h1>".$user->TeamName()."";
	$v1 = $user->ShowMoral();
	$v2 = 100 - $v1;
	echo "&nbsp;&nbsp;&nbsp;<img width=\"21\" src=\"pie.php?n1=$v1&n2=$v2\"></h1>";
	echo "<div class=\"container-3\">";
	if($user->Imagine() != "") $poza = $user->Imagine();
	else $poza =  "images/manager.jpg";
	
	$stad = new Stadium($_SESSION['STADIUMID']);
	$nrl = $stad->AvailableSeats();
//$nrl = 25000;

	switch($nrl) {
		case ($nrl<3000): $img = "images/stadion-1.jpg";break;
		case ($nrl>=3000 && $nrl<6000): $img = "images/stadion-2.jpg";break;
		case ($nrl>=6000 && $nrl<10000): $img = "images/stadion-3.jpg";break;
		case ($nrl>=10000 && $nrl<15000): $img = "images/stadion-4.jpg";break;
		case ($nrl>=15000 && $nrl<25000): $img = "images/stadion-5.jpg";break;
		case ($nrl>=25000 && $nrl<35000): $img = "images/stadion-6.jpg";break;
		case ($nrl>=35000 && $nrl<50000): $img = "images/stadion-7.jpg";break;
		case ($nrl>=50000 && $nrl<60000): $img = "images/stadion-8.jpg";break;
		case ($nrl>=60000 && $nrl<80000): $img = "images/stadion-9.jpg";break;
		case ($nrl>=80000): $img = "images/stadion-10.jpg";break;
	}

	
	echo "<img src=\"suprapuse.php?id=".$_SESSION['USERID']."&img=$poza&imgstad=$img\" width=\"310\" class=\"img-1\">";

	$user->EchoClub();
?>

						<table>
							<tr>
							<TD>
							<a href= "index.php?option=meciuri"><img src="images/meciuri.png" width="70"></a>
							</TD>
							<td>
							<a href= "index.php?option=club&players=1"><img src="images/jucatori.png" width="90"></a>
							</td>
							<td>
							<a href= "sponsoriclub.php"><img src="images/sponsori.png" width="80"></a> 
							</td>
							</tr>
							<tr>
							<td align="center"><?php echo translate('Games'); ?></td>
							<td align="center"><?php echo translate('Players'); ?></td>
							<td align="center"><?php echo translate('Sponsors'); ?></td>
							</tr>
							<tr>
							<td>
							<a href= "index.php?option=club&antrenor=1"><img src="images/trainer.png" width="80"></a> 
							</td>
							<td>
							<a href= "index.php?option=t-shirt"><img src="images/number.png" width="80"></a> 
							</td>
							<td>
							<a href= "index.php?option=transferuri"><img src="images/transferuri.png" width="80"></a> 
							</td>
							<td></td>
							</tr>
							<tr>
							<td align="center"><?php echo translate('Training'); ?></td>
							<td align="center"><?php echo translate('T-shirt no.'); ?></td>
							<td align="center"><?php echo translate('Transfers'); ?></td>
							<td></td>
							</tr>
							<tr>
							<td>
							<a href= "stadium.php"><img src="images/stadion.jpg" width="80"></a> 
							</td>
							<td>
							<a href= "index.php?option=facilitati"><img src="images/facilitati.png" width="70"></a> 
							</td>
							<td><a href= "index.php?option=competitii"><img src="images/competitii.png" width="70"></a> 
							</td>
							</tr>
							<tr>
							<td align="center"><?php echo translate('Stadium'); ?></td>
							
							<td align="center"><?php echo translate('Facilities'); ?></td>
							<td align="center"><?php echo translate('Competitions'); ?></td>
							</tr>
							</table>
<br/>
<script type="text/javascript" src="//profitshare.ro/j/aFNh"></script>							
<script>							
$(function () {

    $(".div2").hide();
    
    $(".link1, .link2").bind("click", function () {

      $(".div1, .div2").hide();        
        
      if ($(this).attr("class") == "link1")
      {
        $(".div1").show();
      }
      else 
      { 
        $(".div2").show();
      }
    });

});
</script>							
<?php

	echo "</div><div class=\"container-3-text\">";
	//include('echipa_meniu.php');
	
	echo "<A href=\"#\" class=\"link1\"><img src=\"images/seniori.png\" width=\"30\" title=\"Seniori\"></a> <A href=\"#\" class=\"link2\" title=\"Juniori\"><img src=\"images/juniori.png\" width=\"30\"></a><br/>";
	echo "<div class=\"div1\">";
	$user->EchoTeamNew(0);
	echo "</div>";
	echo "<div class=\"div2\">";
	$user->EchoTeamNew(1);
	echo "</div>";
	echo "</div>";


}

?>

</div>
