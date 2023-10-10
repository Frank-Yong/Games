<?php
$state1 = "";
$state2 = "display:none";
$state3 = "display:none";
$state4 = "display:none";
if (isset($_REQUEST['poss'])) {
	switch ($_REQUEST['poss']) {
		case 1:
			$state1 = "";
			$state2 = "display:none";
			$state3 = "display:none";
			$state4 = "display:none";
			break;
		case 2:
			$state1 = "display:none";
			$state2 = "";
			$state3 = "display:none";
			$state4 = "display:none";
			break;
		case 3:
			$state1 = "display:none";
			$state2 = "display:none";
			$state3 = "";
			$state4 = "display:none";
			break;
		case 4:
			$state1 = "display:none";
			$state2 = "display:none";
			$state3 = "display:none";
			$state4 = "";
			break;
	}		
}
?>
<style>
  #wrapper {
	height: 265px;
	width: 222px;
	overflow: auto;
  }
</style>
				<div class="container-8">
<div id="wrapper">
					<div id="container_1_3" class="container-8-text" style="<?php echo $state1; ?>">
						<h6>Portari</h6>
						<?php $user->EchoCompartiment(1); ?>
					</div>
					<div id="container_2_3" class="container-8-text" style="<?php echo $state2; ?>">
						<h6>Fundasi</h6>
						<?php $user->EchoCompartiment(2); ?>
					</div>
					<div id="container_3_3" class="container-8-text" style="<?php echo $state3; ?>">
						<h6>Mijlocasi</h6>
						<?php $user->EchoCompartiment(3); ?>
					</div>
					<div id="container_4_3" class="container-8-text" style="<?php echo $state4; ?>">
						<h6>Atacanti</h6>
						<?php $user->EchoCompartiment(4); ?>
					</div>
</div>
					<div class="container-8-menu">
						<a href="index.php?option=club&poss=1#" id="tab_1_3" onclick="show_team(1,4,3)">Portari</a>
						<a href="index.php?option=club&poss=2#" id="tab_2_3" onclick="show_team(2,4,3)">Fundasi</a>
						<a href="index.php?option=club&poss=3#" id="tab_3_3" onclick="show_team(3,4,3)">Mijlocasi</a>
						<a href="index.php?option=club&poss=4#" id="tab_4_3" onclick="show_team(4,4,3)">Atacanti</a>
						<div class="clear"></div>
					</div>
				</div>
