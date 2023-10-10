<?php
error_reporting(63);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

include('admin.head.php');

if(!empty($_REQUEST['Send'])) {
	
	for($i=0;$i<$_REQUEST['nrechipe'];$i++) {
		generateTeam();
	}
		
}

function generateTeam() {
		$teams = array('Atletico Madrid', 'Athletic Bilbao', 'Real Sociedad', 'Real Madrid', 
						'FC Barcelona', 'Espanyol', 'Real Zaragoza', 'Elche', 'Valencia', 'Valladolid',
						'Sevilla', 'Real Burgos', 'Deportivo la Coruna', 'Villareal', 'Assuncion', 'Cadiz', 
						'Real Mallorca', 'Cordoba', 'Compostela', 'Real Sevilla', 'FC Barca', 'Real', 'Monnas', 
						'Albacette', 'Cotrina', 'Celta Vigo', 'Costa Brava', 'Cotoni');
		$nr = rand(1,28);
		
		$bot = rand(0,1000000);
	
		$name = 'bot'.$bot;
		$stadiumname = $name. ' Arena';
		$teamname = $teams[$nr].$bot;
		$username = $name;
		$password = 'botpassword'.rand(0,1000000);
		$email = $name.'@myfm.com';

		echo "<br/>Generated team: $teamname<br/>";
		$country = $_REQUEST['country'];

		$user = new user();
		
		$user->CreateTeam($teamname, $stadiumname, $username, $password, $email, '');
		
		//$user->EchoClub();



		//Initial team
		//3 GKs
		//5 defenders - any kind (can all be DL)
		//6 midfielders - any kind (can all be MC)
		//4 attackers -any kind (can all be FR)
		//5 trainers unallocated to the team

		$young = 0;
		$coeficient_liga = 1;

		//GKs
		for($i=0;$i<3;$i++) {
			$poz = 1;
			//here comes the definition
			$den = "juc".$i;
			$$den = new Player($user->ReturnID(), 0, $country, $young, $poz, $coeficient_liga);
			//$$den->EchoPlayer();
		}

		//defenders
		for($i=0;$i<5;$i++) {
			$poz = rand(2,4);
			//here comes the definition
			$den = "juc".$i;
			$$den = new Player($user->ReturnID(), 0, $country, $young, $poz, $coeficient_liga);
			//$$den->EchoPlayer();
		}

		//midfielders
		for($i=0;$i<6;$i++) {
			$poz = rand(5,7);
			//here comes the definition
			$den = "juc".$i;
			$$den = new Player($user->ReturnID(), 0, $country, $young, $poz, $coeficient_liga);
			//$$den->EchoPlayer();
		}

		//attackers
		for($i=0;$i<4;$i++) {
			$poz = rand(8,10);
			//here comes the definition
			$den = "juc".$i;
			$$den = new Player($user->ReturnID(), 0, $country, $young, $poz, $coeficient_liga);
			//$$den->EchoPlayer();
		}

		$young = 1;
		$coeficient_liga = 1;

		//GKs
		for($i=0;$i<3;$i++) {
			$poz = 1;
			//here comes the definition
			$den = "juc".$i;
			$$den = new Player($user->ReturnID(), 0, $country, $young, $poz, $coeficient_liga);
			//$$den->EchoPlayer();
		}

		//defenders
		for($i=0;$i<5;$i++) {
			$poz = rand(2,4);
			//here comes the definition
			$den = "juc".$i;
			$$den = new Player($user->ReturnID(), 0, $country, $young, $poz, $coeficient_liga);
			//$$den->EchoPlayer();
		}

		//midfielders
		for($i=0;$i<6;$i++) {
			$poz = rand(5,7);
			//here comes the definition
			$den = "juc".$i;
			$$den = new Player($user->ReturnID(), 0, $country, $young, $poz, $coeficient_liga);
			//$$den->EchoPlayer();
		}

		//attackers
		for($i=0;$i<4;$i++) {
			$poz = rand(8,10);
			//here comes the definition
			$den = "juc".$i;
			$$den = new Player($user->ReturnID(), 0, $country, $young, $poz, $coeficient_liga);
			//$$den->EchoPlayer();
		}


		
		/*
		for($i=0;$i<5;$i++) {
			//here comes the definition
			$den = "antrenor".$i;
			$Liga = 1 - $i/10;
			$$den = new Trainer($Liga);
			//$$den->EchoTrainer();
		}
*/

		//generate default line-up
		//  4-4-2
		
		$pgroup=1;
		
		$gk=$defenders=$midfielders=$attackers=0;
		$sql = "SELECT a.playerid, a.userid 
				FROM userplayer a
				LEFT JOIN player b
				ON a.playerid=b.id
				WHERE b.youth=0 AND a.userid=".$user->ReturnID();
		echo "$sql<br/>";
		$res = mysqli_query($GLOBALS['con'],$sql);
		$valini = 0;
		$def = array();
		$midf = array();
		$atac = array();
		//i take all the players and i put their values in a table
		//i order the table and take 4-4-2
		$iduser = $user->ReturnID();
		while(list($pid, $uid) = mysqli_fetch_row($res)) {
			$player = new Player($uid, $pid);
			//print_r($player);
			switch($player->Position) {
				case 1: $val = $player->GetDFWork();
						if($val>$valini) $gk = $pid;
						break;
				case 2:
				case 3:
				case 4: $val = $player->GetDFWork();
						$def[$pid] = $val;
						break;
				case 5:
				case 6:
				case 7:
						$val = $player->GetMFWork();
						$midf[$pid] = $val;
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
		$update = "UPDATE lineup SET post=1, playerId=$gk, userId=$iduser, pgroup=1 WHERE playerId=$gk AND userId=$iduser"; 
		echo "$update<br/>";
		mysqli_query($GLOBALS['con'],$update);


		arsort($def);
		arsort($midf);
		arsort($atac);
		$i=0;
		//lineup(post, player, user)
		while(list($k,$v) = each($def)) {
			switch($i) {
				//primul fundas lateral, urmatorii centrali, ultimul lateral
				case 0: $post=2; break;
				case 1: $post=3; break;
				case 2: $post=3; break;
				case 3: $post=4; break;
			}
			$insert = "INSERT INTO lineup(post, playerId, userId, pgroup)
					   VALUES($post, $k, $iduser, $pgroup)";
			$insert = "UPDATE lineup SET post=$post, playerId=$k, userId=$iduser, pgroup=$pgroup WHERE playerId=$k AND userId=$iduser"; 

		   echo "$insert<br/>";
			mysqli_query($GLOBALS['con'],$insert);
			$i++;
			if($i==4) break;
		}

		$i=0;
		//lineup(post, player, user)
		while(list($k,$v) = each($midf)) {
			switch($i) {
				//primul fundas lateral, urmatorii centrali, ultimul lateral
				case 0: $post=5; break;
				case 1: $post=6; break;
				case 2: $post=6; break;
				case 3: $post=7; break;
			}
			$insert = "INSERT INTO lineup(post, playerId, userId, pgroup)
					   VALUES($post, $k, $iduser, $pgroup)";
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
			$insert = "UPDATE lineup SET post=$post, playerId=$k, userId=$iduser WHERE playerId=$k AND userId=$iduser"; 
			echo "$insert<br/>";
			mysqli_query($GLOBALS['con'],$insert);
			$i++;
			if($i==2) break;
		}
		//end of lineup construction
		

		//calculare rating
		$user->ComputeTeamValues(1);
		$rating = ($user->Vfw + $user->Vdf+ $user->Vmf)/30;
		$sql = "UPDATE user
				SET rating = $rating
				WHERE id=". $user->ReturnID();
		mysqli_query($GLOBALS['con'],$sql);


		
				//youth team
		// o sa fie 4-4-2
		$pgroup=2;
		
		$gk=$defenders=$midfielders=$attackers=0;
		$sql = "SELECT a.playerid, a.userid 
				FROM userplayer a
				LEFT JOIN player b
				ON a.playerid=b.id
				WHERE b.youth=1 AND a.userid=".$user->ReturnID();
		echo "$sql<br/>";
		$res = mysqli_query($GLOBALS['con'],$sql);
		$valini = 0;
		$def = array();
		$midf = array();
		$atac = array();
		//iau toti jucatorii si le bag valorile intr-un tabel cu valori
		//ordonez tabelele in functie de valori si preiau 4-4-2
		$iduser = $user->ReturnID();
		while(list($pid, $uid) = mysqli_fetch_row($res)) {
			$player = new Player($uid, $pid);
			switch($player->Position) {
				case 1: $val = $player->GetDFWork();
						if($val>$valini) $gk = $pid;
						break;
				case 2:
				case 3:
				case 4: $val = $player->GetDFWork();
						$def[$pid] = $val;
						break;
				case 5:
				case 6:
				case 7:
						$val = $player->GetMFWork();
						$midf[$pid] = $val;
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
		$update = "UPDATE lineup SET post=1, playerId=$gk, userId=$iduser, pgroup=2 WHERE playerId=$gk AND userId=$iduser"; 
		echo "$update<br/>";
		mysqli_query($GLOBALS['con'],$update);


		arsort($def);
		arsort($midf);
		arsort($atac);
		$i=0;
		//lineup(post, player, user)
		while(list($k,$v) = each($def)) {
			switch($i) {
				//first, side defender, then central, then side again
				case 0: $post=2; break;
				case 1: $post=3; break;
				case 2: $post=3; break;
				case 3: $post=4; break;
			}
			$insert = "INSERT INTO lineup(post, playerId, userId, pgroup)
					   VALUES($post, $k, $iduser, $pgroup)";
			$insert = "UPDATE lineup SET post=$post, playerId=$k, userId=$iduser, pgroup=$pgroup WHERE playerId=$k AND userId=$iduser"; 

		   echo "$insert<br/>";
			mysqli_query($GLOBALS['con'],$insert);
			$i++;
			if($i==4) break;
		}

		$i=0;
		//lineup(post, player, user)
		while(list($k,$v) = each($midf)) {
			switch($i) {
				
				case 0: $post=5; break;
				case 1: $post=6; break;
				case 2: $post=6; break;
				case 3: $post=7; break;
			}
			$insert = "INSERT INTO lineup(post, playerId, userId, pgroup)
					   VALUES($post, $k, $iduser, $pgroup)";
			$insert = "UPDATE lineup SET post=$post, playerId=$k, userId=$iduser, pgroup=$pgroup WHERE playerId=$k AND userId=$iduser"; 

			echo "$insert<br/>";
			mysqli_query($GLOBALS['con'],$insert);
			$i++;
			if($i==8) break;
		}

				$i=0;
		//lineup(post, player, user)
		while(list($k,$v) = each($atac)) {
			switch($i) {
				case 0: $post=9; break;
				case 1: $post=9; break;
			}
			$insert = "INSERT INTO lineup(post, playerId, userId, pgroup)
					   VALUES($post, $k, $iduser, $pgroup)";
			$insert = "UPDATE lineup SET post=$post, playerId=$k, userId=$iduser WHERE playerId=$k AND userId=$iduser"; 
			echo "$insert<br/>";
			mysqli_query($GLOBALS['con'],$insert);
			$i++;
			if($i==2) break;
		}
		//end youth lineup

		
		
		
		mysqli_free_result($res);
		
}

?>
<h1>Generate Bot-team</h1>
<form action="" methos="POST">
Country: <select name = "country">
<?php
$sql = "SELECT id, name FROM country ORDER BY name ASC";
$res = mysqli_query($GLOBALS['con'], $sql);
while(list($countryid, $name) = mysqli_fetch_array($res)) {
	echo "<option value=\"$countryid\">$name</option>";
}
mysqli_free_result($res);
?>
</select>
<br/>
No. of teams: <input type="text" name="nrechipe" size="10">
<br/>
<input type="Submit" name="Send" value="Create teams">
</form>
