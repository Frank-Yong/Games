<?php 
error_reporting(0);
include('app.conf.php');
include('UserStadium.php');


include('app.head.php'); 
?>

	<div id="content">
		<div id="content-left">

			<div class="container-1">

			<h1>myFM.com</h1>
			<div class="clear"></div>
			<div class="container-3d">
				<h3>Mesaj</h3>
				<div class="container-3d-text">
					<?php
					echo "<h2>Start the coding... Loading my Football Manager!</h2>";
					$_SESSION['_MESSAGE'] = '';
					?>
				</div>
			</div>

			</div>

			<div class="clear"></div>
			
			<h1>myFM.com</h1>
			<div class="clear"></div>
			<div class="container-3">
				<h3>Tips...</h3>
				<div class="containet-3-text">
					<?php include('ponturi.php'); ?>
					<!--
					<a href="#" class="link-1">mai multe detalii &raquo;</a>
					-->
				</div>
			</div>
			<div class="container-3 right">
				<h3>Contact</h3>
				<div class="containet-3-text">
					Contact us at cccc@myFM.com .
					<div align="center">
					<script type="text/javascript" src="//profitshare.ro/j/VLJf"></script>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>

		<div id="content-right">
			<?php include('right.php'); ?>
                        
		</div>

		<div class="clear"></div>
<?php include('app.foot.php'); ?>
