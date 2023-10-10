<?php
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

include('admin.head.php');

if (isset($_REQUEST['StabilesteEchipa'])) {
		//generare echipa de start default
		// o sa fie 4-4-2
		
		$sql = "DELETE from lineup WHERE userid=".$_REQUEST['useri'];
		echo "$sql<br/>";
		mysqli_query($GLOBALS['con'],$sql);
		
		$pgroup=1;
		
		$gk=$fundasi=$mijlocasi=$atacanti=0;
		$sql = "SELECT a.playerid, a.userid 
				FROM userplayer a
				LEFT JOIN player b
				ON a.playerid=b.id
				WHERE b.youth=0 AND a.userid=".$_REQUEST['useri'];
		echo "$sql<br/>";
		$res = mysqli_query($GLOBALS['con'],$sql);
		$valini = 0;
		$fund = array();
		$mijl = array();
		$atac = array();
		//iau toti jucatorii si le bag valorile intr-un tabel cu valori
		//ordonez tabelele in functie de valori si preiau 4-4-2
		$iduser = $_REQUEST['useri'];
		while(list($pid, $uid) = mysqli_fetch_row($res)) {
			$player = new Player($uid, $pid);
			switch($player->Position) {
				case 1: $val = $player->GetDFWork();
						if($val>$valini) $gk = $pid;
						break;
				case 2:
				case 3:
				case 4: $val = $player->GetDFWork();
						$fund[$pid] = $val;
						break;
				case 5:
				case 6:
				case 7:
						$val = $player->GetMFWork();
						$mijl[$pid] = $val;
						break;
				case 8:
				case 9:
				case 10:
						$val = $player->GetFWWork();
						$atac[$pid] = $val;
						break;
				
			}
			
		}
		//portarul
		$insert = "INSERT INTO lineup(post, playerId, userId, pgroup)
				   VALUES(1, $gk, $iduser, 1)";
		mysqli_query($GLOBALS['con'],$insert);
		$update = "UPDATE lineup SET post=1, playerId=$gk, userId=$iduser, pgroup=1 WHERE playerId=$gk AND userId=$iduser"; 
		echo "$update<br/>";
		mysqli_query($GLOBALS['con'],$update);


		arsort($fund);
		arsort($mijl);
		arsort($atac);
		$i=0;
		//lineup(post, player, user)
		while(list($k,$v) = each($fund)) {
			switch($i) {
				//primul fundas lateral, urmatorii centrali, ultimul lateral
				case 0: $post=2; break;
				case 1: $post=3; break;
				case 2: $post=3; break;
				case 3: $post=4; break;
			}
			$insert = "INSERT INTO lineup(post, playerId, userId, pgroup)
					   VALUES($post, $k, $iduser, $pgroup)";
		mysqli_query($GLOBALS['con'],$insert);
			$insert = "UPDATE lineup SET post=$post, playerId=$k, userId=$iduser, pgroup=$pgroup WHERE playerId=$k AND userId=$iduser"; 

		   echo "$insert<br/>";
			mysqli_query($GLOBALS['con'],$insert);
			$i++;
			if($i==4) break;
		}

		$i=0;
		//lineup(post, player, user)
		while(list($k,$v) = each($mijl)) {
			switch($i) {
				//primul fundas lateral, urmatorii centrali, ultimul lateral
				case 0: $post=5; break;
				case 1: $post=6; break;
				case 2: $post=6; break;
				case 3: $post=7; break;
			}
			$insert = "INSERT INTO lineup(post, playerId, userId, pgroup)
					   VALUES($post, $k, $iduser, $pgroup)";
		mysqli_query($GLOBALS['con'],$insert);
			$insert = "UPDATE lineup SET post=$post, playerId=$k, userId=$iduser, pgroup=$pgroup WHERE playerId=$k AND userId=$iduser"; 

			echo "$insert<br/>";
			mysqli_query($GLOBALS['con'],$insert);
			$i++;
			if($i==4) break;
		}

				$i=0;
		//lineup(post, player, user)
		while(list($k,$v) = each($atac)) {
			switch($i) {
				//primul fundas lateral, urmatorii centrali, ultimul lateral
				case 0: $post=9; break;
				case 1: $post=9; break;
			}
			$insert = "INSERT INTO lineup(post, playerId, userId, pgroup)
					   VALUES($post, $k, $iduser, $pgroup)";
		mysqli_query($GLOBALS['con'],$insert);
			$insert = "UPDATE lineup SET post=$post, playerId=$k, userId=$iduser, pgroup=$pgroup WHERE playerId=$k AND userId=$iduser"; 
			echo "$insert<br/>";
			mysqli_query($GLOBALS['con'],$insert);
			$i++;
			if($i==2) break;
		}
		//final generare echipa de baza
		
		
				//generare echipa de start tineret
		// o sa fie 4-4-2
		$pgroup=2;
		
		$gk=$fundasi=$mijlocasi=$atacanti=0;
		$sql = "SELECT a.playerid, a.userid 
				FROM userplayer a
				LEFT JOIN player b
				ON a.playerid=b.id
				WHERE b.youth=1 AND a.userid=".$_REQUEST['useri'];
		echo "$sql<br/>";
		$res = mysqli_query($GLOBALS['con'],$sql);
		$valini = 0;
		$fund = array();
		$mijl = array();
		$atac = array();
		//iau toti jucatorii si le bag valorile intr-un tabel cu valori
		//ordonez tabelele in functie de valori si preiau 4-4-2
		$iduser = $_REQUEST['useri'];
		while(list($pid, $uid) = mysqli_fetch_row($res)) {
			$player = new Player($uid, $pid);
			switch($player->Position) {
				case 1: $val = $player->GetDFWork();
						if($val>$valini) $gk = $pid;
						break;
				case 2:
				case 3:
				case 4: $val = $player->GetDFWork();
						$fund[$pid] = $val;
						break;
				case 5:
				case 6:
				case 7:
						$val = $player->GetMFWork();
						$mijl[$pid] = $val;
						break;
				case 8:
				case 9:
				case 10:
						$val = $player->GetFWWork();
						$atac[$pid] = $val;
						break;
				
			}
			
		}
		//portarul
		$insert = "INSERT INTO lineup(post, playerId, userId, pgroup)
				   VALUES(1, $gk, $iduser, 2)";
		mysqli_query($GLOBALS['con'],$insert);
		$update = "UPDATE lineup SET post=1, playerId=$gk, userId=$iduser, pgroup=2 WHERE playerId=$gk AND userId=$iduser"; 
		echo "$update<br/>";
		mysqli_query($GLOBALS['con'],$update);


		arsort($fund);
		arsort($mijl);
		arsort($atac);
		$i=0;
		//lineup(post, player, user)
		while(list($k,$v) = each($fund)) {
			switch($i) {
				//primul fundas lateral, urmatorii centrali, ultimul lateral
				case 0: $post=2; break;
				case 1: $post=3; break;
				case 2: $post=3; break;
				case 3: $post=4; break;
			}
			$insert = "INSERT INTO lineup(post, playerId, userId, pgroup)
					   VALUES($post, $k, $iduser, $pgroup)";
		mysqli_query($GLOBALS['con'],$insert);
			$insert = "UPDATE lineup SET post=$post, playerId=$k, userId=$iduser, pgroup=$pgroup WHERE playerId=$k AND userId=$iduser"; 

		   echo "$insert<br/>";
			mysqli_query($GLOBALS['con'],$insert);
			$i++;
			if($i==4) break;
		}

		$i=0;
		//lineup(post, player, user)
		while(list($k,$v) = each($mijl)) {
			switch($i) {
				//primul fundas lateral, urmatorii centrali, ultimul lateral
				case 0: $post=5; break;
				case 1: $post=6; break;
				case 2: $post=6; break;
				case 3: $post=7; break;
			}
			$insert = "INSERT INTO lineup(post, playerId, userId, pgroup)
					   VALUES($post, $k, $iduser, $pgroup)";
		mysqli_query($GLOBALS['con'],$insert);
			$insert = "UPDATE lineup SET post=$post, playerId=$k, userId=$iduser, pgroup=$pgroup WHERE playerId=$k AND userId=$iduser"; 

			echo "$insert<br/>";
			mysqli_query($GLOBALS['con'],$insert);
			$i++;
			if($i==4) break;
		}

				$i=0;
		//lineup(post, player, user)
		while(list($k,$v) = each($atac)) {
			switch($i) {
				//primul fundas lateral, urmatorii centrali, ultimul lateral
				case 0: $post=9; break;
				case 1: $post=9; break;
			}
			$insert = "INSERT INTO lineup(post, playerId, userId, pgroup)
					   VALUES($post, $k, $iduser, $pgroup)";
		mysqli_query($GLOBALS['con'],$insert);
			$insert = "UPDATE lineup SET post=$post, playerId=$k, userId=$iduser, pgroup=$pgroup WHERE playerId=$k AND userId=$iduser"; 
			echo "$insert<br/>";
			mysqli_query($GLOBALS['con'],$insert);
			$i++;
			if($i==2) break;
		}
		//final generare echipa de baza
}

?>
<h1>Set first 11</h1>
<form action="setFirst11.php" method="POST">
<?php
$sql = "SELECT id, username, teamname FROM user WHERE activated=1 ORDER BY LastActive DESC";
$ruser = mysqli_query($GLOBALS['con'],$sql);
echo "<select name=\"useri\">";
while(list($uid,$uname,$tname) = mysqli_fetch_row($ruser)) {
	echo "<option value=\"$uid\">$tname - $uname";
}
echo "</select>";
mysqli_free_result($ruser);
?>
<br/>
<input type="Submit" name="StabilesteEchipa" value="Set the lineup">
</form>