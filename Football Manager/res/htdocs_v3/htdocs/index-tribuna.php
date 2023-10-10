<?php
include('app.conf.php');
include('constante.php');
include('define.php');


$afostlogoff=0;

$afostreferal = 0;

if(!empty($_REQUEST['Recomanda'])) {
	$date = json_decode(file_get_contents('https://graph.facebook.com/'.$_SESSION['USER_ID']));

	$body = '
		<html>
		<body>
		Buna ziua,<br/><br/>
		<img src=\"http://graph.facebook.com/'.$_SESSON['USER_ID'].'/picture?type=large" width=\"150\" height=\"110\" align=\"left\">
		Prietenul tau de pe Facebook, '.$date->first_name.' '.$date->last_name.', te invita sa i te alaturi pe <a href=\"http://www.InTribuna.ro\" target=\"_blank\">www.InTribuna.ro</a>!
		<br/><br/>
		www.InTribuna.ro este site-ul in care tu hotaresti care este cea mai iubita echipa din Romania! Iti poti alege echipe din diverse ligi, iar prin intrarea in tribuna meciului urmator, iti ajuti echipa sa acumuleze puncte in clasament!<br/><br/>Te asteptam pe www.InTribuna.ro!		
		</body>
		</html>
	';
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";


	$subject = "Invitatie InTribuna.ro";
	mail($_REQUEST['email'], $subject, $body, $headers);
	$subject = $subject.': '.$_REQUEST['email'];
	mail('fcbrasov@yahoo.com', $subject, $body, $headers);
}

if(!empty($_REQUEST['TrimiteComentariu'])) {

	$blocked=0;
	//if(stristr($_REQUEST['comentariu'], 'http:')) $blocked=1;

	$tabel = '1';
	$comment = $_REQUEST['comentariu'];

	$probabile=0;
	if(isset($_REQUEST['probabile'])) {
		$probabile = 1;
	}

	if($blocked==0) {
		//se dau si puncte pentru postat comentariu!
		$premiu = rand(1,5);
		$sql = "UPDATE cont
				SET suma=suma+$premiu 
				WHERE userid=".$_SESSION['USER_ID'];
		mysql_query($sql);

		$data_curenta = Date("Y-m-d H:i:s"); 
		$sql = "INSERT INTO comentarii (stireId, comentariu, tabel, data, fbid, premiu, echipeprobabile)
			VALUES('".$_REQUEST['id']."', '".$comment."', '$tabel', '".$data_curenta."', ".$_SESSION['USER_ID'].", $premiu, $probabile)";

		mysql_query($sql);	
		


	}
}

if(isset($_REQUEST['option'])) {

		if($_REQUEST['option']=='like') {
			$sql = "SELECT isliked FROM user WHERE fbid=".$_SESSION['USER_ID'];
			$res = mysql_query($sql);
			list($isliked) = mysql_fetch_row($res);
			mysql_free_result($res);

			//echo "isli: $isliked";
			if($isliked == 0) {
				$sql = "UPDATE cont
						SET suma=suma+300 WHERE userid=".$_SESSION['USER_ID'];
				//echo "$sql";
				mysql_query($sql);
				//echo "$sql";

				$sql = "UPDATE user
						SET isliked=1
						WHERE fbid=".$_SESSION['USER_ID'];
				mysql_query($sql);
				//echo "$sql";
			}
		}


		if(substr($_REQUEST['option'],0,7)=='tribuna') {
			//vine referal de forma intribuna.ro/?option=tribuna-233     -> ultimele cifre, dupa - , id-ul jucatorului
			list($k,$refid) = split('-', $_REQUEST['option']);
			//in baza de date exista cheie unica pe userid si IP si data
			$premiu = rand(1,5);
			$sql = "INSERT INTO vizite(userid, IP, data, puncte)
					VALUES($refid, '". $_SERVER['REMOTE_ADDR'] . "', '".Date("Y-m-d")."', ". $premiu. ")";
			//echo "$sql";
			mysql_query($sql);
			$afostreferal = $refid;
		}
	}

$afoststabilirefavorita = 0;
if(isset($_REQUEST['Favorite'])) {
	list($echipaid, $ligaid) = split("-",$_REQUEST['echipa']);
	//verificare daca mai exista echipa din aceeasi liga
	$amgasit = 0;
	$sql = "SELECT a.id, a.echipaid, b.ligaid
			FROM favorite a
			LEFT OUTER JOIN echipaliga b
			ON a.echipaid=b.echipaid 
			WHERE b.sezon='2011' AND a.userid=".$_SESSION['USER_ID'];
	$res = mysql_query($sql);
	while(list($idinreg, $echipa, $liga) = mysql_fetch_row($res)) {
		if($ligaid==$liga) {
			$amgasit=$idinreg;
			break;
		}
	}
	mysql_free_result($res);
	//am gasit echipa din liga, fac update, daca nu insert
	if($amgasit>0) {
		$sql = "UPDATE favorite
				SET echipaid=$echipaid
				WHERE id=$amgasit";
	} else {
		$sql = "INSERT INTO favorite (userid, echipaid)
				VALUES (".$_SESSION['USER_ID'].", $echipaid)";

	}

	$afoststabilirefavorita = 1;
	mysql_query($sql);
	}

$intratintribuna = 0;


include('app.head.php');
?>



  <div id="sidebar-a">
			<div class="padding">
			<?php include('right.php'); ?>
			</div>
		</div>


	    <div id="content">
			<div class="padding">
			<?php 


			if($_SESSION['USER_ID']>0) {
				$sql = "SELECT count(id)
						FROM user
						WHERE fbid=".$_SESSION['USER_ID'];
				$existauser=1;
				//$res = mysql_query($sql);
				//list($existauser) = mysql_fetch_row($res);
				//mysql_free_result($res);

				if($existauser>0) {
				} else {
					//e prima oara in joc si trebuie sa-l bagam in tabel si sa-i dam puncte
						$curdate = Date('Y-m-d H:i:s');			
						$sql = "INSERT INTO user (prenume, nume, tribuna, poza, datain, fbid)
								VALUES ('', '', '', '', '$curdate',".$_SESSION['USER_ID'].")";
						mysql_query($sql);
						$id = mysql_insert_id();
						$sql = "INSERT INTO cont (userid, suma)
								VALUES (".$_SESSION['USER_ID'].", ".BANISTART.")";
						mysql_query($sql);

				}
			}

			if($afostreferal>0) {
				//a primit puncte din exterior
				afisare_casuta($afostreferal,$premiu);
			}

			if($_SESSION['USER_ID']>0) 
				if(isset($_REQUEST['Vizualizare'])) 
					include('vezitribuna.php');

				
			if($intratintribuna == 1) { 
				include('vezitribuna.php');
			}

			$sql = "SELECT b.suma, a.tribuna, a.email
					FROM user a
					LEFT OUTER JOIN cont b
					ON a.fbid=b.userid
					WHERE a.fbid=".$_SESSION['USER_ID'];

			//$res = mysql_query($sql);
			//list($sumabani, $b_tribuna, $eemail) = mysql_fetch_row($res);
			//mysql_free_result($res);
			/*
			if($eemail == '' && $user->email<>'') {
					$swl = "UPDATE user
						SET email='".$user->email."'
						WHERE fbid=".$_SESSION['USER_ID'];
					mysql_query($swl);
						}
			*/
			?>

			<div class="block block-layered-nav">
					<div class="block-title">
					<strong>
					<?php
					if($_SESSION['USER_ID']>0) {
 						//echo "HI, ".$user->name."(";
						//if($eemail=='') echo "<a href=\"modifica.php\" class=\"link-2\">update email aici)</a>";
						//	else echo "$eemail)"; 
					} else {
						echo "HI THERE!";
					}
					?>
					</strong>
					</div>

					





				<?php
				if($_SESSION['USER_ID']>0) {
						?>



						<!--
						<h3><a onclick="showComment();" href="javascript:;" class="link-3">Detalii</a></h3>
						-->
						<div class="1" id="detalii">
							
						<table>
						<tr>
							<td valign="top">
							<table width="230">
							<tr>
								<td valign="top">
								<!--
								<img src="http://graph.facebook.com/<?php echo $user; ?>/picture" class="img-1" align="left" width="55">
								-->
								</td>
								<td valign="top">
								<div align="center">
								<img src="images/bani.jpg" height="40" alt="Bani">
								<br/>
								<font class="bani"><?php echo $sumabani; ?></font>
								</div>
								</td>
								<td valign="top">
								<div align="center">
								<img src="images/stadion.jpg" height="40" alt="Denumirea tribunei tale"> 
								<br/>
								<font class="bani">
								<?php 
								if($b_tribuna == '') echo "<a href=\"modifica.php\" class=\"link-2\">(denumeste-o aici)</a>";
								else echo "$b_tribuna"; 
								?>
								</font>
								</div>
								</td>
							</tr>
							<tr>
								<td colspan="3">
								<a href="http://www.shareaholic.com/api/share/?v=1&apitype=1&apikey=8943b7fd64cd8b1770ff5affa9a9437b&service=5&title=InTribuna.ro&link=http://www.InTribuna.ro/?option=tribuna-<?php echo $_SESSION['USER_ID']; ?>&source=Shareaholic\" target=\"_blank\">
								<img src="http://www.steagu.ro/images/facebook.jpg" width="14"></a>&nbsp;
								<input class="input-winface" type="text" name="notimportant" value="http://InTribuna.ro/?option=tribuna-<?php echo $_SESSION['USER_ID']; ?>">
								</td>
							</tr>
							<tr>
								<td colspan="3">
								      <a href="<?php echo $logoutUrl; ?>">Logout</a>
								</td>
							</tr>
							</table>
							</td>

							<td valign="top">
							<ul>
							<li><font class="text">Denumesti tribuna echipei favorite daca primesti cele mai multe vizite de la prieteni!</font></li>
							<li><font class="text">Trimite linkul prietenilor, pentru a acumula bani!</font></li>
							<li><font class="text">Banii iti sunt necesari pentru a cumpara locuri in tribuna!</font></li>
							</ul>
<br/>
							<fb:like href="http://www.facebook.com/InTribuna" layout="button_count" show-faces="false" width="10" action="like|recommend" colorscheme="light|dark" font="arial|lucida grande|segoe ui|tahoma|trebuchet ms|verdana"></fb:like><font class="font">Apesi "Like" si iti dam un premiu de 300 de ron virtuali! (acest premiu se primeste o singura data)</font>
						

							</td>
						</tr>
						</table>

						<?php
							include('integrare.php');
							require_once "includes/functions-scurt.php"; 
						?>

						<?php
							include('verificare.php'); 
						?>
						<font class="text">Adresa aplicatiei Facebook este <a href="http://apps.facebook.com/intribuna" class="link-2"> aici</a></font>
						</div>
				<?php
					//else pt SESSION
				} else { ?>
					  <br/>
					  <font class="text">Trebuie sa te loghezi in contul de Facebook! Site-ul nostru doar va prelua informatiile publice de pe Facebook, pentru a va putea loga tot timpul la InTribuna.ro, fara alte inregistrari suplimentare! </font><br/><br/>
<!--
							<div>
							<fb:login-button data-scope="email,status_update,publish_stream"></fb:login-button>
  					      <div class="fb-login-button" data-scope="email,status_update,publish_stream">Login with Facebook</div>
-->				  
							 <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
						    
					  <br/><br/>
					  <table width="100%">
					  <tr>
						<td width="33%" valign="top">
						  <font class="text">Este foarte simplu! Dai click pe butonul de Facebook Log In, iar apoi apesi "Allow"!</font>
						  <img src="images/intribuna-1.jpg" class="img-1" align="bottom" width="190">
						 </td>
						 <td valign="top">
						  <font class="text">Iti alegi echipa/echipele favorite la sectiunea FAVORITE! (o singura echipa/liga)</font>
						  <br/>
						  <img src="images/intribuna-2.jpg" class="img-1" align="bottom" width="190">
						 </td>
						 <td valign="top">
						  <font class="text">Intri in tribuna meciului urmator pentru echipa favorita!</font>
						  <br/>
						  <img src="images/intribuna-3.jpg" class="img-1" align="bottom" width="190">
						  </td>
					  </tr>
					  </table>
				<?php } ?>



				</div>

<?php		
				if($_REQUEST['option'] == 'meciuri') {
					include('urmatoarelemeciuri.php');
				}

				if($_REQUEST['option'] == 'coeficient') {
					include('uefa-coefficients-2011.htm');
				}

				if($_REQUEST['option'] == 'livetv') {
					include('livetv.htm');
				}

				if($_REQUEST['option'] == 'lastgames') {
					include('meciuridisputate.php');
				}

				if($_REQUEST['option'] == 'detalii') {
					include('detaliimeci.php');
				}

				if($_REQUEST['option'] == '') {
					include('news.php');
				}

				if($afoststabilirefavorita > 0 OR empty($_REQUEST['option'])) {
?>
				<div class="block block-layered-nav">
					<div class="block-title">
					<strong>
					Echipe favorite
					</strong>
					</div>
					<?php
					$sql = "SELECT b.nume, b.sigla
							FROM favorite a
							LEFT OUTER JOIN echipe b
							ON a.echipaid=b.id
							WHERE a.userid=".$_SESSION['USER_ID'];
					$res = mysql_query($sql);
					$ifav = 0;
					while(list($numefav1, $siglafav1) = mysql_fetch_row($res)) {
						$ifav++;
						?>
						<img src="images/echipe/<?php echo $siglafav1; ?>" class="img-1" width="70" height="70">
						<?php
					}
					if($ifav==0) {
						echo "<h2>Nu ai nici o echipa favorita!</h2>";
					}

					mysql_free_result($res);
					?>
					<br/><br/><br/><br/><br/>
				</div>

			<?php 
			} 
			if($_REQUEST['option'] == 'presa') { 
				include('index-rss.php'); 
			}

			if($_REQUEST['option'] == 'favorite') { 
				include('mecifavorita.php'); 
			}

			?>
			<!--
			<br/>
			<h2><img src="images/headings/about.gif" width="54" height="14" alt="About" /></h2>
		    <p><strong>Enlighten Designs</strong> is </p>
		    <p>We are.</p>
		     
		    <p>Phone:   (07) 853 6060<br />
		    Fax:     (07) 853 6060<br />
		    Email:   <a href="mailto:info@enlighten.co.nz">info@enlighten.co.nz</a><br />
		    P.O Box: 14159, Hamilton, New Zealand</p>
		    <p><a href="#">More contact information…</a></p>
			-->
			</div>
		</div>




	     
<?php include('app.foot.php'); ?>