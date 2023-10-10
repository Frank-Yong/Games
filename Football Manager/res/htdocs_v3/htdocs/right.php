<h3>
	<span id="date_time" ></span>
	<script type="text/javascript">window.onload = date_time('date_time');</script>
</h3>

<div class="fb-page" data-href="https://www.facebook.com/cupaligii" data-width="277" data-height="300" data-hide-cover="true" data-show-facepile="true" data-show-posts="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/cupaligii"><a href="https://www.facebook.com/cupaligii">Cupa Ligii</a></blockquote></div></div>

<div class="container-6">


		<h3>myFM.com</h3>
				<div class="tab-menu">	
					<div id="tab_1_1" onclick="show_content(1,3,1)" class="tab-1">
						<div id="tab_1_left_1" class="tab-1-l"></div>
							<div class="center-tab">
							Info
							</div>
						<div id="tab_1_right_1" class="tab-1-r"></div>
					</div>
					<div id="tab_2_1" onclick="show_content(2,3,1)" class="tab-2">
						<div id="tab_2_left_1" class="tab-2-l"></div>
							<div class="center-tab">
							Tab 2
							</div>
						<div id="tab_2_right_1" class="tab-2-r"></div>
					</div>
					<div id="tab_3_1" onclick="show_content(3,3,1)" class="tab-2">
						<div id="tab_3_left_1" class="tab-2-l"></div>
							<div class="center-tab">
							Tab 3
							</div>
						<div id="tab_3_right_1" class="tab-2-r"></div>
					</div>
				</div>
					<div class="container-6-text" id="container_1_1">
					<ul>
					<?php 
					echo "Line up";
					$sql = "SELECT post FROM lineup WHERE pgroup=1 AND userId=".$_SESSION['USERID'];
					//echo "$sql<br/>";
					$estart = mysqli_query($con, $sql);
					$jin = 0;
					while(list($post) = mysqli_fetch_row($estart)) {
						if($post<>0)$jin++;				
					}
					if($jin == 0) echo "<li>Please check, no line up for seniors!!!</li>";  
					if($jin > 0 && $jin < 11) echo "<li>Please check, seniors line up is not complete!</li>";  
					if($jin == 11) echo "<li>Your seniors line up is complete!</li>";  
					mysqli_free_result($estart);

					$sql = "SELECT post FROM lineup WHERE pgroup=2 AND userId=".$_SESSION['USERID'];
					//echo "$sql<br/>";
					$estart = mysqli_query($con, $sql);
					$jin = 0;
					while(list($post) = mysqli_fetch_row($estart)) {
						if($post<>0)$jin++;				
					}
					if($jin == 0) echo "<li>Please check, youth line up is not set!!!</li>";  
					if($jin > 0 && $jin < 11) echo "<li>Please check, youth team is not complete!</li>";  
					if($jin == 11) echo "<li>Youth team is complete!</li>";  
					mysqli_free_result($estart);
					?>			
					</ul>
					<?php
					echo "Ticket price";
					$sql = "SELECT a.price 
							FROM stadium a 
							LEFT OUTER JOIN user b
							ON a.id=b.stadiumid
							WHERE b.id=".$_SESSION['USERID'];
					//echo "$sql<br/>";
					$pb = mysqli_query($con, $sql);
					$jin = 0;
					list($prbil) = mysqli_fetch_row($pb);
					if($prbil == 0) echo "<li>No price set for the tickets!</li>";  
					else echo "<li>Ticket price is: $prbil &euro;</li>";
					mysqli_free_result($pb);
					?>			
					</ul>

					
					<br/><br/>
					My last bets:<br/>
					<?php
					$sql = "SELECT b.fname, b.lname, a.playerid, b.TransferDeadline, c.userid, a.playerid
							FROM playerbid a
							LEFT OUTER JOIN player b
							ON a.playerid=b.id
							LEFT OUTER JOIN userplayer c
							ON a.playerid=c.playerid
							WHERE a.activ=1 and date(b.TransferDeadline)>='".Date("Y-m-d")."' AND a.userid=".$_SESSION['USERID'] . " ORDER BY b.TransferDeadline ASC";
					$res = mysqli_query($con, $sql);
					//echo "$sql<br/>";
					while(list($fnume, $lnume, $pariat, $tdeadline, $uid, $pid) = mysqli_fetch_row($res)) {
						list($val,$echipa, $tid) = explode(";", $pariat);
						if($tdeadline<Date("Y-m-d H:i:s")) {
							$deaf = "Sold";
						} else {
							$deaf = date("d M H:i:s", strtotime($tdeadline));
						}
						
						echo "<a href=\"index.php?option=viewplayer&pid=$pid&uid=$uid\" class=\"link-5\">$fnume $lnume</a><br/>$echipa&nbsp;&nbsp;&nbsp;&nbsp;$val&euro;&nbsp;&nbsp;&nbsp;&nbsp;$deaf<br/>";
					}
					mysqli_free_result($res);
					?>
					<br/>
					Last game:<br/>
					<?php
					$sql = "SELECT a.id, a.gamedate, b.TeamName, c.TeamName, a.score, d.name
							FROM gameinvitation a
							LEFT OUTER JOIN user b
							ON a.userId_1=b.id
							LEFT OUTER JOIN user c
							ON a.userId_2=c.id
							LEFT OUTER JOIN competition d
							ON a.competitionid=d.id
							WHERE (a.userId_1=".$_SESSION['USERID']." OR a.userId_2=".$_SESSION['USERID'].") and a.accepted=1 ORDER BY a.gamedate ASC";
					//echo "$sql<br/>";
					$resmeci = mysqli_query($con, $sql);
					$meciid1 = $meciid2 = 0;
					$mecidata1 = $mecidata2 = "0000-00-00 00:00:00";
					$Team11 = $Team12 = $Team21 = $Team22 = "";
					$scor1 = $scor2 = "";
					$numecomp1 = $numecomp2 = "";
					while(list($meciid, $mecidata, $Team1, $Team2, $scor, $numecomp) = mysqli_fetch_row($resmeci)) {
						if($mecidata." 12:00:00"<Date("Y-m-d H:i:s")) {
							$mecidata1 = $mecidata;
							$Team11 = $Team1;
							$Team12 = $Team2;
							$meciid1=$meciid;
							$scor1 = $scor;
							$numecomp1 = $numecomp;
							//echo "$Team11 - $Team12<br/>";
						} else {
							$mecidata2 = $mecidata;
							$Team21 = $Team1;
							$Team22 = $Team2;
							$meciid2=$meciid;
							$scor2 = $scor;
							$numecomp2 = $numecomp;
							break;
						}
					}
					//echo "$scor1 $mecidata1<br/>";
					$sc = $mecidata1." 13:45">Date("Y-m-d H:i") ? $sc = ":" : $sc=$scor1; 
					if($numecomp1 == "") $numecomp1 = "Amical";
					if($numecomp2 == "") $numecomp2 = "Amical";
					echo "<a href=\"index.php?option=mecionline&meciID=$meciid1\" class=\"link-5\">($mecidata1) $numecomp1: $Team11 $sc $Team12</a><br/>";
					echo "Next game<br/>";
					echo "<a href=\"index.php?option=mecionline&meciID=$meciid2\" class=\"link-5\">($mecidata2) $numecomp2: $Team21 : $Team22</a><br/>";
					mysqli_free_result($resmeci);
					include('estimare.php');
					?>
					</div>
					
					
					<div class="container-6-text" id="container_2_1"  style="display:none">
<?php 
echo "My Football Manager!";
 ?>			

					</div>
					<div class="container-6-text" id="container_3_1" style="display:none">
Testing
					</div>
			</div>


<div>
<?php include('clasament.php'); ?>
</div>
			
<br/>

			<div class="container-7">
				<h3>myFM.com</h3>
				<div class="container-7-text">
					<h5>Fotbal Manager</h5>
					<h2>Be Manager!</h2>
Be the creator for your new champion! Build your team and win right now!				</div>
			</div>



