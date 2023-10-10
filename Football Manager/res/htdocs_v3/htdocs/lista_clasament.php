<?php
include('app.conf.php');
include('player.php');
include('UserStadium.php');
include('cls.php');
include('app.head.php');

$be1=0;
$be2=0;
$be3=0;
$be4=0;


if(isset($_REQUEST['dst'])) {
	$date = date('Y-m-d'). " 00:00:00";

	$sql = "SELECT rating 
			FROM user
			WHERE id=".$_SESSION['USERID'];
	$res = mysql_query($sql);
	list($rating) = mysql_fetch_row($res);
	mysql_free_result($res);
	
	//se genereaza valoarea la bannere
	$vbannerluna = $rating*10;
	$vbannerzi = $rating;
	echo "LLLL: $vbannerzi";

	if(isset($_REQUEST['b1'])) {
		$sql = "SELECT daily
				FROM usersponsor
				WHERE locatie=1 and userid=".$_SESSION['USERID'];
		$res = mysql_query($sql);
		list($zi) = mysql_fetch_row($res);
		mysql_free_result($res);
		
		if($zi <> $date) {
			$sql = "UPDATE usersponsor
				SET daily='$date'
				WHERE locatie=1 and userid=".$_SESSION['USERID'];
			mysql_query($sql);

			$sql = "UPDATE user
				SET Funds=Funds+$vbannerzi
				WHERE id=".$_SESSION['USERID'];
			mysql_query($sql);
		}
	}
	if(isset($_REQUEST['b2'])) {
		$sql = "SELECT daily
				FROM usersponsor
				WHERE locatie=2 and userid=".$_SESSION['USERID'];
		$res = mysql_query($sql);
		list($zi) = mysql_fetch_row($res);
		mysql_free_result($res);
		
		if($zi <> $date) {
			$sql = "UPDATE usersponsor
				SET daily='$date'
				WHERE locatie=2 and userid=".$_SESSION['USERID'];
			mysql_query($sql);

			$sql = "UPDATE user
				SET Funds=Funds+$vbannerzi
				WHERE id=".$_SESSION['USERID'];
			mysql_query($sql);
		}
	}
	if(isset($_REQUEST['b3'])) {
		$sql = "SELECT daily
				FROM usersponsor
				WHERE locatie=3 and userid=".$_SESSION['USERID'];
		$res = mysql_query($sql);
		list($zi) = mysql_fetch_row($res);
		mysql_free_result($res);
		
		if($zi <> $date) {
			$sql = "UPDATE usersponsor
				SET daily='$date'
				WHERE locatie=3 and userid=".$_SESSION['USERID'];
			mysql_query($sql);

			$sql = "UPDATE user
				SET Funds=Funds+$vbannerzi
				WHERE id=".$_SESSION['USERID'];
			mysql_query($sql);
		}
	}
	if(isset($_REQUEST['b4'])) {
		$sql = "SELECT daily
				FROM usersponsor
				WHERE locatie=4 and userid=".$_SESSION['USERID'];
		$res = mysql_query($sql);
		list($zi) = mysql_fetch_row($res);
		mysql_free_result($res);
		
		if($zi <> $date) {
			$sql = "UPDATE usersponsor
				SET daily='$date'
				WHERE locatie=4 and userid=".$_SESSION['USERID'];
			mysql_query($sql);

			$sql = "UPDATE user
				SET Funds=Funds+$vbannerzi
				WHERE id=".$_SESSION['USERID'];
			mysql_query($sql);
		}
	}
	
	
}


if(isset($_REQUEST['st'])) {
	$date = date('Y-m-d H:i:s', strtotime("+1 month"));

	$sql = "SELECT rating 
			FROM user
			WHERE id=".$_SESSION['USERID'];
	$res = mysql_query($sql);
	list($rating) = mysql_fetch_row($res);
	mysql_free_result($res);
	
	//se genereaza valoarea la bannere
	$vbannerluna = $rating*10;
	$vbannerzi = $rating;

	if(isset($_REQUEST['b1'])) {
		$sql = "UPDATE usersponsor
				SET expirare='$date'
				WHERE locatie=1 and userid=".$_SESSION['USERID'];
		mysql_query($sql);

		$sql = "UPDATE user
				SET Funds=Funds+1$vbannerluna
				WHERE id=".$_SESSION['USERID'];
		mysql_query($sql);

	}
	if(isset($_REQUEST['b2'])) {
		$sql = "UPDATE usersponsor
				SET expirare='$date'
				WHERE locatie=2 and userid=".$_SESSION['USERID'];
		mysql_query($sql);

		$sql = "UPDATE user
				SET Funds=Funds+1$vbannerluna
				WHERE id=".$_SESSION['USERID'];
		mysql_query($sql);

	}
	if(isset($_REQUEST['b3'])) {
		$sql = "UPDATE usersponsor
				SET expirare='$date'
				WHERE locatie=3 and userid=".$_SESSION['USERID'];
		mysql_query($sql);

		$sql = "UPDATE user
				SET Funds=Funds+1$vbannerluna
				WHERE id=".$_SESSION['USERID'];
		mysql_query($sql);

	}
	if(isset($_REQUEST['b4'])) {
		$sql = "UPDATE usersponsor
				SET expirare='$date'
				WHERE locatie=4 and userid=".$_SESSION['USERID'];
		mysql_query($sql);

		$sql = "UPDATE user
				SET Funds=Funds+1$vbannerluna
				WHERE id=".$_SESSION['USERID'];
		mysql_query($sql);

	}
}

$sql = "SELECT locatie, expirare
		FROM usersponsor
		WHERE userid=".$_SESSION['USERID'];
$res = mysql_query($sql);
while(list($locatie, $expirare) = mysql_fetch_row($res)) {
	if($expirare>Date("Y-m-d H:i:s")) {
		switch($locatie) {
			case 1: $be1=1;break;
			case 2: $be2=1;break;
			case 3: $be3=1;break;
			case 4: $be4=1;break;
		}
	}
}
mysql_free_result($res);


?>

<div id="content">
	<div id="content-left">

	<div class="container-1">

				<?php 
				$cls = new cls(1);

				$cls->clasament(-1);
				$cls->etape(-1);

				?>
				<div class="clear"></div>
			</div>

			<h1>Vocea suporterului</h1>
			<div class="clear"></div>
			<div class="container-3">
				<h3>Opinii...</h3>
				<div class="containet-3-text">
					<h5>Marius Lacatus</h5>
					<h2>Manager de fotbal</h2>
					Vrei sa-ti conduci propria echipa? Aici poti sa pornesti de la 0 cu una!
					<a href="#" class="link-1">mai multe detalii &raquo;</a>
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
