<?php
error_reporting(63);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

include('admin.head.php');

if(!empty($_REQUEST['Trimite'])) {
	$sql = "SELECT username, teamname, email, botteam, stadiumid FROM user WHERE id=".$_REQUEST['echipa'];
	$res = mysqli_query($GLOBALS['con'],$sql);
	list($username, $teamname, $emailadv, $estebot, $stadionadv) = mysqli_fetch_row($res);
	mysqli_free_result($res);

	$headers = "From: my@domain.com\r\n";
	$headers .= "Reply-To: contact@domain.com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
	$subject = "myFM.com - new about the game!";
	$body = $_REQUEST['mesaj'];

	$sql = "INSERT INTO messages (fromID, toID, subject, body, data, meciID)
			VALUES (".$_SESSION['USERID'].", ".$_REQUEST['club_id']. ", '$subject', '$body', '$data_curenta', $meci_id)";
	mysqli_query($GLOBALS['con'],$sql);

	$mes = $_REQUEST['mesaj'];
	$mes = "<img src=\"http://localhost/images/mecionline.jpg\" align=\"left\" width=\"250\">Hi $username!<br/><br/>".$mes;
	$mes = wordwrap($mes, 70, "\r\n");
	if($estebot) {
	} else {
		mail($emailadv, $subject, $mes, $headers);
		mail('fcbrasov@yahoo.com', $subject, $mes, $headers);
	}
}
?>
<form action="MailTo.php" method="POST">
	<br/>
	<select name="echipa" size="12">
	<?php
		$sql = "SELECT a.id, a.TeamName, a.LastActive, a.LeagueID, b.name, a.email
				FROM user a
				LEFT JOIN competition b
				on a.LeagueID=b.id
				WHERE a.activated=1
				ORDER BY a.LastActive DESC";
		$res = mysqli_query($GLOBALS['con'],$sql);
		while(list($id, $echipa,$activ,$ligaid,$liganume, $email) = mysqli_fetch_row($res)) {
			echo "<option value=\"$id\">$echipa($activ) - $liganume -- $email";
		}
		mysqli_free_result($res);
	?>
	</select>
<br/><br/>
<textarea name="mesaj" rows="5" cols="20"></textarea>
<br/>	<br/>
<input type="Submit" name="Trimite" value="Send...">
	
</form>