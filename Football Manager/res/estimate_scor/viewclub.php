<script>							
$(function () {

    $(".div2").hide();
    
    $(".link1, .link2").bind("click", function () {

      $(".div1, .div2").hide();        
        
      if ($(this).attr("class") == "link1")
      {
        $(".div1").show();
      }
      else 
      { 
        $(".div2").show();
      }
    });

});
</script>	
<?php
$sql = "SELECT u.id, u.TeamName, u.CountryID, c.competitionid, u.Rating , u.Username, u.Funds, u.LastActive, 1, d.name, u.pic, u.StadiumID, u.Moral
		FROM user u 
		LEFT JOIN leagueuser c
		ON u.id=c.userid
		LEFT JOIN competition d
		on c.competitionid=d.id
		WHERE u.id=".$_REQUEST['club_id'] . " AND c.season=".$_SESSION['_SEASON'];
//echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);

list($team_id, $teamname, $countryId, $LeagueId, $Rating, $username, $funds, $lastactive, $Online, $LeagueName, $poza, $stadionID, $moral) = mysqli_fetch_row($res);
	//echo "Stadion $stadionID<br/>";
	$stad = new Stadium($stadionID);
	$v1 = $moral;
	$v2 = 100 - $v1;
	echo "<h1>$teamname ($Rating)";
	echo "&nbsp;&nbsp;&nbsp;<img width=\"21\" src=\"pie.php?n1=$v1&n2=$v2\"></h1>";
	echo "<div class=\"container-3\">";
	//if($poza != "") echo "<img src=\"".$poza."\" class=\"img-1\" width=\"310\" align=\"left\">";
	//else echo "<img src=\"images/manager.jpg\" class=\"img-1\" width=\"330\" align=\"left\">";
	if($poza == "") $poza = "images/manager.jpg";
	
	$nrl = $stad->AvailableSeats();
//$nrl = 25000;

	switch($nrl) {
		case ($nrl<3000): $img = "images/stadion-1.jpg";break;
		case ($nrl>=3000 && $nrl<6000): $img = "images/stadion-2.jpg";break;
		case ($nrl>=6000 && $nrl<10000): $img = "images/stadion-3.jpg";break;
		case ($nrl>=10000 && $nrl<15000): $img = "images/stadion-4.jpg";break;
		case ($nrl>=15000 && $nrl<25000): $img = "images/stadion-5.jpg";break;
		case ($nrl>=25000 && $nrl<35000): $img = "images/stadion-6.jpg";break;
		case ($nrl>=35000 && $nrl<50000): $img = "images/stadion-7.jpg";break;
		case ($nrl>=50000 && $nrl<60000): $img = "images/stadion-8.jpg";break;
		case ($nrl>=60000 && $nrl<80000): $img = "images/stadion-9.jpg";break;
		case ($nrl>=80000): $img = "images/stadion-10.jpg";break;
	}
	
	//echo "AAAAAAAAAAAAAAAAAAAAAA::::".$_SESSION['USERID'];
	echo "<img src=\"suprapuse.php?id=".$_REQUEST['club_id']."&img=$poza&imgstad=$img\" width=\"300\" class=\"img-1\">";
	echo "Manager: <a href=\"index.php?option=sendmess&to=$team_id&toname=$username\">$username</a>";
	echo "<div class=\"hr-replace\"></div>";
	echo "Stadion: ".$stad->ReturnStadiumName();
	echo "<div class=\"hr-replace\"></div>";
	$liga = $LeagueId == 0? 'nealocat': $LeagueName;

	echo "Liga: $liga<br/>";
	$activ = $Online == 1 ? 'Activ':'Inactiv';
	//echo "Online: $activ</br/>"; 
	echo "Activ ultima data: " . $lastactive;
	echo "<div class=\"hr-replace\"></div>";
	if($team_id <> $_SESSION['USERID'] && !empty($_SESSION['USERID'])) { ?>
		<a href="index.php?option=amical&club_id=<?php echo $team_id; ?>"/><img src="images/playgame.png" border="0" width="70"></a>
		<a href="index.php?option=sendmess&to=<?php echo $team_id; ?>&toname=<?php echo $username; ?>"><img src="images/citit.png" border="0" width="70"></a>
	<?php 
		echo "<a href=\"index.php?option=meciuri&teamid=$team_id\"><img src=\"images/meciuri.png\" border=\"0\" width=\"65\"></a>";
	} 
	echo "</div>";
	//echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/>";
	//echo "<br/><br/><br/><br/><br/><br/><br/><br/>";
	
	if(!empty($_SESSION['USERID'])) {
		echo "<A href=\"#\" class=\"link1\"><img src=\"images/seniori.png\" width=\"30\" title=\"Seniori\"></a> <A href=\"#\" class=\"link2\" title=\"Juniori\"><img src=\"images/juniori.png\" width=\"30\"></a><br/>";
		echo "<div class=\"div1\">";
		$user->EchoOtherTeamNou($_REQUEST['club_id'],0);
		echo "</div>";
		echo "<div class=\"div2\">";
		$user->EchoOtherTeamNou($_REQUEST['club_id'],1);
		echo "</div>";
//	$user->EchoOtherTeamNou($_REQUEST['club_id']);
	}
	include("trophyroom.php");


?>
<br/>
<h2>Tribuna ta ("Socios")</h2>
<br/>
<div class="fb-like" data-href="http://cupaligii.ro/index.php?fbclub=<?php echo $team_id; ?>" data-send="false" data-width="200" data-show-faces="true"></div>

<?php
$url = "https://api.facebook.com/method/fql.query?query=select%20%20like_count%20from%20link_stat%20where%20url=%22http://www.cupaligii.ro/index.php?fbclub=$team_id%22";
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
	  Invita-ti prietenii sa te sustina, printr-un like pentru clubul tau! Link-ul prin care iti promovezi clubul este: http://www.cupaligii.ro/index.php?fbclub=<?php echo $team_id; ?> 
	  </td>
	  </tr>
	  </table>
	  <?php	
   }
}
mysqli_free_result($res);
?>
