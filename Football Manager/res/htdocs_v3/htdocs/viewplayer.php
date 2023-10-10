<?php
$p = new Player($_REQUEST['uid'],$_REQUEST['pid']);

//2 reguli de implementat:
// REZOLVAT --- 1. nu poti paria pe jucatorul propriu
// REZOLVAT --- 2. trebuie verificati banii din cont la pariere
// REZOLVAT --- mai trebuie verificat daca timpul nu a expirat cumva, iar daca se pariaza in ultimele doua minute, sa se modifice data transf. = ora parierii+2 minute.

//REZOLVAT ---- trebuie pus un T in dreptul jucatorului, in cadran de culoare
//REZOLVAT ---- nu trebuie sa mai poti scoate jucatorul de pe transfer cind deja s-a pariat pe el
//trebuie implementat ca dupa ce cineva cistiga licitatia, sa se duca informatia in tabelul requests, ca sa fie procesat ziua urmatoare

//sa nu se poata paria mai putin decit suma maxima existenta + 1000;




if(!empty($_REQUEST['BidPlayer'])) {
			$timpexpirat = 0;
			if($p->TransferDeadline<=Date("Y-m-d H:i:00")) {
				$timpexpirat =1;
			}

			$piid = $_REQUEST['pid'];
			$fonduri = $user->Fonduri();
			$insuficientibani = 0;

			//trebuie verificat si daca jucatorul e liber de contract
			//sa nu se permita un salariu mai mare de salariu*10>banicont
			$sql = "SELECT userid FROM userplayer WHERE player=$piid";
			$resech = mysqli_query($GLOBALS['con'],$sql);
			list($uuid) = mysqli_fetch_row($resech);
			if($uuid == 0) {
				//este liber de contract
				//verific suma pariata comparativ cu bani din cont/10
				if($fonduri/10<$_REQUEST['BidValue']) {
				//nu poate paria pentru ca fondurile nu sunt suficiente
					$insuficientibani = 1;
				}
				
			}
			mysqli_free_result($resech);

			if($fonduri<$_REQUEST['BidValue']) {
				//nu poate paria pentru ca fondurile nu sunt suficiente
				$insuficientibani = 1;
			}

			$sql = "SELECT MaxBid($piid)";
			$res = mysqli_query($GLOBALS['con'],$sql);
			list($pariere) = mysqli_fetch_row($res);
			mysqli_free_result($res);
			
			$valoareinsuficienta = 0;
			list($val,$echipa, $tid) = explode(";", $pariere);
			if($val>$_REQUEST['BidValue']) {
				$valoareinsuficienta = 1;
			}				

			if($_REQUEST['uid'] != $_SESSION['USERID'] && $insuficientibani == 0 && $timpexpirat == 0 && $valoareinsuficienta == 0) {
				$sql = "SELECT id FROM playerbid
						WHERE activ=1 AND userid=".$_SESSION['USERID']." AND playerid=".$_REQUEST['pid'];
				$res = mysqli_query($GLOBALS['con'],$sql);
				$exista_bet = mysqli_num_rows($res);
				mysqli_free_result($res);
				if($exista_bet>0) {
					//update - exista o pariere pe acest jucator
					$curDate = Date("Y-m-d H:i:s");
					$sql = "UPDATE playerbid SET suma=".$_REQUEST['BidValue'].", data='$curDate' WHERE userid=".$_SESSION['USERID']." AND playerid=".$_REQUEST['pid'];
					mysqli_query($GLOBALS['con'],$sql);
					//echo "$sql<br/>";

					} else {
					//insert
					$curDate = Date("Y-m-d H:i:s");
					$sql = "INSERT INTO playerbid(data, userid, playerid, suma, activ)
							VALUES('$curDate', ".$_SESSION['USERID'].", ". $_REQUEST['pid']. ", ".$_REQUEST['BidValue'].",1)";
					//echo "$sql<br/>";
					mysqli_query($GLOBALS['con'],$sql);
					
						
					}
					//daca data parierii se afla in 2 minute sau mai putin fata de data expirarii, maresc limita cu 2 minute0
					//echo '<br/>'.date("Y-m-d h:i:s", strtotime("+2 minutes")).'   '.$p->TransferDeadline.'<br/>';
					if(date("Y-m-d h:i:s", strtotime("+2 minutes")) >= $p->TransferDeadline) {
						$datanoua = date("Y-m-d h:i:0", strtotime("+2 minutes"));
						$sql = "UPDATE player SET TransferDeadline='$datanoua' WHERE id=".$_REQUEST['pid'];
						mysqli_query($GLOBALS['con'],$sql);
					
					//echo "$sql";
				}

			}
}

if(isset($_REQUEST['StartBidPlayer'])) {
			//echo "USERI: Proprietar:".$_REQUEST['uid']."  Parior: ".$_SESSION['USERID'].'<br/>';
			//$u = new user();
			//$u->LoginID($_SESSION['USERID']);
			//nu trebuie sa verific aici daca e timpul expirat... ca abia se incepe licitatia
			$timpexpirat = 0;
			$fonduri = $user->Fonduri();
			$insuficientibani = 0;
			if($fonduri<$_REQUEST['BidValue']) {
				//nu poate paria pentru ca fondurile nu sunt suficiente
				$insuficientibani = 1;
			}

		if($_REQUEST['uid'] != $_SESSION['USERID'] && $insuficientibani == 0 && $timpexpirat == 0) {
				$curDate = Date("Y-m-d H:i:00"); //0 la secunde
		//		$curDateTime = new DateTime('NOW');
					$sql ="INSERT INTO playerbid (userid, playerid, suma, data, activ)
						   VALUES(".$_SESSION['USERID'].", ".$_REQUEST['pid'].", ".$_REQUEST['BidValue'].", '$curDate', 1)";
					//echo "$sql";
					mysqli_query($GLOBALS['con'],$sql);
					$deadline = date('Y-m-d H:i:00', strtotime("+2 days"));
					$sql = "UPDATE player SET TransferDeadline='$deadline' WHERE id=".$_REQUEST['pid'];
					mysqli_query($GLOBALS['con'],$sql);
			}
		}

		if($timpexpirat == 1) {
		?>
		<table class="tftable" width="100%">
		<tr>
			<th>LICITATIA S-A INCHEIAT!</th>
		</tr>
		</table>
		<?php
		}

		
		if($insuficientibani == 1) {
		?>
		<table class="tftable" width="100%">
		<tr>
			<th>NU AI BANI SUFICIENTI IN CONT PENTRU A CUMPARA JUCATORUL! (IN CONT: <?php echo $fonduri; ?>&euro;)</th>
		</tr>
		</table>
		<?php
		}

		if($valoareinsuficienta == 1) {
		?>
		<table class="tftable" width="100%">
		<tr>
			<th>TREBUIE SA PARIEZI MAI MULT DECIT SUMA CURENTA!</th>
		</tr>
		</table>
		<?php
		}

		$p = new Player($_REQUEST['uid'],$_REQUEST['pid']);		
		$p->EchoPlayer();
?>