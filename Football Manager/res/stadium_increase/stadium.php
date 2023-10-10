<?php
include('app.conf.php');
include('player.php');
include('UserStadium.php');
include('app.head.php');

//echo "ID-ul stadionului: ".$_SESSION['STADIUMID'].'<br/>';
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
?>
<div id="content">
	<div id="content-left">
	<br/>
	<h2><?php echo translate('Name').$stad->Name; ?></h2>
	<div class="container-1">
			<table>
			<tr>
				<td>
				<br/><br/>
				<img src="<?php echo $img; ?>" height="230" class="img-1">
				</td>
				<td>
				<?php
				$stad->ViewStadium();
				?>
				</td>
			</tr>
			</table>
			<div class="clear"></div>
			<h2><?php echo translate('Tickets'); ?></h2>
			<form action="index.php" method="post">
			<?php echo translate('Price'); ?>: <input type="text" name="PretB" value="<?php echo $stad->Pret; ?>" class="input-1">&nbsp;&euro;&nbsp;
			<input type="Submit" name="SetBilete" value="<?php echo translate('Set'); ?>" class="button-2">
			</form>
			
			<?php
				$stad->BuildStadium();
			?>
			</div>

			<h1>Utile</h1>
			<div class="clear"></div>
			<div class="container-3">
				<h3>Sfaturi zilnice</h3>
				<div class="containet-3-text">
					<h5>CupaLigii.ro</h5>
					<h2>Ponturi</h2>
					<br/><br/>
					Pentru un mijlocas, calitatiile care conteaza cel mai mult sunt: creativitatea, jocul de pase, lansarile si suturile de la distanta. Aceste calitati se imbunatatesc prin antrenamente.<br/> 
					<a href="#" class="link-1">CupaLigii.ro &raquo;</a>
				</div>
			</div>
			<div class="container-3 right">
				<h3>Opinii...</h3>
				<div class="containet-3-text">
					<?php include('opinii.php'); ?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div id="content-right">
			<?php include('right.php'); ?>
                        
		</div>
		<div class="clear"></div>

<?php include('app.foot.php'); ?>