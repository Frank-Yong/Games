<?php

if(!empty($_REQUEST['PickTShirt'])) {
	//$_REQUEST['numar']::$_REQUEST['juc']::$_SESSION['USERID']
	//check if the number already exists
	$numar = $_REQUEST['numar'];
	$pid = $_REQUEST['juc'];
	
	$nrnumar = 0;
	$sql = "SELECT number, playerid FROM userplayer WHERE number=$numar AND userid=".$_SESSION['USERID'];
	//echo "$sql<br/>";
	$res = mysql_query($sql);
	$nrnumar = mysql_num_rows($res);
	//echo "Numar de numere : ".$nrnumar.'<br/>';
	if($nrnumar>0 && $numar<>0) {	
		$_SESSION['_MESSAGE'] = 'Numarul deja exista in echipa ta!';
	} else {
		$sql = "UPDATE userplayer SET number=$numar WHERE playerid=$pid AND userid=".$_SESSION['USERID'];
		//echo "$sql<br/>";
		mysql_query($sql);
	}
	
	mysql_free_result($res);
	
}

//inceteaza contractul cu jucatorul
if(!empty($_REQUEST['InceteazaContract'])){
			$piid = $_REQUEST['pid'];
			if($_REQUEST['uid'] != $_SESSION['USERID']) {
				//nu poti concedia jucatorul care nu este al tau
				$_SESSION['MESSAGE'] = 'Actiune imposibila!';
			} else {
				$sql = "SELECT userid FROM userplayer WHERE playerid=$piid";
				$replayer = mysql_query($sql);
				list($userid) = mysql_fetch_row($replayer);
				//fac inca o verificare pentru a preveni 'refresh'-ul ferestrei, care ar lua bani de fiecare data
				if($userid != 0) {
					//pun jucatorul pe lista de transfer
					$sql = "UPDATE player SET transfer=1 WHERE id=$piid";
					mysql_query($sql);
					//echo "$sql<br/>";
					
					$sql = "UPDATE userplayer SET userid=0 WHERE playerid=$piid AND userid=".$_REQUEST['uid'];
					mysql_query($sql);
					//echo "$sql<br/>";
					
					$sql = "UPDATE user SET Funds=Funds-".$_REQUEST['compensatii']." WHERE id=".$_REQUEST['uid'];
					mysql_query($sql);
					//echo "$sql<br/>";
		
					//se scoate din echipa de start jucatorul
					$sql = "DELETE FROM echipastart
						WHERE playerid=$piid";
					mysql_query($sql);			
					//echo "$sql<br/>";
				}
				mysql_free_result($replayer);

			}
}

if(!empty($_REQUEST['CerceteazaTalent'])){
			$piid = $_REQUEST['pid'];
			$uid = $_REQUEST['uid'];
			
			$sql = "SELECT id FROM talent WHERE userid=$uid AND data='".Date("Y-m-d")."'";
			$res = mysql_query($sql);
			$interogari = mysql_num_rows($res);
			mysql_free_result($res);
			
			if($_REQUEST['uid'] != $_SESSION['USERID'] || $interogari>=3) {
				//nu poti cere de la alt jucator informatii
				echo '<h3>Actiune imposibila (numarul maxim de interogari a fost atins in aceasta zi)!</h3>';
			} else {
				$sql = "SELECT position, talent FROM player WHERE id=$piid";
				$res = mysql_query($sql);
				list($pos_player, $talent) = mysql_fetch_row($res);
				mysql_free_result($res);
				

				$trid=0;
				$sql = "SELECT a.trainerid, b.Goalkeeping, b.Defence, b.Midfield, b.Attack 
						FROM usertrainer a
						LEFT JOIN trainer b
						ON a.trainerid=b.id
						WHERE a.userid=$uid";
				$re = mysql_query($sql);
				list($trid, $gk, $df, $md, $at) = mysql_fetch_row($re);
				mysql_free_result($re);

				
				if($trid==0) {
					$gk=$df=$md=$at=10;
				}
				
				switch($pos_player) {
					case 1: //Goalkeeper
						$post = $gk;
						break;
					case 2: //defender DR
						$post = $df;
						break;
					case 3: //defender DC
						$post = $df;
						break;
					case 4: //defender DL
						$post = $df;
						break;
					case 5: //midfielder MR
						$post = $md;
						break;
					case 6: //midfielder MC
						$post = $md;
						break;
					case 7: //midfielder ML
						$post = $md;
						break;
					case 8: //forward FR
						$post = $at;
						break;
					case 9: //forward FC
						$post = $at;
						break;
					case 10: //forward FL
						$post = $at;
						break;
				}

				//folosesc valoarea de la antrenor, la respectiva categorie, pentru a estima talentul.
				//in functie de valoare, marja de eroare mai mare.
				//valoare intre 0-20: marja 20
				//valaore intre 21-30: marja 15;
				//valoare intre 31-40: marja 10;
				//valoare intre 41-50: marja 5;
				
				if($post<=20) $estimare = rand($talent-20, $talent+10);
				if($post>20 && $post<=30) $estimare = rand($talent-15, $talent+7);
				if($post>30 && $post<=40) $estimare = rand($talent-10, $talent+4);
				if($post>40 && $post<=50) $estimare = rand($talent-5, $talent+2);
				
				if($estimare<=20) $talentest=1;
				if($estimare>20 && $estimare<=40) $talentest=2;
				if($estimare>40 && $estimare<=60) $talentest=3;
				if($estimare>60 && $estimare<=80) $talentest=4;
				if($estimare>80) $talentest=5;
				
				$sql = "INSERT INTO talent(userid, playerid, talent, data)
						VALUES($uid, $piid, $talentest, '".Date("Y-m-d")."')";
				//echo "$sql<br/>";
				$res = mysql_query($sql);				
			}
}


//inceteaza contractul cu jucatorul
if(!empty($_REQUEST['ReinnoiesteContract'])){
			$piid = $_REQUEST['pid'];
			if($_REQUEST['uid'] != $_SESSION['USERID']) {
				//nu poti reinnoi contractul unuia care nu este al tau
				$_SESSION['MESSAGE'] = 'Actiune imposibila!';
			} else {
				$sql = "SELECT userid FROM userplayer WHERE playerid=$piid";
				$replayer = mysql_query($sql);
				list($userid) = mysql_fetch_row($replayer);
				//fac inca o verificare pentru a preveni 'refresh'-ul ferestrei, care ar lua bani de fiecare data
				if($userid != 0) {
					$salnou = 1;
					//preiau salariul din baza de date, ca sa nu permit vreo injectie in adresa
					$sql = "SELECT age, wage FROM player WHERE id=$piid";
					$rp = mysql_query($sql);
					list($page, $pwage) = mysql_fetch_row($rp);
					if($page>31) {
						$salnou = $pwage*0.95;
					} else {
						$salnou = $pwage*1.05;
					}						
					mysql_free_result($rp);
					
					//maresc contractul cu un sezon
					$sql = "UPDATE player SET contract=contract+1, wage=$salnou WHERE id=$piid";
					mysql_query($sql);
					//echo "$sql<br/>";
		
				}
				mysql_free_result($replayer);

			}
}


if (isset($_REQUEST['players'])) {
	$user->EchoTeam();
	
} elseif(!empty($_REQUEST['antrenor'])) {
			//echo "am intrat aici";
			$ant = 1;
			include('viewtrainer.php');
	} else 	{
		include('player_detail.php'); 
?>

					<div class="container-7-menu">
						<div class="clear"></div>
<table class="tftable">
<tr>
	<th>Moralul echipei</th>
	<td>Acesta este afisat mai sus, langa numele si ratingul echipei.
<br/>
Ce semnificatie are acesta: cu cat sunt mai multi jucatori in echipa, cu atat starea de spirit nu mai este atat de prielnica pentru antrenament si implicit, meci. Jucatorii devin irascibili, tensionati si rezultatul este unul singur: meciuri cu randament mai slab.
<br/>
Numarul maxim de jucatori pentru a nu scadea din moral este de 31 (inclusiv). De la 32 in sus, se scad puncte de moral. Aceasta se va face zilnic, pana se va ajunge la numarul acceptat. S-a recurs la aceasta modificare pentru a da sansa si altor utilizatori sa transfere jucatori buni, sa se creeze astfel o miscare de jucatori de la un utilizator la altii.</td>
</tr>
</table>
						<div class="clear"></div>
					<br/>
					<?php
					include('trophyroom.php');
					?>
						
					<br/>	
					<h2>Ultimele parieri</h2>
					<table class="tftable">
					<tr>
						<th>Jucator</th>
						<th>Cel mai mare pariu</th>
						<th>Suma</th>
						<th>Expirare</th>
					</tr>
					<?php
					$sql = "SELECT b.fname, b.lname, MaxBid(a.playerid), b.TransferDeadline, c.userid, a.playerid
							FROM playerbid a
							LEFT OUTER JOIN player b
							ON a.playerid=b.id
							LEFT OUTER JOIN userplayer c
							ON a.playerid=c.playerid
							WHERE a.activ=1 and date(b.TransferDeadline)>='".Date("Y-m-d")."' AND a.userid=".$_SESSION['USERID'] . " ORDER BY b.TransferDeadline ASC";
					$res = mysql_query($sql);
					//echo "$sql<br/>";
					while(list($fnume, $lnume, $pariat, $tdeadline, $uid, $pid) = mysql_fetch_row($res)) {
						list($val,$echipa, $tid) = split(";", $pariat);
						if($tdeadline<Date("Y-m-d H:i:s")) {
							$deaf = "Vandut";
						} else {
							$deaf = date("d M H:i:s", strtotime($tdeadline));
						}
						
						echo "<tr><td><a href=\"index.php?option=viewplayer&pid=$pid&uid=$uid\" class=\"link-5\">$fnume $lnume</a></td><td>$echipa</td><td>".number_format($val)." &euro;</td><td>$deaf</td></tr>";
					}
					mysql_free_result($res);
					?>
					</table>
					<br/>	
					<h2>Jucatori aflati pe lista de transfer</h2>
					<table class="tftable">
					<tr>
						<th>Jucator</th>
						<th>Cel mai mare pariu</th>
						<th>Suma</th>
						<th>Expirare</th>
						<th>Suma pornire</th>
					</tr>
					<?php
					$sql = "SELECT a.fname, a.lname, MaxBid(a.id), a.TransferDeadline, a.id, b.userid, a.TransferSuma 
							FROM player a
							LEFT OUTER JOIN userplayer b
							ON a.id=b.playerid
							WHERE a.Transfer=1 and (a.TransferDeadline>'".Date("Y-m-d")."' or a.TransferDeadline='0000-00-00 00:00:00') AND b.userid=".$_SESSION['USERID'];
					$res = mysql_query($sql);
					while(list($fnume, $lnume, $pariat, $tdeadline, $pid, $uid, $sumatransfer) = mysql_fetch_row($res)) {
						list($val,$echipa, $tid) = split(";", $pariat);
						//echo "Datalimita: $tdeadline --- ".Date("Y-m-d H:i:s").'<br/>';
						//if($datalimita>Date("Y-m-d H:i:s") and $datalimita<>"") $datalimita = "Vandut";
						if(date('Y-m-d H:i:s',strtotime($tdeadline))<Date("Y-m-d H:i:s") AND $tdeadline<>'0000-00-00 00:00:00') {
							$deaf = "Vandut";
						} else {
							$deaf = ($tdeadline == "0000-00-00 00:00:00")?"":date("d M H:i:s", strtotime($tdeadline));
							//$deaf = date("d M H:i", strtotime($tdeadline));
						}
						
						if($val == 0) $val="";
						else $val = number_format($val)." &euro;";
						echo "<tr><td><a href=\"index.php?option=viewplayer&pid=$pid&uid=$uid\" class=\"link-5\">$fnume $lnume</a></td><td>$echipa</td><td>$val</td><td>$deaf</td><td>".number_format($sumatransfer)." &euro;</td></tr>";
					}
					mysql_free_result($res);
					?>
					</table>
						
						
							</div>


<?php } ?>
<br/>
<h2>Tribuna ta ("Socios")</h2>
<br/>
<div class="fb-like" data-href="http://cupaligii.ro/index.php?fbclub=<?php echo $_SESSION['USERID']; ?>" data-send="false" data-width="200" data-show-faces="true"></div>

<?php
$url = "https://api.facebook.com/method/fql.query?query=select%20%20like_count%20from%20link_stat%20where%20url=%22http://www.cupaligii.ro/index.php?fbclub=".$_SESSION['USERID']."%22";
/*
$xml = simplexml_load_file($url);
while(list($k,$v) = each($xml)) {
	echo "$k -- $v<br/>";
}
print_r($xml);
*/
?>

<?php
if (($response_xml_data = file_get_contents($url))===false){
    echo "Error fetching XML\n";
} else {
   libxml_use_internal_errors(true);
   $data = simplexml_load_string($response_xml_data);
   if (!$data) {
       echo "Error loading XML\n";
       foreach(libxml_get_errors() as $error) {
           echo "\t", $error->message;
       }
   } else { 
   ?>
	  <table class="tf2"> 
	  <tr>
	  <th>
		Numar socios:
	  </th>
	  <td>
	  <?php echo $data->link_stat->like_count; ?>
	  </td>
	  </tr>
	  <tr>
	  <td colspan="2">
	  Invita-ti prietenii sa te sustina, printr-un like pentru clubul tau! Link-ul prin care iti promovezi clubul este: http://www.cupaligii.ro/index.php?fbclub=<?php echo $_SESSION['USERID']; ?> 
	  </td>
	  </tr>
	  </table>
	  <?php	
   }
}
?>