<?php
//verifica daca a dat stergere in grup
if(!empty($_REQUEST['StergeGrup'])) {
	$vvv = $_REQUEST['ste'];
	foreach($vvv as $key){
		//echo "$key este selectat<br/>";
		$sql = "DELETE FROM messages WHERE id=$key AND toID=".$_SESSION['USERID'];
		mysqli_query($GLOBALS['con'],$sql);
	} 
}
//verifica daca vrea sa le faca citite pe toate
if(!empty($_REQUEST['ModificaCitite'])) {
	$sql = "UPDATE messages SET citit=1 WHERE citit=0 AND toID=".$_SESSION['USERID'];
	mysqli_query($GLOBALS['con'],$sql);
	 
}
?>

<h2>Messages</h2><br/>
			<div class="container-1">
<table  class="tftable" width="100%" cellpadding="1">
<?php if (isset($_REQUEST['mess_id'])) { ?>
<tr>
	<th></th>
	<th>Status</th>
	<th>Action</th>
	<th align="left">From:</th>
	<th align="left">Message:</th>
</tr>
<?php } else { ?>

					<tr>
						<th></th>
						<th>Status</th>
						<th>Action</th>
						<th align="left"><font color="<?php echo $gk; ?>">&nbsp;From</font></th>
						<th align="left"><font color="<?php echo $df; ?>">&nbsp;Subject</font></th>	
						<th align="left"><font color="<?php echo $gk; ?>">&nbsp;Date</font></th>
					</tr>

<?php
}

$cantitate = 10;
if(empty($_REQUEST['page'])) $start = 0;
else $start = ($_REQUEST['page']-1)*10;

if(!empty($_REQUEST['ReplyMesaj'])) {
	$mesaj = $_REQUEST['raspunde'];

	$sql = "INSERT INTO messages(fromid,toid,subject,body,meciid,sponsor, data)
			VALUES(".$_SESSION['USERID'].",".$_REQUEST['toid'].",'".$_REQUEST['subject']."','".$mesaj."',0,0,'".Date("Y-m-d")."')";
	//echo "$sql<br/>";
	mysqli_query($GLOBALS['con'],$sql);
	echo "<h3>Message has been sent!</h3>";
}

if(isset($_REQUEST['sponsorac'])) {
	//verificare daca este sponsor de tricouri, trebuie sa aiba tricou=1
	$sql = "SELECT tricou, tricousuma, tricoudisponibil FROM messages WHERE id=".$_REQUEST['mesid'];
	$res = mysqli_query($GLOBALS['con'],$sql);
	$tricou = 0;
	list($tricou, $tricousuma, $tricoudisponibil) = mysqli_fetch_row($res);
	
	if($tricou==1) {
		//s-a primit oferta pentru tricou inainte de meci
		//trebuie sa verific ca e inca disponibil, adica n-a trecut meciul
		//in tricoudisponibil se afla ora de incepere a meciului, completata automat de job
		if($tricoudisponbil>Date('Y-m-d 12:00:00')) {
			//oferta expirata, pun pe indisponibil oferta
			$sql = "UPDATE messages SET hideQuestion=1, tricou=0 WHERE id=".$_REQUEST['mesid'];
			mysqli_query($GLOBALS['con'],$sql);
		} else {
			//actualizare suma
			$sql = "UPDATE user SET Funds=Funds+$tricousuma WHERE id=".$_SESSION['USERID'];
			//echo "$sql<br/>";
			mysqli_query($GLOBALS['con'],$sql);
			
			$sql = "INSERT INTO balanta(userid, suma, motiv, sezon)
					VALUES(".$_SESSION['USERID'].", $tricousuma, 'Sponsori', 1)";
			//echo "$sql<br/>";
					mysqli_query($GLOBALS['con'],$sql);		
			
			//ascund intrebarea sa nu mai apara
			$sql = "UPDATE messages SET hideQuestion=1, tricou=0 WHERE id=".$_REQUEST['mesid'];
			mysqli_query($GLOBALS['con'],$sql);

		}
	}
	
	mysqli_free_result($res);
	//final sponsor tricouri

	//prevent "Refresh" and allocation of the money again, check if it is processed already
	
	$sql = "SELECT a.userid, a.procesat, b.pret
			FROM requests a
			LEFT OUTER JOIN sponsoribuffer b
			ON a.spbuffer=b.id
			WHERE a.spbuffer=".$_REQUEST['sponsorac'];
	$res = mysqli_query($GLOBALS['con'],$sql);
	list($sponsor_userid, $sponsor_procesat, $sponsor_suma) = mysqli_fetch_row($res);
	mysqli_free_result($res);
	if($sponsor_procesat == 1) {
		//deja s-a procesat
		//sa nu se mai introduca inca o data in baza de date cind se da refresh
	}
	else {
		//s-a acceptat oferta de sponsorizare
		//in request['sponsorac'] se afla id-ul de la sponsorbuffer
		$sql = "INSERT INTO sponsoriuser(sponsorid, userid, pozitie, perioada, pret, sezon, activ, spbuffer)
				SELECT sponsorid, userid, pozitie, perioada, pret, sezon, 1, id
				FROM sponsoribuffer
				WHERE id=".$_REQUEST['sponsorac'];
		$res = mysqli_query($GLOBALS['con'],$sql);
		//echo "$sql<br/>";
		
		//trecere in requests ca fiind procesat
		$sql = "UPDATE requests SET procesat=1 WHERE procesat=0 AND spbuffer=".$_REQUEST['sponsorac'];
		//echo "$sql<br/>";
		mysqli_query($GLOBALS['con'],$sql);
		
		//actualizare suma
		$sql = "UPDATE user SET Funds=Funds+$sponsor_suma WHERE id=".$_SESSION['USERID'];
		//echo "$sql<br/>";
		mysqli_query($GLOBALS['con'],$sql);
	
		$sql = "INSERT INTO balanta(userid, suma, motiv, sezon)
				VALUES(".$_SESSION['USERID'].", $sponsor_suma, 'Sponsori', 1)";
		mysqli_query($GLOBALS['con'],$sql);		

		//ascund intrebarea sa nu mai apara
		$sql = "UPDATE messages SET hideQuestion=1 WHERE id=".$_REQUEST['mesid'];
		mysqli_query($GLOBALS['con'],$sql);

		
	}
}

if(isset($_REQUEST['sponsorde'])) {
	//de implementat refuzul de sponsor
	$sql = "UPDATE messages SET hideQuestion=1 WHERE id=".$_REQUEST['mesid'];
	mysqli_query($GLOBALS['con'],$sql);
}

if(isset($_REQUEST['Sterge'])) {
	$sql = "DELETE FROM messages
			WHERE id=".$_REQUEST['stergeid']. " AND toID=".$_SESSION['USERID'];
	mysqli_query($GLOBALS['con'],$sql);
}

if (isset($_REQUEST['mess_id'])) {
	$sql = "SELECT a.id, a.fromID, a.Subject, a.data, b.Username, a.citit, a.body, a.meciID, a.sponsor, a.hideQuestion 
		FROM messages a 
		LEFT OUTER JOIN user b 
		ON a.fromID=b.id 
		WHERE a.toID=".$_SESSION['USERID']." AND a.id=". $_REQUEST['mess_id'];
} else {
	$sql = "SELECT a.id FROM messages a
			LEFT OUTER JOIN user b 
			ON a.fromID=b.id 
			WHERE a.toID=".$_SESSION['USERID'];
	$rescount = mysqli_query($GLOBALS['con'],$sql);
	$numarinreg = mysqli_num_rows($rescount);
	mysqli_free_result($rescount);
	
	$sql = "SELECT a.id, a.fromID, a.Subject, a.data, b.Username, a.citit, a.body, a.meciID, a.sponsor, a.hideQuestion 
		FROM messages a 
		LEFT OUTER JOIN user b 
		ON a.fromID=b.id 
		WHERE a.toID=".$_SESSION['USERID'].
		" ORDER BY a.id DESC LIMIT $start, $cantitate";

}

$res = mysqli_query($GLOBALS['con'],$sql);

if (isset($_REQUEST['mess_id'])) {
	$sql = "UPDATE messages SET citit=1 WHERE id=".$_REQUEST['mess_id'];
	mysqli_query($GLOBALS['con'],$sql);
}
echo "<form action=\"\" method=\"post\">";

$imes = 0;
while(list($messid, $messfromID, $messSubject, $messdata, $dela, $citit, $body, $meciID,$sponsoroffer, $mquestion) = mysqli_fetch_row($res)) {
	$game = $meciID;
	$msubject = $messSubject;
	$sponoffer = $sponsoroffer;
	$mesajid=$messid;
	$hideque = $mquestion;
	$mbody = $body;
	$mfromid=$messfromID;
	if($dela =='') $dela = 'Admin';
	if($citit == 0) {
		$color="color=\"red\"";
	} else {
		$color="color=\"black\"";
	}
	echo "<tr><td><a href=\"index.php?option=messages&Sterge=1&stergeid=$messid\"><img src=\"images/delete.png\" border=\"0\" width=\"25\"></a></td>";
	if($citit == 0) {
		echo "<td><img src=\"images/necitit.png\" border=\"0\" width=\"25\"></td>";
	} else {
		echo "<td><img src=\"images/citit.png\" border=\"0\" width=\"25\"></td>";	
	}
	if($messSubject == "") $messSubject = "(fara subiect)";
	echo "<td align=\"left\"><input type=\"checkbox\" name=\"ste[]\" value=\"$messid\"></td>";
	echo "<td align=\"left\"><font $color>$dela</font></td>";
	echo "<td align=\"left\"><font $color><a href=\"index.php?option=messages&read=1&mess_id=$messid\">$messSubject</a></font></td>";
	if (isset($_REQUEST['mess_id'])) {
		echo "<tr><td colspan=\"5\" align=\"left\">".nl2br($body)."</td></tr><tr>";
		if($messfromID<>0) echo "<td><a href=\"index.php?option=viewclub&club_id=$messfromID\" class=\"button-2\">&nbsp;See club&nbsp;</a></td>";
		else echo "<td></td>";
	}
	echo "<td colspan=\"4\"><font $color>".Date('d.M.Y', strtotime($messdata))." </font></td></tr>";
	$imes++;
	if($imes>30) break;
	//aiciiiii
	
}
if(empty($_REQUEST['mess_id'])) {
	echo "<tr><th colspan=\"5\"><input type=\"Submit\" name=\"StergeGrup\" value=\"Delete selected\" class=\"button-2\"></th><th colspan=\"1\"><input type=\"Submit\" name=\"ModificaCitite\" value=\"Set all to read\" class=\"button-2\"></th></tr>";
}
echo "<tr><th colspan=\"6\">";
echo "</form>";
//partea cu navigarea
$pagini = $numarinreg/$cantitate+1;
for($i=1;$i<=$pagini;$i++) {
	if(empty($_REQUEST['page'])) $curenta = 1;
	else $curenta = $_REQUEST['page'];
	
	if($i==$curenta) echo "<font color=\"green\">$i&nbsp;</font>";
	else echo "<a href=\"index.php?option=messages&page=$i\" class=\"div-33\">$i</a>&nbsp;";
}
echo "</th></tr>";
mysqli_free_result($res);
?>
<?php
if($sponoffer>0) {
		if($hideque == 0) {
		//a primit oferta de la sponsori
		echo "<tr><th colspan=\"5\"><a href=\"index.php?option=messages&sponsorac=$sponoffer&mesid=$mesajid\"><span class=\"button-2\">&nbsp;Accepta&nbsp;</span></a>  &nbsp;&nbsp;  <a href=\"index.php?option=messages&sponsorde=$sponoffer&mesid=$mesajid\"><span class=\"button-2\">&nbsp;Respinge&nbsp;</span></a></th></tr>";
		} else {
			echo "<tr><th colspan=\"5\"><span class=\"button-2\">&nbsp;Already a decision was taken for this!&nbsp;</span></th></tr>";
		
		}
}
echo "</table>";

if($game>0 && isset($_REQUEST['mess_id'])) {
	//inseamna ca e invitatie la meci acel mesaj
	//afisare butoane de accept si deny
	//odata ce a dat pe unul din butoane, nu mai poate reveni
	
	$sql = "SELECT userId_1, userId_2, accepted, datameci FROM invitatiemeci WHERE id=$game";

	$res = mysqli_query($GLOBALS['con'],$sql);
	list($usr1, $usr2, $accepted, $datameci) = mysqli_fetch_row($res);
	
	$sql2 = "SELECT Count(id)
			 FROM invitatiemeci
			 WHERE datameci='$datameci' AND accepted=1 AND (userId_1=$usr1 OR userId_2=$usr1)";
	$res2 = mysqli_query($GLOBALS['con'],$sql2);
	list($gamesSameDay1) = mysqli_fetch_row($res2);
	mysqli_free_result($res2);

	$sql2 = "SELECT Count(id)
		 FROM invitatiemeci
		 WHERE datameci='$datameci' AND accepted=1 AND (userId_1=$usr2 OR userId_2=$usr2)";
	$res2 = mysqli_query($GLOBALS['con'],$sql2);
	list($gamesSameDay2) = mysqli_fetch_row($res2);
	mysqli_free_result($res2);

	
	$sql2 = "SELECT Count(id)
			 FROM invitatiemeci
			 WHERE datameci='$datameci' AND accepted=1 AND (userId_1=".$_SESSION['USERID']." OR userId_2=".$_SESSION['USERID'].")";
	$res2 = mysqli_query($GLOBALS['con'],$sql2);
	list($gamesSameDay) = mysqli_fetch_row($res2);
	mysqli_free_result($res2);


	$sql2 = "SELECT COUNT(id)
			 FROM evenimente
			 WHERE data='$datameci' AND (userid=$usr1 or userid=$usr2)";
	$res2 = mysqli_query($GLOBALS['con'],$sql2);
	list($ev) = mysqli_fetch_row($res2);
	mysqli_free_result($res2);
	
	if($ev>0) {
		//inseamna ca mai are un eveniment stabilit in acceasi zi";
		$accepted=0;
		$eroare = " There is another event today (for you or for the other person)!";
	}

	if($gamesSameDay1>0) {
		//inseamna ca mai are un meci stabilit in acceasi zi";
		$accepted=0;
		$eroare = " There is another game in this day!";
	}
	if($gamesSameDay2>0) {
		//inseamna ca mai are un meci stabilit in acceasi zi";
		$accepted=0;
		$eroare = " There is another game in this day!";
	}

	$datacurenta = DATE("Y-m-d");
	if($datacurenta>$datameci) {
		//a expirat invitatia, nu mai tebuie sa apara butoane
		$accepted = 0;
		$eroare = " Invitation has expired!";
	}

	if($accepted == -1) {
		//inseamna ca nu a luat o decizie asupra meciului
		echo "<br/><a href=\"index.php?option=messages&accept=$game\"><span class=\"button-2\">&nbsp;Accepta&nbsp;</span></a>&nbsp;&nbsp;<a href=\"index.php?option=messages&deny=$game\" class=\"button-2\">&nbsp;Refuza&nbsp;</a><br/>";
	}
	if($accepted == 1) {
		//inseamna ca a acceptat deja meciul
		echo "<br/><span class=\"button-2\">Game set already</span><br/>";
	}
	if($accepted == 0) {
		//inseamna ca nu poate accepta meciul
		echo "<br/><span class=\"button-2\">You cannot accept the game! $eroare</span><br/>";
	}
	mysqli_free_result($res);

}
?>
</div>

<?php 	
//afisez reply doar daca mesajul nu vine de la admin
if (isset($_REQUEST['mess_id']) && $mfromid<>0) { 
?>
<h3><a onclick="showComment();" href="javascript:;" class="link-3">Reply - press here</a></h3>
<div class="1" id="comentariu" style="display:none">
	<form action="" method="post">
	<input type="hidden" name="subject" value="<?php echo "Re: ".$msubject; ?>">
	<input type="hidden" name="toid" value="<?php echo $mfromid; ?>">
	<table class="tftable">
	<tr>
	</tr>
	<tr>
	<td>
	Mesaj
	</td>
	<td>
	<textarea name="raspunde" cols="40" rows="5"><?php echo strip_tags('&#10;&#13;&#10;&#13;['.$mbody.']'); ?></textarea>
	</td>
	</tr>
	<tr>
	<td colspan="2" align="right">
	<input type="Submit" name="ReplyMesaj" value="Send" class="button-2"/>
	</td>
	</tr>
	</table>
	</form>
</div>
<a href="##" onClick="history.go(-1); return false;"><span class="button-2">&nbsp;Back&nbsp;</span></a>
<?php } ?>
<br/><br/>