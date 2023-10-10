<?php 
include('app.conf.php'); 
include('player.php');
include('UserStadium.php');
include('trainer.php');

include('app.head.php'); 

if (isset($_REQUEST['SetPrice'])) {
	//verificare daca exista pariere pe el
	//daca exista, nu mai este posibil sa se faca nici o modificare pe jucator
	$p = new Player($_SESSION['USERID'], $_REQUEST['playerid']);
	$pariereinceputa = 0;
	
	if($p->Transfer == 1 && $p->TransferDeadline != '0000-00-00 00:00:00') {
		$pariereinceputa = 1;
	}
	if($pariereinceputa == 0) {
		//verifica daca jucatorul ii apartine userului
		//totodata, daca numarul de saptamini de la club este mai mare de 10
		//nu poate vinde un jucator daca nu a trecut o perioada importanta
		$uid = 0;
		$sql = "SELECT UserID, weeks FROM userplayer WHERE playerID=".$_REQUEST['playerid']." AND UserID=".$_SESSION['USERID'];
		//echo "$sql<br/>";
		$res = mysqli_query($GLOBALS['con'],$sql);
		list($uid, $sapt) = mysqli_fetch_row($res);
		mysqli_free_result($res);
		if($uid == 0) $_SESSION['_MESSAGE'] = 'Player is not part of your team!';
		else {
			if($sapt<8) $_SESSION['_MESSAGE'] = 'You cannot sell him (not before 7 weeks at the club!)';
			else {
				if ($_REQUEST['transfer']==1) {
					$sql = "UPDATE player SET Transfer=1, TransferSuma=".$_REQUEST['pret']." WHERE id=".$_REQUEST['playerid'];
					mysqli_query($GLOBALS['con'],$sql);
				} else {
					$sql = "UPDATE player SET Transfer=0, TransferSuma=0, TransferDeadline='0000-00-00 00:00:00' WHERE id=".$_REQUEST['playerid'];
					mysqli_query($GLOBALS['con'],$sql);	
				}
			}
		}
	}
}



?>
	<div id="content">
		<div id="content-left">

			<div class="container-1">
<?php
			if(!empty($_SESSION['_MESSAGE'])) {
?>
			<div class="container-3d">
				<h3>Informare</h3>
				<div class="container-3d-text">
					<?php
					echo "<h2>".$_SESSION['_MESSAGE']."</h2>";
					$_SESSION['_MESSAGE'] = '';
					?>
				</div>
			</div>
			<div class="clear"></div>
			<?php 
} 
			
include('player_detail.php'); 
?>
<div class="clear"></div>
			</div>

			<h1>myFM.com</h1>
			<div class="clear"></div>
			<div class="container-3">
				<h3>Tips...</h3>
				<div class="containet-3-text">
					<?php include('tips.php'); ?>
					<!--
					<a href="#" class="link-1">mai multe detalii &raquo;</a>
					-->
				</div>
			</div>
			<div class="container-3 right">
				<h3>News...</h3>
				<div class="containet-3-text">
					<?php 
					//include('.php'); 
					?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		<div id="content-right">
			<?php include('right.php'); ?>
                        
		</div>
		<div class="clear"></div>
		<?php
include('app.foot.php'); 
?>
