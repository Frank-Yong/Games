<?php
//1,2,3,8,9,10 - big billboard
//4,5,6,7 - small billboard
//big billboard - width="254" height="133"
//small billboard - width="125" height="125"
//------------------------
//   1       2       3   |
//   4               6   |
//   5               7   |
//   8       9      10   |  
//------------------------

include('app.conf.php');
include('player.php');
include('UserStadium.php');
include('app.head.php');

$be1=0;
$be2=0;
$be3=0;
$be4=0;

if(!empty($_REQUEST['Bonus'])) {
	$date = date('Y-m-d'). " 00:00:00";


	//verific daca exista banner acolo
	$pretbanner=0;
	$sql = "SELECT pret FROM sponsoriuser WHERE userid=".$_SESSION['USERID']." AND pozitie=".$_REQUEST['pozitie']. " AND activ=1";
	//echo "$sql<br/>";
	$res = mysqli_query($GLOBALS['con'],$sql);
	list($pretbanner) = mysqli_fetch_row($res);
	mysqli_free_result($res);

	//se genereaza valoarea la bannere
	$vbannerzi = $pretbanner/173.5;
	
	
	if($pretbanner<>0) {
		//verifica daca are click in ziua curenta
		$cite=0;
		$sql = "SELECT id FROM sponsoriclick WHERE userid=".$_SESSION['USERID']." AND pozitie=".$_REQUEST['pozitie']." AND data='".Date("Y-m-d")."'";
		//echo "$sql<br/>";
		$res = mysqli_query($GLOBALS['con'],$sql);
		list($cite) = mysqli_fetch_row($res);
		mysqli_free_result($res);
		if($cite==0) {
			//daca nu s-a dat click in ziua respectiva
			$sql = "INSERT INTO sponsoriclick(userid, pozitie, data)
					VALUES(".$_SESSION['USERID'].",".$_REQUEST['pozitie'].",'".Date("Y-m-d")."')";
		//echo "$sql<br/>";
			$res = mysqli_query($GLOBALS['con'],$sql);
			$sql = "UPDATE user
				SET Funds=Funds+$vbannerzi
				WHERE id=".$_SESSION['USERID'];
			mysqli_query($GLOBALS['con'],$sql);
		}
	}
}


//nou
if (!empty($_REQUEST['Cere'])) {
	$ziuaurm = Date("Y-m-d", strtotime("+1 days"));
	$sql = "INSERT INTO requests (userid, data, categorie, detaliu)
			VALUES (".$_SESSION['USERID'].", '$ziuaurm', 'sponsori', '".$_REQUEST['pozitie']."')";
	
	mysqli_query($GLOBALS['con'],$sql);
}

//verific daca exista cerere pentru sponsori
$sql = "SELECT c.detaliu 
		FROM requests c
		WHERE c.categorie = 'sponsori' and c.procesat=0 AND c.userid=".$_SESSION['USERID'];
$res = mysqli_query($GLOBALS['con'],$sql);
while(list($r_detaliu) = mysqli_fetch_row($res)) {
	$sp_det[$r_detaliu] = 1;
}
mysqli_free_result($res);

//verific la intrare daca exista sponsori
$sql = "SELECT a.sponsorid, a.pozitie, b.nume, b.poza
		FROM sponsoriuser a
		LEFT OUTER JOIN sponsori b
		ON a.pozitie = b.id
		WHERE a.activ=1 AND a.userid=".$_SESSION['USERID'];
$res = mysqli_query($GLOBALS['con'],$sql);
//echo "$sql<br/>";
while(list($s_sponsorid, $s_pozitie, $s_nume, $s_poza) = mysqli_fetch_row($res)) {
	$sp_[$s_pozitie] = $s_sponsorid;
	$sp_p[$s_pozitie] = $s_poza;
}

mysqli_free_result($res);

//functie pentru afisarea sponsorului. in caz ca nu exista, sa afiseze cererea
function checksponsor($pos, &$sp_, &$sp_p, &$sp_det) {
		//echo "$pos<br/>";
		if($sp_p[$pos]=="") {
			if($sp_det[$pos] == 1) {
				//daca exista cerere pentru sponsori
				echo "<i>Wait for the offer</i>";
			} else {
				echo "<a href=\"sponsoriclub.php?Cere=1&pozitie=$pos\">Get offer</a>";
			}
		} else {
			echo "<img src=\"$sp_p[$pos]\"><br/><a href=\"sponsoriclub.php?pozitie=$pos&Bonus=1\">Get daily bonus</a>";
		}
}

?>

<div id="content">
	<div id="content-left">

	<div class="container-1">

	<!--
	sponsorii orizontali: 254x133
	sponsorii verticali: 125x125
	-->
<?php
if(!empty($_SESSION['USERID'])) {
?>	
<table>
<tr>
	<td colspan="3">
	<table>
	<tr>
		<td width="254" align="center">
		<?php
			checksponsor(1, $sp_, $sp_p, $sp_det);
		?>

		</td>
		<td width="254" align="center">
		<?php
			checksponsor(2, $sp_, $sp_p, $sp_det);
		?>
		<!--
		<script type="text/javascript" src="//profitshare.ro/j/Rv0e"></script>
		-->
		</td>
		<td width="254" align="center">
		<?php
			checksponsor(3, $sp_, $sp_p, $sp_det);
		?>
		<!--
		<script type="text/javascript" src="//profitshare.ro/j/Rv0e"></script>
		-->
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td>
	<table>
	<tr>
		<td valign="center">
		<!--
		<img src="images/125x125-1.jpg" width="125" height="125">
		-->
		<?php checksponsor(4, $sp_, $sp_p, $sp_det); ?>

		</td>
	</tr>
	<tr>
		<td valign="center">
		<?php checksponsor(5, $sp_, $sp_p, $sp_det); ?>
		<!--
		<img src="images/125x125-2.jpg" width="125" height="125">
		-->
		</td>
	</tr>
	</table>
	</td>
	<td><img src="images/teren.jpg" width="470" height="280"></td>
	<td>
		<table>
	<tr>
		<td valign="center">
		<?php checksponsor(6, $sp_, $sp_p, $sp_det); ?> 
		<!--
		<img src="images/125x125-3.jpg" width="125" height="125">
		-->
		</td>
	</tr>
	<tr>
		<td>
		<?php checksponsor(7, $sp_, $sp_p, $sp_det); ?> 
		<!-- <img src="images/125x125-4.jpg" width="125" height="125"> -->
		</td>
	</tr>
	</table>

	</td>
</tr>
<tr>
	<td colspan="3">
	<table>
	<tr>
		<td width="254" align="center">
		<?php checksponsor(8, $sp_, $sp_p, $sp_det); ?> 
		<!--<img src="images/blog-hover.jpg" width="254" height="133">-->
		</td>
		<td width="254" align="center">
		<?php checksponsor(9, $sp_, $sp_p, $sp_det); ?> 
		<!--<img src="images/blog-hover.jpg" width="254" height="133">-->
		</td>
		<td width="254" align="center">
		<?php checksponsor(10, $sp_, $sp_p, $sp_det); ?> 
		<!--<img src="images/blog-hover.jpg" width="254" height="133">-->
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>	
<?php 
} else {
		include('news.php');
}
?>
	

				<div class="clear"></div>
			</div>

			<h1>myfm.com</h1>
			<div class="clear"></div>
			<div class="container-3">
				<h3>Tips</h3>
				<div class="containet-3-text">
				<?php include('tips.php'); ?>
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
