<table class="tftable">
<tr>
	<th><h1>Get a free player!</h1></th>
</tr>
<tr>
	<td>
	<img src="images/premiu100.jpg" class="img-1" align="left" width="70"><h1>Castiga un jucator!</h1>Invita un prieten sa se alature jocului si castigi un nou jucator in echipa! Dupa inscrierea prietenului, trimite-ne pe <A href="mailto:contact@CupaLigii.ro" class="link-2">contact [at] CupaLigii.ro</a> adresa lui de mail, impreuna cu numele echipei tale! 
	<br/><br/>
	More friends in the game, more free players!
	</td>
</tr>
</table>
<br/>
<?php

$sql = "SELECT title, text, data FROM news ORDER BY data DESC, id DESC";
$res = mysqli_query($GLOBALS['con'], $sql);

while(list($titlu, $text, $data) = mysqli_fetch_row($res)) {
?>
			<div class="container-3d">
				<h3><?php echo $titlu; ?></h3>
				<div class="container-3d-text">
					<?php
					echo "<h4>$text ($data)</h4>";
					?>
				</div>
			</div>
			<br/>
			<div class="clear"></div>
			<br/>
			<?php 
} 
mysqli_free_result($res);

?>

