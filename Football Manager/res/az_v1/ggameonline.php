<?php
include('app.conf.php');
include('player.php');
include('UserStadium.php');
include('trainer.php');

include('app.head.php');


?>
<meta http-equiv="refresh" content="60">
    <main>
        <!-- About US Start -->
        <div class="about-area">
            <div class="container">
                    <!-- Hot Aimated News Tittle-->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="trending-tittle">
                                <strong>Trending now</strong>
                                <!-- <p>Rem ipsum dolor sit amet, consectetur adipisicing elit.</p> -->
                                <div class="trending-animated">
                                    <ul id="js-news" class="js-hidden">
                                        <li class="news-item">Bangladesh dolor sit amet, consectetur adipisicing elit.</li>
                                        <li class="news-item">Spondon IT sit amet, consectetur.......</li>
                                        <li class="news-item">Rem ipsum dolor sit amet, consectetur adipisicing elit.</li>
                                    </ul>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                   <div class="row">
                        <div class="col-lg-8">
                            <!-- Trending Tittle -->
                                    <div class="about-right mb-90">
                                        <div class="about-img">
                                            <img src="images/online2.jpg" alt="">
                                        </div>
                                        <div class="section-tittle mb-30 pt-30">
                                            <h3>Game Day</h3>
                                        </div>
                                        <div class="about-prea">
                                        
										
										<table class="tftable">

<?php
$sql = "SELECT c.fname, c.lname, a.mminute, a.team, b.gamedate 
		FROM gamedetail a
		LEFT OUTER JOIN gameinvitation b
		ON a.gameID=b.id
		LEFT OUTER JOIN player c
		ON a.playerid = c.id
		WHERE a.gameID=".$_REQUEST['gameid']." AND a.action=1 ORDER BY a.mminute ASC";
//echo "$sql<br/>";		
$todisplay1 = "";
$score1 = 0;
$score2 = 0;
$todisplay2 = "";
$res = mysql_query($sql);
while(list($fname, $lname, $minut, $noteam, $gamedate)=mysql_fetch_row($res)) {
	if(Date("Y-m-d")>$gamedate) {
		//echo "sunt aici<br/>";
		//afisez marcatorii, ca s-a terminat meciul
		if($minut>45) {
			$rest = $minut % 45;
			$rest = ($rest<10)? " 13:0".$rest:" 13:".$rest;
				if($noteam == 1) {
					$todisplay1 .= "<img src=\"images/minge.png\" width=\"11\">&nbsp;$fname $lname $minut<br/>";
					$score1++;
				} else {
					$todisplay2 .= "<img src=\"images/minge.png\" width=\"11\">&nbsp;$fname $lname $minut<br/>";
					$score2++;
				}
			
		} else {
			$rest = ($minut<10)? " 12:0".$minut:" 12:".$minut;
				if($noteam == 1) {
					$todisplay1 .= "<img src=\"images/minge.png\" width=\"11\">&nbsp;$fname $lname $minut<br/>";
					$score1++;
				} else {
					$todisplay2 .= "<img src=\"images/minge.png\" width=\"11\">&nbsp;$fname $lname $minut<br/>";
					$score2++;
				}
		}
	}
	if(Date("Y-m-d") == $gamedate) {
		if($minut>45) {
			$rest = $minut % 45;
			$rest = ($rest<10)? " 13:0".$rest:" 13:".$rest;
			if(Date("Y-m-d").$rest<Date("Y-m-d H:i", strtotime("+1 hours"))) 
				if($noteam == 1) {
					$todisplay1 .= "<img src=\"images/minge.png\" width=\"11\">&nbsp;$fname $lname $minut<br/>";
					$score1++;
				} else {
					$todisplay2 .= "<img src=\"images/minge.png\" width=\"11\">&nbsp;$fname $lname $minut<br/>";
					$score2++;
				}
			
		} else {
			$rest = ($minut<10)? " 12:0".$minut:" 12:".$minut;
			if(Date("Y-m-d").$rest<Date("Y-m-d H:i", strtotime("+1 hours")))
				if($noteam == 1) {
					$todisplay1 .= "<img src=\"images/minge.png\" width=\"11\">&nbsp;$fname $lname $minut<br/>";
					$score1++;
				} else {
					$todisplay2 .= "<img src=\"images/minge.png\" width=\"11\">&nbsp;$fname $lname $minut<br/>";
					$score2++;
				}
		}
	}
}
mysql_free_result($res);
?>
<?php
$sql = "SELECT a.mminute, a.goal, a.text, a.attacking_team, b.TeamName, d.TeamName, e.TeamName, d.id, e.id, a.goal, c.gamedate, c.score, d.rating, e.rating, c.competitionid, a.realminute
		FROM gametext a
		LEFT OUTER JOIN user b
		ON a.attacking_team=b.id
		LEFT OUTER JOIN gameinvitation c
		ON a.gameID=c.id
		LEFT OUTER JOIN user d
		ON d.id=c.userId_1
		LEFT OUTER JOIN user e
		ON e.id=c.userId_2
		WHERE a.gameID=".$_REQUEST['gameid']." AND CONCAT(c.gamedate,' ',a.mminute)<'".Date("Y-m-d H:i", strtotime("+1 hours"))."' ORDER BY a.mminute DESC";
//echo "$sql<br/>";		
$res = mysql_query($sql);

//returnez numarul de linii cu comentariu
//il inmultesc cu 2, pentru ca fac afisarea pe 2 linii
//$numar il folosesc cu un rowspan, pt a afisa imaginea cu actiunea pe prima coloana
$numar = mysql_num_rows($res);
$numar = $numar*2;
$rand = rand(1,5);

$i=0;



while (list($minut, $gol, $text, $atacatorid, $team, $team1, $team2, $userid1, $userid2, $gol, $gamedate, $score, $rat1, $rat2, $competitieid, $minreal) = mysql_fetch_row($res)) {
	if($i == 0) {
		//don't display the score till the end of the game
		if($gamedate.' '.'13:45' > Date("Y-m-d H:i", strtotime("+1 hours"))) $score = "<h1 align=\"center\">$score1:$score2</h1>";

		
		echo "<tr><th colspan=\"4\"><h2>$gamedate</h2>";
		//echo "<br/>Numar spectatori: $nrspect";
		echo "</th></tr><tr><th>$team1</th><th colspan=\"2\"><h1 align=\"center\">$score</h1></th><th><p align=\"right\">$team2</p></h2></th></tr>";
		if($todisplay1 <> "" || $todisplay2 <> "") {
		?>
			<tr>
				<td colspan="2">
				<?php echo $todisplay1; ?>
				</td>
				<td colspan="2">
				<?php echo $todisplay2; ?>
				</td>
			</tr>

		<?php
		}
	}
	$uid = ($team == $team1)?$userid1:$userid2;
	$imagine2 = ($gol == 1)?"<img src=\"images/minge.png\" width=\"20\" align=\"right\">":"";
	if ($gol == 10) $imagine2 = "<img src=\"images/schimbare.png\" width=\"20\" align=\"right\">";
	if ($gol == 5) $imagine2 = "<img src=\"images/fanion.png\" width=\"20\" align=\"right\">";
	if ($gol == 2 || $gol == 3 || $gol == 4) $imagine2 = "<img src=\"images/fluier.png\" width=\"20\" align=\"right\">";
	switch($rand) {
		case 1:	$imagine = "online1.jpg"; break;
		case 2:	$imagine = "online2.jpg"; break;
		case 3:	$imagine = "online3.jpg"; break;
		case 4:	$imagine = "online4.jpg"; break;
		case 5:	$imagine = "online5.jpg"; break;
		case 6:	$imagine = "online6.jpg"; break;
	}
	echo "<tr>";
	if($i==0) {
		echo "<td rowspan=\"$numar\"  style=\"vertical-align: top;\"><img src=\"images/$imagine\" class=\"img-1\" width=\"250\"></td>";
	}
	echo "<td>min.$minreal&nbsp;</td><td><a href=\"index.php?option=viewclub&club_id=$uid\" class=\"link-2\">$team</a></td><td></td></tr><tr><td></td>";
	echo "<td>$imagine2</td><th>$text</th>";
	echo "</tr>";
	$i++;
}
if($i==0) {
	echo "<tr><th colspan=\"4\"><h2>Game isn't started!</h2></th></tr>";
}
mysql_free_result($res);
?>
</table>
										
										
										
										</div>
                                    </div>
                        </div>
                        <div class="col-lg-4">
                            <!-- Section Tittle -->
                            <div class="section-tittle mb-40">
                                <h3>Follow Us</h3>
                            </div>
                            <!-- Flow Socail -->
                            <div class="single-follow mb-45">
                                <div class="single-box">
                                    <div class="follow-us d-flex align-items-center">
                                        <div class="follow-social">
                                            <a href="#"><img src="assets/img/news/icon-fb.png" alt=""></a>
                                        </div>
                                        <div class="follow-count">  
                                            <span>8,045</span>
                                            <p>Fans</p>
                                        </div>
                                    </div> 
                                    <div class="follow-us d-flex align-items-center">
                                        <div class="follow-social">
                                            <a href="#"><img src="assets/img/news/icon-tw.png" alt=""></a>
                                        </div>
                                        <div class="follow-count">
                                            <span>8,045</span>
                                            <p>Fans</p>
                                        </div>
                                    </div>
                                        <div class="follow-us d-flex align-items-center">
                                        <div class="follow-social">
                                            <a href="#"><img src="assets/img/news/icon-ins.png" alt=""></a>
                                        </div>
                                        <div class="follow-count">
                                            <span>8,045</span>
                                            <p>Fans</p>
                                        </div>
                                    </div>
                                    <div class="follow-us d-flex align-items-center">
                                        <div class="follow-social">
                                            <a href="#"><img src="assets/img/news/icon-yo.png" alt=""></a>
                                        </div>
                                        <div class="follow-count">
                                            <span>8,045</span>
                                            <p>Fans</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- New Poster -->
                            <div class="news-poster d-none d-lg-block">
                                <img src="assets/img/news/news_card.jpg" alt="">
                            </div>
                        </div>
                   </div>
            </div>
        </div>
        <!-- About US End -->
    </main>
	
<?php include('app.foot.php'); ?>