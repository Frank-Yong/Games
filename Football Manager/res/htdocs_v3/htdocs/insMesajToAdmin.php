<?php
include('app.conf.php');
include('player.php');
include('UserStadium.php');
include('trainer.php');

if(!empty($_REQUEST['TrimiteMesajAdmin'])) {
	//mesajtoadmin
	//fromid
		$subject = "Mesaj de la userul ".$_SESSION['USERID'];
		$body = $_REQUEST['mesajtoadmin'];
		$data_curenta = Date("Y-m-d H:i:s");
		$meci_id=0;
		$sql = "INSERT INTO messages (fromID, toID, subject, body, data, meciID)
				VALUES (".$_SESSION['USERID'].", 0, '$subject', '$body', '$data_curenta', $meci_id)";
		mysql_query($sql);
		
		$mes = $body;
		$mes = wordwrap($mes, 70, "\r\n");
		mail('fcbrasov@yahoo.com', $subject, $mes);

		header("Location: index.php");
		exit;
}
?>