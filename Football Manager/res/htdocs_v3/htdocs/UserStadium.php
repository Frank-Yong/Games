<?php
class user {
	public $UserID;
	private $TeamName;
	public $StadiumID;
	private $Rating;
	private $ManagerName;
	private $Status;
	public $LastActive;
	public $LeagueID;
	public $LeagueName;

	private $Funds;

	private $Username;
	private $Password;
	private $Email;
	private $pic;

	private $CountryID;
	private $Language;

	public $Vfw;
	public $Vmf;
	public $Vdf;

	private $activationkey;
	private $activated;
	private $botteam;
	
	private $Moral;
	public $Online; //cimp din useronline
	

		public function CreateTeam($teamname, $stadiumname, $username, $password, $email, $activationkey) {
		$this->Rating=10;
		$this->TeamName = $teamname;
		$this->Username = $username;
		$this->Password = $password;
		$this->Email = $email;
		if($activationkey == '') {
			//botteam - activation not needed
			$activated=1;
			$botteam=1;
		} else {
			$activated=0;
			$botteam=0;
		}
		$this->activationkey = $activationkey;
		$this->activated = $activated;
		$this->botteam = $botteam;

		$stadium = new Stadium(0,$stadiumname);
		$this->StadiumID = $stadium->ReturnID();

		$this->WriteDataSmall();

	}

	public function LoginID($userid) {
		
		$_SESSION['badlogin'] = 0;
		$sql = "SELECT a.id, a.TeamName, a.Username, a.Rating, a.Funds, c.competitionid, a.StadiumID, a.LastActive, b.name, a.pic, a.Moral 
				FROM user a
				LEFT JOIN leagueuser c
				ON a.id=c.userid AND c.season=".$_SESSION['_SEASON'] .
				" LEFT JOIN competition b
				ON c.competitionid=b.id
				WHERE a.id=$userid" ;
		//echo "$sql<br/>";
		$res = mysqli_query($GLOBALS['con'], $sql);
		list($user_id, $team_name, $username, $rating, $funds, $lid, $StadiumID, $lastactive, $leaguename, $pic, $mor) = mysqli_fetch_row($res);
		//echo "$user_id, $team_name, $username, $rating, $funds, $lid, $StadiumID, $lastactive, $leaguename, $pic, $mor<br/><br/>";
		mysqli_free_result($res);

		if ($user_id > 0) {
			$this->UserID = $user_id;
			$this->TeamName = $team_name;
			$this->Username = $username;
			$this->Rating = $rating;
			$this->Funds = $funds;
			$this->LeagueID = $lid;
			$this->StadiumID = $StadiumID;
			$this->LastActive = $lastactive;
			$this->LeagueName = $leaguename;
			$this->Moral = $mor;
			$this->poza = $pic;
			$_SESSION['USERID'] = $this->UserID;
			$_SESSION['STADIUMID'] = $this->StadiumID;
			$_SESSION['poza'] = $this->poza;
			
			//$sql = "SELECT online FROM useronline WHERE userid=".$this->UserID;
			//echo "$sql<br/>";
		    //$res = mysqli_query($GLOBALS['con'], $sql);
			//list($online) = mysqli_fetch_row($res);
			//$this->Online=$online;
			//mysqli_free_result($res);
		} else {
			$_SESSION['_MESSAGE'] =  "Updating...";
			$_SESSION['badlogin'] = "aaaaa";
			
		}
	}


	public function Login($username, $password) {
		$_SESSION['badlogin'] = 0;

		$user_id = -1;
		$sql = "SELECT a.id, a.TeamName, a.Username, a.Rating, a.Funds, a.StadiumID, a.email, a.LastActive, a.activated, b.name, a.pic, a.Moral
				FROM user a
				LEFT JOIN leagueuser c
				ON a.id=c.userid  AND c.season=".$_SESSION['_SEASON'].
				" LEFT JOIN competition b
				ON c.competitionid=b.id
				WHERE a.username='$username' AND a.password='$password'";
		$res = mysqli_query($GLOBALS['con'], $sql);
		list($user_id, $team_name, $username, $rating, $funds, $StadiumID, $email, $lastactive, $activated, $leaguename, $pic, $mor) = mysqli_fetch_row($res);
		mysqli_free_result($res);
		if ($user_id > 0) {
			if($activated == 1) {
				$this->UserID = $user_id;
				$this->TeamName = $team_name;
				$this->Username = $username;
				$this->Email = $email;
				$this->Rating = $rating;
				$this->Funds = $funds;
				$this->StadiumID = $StadiumID;
				$this->poza = $pic;
				$this->Moral = $mor;
				$_SESSION['USERID'] = $this->UserID;
				$_SESSION['STADIUMID'] = $this->StadiumID;
				$this->LastActive = Date("Y-m-d H:i:s");
				$this->UpdateValue('LastActive', $this->LastActive);
				
				$this->Online = 1;		
				$this->LeagueName = $leaguename;
				$sql = "UPDATE useronline SET online=1 WHERE userid=$user_id";
				mysqli_query($GLOBALS['con'], $sql);
				//echo "The value of USERID is ".$this->UserID." and this is the sql $sql<br/>";
				
			} else {
				$_SESSION['_MESSAGE'] = 'Account is not activated! Please check your email (inclusive Spam/Junk)';
			}
		} else {
			$_SESSION['_MESSAGE'] = "Updating...!!!";
			$_SESSION['badlogin'] = 1;
		}
	}

	public function EchoPlayerSolo($pid) {
		$pl = new Player($_SESSION['USERID'], $pid);
		$pl->EchoPlayer();
	}


	private function UpdateValue($key, $value) {
		$sql = "UPDATE user SET $key='".$value."' WHERE id=".$this->UserID;
		mysqli_query($GLOBALS['con'], $sql);
	}
	

		private function WriteDataSmall() {
		$sql = "INSERT INTO user(Username, TeamName, StadiumID, Password, Funds, email, activationkey,CountryID, Rating, botteam, activated) 
		VALUES ('" .$this->Username . "', '" . $this->TeamName . "', " . $this->StadiumID . ", '" . 
		$this->Password . "', 1000000, '".$this->Email."', '".$this->activationkey."', 3, ".$this->Rating.",".$this->botteam.",".$this->activated.")";
		//echo "$sql<br>";
		$res = mysqli_query($GLOBALS['con'], $sql);
		$this->UserID = mysqli_insert_id($GLOBALS['con']);

		$sql = "INSERT INTO leagueuser (userid, competitionid, season)
				VALUES(".$this->UserID.",0,".$_SESSION['_SEASON'].")";
				//echo "$sql<br/>";
		mysqli_query($GLOBALS['con'], $sql);
		}


	public function ReturnID() {
		//echo "The user in the method ReturnID is ". $this->UserID ." !!!!<br/>";
		return $this->UserID;
	}
	
	public function GetManagerName() {
    	return $this->Username;
	}
	
	public function GetRating() {
		return $this->Rating;
	}
	
	
	public function EchoClub() {
	echo "User ID: " . $this->UserID.'';
	echo "<div class=\"hr-replace\"></div>";
	$s = new Stadium($this->StadiumID);
	$numestad = $s->ReturnStadiumName();
	echo "Stadium: " . $numestad;
	echo "<div class=\"hr-replace\"></div>";
	$liga = $this->LeagueID == 0? 'none': $this->LeagueName;
	echo "League: " . $liga."<br/>";
	$activ = $this->Online == 1 ? 'Active':'Not active';
	//echo "Online: $activ</br/>"; 
	echo "Online last time: " . $this->LastActive;
	echo "<div class=\"hr-replace\"></div>";
	if($_SESSION['USERID'] == $this->UserID)
		echo "<b>Account: " . number_format($this->Funds) . " &euro;</b>";

	
	}

	
	public function ComputeTeamValues($pgroup=1) {
		//attacking value
		// = Maxim(attackers)+ 0.7*Medium(attackers) + AttackingValueMidfielders * .4 + AttackingValueDefenders * .4
		$Vof = 0;
		$Vdef = 0;
		$Vmidfiel = 0;
		$fw_max = 0;
		$fw_medie = 0;
		$fw_index=0;



		//trebuie sa aduc parametru = la ce liga se joaca! pentru tineret si seniori
		//b.pgroup=1 (seniori) sau b.pgroup=2 (tineret)
		
		$sql = "SELECT b.PlayerId, c.Position, b.post, d.tactics, d.midfield, d.atacks, d.passes
				FROM user a
				LEFT OUTER JOIN lineup b 
				ON b.UserId = a.id
				LEFT OUTER JOIN player c 
				ON c.id = b.PlayerId
				LEFT OUTER JOIN tactics d
				ON a.id=d.userid
				WHERE b.pgroup=$pgroup AND b.post<>0 AND a.id = ". $this->UserID;
		$res = mysqli_query($GLOBALS['con'], $sql);
		//echo "$sql<br/>";
		$defenders = 1;
		$midfielders = 1;
		$attackers = 1;
		
		while(list($p_id, $p_position, $heisusedlike, $tactics, $midfield, $atacks, $passes) = mysqli_fetch_row($res)) {
			//echo "i'm right here<br/></br>";
			$pl = new Player($this->UserID, $p_id);
			//$pl->EchoPlayer();
			//take in consideration the position of the player and also on what position is he playing
			//if he is DC and he plays DL, subtract 10% from his efficiency
			//if he is DC and he plays MC, subtract 30% 
			//if he is DC and he plays FC, subtract 60% 
			//if he is DC and he plays GK, subtract 60%
			$df = $pl->GetDFWork();
			$mf = $pl->GetMFWork();
			$fw = $pl->GetFWWork();

			if(!$tactics) $tactics=1;
			if(!$midfield) $midfield=1;
			if(!$atacks) $atacks=1;
			if(!$passes) $passes=1;
			
			if($midfield == 1) {
				//normal, unchanged
			}
			if($midfield == 2) {
				//attacking style
				//so, lower values for defending and bigger for attacking
				$df = $df *.92;
				$mf = $mf * 1.08;
				$fw = $fw * 1.13;
			}
			if($midfield == 3) {
				//defensive style
				//increase defending values, decrease attacking values
				$df = $df *1.17;
				$mf = $mf * 0.98;
				$fw = $fw * 0.93;
			}
			
			if($p_position != $heisusedlike) {
				//not on his position
				switch($p_position) {
					case 1: 
						//is GK and he plays on the pitch
						if($heisusedlike>1) {
						$df = $df*.3;
						$mf = $mf*.15;
						$fw = $fw*.1;
						}
						break;
					case 2:
						//side defender
						switch($heisusedlike) {
							case 1: $df = $df*.3;$mf=0;$fw=0;break;
							case 3: $df = $df*.85; break;
							case 4: $df = $df*.9; break;
							case 5: 
							case 6:
							case 7:
							$df = $df*.4; $mf = $mf*1.25; $fw = $fw *1.15; break;
							case 8:
							case 9:
							case 10:
							$df = $df*.2; $mf = $mf*1.1; $fw = $fw * 1.25; break;
						}					
						break;
					case 3:
						//e fundas central
						switch($heisusedlike) {
							case 1: $df = $df*.3;$mf=0;$fw=0;break;
							case 2: $df = $df*.9; break;
							case 4: $df = $df*.9; break;
							case 5: 
							case 6:
							case 7:
							$df = $df*.4; $mf = $mf*1.25; $fw = $fw *1.15; break;
							case 8:
							case 9:
							case 10:
							$df = $df*.2; $mf = $mf*1.1; $fw = $fw * 1.25; break;
						}					
						break;
					case 4:
						//e fundas lateral
						switch($heisusedlike) {
							case 1: $df = $df*.3;$mf=0;$fw=0;break;
							case 2: $df = $df*.9; break;
							case 3: $df = $df*.85; break;
							case 5: 
							case 6:
							case 7:
							$df = $df*.4; $mf = $mf*1.25; $fw = $fw *1.15; break;
							case 8:
							case 9:
							case 10:
							$df = $df*.2; $mf = $mf*1.1; $fw = $fw * 1.25; break;
						}					
						break;
					case 5:
						//e mijlocas lateral
						switch($heisusedlike) {
							case 1: $df = $df*.2;$mf=0;$fw=0;break;
							case 2: 
							case 3: 
							case 4:
									$df = $df * 1.25; $mf = $mf*.85; $fw = $fw * .9; break;
							case 6:
									$df = $df *.95; $mf = $mf * .9; break;
							case 7:
									$df = $df*.95; $mf = $mf *.95; break;
							case 8:
							case 9:
							case 10:
								$df = $df*.6; $mf = $mf*0.7; $fw = $fw * 1.25; break;
						}					
						break;
					case 6:
						//e mijlocas central
						switch($heisusedlike) {
							case 1: $df = $df*.2;$mf=0;$fw=0;break;
							case 2: 
							case 3: 
							case 4:
									$df = $df * 1.25; $mf = $mf*.85; $fw = $fw * .9; break;
							case 5:
									$df = $df *.95; $mf = $mf * .95; break;
							case 7:
									$df = $df*.95; $mf = $mf *.95; break;
							case 8:
							case 9:
							case 10:
								$df = $df*.6; $mf = $mf*0.7; $fw = $fw * 1.25; break;
						}					
						break;
					case 7:
						//e mijlocas lateral
						switch($heisusedlike) {
							case 1: $df = $df*.2;$mf=0;$fw=0;break;
							case 2: 
							case 3: 
							case 4:
									$df = $df * 1.25; $mf = $mf*.85; $fw = $fw * .9; break;
							case 6:
									$df = $df *.95; $mf = $mf * .9; break;
							case 5:
									$df = $df*.95; $mf = $mf *.95; break;
							case 8:
							case 9:
							case 10:
								$df = $df*.6; $mf = $mf*0.7; $fw = $fw * 1.25; break;
						}					
						break;
					case 8:
						//e atacant lateral
						switch($heisusedlike) {
							case 1: $df = $df*.2;$mf=0;$fw=0;break;
							case 2: 
							case 3: 
							case 4:
									$df = $df * 1.25; $mf = $mf*.65; $fw = $fw * .35; break;
							case 5:
							case 6:
							case 7:
									$df = $df*1.15; $mf = $mf *1.35; $fw = $fw *.5; break;
							case 9:
							case 10:
									$df = $df*.95; $mf = $mf*0.95; $fw = $fw * 0.9; break;
						}					
						break;
					case 9:
						//e atacant central
						switch($heisusedlike) {
							case 1: $df = $df*.2;$mf=0;$fw=0;break;
							case 2: 
							case 3: 
							case 4:
									$df = $df * 1.25; $mf = $mf*.65; $fw = $fw * .35; break;
							case 5:
							case 6:
							case 7:
									$df = $df*1.15; $mf = $mf *1.35; $fw = $fw *.5; break;
							case 8:
							case 10:
									$df = $df*.95; $mf = $mf*0.95; $fw = $fw * 0.9; break;
						}					
						break;
					case 10:
						//e atacant lateral
						switch($heisusedlike) {
							case 1: $df = $df*.2;$mf=0;$fw=0;break;
							case 2: 
							case 3: 
							case 4:
									$df = $df * 1.25; $mf = $mf*.65; $fw = $fw * .35; break;
							case 5:
							case 6:
							case 7:
									$df = $df*1.15; $mf = $mf *1.35; $fw = $fw *.5; break;
							case 8:
							case 9:
									$df = $df*.95; $mf = $mf*0.95; $fw = $fw * 0.9; break;
						}					
						break;
				}
				
			}
			
			$Vdef = $Vdef + $df;
			$Vmidfiel = $Vmidfiel + $mf;

			//if he is attacker, take maximum and make the average
			if ($p_position == 8 or $p_position == 9 or $p_position == 10) {
				$fw_max = max($fw_max,$fw);
				$fw_medie = $fw_medie+$fw;
				$fw_index++;
			} else {
				$Vof = $Vof + $fw*.4;
			}
				
		}
		$fw_medie = $fw_medie/$fw_index;
		$Vof = 1.95*($Vof + $fw_max + 0.7*$fw_medie);

		$this->Vfw = $Vof;
		$this->Vmf = $Vmidfiel;
		$this->Vdf = $Vdef;

		echo "Attacking value: $Vof";
		echo "<br/>Midfield value: $Vmidfiel";
		echo "<br/>Defensive value: $Vdef";
		mysqli_free_result($res);
	}
	
		public function TeamName() {
		return $this->TeamName." (".$this->Rating.")";
	}
	public function ShowMoral() {
		return $this->Moral;
	}
	public function Fonduri() {
		//echo "Aplelez fonduri".$this->Funds;
		return $this->Funds;
	}
	public function Imagine() {
		return $this->poza;
	}

	public	function ColorIt($valoare) 
	{

		if($valoare<=20) 
			return "$valoare";
		if($valoare>20 && $valoare<=35) 
			return "<font color=\"orange\">$valoare</font>";
		if($valoare>35) 
			return "<font color=\"green\">$valoare</font>";

	}

	public function EchoTeam() {
		$sql = "SELECT p.id FROM user u, userplayer up, player p
				WHERE up.PlayerID=p.id AND up.UserID=u.id AND u.id=" . $this->UserID . " ORDER BY p.Position ASC";
		$res = mysqli_query($GLOBALS['con'], $sql);
		//echo "Portari<br>";


		$fundasi = 1;
		$mijlocasi = 1;
		$atacanti = 1;
		while(list($p_id) = mysqli_fetch_row($res)) {
			$pl = new Player($this->UserID, $p_id);
			$pl->EchoPlayer();
		}
		mysqli_free_result($res);
	}
	public function EchoTeamNew($youth) {
	
		echo "<table cellspacing=\"1\" CLASS=\"tf2\">";
	
		$sql = "SELECT p.*, c.post, up.number 
		FROM user u 
		LEFT JOIN userplayer up
		ON up.UserID=u.id
		LEFT JOIN player p
		ON up.PlayerID=p.id
		LEFT JOIN lineup c
		ON p.id=c.playerid AND c.userid=u.id
		WHERE  p.youth=$youth AND u.id=".$_SESSION['USERID']." ORDER BY p.Position ASC, p.id ASC";

//echo "$sql<br/>";
		
		
		$res = mysqli_query($GLOBALS['con'], $sql);


		$gks = 1;
		$defenders = 1;
		$midfielders = 1;
		$attackers = 1;	
		while($p_array = mysqli_fetch_assoc($res)) {
			
			$sqlc = "SELECT characteristic
					FROM loggrows
					WHERE data='".Date("Y-m-d")."' AND playerid=".$p_array['id'];
			//echo "$sqlc<br/>";
			$resc = mysqli_query($GLOBALS['con'], $sqlc);
			list($caracteristica) = mysqli_fetch_row($resc);
			mysqli_free_result($resc);
			
			$img = '<img src="images/crestere.png" width="12">';
			$img_com=""; $img_ref=""; $img_ooo=""; $img_han = ""; $img_tack="";
			$img_mark=""; $img_head = ""; $img_shoot=""; $img_long=""; $img_pos=""; $img_first="";
			$img_cros="";$img_team="";$img_speed="";$img_drib="";$img_pass="";$img_crea="";$img_cond="";
			$img_agre="";$img_exp="";$img_stre="";
			switch($caracteristica) {
				case 'Communication': $img_com = $img; break;
				case 'reflexes': $img_ref = $img; break;
				case 'OneonOne': $img_ooo = $img; break;
				case 'Handling': $img_han = $img; break;
				case 'Tackling': $img_tack = $img; break;
				case 'Marking': $img_mark = $img; break;
				case 'Heading': $img_head = $img; break;
				case 'Shooting': $img_shoot = $img; break;
				case 'LongShot': $img_long = $img; break;
				case 'Positioning': $img_pos = $img; break;
				case 'FirstTouch': $img_first = $img; break;
				case 'Crossing': $img_cros = $img; break;
				case 'TeamWork': $img_team = $img; break;
				case 'Speed': $img_speed = $img; break;
				case 'Dribbling': $img_drib = $img; break;
				case 'Passing': $img_pass = $img; break;
				case 'Creativity': $img_crea = $img; break;
				case 'Conditioning': $img_cond = $img; break;
				case 'Aggresivity': $img_agre = $img; break;
				case 'Experience': $img_exp = $img; break;
				case 'Strength': $img_stre = $img; break;
				
			}
			
			switch ($p_array['Position']) {
					case 1: $pos = "GK"; break;
					case 2: $pos = "DR"; break;
					case 3: $pos = "DC"; break;
					case 4: $pos = "DL"; break;
					case 5: $pos = "MR"; break;
					case 6: $pos = "MC"; break;
					case 7: $pos = "ML"; break;
					case 8: $pos = "FR"; break;
					case 9: $pos = "FC"; break;
					case 10: $pos = "FL"; break;
			}
			$pid = $p_array['id'];

			if ($pos == 'GK' and $gks==1) {
				echo "<tr>";
				echo "<th>&nbsp;</th><th>".translate('Age')."</th><th></th>";
				echo "<th title='".translate('Reflexes')."'>".substr(translate('Reflexes'),0,2)."</th><th title='".translate('OneonOne')."'\">".substr(translate('OneonOne'),0,2)."</th>";
				echo "<th title='".translate('Handling')."'>".substr(translate('Handling'),0,2)."</th><th title='".translate('Communication')."'>".substr(translate('Communication'),0,2)."</th>";
				echo "<th title='".translate('Form')."'>".translate('Form')."</th>";
				echo "<th title='".translate('Contract')."'>".substr(translate('Contract'),0,3)."</th>";
				echo "</tr>";
				$gks=0;
			}

			if (($pos == 'DL' or $pos == 'DC' or $pos == 'DR') and $defenders==1) {
				echo "<tr>";
				echo "<th colspan=\"3\">&nbsp;</th>";
				echo "<th title='".translate('Tackling')."'>".substr(translate('Tackling'),0,2)."</th><th title='".translate('Marking')."'\">".substr(translate('Marking'),0,2)."</th>";
				echo "<th title='".translate('Heading')."'>".substr(translate('Heading'),0,2)."</th>";
				echo "<th title='".translate('Positioning')."'>".substr(translate('Positioning'),0,2)."</th>";
				echo "<th title='".translate('Form')."'>".translate('Form')."</th>";
				echo "<th title='".translate('Contract')."'>".substr(translate('Contract'),0,3)."</th>";
				echo "</tr>";
				$defenders=0;
			}

			if (($pos == 'ML' or $pos == 'MC' or $pos == 'MR') and $midfielders==1) {
				echo "<tr>";
				echo "<th colspan=\"3\">&nbsp;</th>";
				echo "<th title='".translate('Creativity')."'>".substr(translate('Creativity'),0,2)."</th><th title='".translate('Crossing')."'\">".substr(translate('Crossing'),0,2)."</th>";
				echo "<th title='".translate('Passing')."'>".substr(translate('Passing'),0,2)."</th>";
				echo "<th title='".translate('LongShot')."'>".substr(translate('LongShot'),0,2)."</th>";
			
				echo "<th title='".translate('Form')."'>".translate('Form')."</th>";
				echo "<th title='".translate('Contract')."'>".substr(translate('Contract'),0,3)."</th>";
				echo "</tr>";
				$midfielders=0;
			}

			if (($pos == 'FL' or $pos == 'FC' or $pos == 'FR') and $attackers==1) {
				echo "<tr>";
				echo "<th colspan=\"3\">&nbsp;</th>";
				echo "<th title='".translate('Shooting')."'>".substr(translate('Shooting'),0,2)."</th><th title='".translate('Heading')."'\">".substr(translate('Heading'),0,2)."</th>";
				echo "<th title='".translate('FirstTouch')."'>".substr(translate('FirstTouch'),0,2)."</th>";
				echo "<th title='".translate('Positioning')."'>".substr(translate('Positioning'),0,2)."</th>";
			
				echo "<th title='".translate('Form')."'>".translate('Form')."</th>";
				echo "<th title='".translate('Contract')."'>".substr(translate('Contract'),0,3)."</th>";
				echo "</tr>";
				$attackers=0;
			}
			$td="";
			if($p_array['post'] <> 0) {
				$td = "class=\"numar\"";
			}

			$numartricou="";
			if($p_array['number']<>0)
				$numartricou = "<font class=\"numar\">".$p_array['number']."</font>&nbsp;";

			$tr = "";
			if($p_array['Transfer'] == 1 && $p_array['TransferDeadline'] == '0000-00-00 00:00:00') {
				//jucatorul este transferabil
				//apare un T in dreptul lui
				$tr =  "<font class=\"numar-tricou\">&nbsp;T&nbsp;</font>";
			} 
			if($p_array['Transfer'] == 1 && $p_array['TransferDeadline'] != '0000-00-00 00:00:00') {
				$tr =  "<img src=\"images/bagofmoney.png\" width=\"20\">";
			}
			$acc="";
			if($p_array['injured'] == 1) {
				//injured
				//red cross near his name
				$acc =  "<img src=\"images/injured.png\" width=\"17\">";
			} 

			if($p_array['training'] == 1) {
				$tr =  "<img src=\"images/respecializare.png\" width=\"20\" title=\"Reassignment\">";
			}
			
			echo "<tr>";
			echo "<td $td><a class=\"link-5\" href=\"echipa.php?id=".$p_array['id']."\">$numartricou ".substr($p_array['fname'],0,1)."."."&nbsp;".$p_array['lname']."</a>$acc"."$tr</td>";
			echo "<td>".$p_array['Age']."</td>";// - ".$p_array['Talent']."</td>";
			echo "<td>".$pos."</td>";
			if ($pos == 'GK') {		
				echo "<td>".$this->ColorIt($p_array['reflexes']).$img_ref."</td>";
				echo "<td>".$this->ColorIt($p_array['OneOnOne']).$img_ooo."</td>";
				echo "<td>".$this->ColorIt($p_array['Handling']).$img_han."</td>";
				echo "<td>".$this->ColorIt($p_array['Communication']).$img_com."</td>";
				echo "<td>";
				echo "<img width=\"23\" height=\"5\" src=\"baragrafica.php?percentage=".$p_array['Form']."\"><br/><br/>";
				//echo "<img width=\"33\" height=\"5\" src=\"baragrafica.php?percentage=".$p_array['Moral']."\">";
				echo "</td>";
				}
			if ($pos == 'DL' or $pos == 'DC' or $pos == 'DR') {		
				echo "<td>".$this->ColorIt($p_array['Tackling']).$img_tack."</td>";
				echo "<td>".$this->ColorIt($p_array['Marking']).$img_mark."</td>";
				echo "<td>".$this->ColorIt($p_array['Heading']).$img_head."</td>";
				echo "<td>".$this->ColorIt($p_array['Positioning']).$img_pos."</td>";

				echo "<td>";
				echo "<img width=\"23\" height=\"5\" src=\"baragrafica.php?percentage=".$p_array['Form']."\"><br/><br/>";
				//echo "<img width=\"33\" height=\"5\" src=\"baragrafica.php?percentage=".$p_array['Moral']."\">";
				echo "</td>";

				}
			if ($pos == 'FL' or $pos == 'FC' or $pos == 'FR') {		
				echo "<td>".$this->ColorIt($p_array['Shooting']).$img_shoot."</td>";
				echo "<td>".$this->ColorIt($p_array['Heading']).$img_head."</td>";
				echo "<td>".$this->ColorIt($p_array['FirstTouch']).$img_first."</td>";
				echo "<td>".$this->ColorIt($p_array['Positioning']).$img_pos."</td>";
				echo "<td>";
				echo "<img width=\"23\" height=\"5\" src=\"baragrafica.php?percentage=".$p_array['Form']."\"><br/><br/>";
				//echo "<img width=\"33\" height=\"5\" src=\"baragrafica.php?percentage=".$p_array['Moral']."\">";
				echo "</td>";
				}
			if ($pos == 'ML' or $pos == 'MC' or $pos == 'MR') {		
				echo "<td>".$this->ColorIt($p_array['Creativity']).$img_crea."</td>";
				echo "<td>".$this->ColorIt($p_array['Crossing']).$img_cros."</td>";
				echo "<td>".$this->ColorIt($p_array['Passing']).$img_pass."</td>";
				echo "<td>".$this->ColorIt($p_array['LongShot']).$img_long."</td>";
				echo "<td>";
				echo "<img width=\"23\" height=\"5\" src=\"baragrafica.php?percentage=".$p_array['Form']."\"><br/><br/>";
				//echo "<img width=\"33\" height=\"5\" src=\"baragrafica.php?percentage=".$p_array['Moral']."\">";
				echo "</td>";
				}
			if($p_array['Contract']==1) $afis = "an fin.";
			else $afis = $p_array['Contract'].translate('years');
			if($p_array['Contract']==1) $tit = "ultimul sezon";
			else $tit = $p_array['Contract'].translate('years');
			echo "<td title=\"durata contract - $tit\">";
			echo "$afis</td>";	
			echo "</tr>";
		}
		mysqli_free_result($res);
		echo "</table>";
	}

public function EchoOtherTeamNou($userid, $youth) {
	
		echo "<table cellspacing=\"1\" CLASS=\"tf2\">";
	
		$sql = "SELECT p.* 
		FROM user u 
		LEFT OUTER JOIN userplayer up
		ON up.UserID=u.id
		LEFT OUTER JOIN player p
		ON up.PlayerID=p.id
		WHERE  u.id=$userid AND p.youth=$youth ORDER BY p.Position ASC";
		$res = mysqli_query($GLOBALS['con'],$sql);


		$portari = 1;
		$fundasi = 1;
		$mijlocasi = 1;
		$atacanti = 1;	
		while($p_array = mysqli_fetch_assoc($res)) {
			switch ($p_array['Position']) {
					case 1: $pos = "GK"; break;
					case 2: $pos = "DR"; break;
					case 3: $pos = "DC"; break;
					case 4: $pos = "DL"; break;
					case 5: $pos = "MR"; break;
					case 6: $pos = "MC"; break;
					case 7: $pos = "ML"; break;
					case 8: $pos = "FR"; break;
					case 9: $pos = "FC"; break;
					case 10: $pos = "FL"; break;
			}
			$pid = $p_array['id'];

			if ($pos == 'GK' and $portari==1) {
				echo "<tr>";
				echo "<th>&nbsp;</th>";
				echo "<th>Ani</th>";
				echo "<th>&nbsp;</th>";
				echo "<th>Refl.</th><th>Unu la unu</th><th>Manevr.</th><th>Com.</th>";
				echo "<th title=\"Forma\">Fo.</th>";//<br/>Moral</th>";
				echo "</tr>";
				$portari=0;
			}

			if (($pos == 'DL' or $pos == 'DC' or $pos == 'DR') and $fundasi==1) {
				echo "<tr>";
				echo "<th>&nbsp;</th>";
				echo "<th>Ani</th>";
				echo "<th>&nbsp;</th>";
				echo "<th>Depos.</th><th>Marcaj</th><th>Joc de cap</th><th>Poz.</th>";
				echo "<th title=\"Forma\">Fo.</th>";//<br/>Moral</th>";
				echo "</tr>";
				$fundasi=0;
			}

			if (($pos == 'ML' or $pos == 'MC' or $pos == 'MR') and $mijlocasi==1) {
				echo "<tr>";
				echo "<th>&nbsp;</th>";
				echo "<th>Ani</th>";
				echo "<th>&nbsp;</th>";
				echo "<th>Creativ</th><th>Lans.</th><th>Pase</th><th>Sut dist.</th>";
				echo "<th title=\"Forma\">Fo.</th>";//<br/>Moral</th>";
				echo "</tr>";
				$mijlocasi=0;
			}

			if (($pos == 'FL' or $pos == 'FC' or $pos == 'FR') and $atacanti==1) {
				echo "<tr>";
				echo "<th>&nbsp;</th>";
				echo "<th>Ani</th>";
				echo "<th>&nbsp;</th>";
				
				echo "<th>Sut</th><th>Joc de cap</th><th>Prima atin.</th><th>Poz.</th>";
				echo "<th title=\"Forma\">Fo.</th>";//<br/>Moral</th>";
				echo "</tr>";
				$atacanti=0;
			}

			$tr = "";
			if($p_array['Transfer'] == 1 && $p_array['TransferDeadline'] == '0000-00-00 00:00:00') {
				//jucatorul este transferabil
				//apare un T in dreptul lui
				$tr =  "<font class=\"numar-tricou\">&nbsp;T&nbsp;</font>";
			} 
			if($p_array['Transfer'] == 1 && $p_array['TransferDeadline'] != '0000-00-00 00:00:00') {
				$tr =  "<img src=\"images/bagofmoney.png\" width=\"20\">";
			}

			echo "<tr>";
			echo "<td><a class=\"link-5\" href=\"index.php?option=viewplayer&pid=".$p_array['id']."\">".substr($p_array['fname'],0,2).'.'." ".$p_array['lname']."</a>&nbsp;$tr</td>";
			echo "<td>".$p_array['Age']."</td>";// - ".$p_array['Talent']."</td>";
			echo "<td>".$pos."</td>";
			if ($pos == 'GK') {		
				echo "<td>".$this->ColorIt($p_array['reflexes'])."</td>";
				echo "<td>".$this->ColorIt($p_array['OneOnOne'])."</td>";
				echo "<td>".$this->ColorIt($p_array['Handling'])."</td>";
				echo "<td>".$this->ColorIt($p_array['Communication'])."</td>";
			}
			if ($pos == 'DL' or $pos == 'DC' or $pos == 'DR') {		
				echo "<td>".$this->ColorIt($p_array['Tackling'])."</td>";
				echo "<td>".$this->ColorIt($p_array['Marking'])."</td>";
				echo "<td>".$this->ColorIt($p_array['Heading'])."</td>";
				echo "<td>".$this->ColorIt($p_array['Positioning'])."</td>";
			}
			if ($pos == 'FL' or $pos == 'FC' or $pos == 'FR') {		
				echo "<td>".$this->ColorIt($p_array['Shooting'])."</td>";
				echo "<td>".$this->ColorIt($p_array['Heading'])."</td>";
				echo "<td>".$this->ColorIt($p_array['FirstTouch'])."</td>";
				echo "<td>".$this->ColorIt($p_array['Positioning'])."</td>";
			}
			if ($pos == 'ML' or $pos == 'MC' or $pos == 'MR') {		
				echo "<td>".$this->ColorIt($p_array['Creativity'])."</td>";
				echo "<td>".$this->ColorIt($p_array['Crossing'])."</td>";
				echo "<td>".$this->ColorIt($p_array['Passing'])."</td>";
				echo "<td>".$this->ColorIt($p_array['LongShot'])."</td>";
			}
			echo "<td>";
			echo "<img width=\"33\" height=\"5\" src=\"baragrafica.php?percentage=".$p_array['Form']."\"><br/><br/>";
			//echo "<img width=\"33\" height=\"5\" src=\"baragrafica.php?percentage=".$p_array['Moral']."\">";
			echo "</td>";
			echo "</tr>";
		}
		mysqli_free_result($res);
		echo "</table>";
	}

}


class Stadium {
	private $ID;
	private $Name;
	private $Capacity;
	private $Grass;
	//capacity and color
	private $Sector1;
	private $Sector2;
	private $Sector3;
	private $Sector4;
	private $Sector5;
	private $Sector6;
	private $Sector7;
	private $Sector8;
	private $Sector1c;
	private $Sector2c;
	private $Sector3c;
	private $Sector4c;
	private $Sector5c;
	private $Sector6c;
	private $Sector7c;
	private $Sector8c;
	//construction
	public $Construction1;
	public $Construction2;
	public $Construction3;
	public $Construction4;
	public $Construction5;
	public $Construction6;
	public $Construction7;
	public $Construction8;
	//finish cosntruction
	private $Data1;
	private $Data2;
	private $Data3;
	private $Data4;
	private $Data5;
	private $Data6;
	private $Data7;
	private $Data8;
	
	private $Price; //price for seat


	public function __construct($id=0, $Name="", $Sector1=50, $Sector2=400, $Sector3=50, $Sector4=200, $Sector5=50, $Sector6=400, $Sector7=50, $Sector8=200, $Sector1c=1, $Sector2c=2, $Sector3c=1, $Sector4c=2, $Sector5c=1, $Sector6c=2, $Sector7c=1, $Sector8c=2) {

		if(func_num_args()==1) {
			//se trimite doar $ID
				$sql = "SELECT name, sector1, sector2, sector3, sector4, sector5, sector6, sector7, sector8, capacity, Price,
								construction1, construction2, construction3, construction4, construction5, construction6, construction7, construction8,
								data1, data2, data3, data4, data5, data6, data7, data8
						FROM stadium 
						WHERE id=$id";
				//echo "$sql";
				$res = mysqli_query($GLOBALS['con'], $sql);
				list($name, $sector1, $sector2, $sector3, $sector4, $sector5, $sector6, $sector7, $sector8, $capacity, $Price,
				$co1, $co2, $co3, $co4, $co5, $co6, $co7, $co8, $da1, $da2, $da3, $da4, $da5, $da6, $da7, $da8) = mysqli_fetch_row($res);
				$this->Name = $name;
				
				$this->Sector1 = $sector1;
				$this->Sector2 = $sector2;
				$this->Sector3 = $sector3;
				$this->Sector4 = $sector4;
				$this->Sector5 = $sector5;
				$this->Sector6 = $sector6;
				$this->Sector7 = $sector7;
				$this->Sector8 = $sector8; 
				
				$this->Construction1 = $co1; 
				$this->Construction2 = $co2; 
				$this->Construction3 = $co3; 
				$this->Construction4 = $co4; 
				$this->Construction5 = $co5; 
				$this->Construction6 = $co6; 
				$this->Construction7 = $co7; 
				$this->Construction8 = $co8; 

				$this->Data1 = $da1; 
				$this->Data2 = $da2; 
				$this->Data3 = $da3; 
				$this->Data4 = $da4; 
				$this->Data5 = $da5; 
				$this->Data6 = $da6; 
				$this->Data7 = $da7; 
				$this->Data8 = $da8; 

				
				$this->Capacity = $capacity;
				$this->Price = $Price;

				$this->ID = $id;
				mysqli_free_result($res);
			return;
		}
	
		$this->Name = $Name;
		
		$this->Sector1 = $Sector1;
		$this->Sector2 = $Sector2;
		$this->Sector3 = $Sector3;
		$this->Sector4 = $Sector4;
		$this->Sector5 = $Sector5;
		$this->Sector6 = $Sector6;
		$this->Sector7 = $Sector7;
		$this->Sector8 = $Sector8;
		//culori
		$this->Sector1c = $Sector1c;
		$this->Sector2c = $Sector2c;
		$this->Sector3c = $Sector3c;
		$this->Sector4c = $Sector4c;
		$this->Sector5c = $Sector5c;
		$this->Sector6c = $Sector6c;
		$this->Sector7c = $Sector7c;
		$this->Sector8c = $Sector8c;
		//constructie. Initial 0;
		$this->Construction1 = 0;
		$this->Construction2 = 0;
		$this->Construction3 = 0;
		$this->Construction4 = 0;
		$this->Construction5 = 0;
		$this->Construction6 = 0;
		$this->Construction7 = 0;
		$this->Construction8 = 0;
		//datele de finalizare. initial, nu exista
		$this->Data1 = '0000-00-00';
		$this->Data2 = '0000-00-00';
		$this->Data3 = '0000-00-00';
		$this->Data4 = '0000-00-00';
		$this->Data5 = '0000-00-00';
		$this->Data6 = '0000-00-00';
		$this->Data7 = '0000-00-00';
		$this->Data8 = '0000-00-00';

		$this->Capacity = $this->Sector1 + $this->Sector2 + $this->Sector3 + $this->Sector4 + $this->Sector5 + $this->Sector6 + $this->Sector7 + $this->Sector8;

		$this->StadiumInit();
	}
	
	
	public function AvailableSeats() {
		$dispo = 0;
		if($this->Construction1==0) $dispo += $this->Sector1;
		if($this->Construction2==0) $dispo += $this->Sector2;
		if($this->Construction3==0) $dispo += $this->Sector3;
		if($this->Construction4==0) $dispo += $this->Sector4;
		if($this->Construction5==0) $dispo += $this->Sector5;
		if($this->Construction6==0) $dispo += $this->Sector6;
		if($this->Construction7==0) $dispo += $this->Sector7;
		if($this->Construction8==0) $dispo += $this->Sector8;

		return $dispo;
	}

	
	private function StadiumInit() {
		$sql = "INSERT INTO stadium 
		(name, sector1, sector2, sector3, sector4, sector5, sector6, sector7, sector8, capacity, price) 
		VALUES ('" . $this->Name . "', " . $this->Sector1 . ", " . $this->Sector2 . ", " . $this->Sector3 . ", " . $this->Sector4 . ", " . $this->Sector5 . ", " . $this->Sector6 . ", " . $this->Sector7 . ", " . $this->Sector8 . "," . $this->Capacity . ",0)";
		//echo "$sql<br>";
		$res = mysqli_query($GLOBALS['con'], $sql);
		$this->ID = mysqli_insert_id($GLOBALS['con']);
		//echo "<br/>STADIUM ID is ".$this->ID;
	}

	public function ReturnID() {
		return $this->ID;
	}

	public function ReturnPrice() {
		return $this->Price;
	}

	public function __set($name,$value) {
		switch($name) { 
		  case 'Price': 
			$this->Price = $value;
			$sql = "UPDATE stadium SET Price=".$this->Price.
				   " WHERE id=".$this->ID;
			mysqli_query($GLOBALS['con'], $sql);
			return $this->Price;
		}
	}

	 public function __get($name) {
		switch($name) {
			case 'Price': 
				return $this->Price;
			case 'Name': 
				return $this->Name;
		}
	}
	
	public function ReturnStadiumName() {
		
		return $this->Name.'<br/>Capacity: '.$this->Capacity.'/ Available: '.$this->Available().' seats';
	}
	
	public function Available() {
		$dispo = 0;
		if($this->Construction1==0) $dispo += $this->Sector1;
		if($this->Construction2==0) $dispo += $this->Sector2;
		if($this->Construction3==0) $dispo += $this->Sector3;
		if($this->Construction4==0) $dispo += $this->Sector4;
		if($this->Construction5==0) $dispo += $this->Sector5;
		if($this->Construction6==0) $dispo += $this->Sector6;
		if($this->Construction7==0) $dispo += $this->Sector7;
		if($this->Construction8==0) $dispo += $this->Sector8;

		return $dispo;
	}
	
	function ViewStadium() {
		$constructie = "<font color=\"red\">".translate('Under construction')."</font><br/>";
		echo "<div>";
		echo translate('Name').": ".$this->ReturnStadiumName().'<br/>';
		echo "<div class=\"hr-replace\"></div>";
		echo "Sector 1 (S1): ".$this->Sector1.translate('seats').'<br/>';
		if($this->Construction1>0) echo $constructie;
		echo "<div class=\"hr-replace\"></div>";
		echo "Sector 2 (S2): ".$this->Sector2.translate('seats').'<br/>';
		if($this->Construction2>0) echo $constructie;
		echo "<div class=\"hr-replace\"></div>";
		echo "Sector 3 (S3): ".$this->Sector3.translate('seats').'<br/>';
		if($this->Construction3>0) echo $constructie;
		echo "<div class=\"hr-replace\"></div>";
		echo "Sector 4 (S4): ".$this->Sector4.translate('seats').'<br/>';
		if($this->Construction4>0) echo $constructie;
		echo "<div class=\"hr-replace\"></div>";
		echo "Sector 5 (S5): ".$this->Sector5.translate('seats').'<br/>';
		if($this->Construction5>0) echo $constructie;
		echo "<div class=\"hr-replace\"></div>";
		echo "Sector 6 (S6): ".$this->Sector6.translate('seats').'<br/>';
		if($this->Construction6>0) echo $constructie;
		echo "<div class=\"hr-replace\"></div>";
		echo "Sector 7 (S7): ".$this->Sector7.translate('seats').'<br/>';
		if($this->Construction7>0) echo $constructie;
		echo "<div class=\"hr-replace\"></div>";
		echo "Sector 8 (S8): ".$this->Sector8.translate('seats').'<br/>';
		if($this->Construction8>0) echo $constructie;
		echo "</div>";
	}

	
	function BuildStadium() {
		echo "<div class=\"hr-replace\"></div>";
		echo "<h2>".translate('Increase capacity')."!</h2><br/><font color=\"red\">(Construction price per seat is 500 &euro;. Increase of capicity will be made if there are enough funds available. In the construction phase, that sector will not be available for spectators. If there are many sectors in development, the finish date will be more in the future!)</font>";
		echo "<form action=\"index.php\" method=\"POST\" onsubmit=\"return validate(this);\">";
		echo "<div class=\"hr-replace\"></div>";
		echo "Sector 1 (S1): ".$this->Sector1.translate('seats').'<br/>';
		$disabled="";
		if($this->Construction1>0) {
			$disabled="disabled";
			echo "Construction of ".$this->Construction1. " places will be finished in ". $this->Data1."!<br/>";
		}
		echo translate('Increase capacity').": <input type=\"text\" name=\"s1_build\" class=\"input-1\" value=\"0\" $disabled id=\"s1\">".translate('seats');
		echo "<div class=\"hr-replace\"></div>";
		echo "Sector 2 (S2): ".$this->Sector2.translate('seats').'<br/>';
		$disabled="";
		if($this->Construction2>0) {
			$disabled="disabled";
			echo "Construction of ".$this->Construction2. " places will be finished in ". $this->Data2."!<br/>";
		}
		echo translate('Increase capacity').": <input type=\"text\" name=\"s2_build\" class=\"input-1\" value=\"0\" $disabled id=\"s2\">".translate('seats');
		echo "<div class=\"hr-replace\"></div>";
		echo "Sector 3 (S3): ".$this->Sector3.translate('seats').'<br/>';
		$disabled="";
		if($this->Construction3>0) {
			$disabled="disabled";
			echo "Construction of ".$this->Construction3. " places will be finished in ". $this->Data3."!<br/>";
		}
		echo translate('Increase capacity').": <input type=\"text\" name=\"s3_build\" class=\"input-1\" value=\"0\" $disabled id=\"s3\">".translate('seats');
		echo "<div class=\"hr-replace\"></div>";
		echo "Sector 4 (S4): ".$this->Sector4.translate('seats').'<br/>';
		$disabled="";
		if($this->Construction4>0) {
			$disabled="disabled";
			echo "Construction of ".$this->Construction4. " places will be finished in ". $this->Data4."!<br/>";
		}
		echo translate('Increase capacity').": <input type=\"text\" name=\"s4_build\" class=\"input-1\" value=\"0\" $disabled id=\"s4\">".translate('seats');
		echo "<div class=\"hr-replace\"></div>";
		echo "Sector 5 (S5): ".$this->Sector5.translate('seats').'<br/>';
		$disabled="";
		if($this->Construction5>0) {
			$disabled="disabled";
			echo "Construction of ".$this->Construction5. " places will be finished in ". $this->Data5."!<br/>";
		}
		echo translate('Increase capacity').": <input type=\"text\" name=\"s5_build\" class=\"input-1\" value=\"0\" $disabled id=\"s5\">".translate('seats');
		echo "<div class=\"hr-replace\"></div>";
		echo "Sector 6 (S6): ".$this->Sector6.translate('seats').'<br/>';
		$disabled="";
		if($this->Construction6>0) {
			$disabled="disabled";
			echo "Construction of ".$this->Construction6. " places will be finished in ". $this->Data6."!<br/>";
		}
		echo translate('Increase capacity').": <input type=\"text\" name=\"s6_build\" class=\"input-1\" value=\"0\" $disabled id=\"s6\">".translate('seats');
		echo "<div class=\"hr-replace\"></div>";
		echo "Sector 7 (S7): ".$this->Sector7.translate('seats').'<br/>';
		$disabled="";
		if($this->Construction7>0) {
			$disabled="disabled";
			echo "Construction of ".$this->Construction7. " places will be finished in ". $this->Data7."!<br/>";
		}
		echo translate('Increase capacity').": <input type=\"text\" name=\"s7_build\" class=\"input-1\" value=\"0\" $disabled id=\"s7\">".translate('seats');
		echo "<div class=\"hr-replace\"></div>";
		echo "Sector 8 (S8): ".$this->Sector8.translate('seats').'<br/>';
		$disabled="";
		if($this->Construction8>0) {
			$disabled="disabled";
			echo "Construction of ".$this->Construction8. " places will be finished in ". $this->Data8."!<br/>";
		}
		echo translate('Increase capacity').": <input type=\"text\" name=\"s8_build\" class=\"input-1\" value=\"0\" $disabled id=\"s8\">".translate('seats');
		echo "<br/><br/>";
		echo "<input type=\"Submit\" name=\"MaresteCapacitate\" value='".translate('Increase capacity')."' class=\"button-2\">";
		echo "</form>";
	}

}

?>