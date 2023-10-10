<?php 
include('app.conf.php');
include('player.php');
include('UserStadium.php');
include('trainer.php');

if(isset($_REQUEST['option'])) {
	if($_REQUEST['option'] == "logoff") {
		$_SESSION['USERID'] = 0;
	}
	if($_REQUEST['option'] == "messages") {
		if(isset($_REQUEST['accept'])) {
			//s-a primit accept pt un amical
			$sql = "UPDATE invitatiemeci SET accepted=1 WHERE id=".$_REQUEST['accept'];
			mysql_query($sql);
			//cind se seteaza cimpul accepted pe 1 (s-a dat ok la invitatia de amical)
			//se trece aceasta inregistrare in tabela 'etapa', cu numar=0, sezon=0 si idcompetitie=-5000 (amical)
			//default in bd, aceste cimpuri au aceste valori - setat pt amical default
			$sql = "INSERT INTO etapa(iduser1, iduser2, data, idstadium)
					SELECT userId_1, userId_2, datameci, stadium FROM invitatiemeci
					WHERE id=".$_REQUEST['accept'];
			echo "$sql";
			mysql_query($sql);
		}
		if(isset($_REQUEST['deny'])) {
			//s-a primit accept pt un amical
			$sql = "UPDATE invitatiemeci SET accepted=0 WHERE id=".$_REQUEST['deny'];
			mysql_query($sql);
		}
	}
}

if(isset($_REQUEST['SetAmical'])) {
	//se baga in tabela 'meci' invitatia de meci, cu cimpul accepted=-1 (default)
	//se baga in tabela 'messages' invitatia
	//daca apasa pe 'accept', se seteaza in cimpul din tabela meci accepted=1
	//daca se apasa pe 'deny', se seteaza cimpul accepted = 0 (ca sa se stie ca s-a apasat pe buton, sa nu mai poti da inapoi)
	//in tablea 'messgaes', in cazul in care exista o invitatie la meci, se pune id-ul meciului pe cimpul meciID
	//daca mesajul este fara invitatie la meci, cimpul acesta ramine necompletat
	$data_curenta = Date("Y-m-d");
	$data = date("Y-m-d",strtotime($_REQUEST['txtDate1']));

	$sql = "INSERT INTO invitatiemeci (UserId_1, UserId_2, tipMeci, datameci, grupaVirsta, stadium)
			VALUES(".$_SESSION['USERID'].", ".$_REQUEST['club_id'].", 10, '".$data."', 1, ".$_REQUEST['stadion'].")";
	
	$res = mysql_query($sql);
	$meci_id = mysql_insert_id();
	

	$sql = "SELECT name FROM stadium WHERE id=".$_REQUEST['stadion'];
	$res = mysql_query($sql);
	list($nume_stadion) = mysql_fetch_row($res);
	mysql_free_result($res);

	$sql = "SELECT TeamName FROM user WHERE id=".$_SESSION['USERID'];
	$res = mysql_query($sql);
	list($nume_adversar) = mysql_fetch_row($res);
	mysql_free_result($res);

	$subject = "Invitatie amical cu $nume_adversar";
	$body = "Clubul <b>$nume_adversar</b> te provoaca la un amical, pe data de $data, pe stadionul $nume_stadion. Pentru a accepta acest meci, apasa mai jos!";

	$sql = "INSERT INTO messages (fromID, toID, subject, body, data, meciID)
			VALUES (".$_SESSION['USERID'].", ".$_REQUEST['club_id']. ", '$subject', '$body', '$data_curenta', $meci_id)";
	mysql_query($sql);
}

if(isset($_REQUEST['Login'])) {
	//dupa login normal
	$username = $_REQUEST['userName'];
	$password = $_REQUEST['password'];

	$user = new User();
	$user->Login($username, $password);


//	$user->EchoClub();

//	$user->ComputeTeamValues();

//	$user->EchoTeam();

}
if(isset($_REQUEST['userid'])) {
	//dupa registration_step1
	//vine id-ul inregistrarii prin GET, dupa home.php?id=...
	$user = new User();
	$user->LoginID($_REQUEST['userid']);
	
	$user->EchoClub();
	$user->EchoTeam();

}
include('app.head.php'); 
?>

	<div id="content">
		<div id="content-left">

			<div class="container-1">
<?php
if(isset($_REQUEST['option'])) {
	if($_REQUEST['option'] == 'tactics')
		include('titulari.php');
	if($_REQUEST['option'] == 'club')
		include('club.php');
	if($_REQUEST['option'] == 'search')
		include('searchteam.php');
	if($_REQUEST['option'] == 'meciuri')
		include('meciuri.php');
	if($_REQUEST['option'] == 'viewclub')
		include('viewclub.php');
	if($_REQUEST['option'] == 'amical')
		include('setamical.php');
	if($_REQUEST['option'] == 'messages')
		include('messages.php');
	if($_REQUEST['option'] == 'mecionline') {
		$_SESSION['meciId'] = $_REQUEST['meciID'];
		include('mecionline.php');
	}
} else {
	 include('news.php');
}
?>
<div class="clear"></div>
			</div>

			<h1>Vocea suporterului</h1>
			<div class="clear"></div>
			<div class="container-3">
				<h3>Opinii...</h3>
				<div class="containet-3-text">
					<h5>02. Apr. 2008</h5>
					<h2>Esti suporter?</h2>
					Aici este locul tau! Ai ceva de spus? Nu ezita, fa-ti cunoscuta opinia suporterilor stegari. Tot ce ai de spus despre Steagu isi gaseste locul pe site-ul Steagu.ro!
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
