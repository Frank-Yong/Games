<?php
//stergere jucatori
 

include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');


if(!empty($_REQUEST['Trimite'])) {

	$i=0;
	while(list($k,$ech)=each($_REQUEST['echipe'])) {
	        $sql = "SELECT a.playerid
                        FROM userplayer a
                        WHERE a.userid=$ech";
                $res = mysqli_query($GLOBALS['con'],$sql);
                while(list($playerid) = mysqli_fetch_row($res)) {
                      $sql2 = "UPDATE player SET active=0 WHERE id=$playerid";
                      mysqli_query($GLOBALS['con'],$sql2);
                      $sql3 = "DELETE FROM grow WHERE playerId = $playerid";
                      mysqli_query($GLOBALS['con'],$sql3);
                      $sql3 = "DELETE FROM percentage WHERE playerId = $playerid";
                      mysqli_query($GLOBALS['con'],$sql3);

                      $sql3 = "DELETE FROM lineup WHERE playerId = $playerid";
                      mysqli_query($GLOBALS['con'],$sql3);

                      $sql3 = "DELETE FROM userplayer WHERE playerId = $playerid";
                      mysqli_query($GLOBALS['con'],$sql3);
                      
                      $i++;
                }
                mysqli_free_result($res);
             
		
	}
	echo "Deactivated $i players<br/>";
}


include('admin.head.php');




?>
<h1>Delete players</h1>
<form action="delJucatoriEchipa.php" method="POST">
<select name="echipe[]" multiple size="12">

	<?php
		$sql = "SELECT a.id, a.TeamName, a.LastActive, a.LeagueID, b.name
				FROM user a
				LEFT JOIN competition b
				on a.LeagueID=b.id
				WHERE a.activated=1
				ORDER BY a.LastActive DESC";
		$res = mysqli_query($GLOBALS['con'],$sql);
		while(list($id, $echipa,$activ,$ligaid,$liganume) = mysqli_fetch_row($res)) {
			echo "<option value=\"$id\">$echipa($activ) - $liganume";
		}
		mysqli_free_result($res);
	?>
</select>
<input type="Submit" name="Trimite" value="Process">
</form>
