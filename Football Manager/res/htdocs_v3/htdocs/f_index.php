<?php

include('app.conf.php');

include('player.php');
include('UserStadium.php');
include('trainer.php');

if(!empty($_REQUEST['AdaugaTopic'])) {
	$link = mysqli_connect($cfg_host, $cfg_user, $cfg_pass, $cfg_db);
	//nume, descriere, userid, categorieid
	$sql = "INSERT INTO f_topic(categorieid, nume, userid, data)
			VALUES(".$_REQUEST['categorieid'].",'".$_REQUEST['nume']."', ".$_REQUEST['userid'].",'".Date('Y-m-d H:i:s')."')";
	mysqli_query($link,$sql);

	$_tid = mysqli_insert_id($link);
	$sql = "INSERT INTO f_post(topicid, comentariu, userid, data)
			VALUES($_tid, '".$_REQUEST['descriere']."', ".$_REQUEST['userid'].",'".Date('Y-m-d H:i:s')."')";
	//echo "$sql<br/>";
	mysqli_query($link, $sql);
	mysqli_close($link);
}

if(!empty($_REQUEST['AdaugaRaspuns'])) {
	//nume, descriere, userid, categorieid
	$sql = "INSERT INTO f_post(topicid, comentariu, userid, data)
			VALUES(".$_REQUEST['topicid'].", '".$_REQUEST['descriere']."', ".$_REQUEST['userid'].",'".Date('Y-m-d H:i:s')."')";
	//echo "$sql<br/>";
	mysql_query($sql);
}


include('app.head.php'); 
?>

	<div id="content">
		<div id="content-left">

			<div class="container-1">
			<?php 
			if(!empty($_SESSION['USERID'])) {
				if(!empty($_REQUEST['catid'])) {
					include('f_topic.php');
				} elseif(!empty($_REQUEST['topid']))
					include('f_post.php');
				  else
					include('f_categorii.php');
			} else {
				echo "Trebuie sa te loghezi";
			}

			?>
			</div>

			<div class="clear"></div>
			
			<h1>CupaLigii.ro</h1>
			<div class="clear"></div>
			<div class="container-3">
				<h3>Ponturi...</h3>
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
					Ne puteti contacta la adresa de email contact@CupaLigii.ro .
					<div align="center">
					<script type="text/javascript" src="//profitshare.ro/j/VLJf"></script>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
<?php
//if(isset($_REQUEST['option'])) {
//	if($_REQUEST['option'] == 'tactics')
//		{ 
//		} else {
?>
		<div id="content-right">
			<?php include('right.php'); ?>
                        
		</div>
<?php 
//}} 
?>
		<div class="clear"></div>
<?php include('app.foot.php'); ?>
