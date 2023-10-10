<?php
include('search1.php'); 

//var_dump($_SESSION['query']));

?>
<h2>Echipe</h2>
<br/>
<div>
<table class="tftable"  width="800" cellpadding="1">
					<tr>
						<th></th>
						<th><font color="<?php echo $gk; ?>">Team</font></th>
						<th><font color="<?php echo $gk; ?>">Manager</font></th>
						<th><font color="<?php echo $gk; ?>">Rating</font></th>
						<th><font color="<?php echo $gk; ?>">Active on</font></th>
						<th><font color="<?php echo $gk; ?>">Friendly</font></th>
					</tr>


<?php
//echo "aici sunt";
$result = $_SESSION['query'];
//var_dump($result);
foreach($result as $key=>$value)  {
			//a.id, a.TeamName, a.Username, b.name, a.LeagueID, a.Rating, a.LastActive
			echo '<tr>';
			echo '<td></td>';
			echo "<td>".$value[1]."</td>"; echo '<td>'.$value[2].'</td>'; echo '<td>'.$value[5].'</td>'; echo '<td>'.$value[6].'</td>';
			echo "<td><a href=\"index.php?option=amical&club_id=".$result['id']."\" class=\"link-3\"><img src=\"images/playgame.png\" border=\"0\" width=\"25\"></a></td>";			
			echo '</tr>';
            //echo '<p>'.$key.' - '.$value.'</p>';
        }
?>
</table>
</div>