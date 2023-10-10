<?php
include('definitions.inc');

class Player {
	
	private $Age;
	private $Aggresivity;
	private $Communication;
	private $Condition;
	private $Contract;
	private $Country;
	private $Creativity;
	private $Crossing;
	private $Dribbling;
	private $Experience;
	public $FirstName;
	private $FirstTouch;
	private $Form;
	private $Handling;
	private $Heading;
	private $Injury;
	public $LastName;
	private $LongShot;
	private $Marking;
	private $Nationality;
	private $OneOnOne;
	private $Passing;
	public $Position;
	private $Positioning;
	private $Rating;
	private $Reflexes;
	private $Shooting;
	private $Speed;
	private $Strength;
	private $Tackling;
	private $Talent;
	private $TeamWork;
	private $UserID;
	private $Value;
	private $Wage;
	private $PlayerID;
	public $Transfer;
	private $TransferSuma;
	public $TransferDeadline;
	public $weeks;
	public $Proprietar;
	private $Moral;
	private $Training;
	
	
	private $NationalityName;
	private $number;
	
	private $CountryCoefficient; 


	//individual values for each player, for each zone.
	private $valDF; 
	private $valMF;
	private $valFW;
	//total values for attacking, defending and midfielders value
	private $vdf; 
	private $vmf;
	private $vfw;

	private $CAge;
	private $CLeague;
	private $YOUTH;
	private $Avatar;


	private $tactics;
	private $midfield;
	private $atacks;
	private $passes;
	
	private $RespePost;
	private $RespeData;
	
	private $injury; //din tabelul injury
	private $injuryData; //din tabelul injury
	
	private $Injured; //dar tabelul Player

	private $Active; // still active or he is retired
	
	//$Youth = 1 - youth player`
	function __construct($user, $PlayerID=0, $MyCountry=3, $Youth=0, $MyPosition=2, $MyLiga=1) {
		//echo "i am right here player $user $PlayerID<br/>";

		
		$this->UserID = $user;
		$this->Country = $MyCountry;
		$this->CLeague = $MyLiga;
		$this->PlayerID = $PlayerID;

		if(func_num_args()==2) {
			//only user and playerid, player exists, so only display it
			$this->GetData();
			return;
		}

		//generate player name
		$this->GenerateFirstName();
		$this->GenerateLastName();
		if($Youth==1) {
			$this->YOUTH = 1;
			$this->Age = rand(Y_AGE_START, Y_AGE_END);
			//age coefficient
			//junior player has different start values than a senior 
			//set up this coefficient to (70%)
			$this->CAge = Y_COEFICIENT_AGE;
		} else {
			$this->YOUTH = 0;
			$this->Age = rand(AGE_START, AGE_END);
			$this->CAge = COEFICIENT_AGE;
		}

		$this->Position = $MyPosition;
		switch($this->Position) {
			case 1: //Goalkeeper
				$this->GenerateGoalkeeper();
				break;
			case 2: //defender DR
				$this->GenerateDefender();
				break;
			case 3: //defender DC
				$this->GenerateDefender();
				break;
			case 4: //defender DL
				$this->GenerateDefender();
				break;
			case 5: //midfielder MR
				$this->GenerateMidfielder();
				break;
			case 6: //midfielder MC
				$this->GenerateMidfielder();
				break;
			case 7: //midfielder ML
				$this->GenerateMidfielder();
				break;
			case 8: //forward FR
				$this->GenerateForward();
				break;
			case 9: //forward FC
				$this->GenerateForward();
				break;
			case 10: //forward FL
				$this->GenerateForward();
				break;
		}

		$this->WriteData();
	}

	function GetData() {
		$this->tactics = 1;
		$this->midfield = 1;
		$this->atacks = 1;
		$this->passes = 1;
		
		$sql = "SELECT a.fname, a.lname, a.reflexes, a.OneOnOne, a.Handling, a.Communication, a.Tackling, a.Passing, a.LongShot, a.Shooting, a.Heading, a.Creativity, a.Crossing, a.Marking, a.TeamWork, a.FirstTouch, a.Strength, a.Speed, a.Experience, a.Conditioning, a.Form, a.Talent, a.Aggresivity, a.Injury, a.Dribbling, a.Positioning, a.Position, a.Nationality, a.Rating, a.Age, a.Wage, a.Value, a.Contract, b.name, a.Transfer, a.TransferSuma, a.TransferDeadline, c.weeks, d.TeamName, a.Avatar, c.number, f.tactics, f.midfield, f.atacks, f.passes, a.Moral, c.userid, a.training, g.post, g.data, a.youth, h.id, h.data, a.Injured, a.active  
		FROM player a 
		LEFT JOIN userplayer c
		ON a.id=c.PlayerID
		LEFT JOIN user d
		ON c.UserID=d.id
		LEFT JOIN tactics f
		ON d.id=f.userid
		LEFT JOIN country b
		ON a.Nationality=b.id
		LEFT JOIN reassign g
		ON g.playerid=a.id
		LEFT JOIN injury h
		ON h.playerid=a.id
		WHERE a.id=".$this->PlayerID;
		//echo "$sql<br/>";
		$res = mysqli_query($GLOBALS['con'],$sql);
		list($this->FirstName, $this->LastName, $this->reflexes, $this->OneOnOne, $this->Handling, $this->Communication, $this->Tackling, $this->Passing, $this->LongShot, $this->Shooting, $this->Heading, $this->Creativity, $this->Crossing, $this->Marking, $this->TeamWork, $this->FirstTouch, $this->Strength, $this->Speed, $this->Experience, $this->Condition, $this->Form, $this->Talent, $this->Aggresivity, $this->Injury, $this->Dribbling, $this->Positioning, $this->Position, $this->Nationality, $this->Rating, $this->Age, $this->Wage, $this->Value, $this->Contract, $this->NationalityName, $this->Transfer, $this->TransferSuma, $this->TransferDeadline, $this->weeks, $this->Proprietar, $this->Avatar, $this->number, $this->tactics, $this->midfield, $this->atacks, $this->passes, $this->Moral, $this->UserID, $this->Training, $this->RespePost, $this->RespeData, $this->YOUTH, $this->injury, $this->injuryData, $this->Injured, $this->Active)=mysqli_fetch_row($res);
		
		mysqli_free_Result($res);
	}

	
	private function GenerateDefender() {
		$this->Aggresivity = rand(REST_START,REST_END);
		$this->Condition = rand(REST_START,REST_END);
		$this->Communication = 1;
		$this->Contract = 2;
		$this->Country = 1;
		$this->Dribbling = 1;
		$this->Experience = 1;
		$this->Form = 100;
		$this->Injury = rand(REST_START,REST_END);
		//$this->Nationality = 1;
		$this->Rating = 1;
		$this->Speed = rand(REST_START,REST_END);
		$this->Strength = rand(REST_START,REST_END);
		$this->TeamWork = rand(REST_START,REST_END);
		$this->Value = 1;
		$this->Wage = 1;

		//country coefficient is from 1-100. To have a good value for better countries, i divide it to 60
		$coe = $this->CountryCoefficient/60;
		
		$this->Creativity = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Crossing = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Dribbling = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->FirstTouch = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Handling = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Heading = round(rand(D_HEADING_START*$this->CAge,D_HEADING_END*$this->CAge)*$this->CLeague*$coe);
		$this->LongShot = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Marking = round(rand(D_MARKING_START*$this->CAge,D_MARKING_END*$this->CAge)*$this->CLeague*$coe);
		$this->OneOnOne = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Passing = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Positioning = round(rand(D_POSITIONING_START*$this->CAge,D_POSITIONING_END*$this->CAge)*$this->CLeague*$coe);
		$this->Reflexes = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Shooting = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Tackling = round(rand(D_TACKLING_START*$this->CAge,D_TACKLING_END*$this->CAge)*$this->CLeague*$coe);
		$this->Talent = rand(1,100);
	}

	private function GenerateGoalkeeper() {
		$this->Aggresivity = rand(REST_START,REST_END);
		$this->Condition = rand(REST_START,REST_END);
		$this->Contract = 2;
		$this->Country = 1;
		$this->Dribbling = 1;
		$this->Experience = 1;
		$this->Form = 100;
		$this->Injury = rand(REST_START,REST_END);
		//$this->Nationality = 1;
		$this->Rating = 1;
		$this->Speed = rand(REST_START,REST_END);
		$this->Strength = rand(REST_START,REST_END);
		$this->TeamWork = rand(REST_START,REST_END);
		$this->Value = 1;
		$this->Wage = 1;
		
		//coeficientul de tara e de la 1-100. Pt a avea o valoare pozitiva petru tarile bune, impart la 60
		$coe = $this->CountryCoefficient/60;
		
		$this->Communication = round(rand(G_COMMUNICATION_START*$this->CAge,G_COMMUNICATION_END*$this->CAge)*$this->CLeague*$coe);
		$this->Creativity = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Crossing = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Dribbling = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->FirstTouch = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Handling = round(rand(G_HANDLING_START*$this->CAge,G_HANDLING_END*$this->CAge)*$this->CLeague*$coe);
		$this->Heading = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->LongShot = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Marking = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->OneOnOne = round(rand(G_ONEONONE_START*$this->CAge,G_ONEONONE_END*$this->CAge)*$this->CLeague*$coe);
		$this->Passing = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Positioning = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Reflexes = round(rand(G_REFLEXES_START*$this->CAge,G_REFLEXES_END*$this->CAge)*$this->CLeague*$coe);
		$this->Shooting = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Tackling = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Talent = rand(1,100);
	}

	private function GenerateMidfielder() {
		$this->Aggresivity = rand(REST_START,REST_END);
		$this->Condition = rand(REST_START,REST_END);
		$this->Contract = 2;
		$this->Country = 1;
		$this->Dribbling = 1;
		$this->Experience = 1;
		$this->Form = 100;
		$this->Injury = rand(REST_START,REST_END);
		//$this->Nationality = 1;
		$this->Rating = 1;
		$this->Speed = rand(REST_START,REST_END);
		$this->Strength = rand(REST_START,REST_END);
		$this->TeamWork = rand(REST_START,REST_END);
		$this->Value = 1;
		$this->Wage = 1;
		
		//country coeficient is from 1-100. To have a positive value for good countries, i divide it to 60
		$coe = $this->CountryCoefficient/60;
		
		$this->Communication = round(rand(G_COMMUNICATION_START*$this->CAge,G_COMMUNICATION_END*$this->CAge)*$this->CLeague*$coe);
		$this->Creativity = round(rand(M_CREATIVITY_START*$this->CAge,M_CREATIVITY_END*$this->CAge)*$this->CLeague*$coe);
		$this->Crossing = round(rand(M_CROSSING_START*$this->CAge,M_CROSSING_END*$this->CAge)*$this->CLeague*$coe);
		$this->Dribbling = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->FirstTouch = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Handling = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Heading = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->LongShot = round(rand(M_LONGSHOT_START*$this->CAge,M_LONGSHOT_END*$this->CAge)*$this->CLeague*$coe);
		$this->Marking = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->OneOnOne = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Passing = round(rand(M_PASSING_START*$this->CAge,M_PASSING_END*$this->CAge)*$this->CLeague*$coe);
		$this->Positioning = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Reflexes = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Shooting = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Tackling = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Talent = rand(1,100);
	}

	private function GenerateForward() {
		$this->Aggresivity = rand(REST_START,REST_END);
		$this->Condition = rand(REST_START,REST_END);
		$this->Contract = 2;
		$this->Country = 1;
		$this->Dribbling = 1;
		$this->Experience = 1;
		$this->Form = 100;
		$this->Injury = rand(REST_START,REST_END);
		//$this->Nationality = 1;
		$this->Rating = 1;//se claculeaza ulterior
		$this->Speed = rand(REST_START,REST_END);
		$this->Strength = rand(REST_START,REST_END);
		$this->TeamWork = rand(REST_START,REST_END);
		$this->Value = 1;//se calculeaza ulterior
		$this->Wage = 1;//se calculeaza ulterior
		
		//country coeficient is from 1-100. To have a positive value for good countries, i divide it to 60
		$coe = $this->CountryCoefficient/60;
		
		$this->Communication = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Creativity = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Crossing = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Dribbling = round(rand(F_DRIBBLING_START*$this->CAge,F_DRIBBLING_END*$this->CAge)*$this->CLeague*$coe);
		$this->FirstTouch = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Handling = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Heading = round(rand(F_HEADING_START*$this->CAge,F_HEADING_END*$this->CAge)*$this->CLeague*$coe);
		$this->LongShot = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Marking = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->OneOnOne = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Passing = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Positioning = round(rand(F_POSITIONING_START*$this->CAge,F_POSITIONING_END*$this->CAge)*$this->CLeague*$coe);
		$this->Reflexes = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Shooting = round(rand(F_SHOOTING_START*$this->CAge,F_SHOOTING_END*$this->CAge)*$this->CLeague*$coe);
		$this->Tackling = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLeague*$coe);
		$this->Talent = rand(1,100);
	}

	private function GenerateFirstName() {
		//picture for each player
		$p=rand(1,35);
		$this->Avatar = "p$p.png";
		
		$sql = "SELECT a.country, a.name, b.coeficient 
				FROM firstname a
				LEFT OUTER JOIN country b
				ON a.country=b.id";// WHERE country=" . $this->Country;
		$res = mysqli_query($GLOBALS['con'],$sql);
		//echo "$sql <br/>";

		$val = rand(0, mysqli_num_rows($res)-1);
		mysqli_data_seek($res, $val);
		$row = mysqli_fetch_assoc($res);
		$this->FirstName = $row['name'];
		$this->Nationality = $row['country'];
		$this->CountryCoefficient = $row['coeficient'];
		mysqli_free_result($res);
	}


	private function GenerateLastName() {
		$sql = "SELECT name FROM lastname WHERE country=" . $this->Nationality;
		$res = mysqli_query($GLOBALS['con'],$sql);

		$val = rand(0, mysqli_num_rows($res)-1);
		mysqli_data_seek($res, $val);
		$row = mysqli_fetch_assoc($res);
		$this->LastName = $row['name'];
		mysqli_free_result($res);
	}

	function SetWage() {
		//echo "Am intrat aici ";
		if ($this->Talent < 21) {
			$coeficient = CST_WN;
			$cst_talent = 'CST_TAL_WN';
		}
		//one chance to become
		if ($this->Talent > 20 && $this->Talent < 41) {
			$coeficient = CST_OC;
			$cst_talent = 'CST_TAL_OC';
		}
		//can become
		if ($this->Talent > 40 && $this->Talent < 61) {
			$coeficient = CST_CB;
			$cst_talent = 'CST_TAL_CB';
		}
		//very talented
		if ($this->Talent > 60 && $this->Talent < 81) {
			$coeficient = CST_VT;
			$cst_talent = 'CST_TAL_VT';
		}
		//superstar
		if ($this->Talent > 80) {
			$coeficient = CST_SS;
			$cst_talent = 'CST_TAL_SS';
		}	

		//rating-ul sa fie alocat aleator
		$this->Rating = rand(RATING_START,RATING_END);

		//salariul sa fie in functie de rating
		$this->Wage = $this->Rating*$coeficient*190;
		//$this->Wage = (($this->GetFWWork() + $this->GetMFWork() + $this->GetDFWork()))*$coeficient*9;
		//echo $this->Rating.' : '.$coeficient.' : ';
		//$this->Rating = ($this->GetFWWork() + $this->GetMFWork() + $this->GetDFWork())*.6;
		$this->Value = $this->Rating*$coeficient*3920;
		//$this->Value = ($this->GetFWWork() + $this->GetMFWork() + $this->GetDFWork())*$coeficient*637;

		/*
		if($this->Position==1) {
			//pentru portar, sa fie alta formula
			$this->Wage = ($this->GetDFWork()+12)*$coeficient*3;

			$this->Rating = ($this->GetDFWork() + 12)*$coeficient*.6;

			$this->Value = ($this->GetDFWork() + 12)*$coeficient*637;

		}
		*/
		if($this->YOUTH == 1) {
			//pt jucator tinar
			//rating-ul sa fie alocat aleator
			$this->Rating = rand(REST_START,21);

			//salariul sa fie in functie de rating
			$this->Wage = $this->Rating*$coeficient*19;
			//$this->Wage = (($this->GetFWWork() + $this->GetMFWork() + $this->GetDFWork()))*$coeficient*9;

			//$this->Rating = ($this->GetFWWork() + $this->GetMFWork() + $this->GetDFWork())*.6;
			$this->Value = $this->Rating*$coeficient*392;
			//$this->Value = ($this->GetFWWork() + $this->GetMFWork() + $this->GetDFWork())*$coeficient*637;
			
		}
	}

	private function Viewleap() {
		$sql = "SELECT Characteristic, data FROM loggrows WHERE playerid=".$this->PlayerID. " ORDER BY data DESC";
		$res = mysqli_query($GLOBALS['con'],$sql);
		$i=0;
		while(list($Characteristic, $data) = mysqli_fetch_row($res)) {
			if($i==0) {
				//echo "<h1>leap dupa antrenament! (max.10)</h1>";
				echo "<table class=\"tftable\">";
				echo "<tr>";
			}
			if($i%2==0 && $i<>0) echo "<tr>";
			switch($Characteristic) {
				case 'Communication': $deafisat='Comunicatie'; break;
				case 'reflexes': $deafisat='Reflexe'; break;
				case 'OneOnOne': $deafisat='Unu la unu'; break;
				case 'Handling': $deafisat='Manevrare'; break;
				case 'Tackling': $deafisat='Deposedare'; break;
				case 'Marking': $deafisat='Marcaj'; break;
				case 'Heading': $deafisat = 'Jocul cu capul'; break;
				case 'Shooting': $deafisat='Sut'; break;
				case 'LongShot': $deafisat='Suturi de la distanta'; break;
				case 'Positioning': $deafisat='Pozitionare'; break;
				case 'FirstTouch': $deafisat='Atingere'; break;
				case 'Crossing': $deafisat='Lansari'; break;
				case 'TeamWork': $deafisat='Joc de echipa'; break;
				case 'Speed': $deafisat='Viteza'; break;
				case 'Dribbling': $deafisat='Dribling'; break;
				case 'Passing': $deafisat='passes'; break;
				case 'Creativity': $deafisat='Creativitate'; break;
				case 'Conditioning': $deafisat='Conditie fizica'; break;
				case 'Aggresivity': $deafisat='Agresivitate'; break;
				case 'Experience': $deafisat='Experienta'; break;
				case 'Strength': $deafisat='Rezistenta'; break;
			}
			echo "<td>$data</td>";
			echo "<td>$deafisat</td>";
			$i++;
			if($i%2==0) echo "</tr>";
			if($i==10)break;
		}
		if($i%2==1) {
			echo "<td></td><td></td></tr></table>";
		} else {
			echo "</table>";
		}
		mysqli_free_result($res);
	}
	
	private function WriteData(){

		$this->SetWage();

		if($this->UserID == 0) {
			//se creaza jucatori liber de transfer, care nu au echipa
			//la acestia trebuie sa se plateasca doar salariul
			//se pun pe lista de transfer automat
			$transfer = 1;
			$transfersuma = 1;
		} else {
			$transfer = 0;
			$transfersuma = 0;		
		}
		if($this->Reflexes>49) $this->Reflexes = 49;
		if($this->OneOnOne>49) $this->OneOnOne =49;
		if($this->Handling>49) $this->Handling = 49;
		if($this->Communication>49) $this->Communication = 49;
		if($this->Tackling>49) $this->Tackling=49;
		if($this->Passing>49)$this->Passing=49;
		if($this->LongShot>49) $this->LongShot=49;
		if($this->Shooting>49)$this->Shooting=49;
		if($this->Heading>49)$this->Heading=49;
		if($this->Creativity>49)$this->Creativity=49;
		if($this->Crossing>49)$this->Crossing=49;
		if($this->Marking>49)$this->Marking=49;
		if($this->TeamWork>49)$this->TeamWork=50;
		if($this->FirstTouch>49)$this->FirstTouch=49;
		if($this->Strength>49)$this->Strength=49;
		if($this->Speed>49)$this->Speed=50;
		if($this->Aggresivity>49)$this->Aggresivity=49;
		if($this->Dribbling>49)$this->Dribbling=50;
		if($this->Positioning>49) $this->Positioning=49;
		
		
		$sql = "INSERT INTO player (
		fname, lname, reflexes, OneOnOne, Handling, Communication, Tackling, Passing, LongShot, Shooting, Heading, Creativity, Crossing, Marking, TeamWork, FirstTouch, Strength, Speed, Experience, Conditioning, Form, Talent, Aggresivity, Injury, Dribbling, Positioning, Position, Nationality, Rating, Age, Wage, Value, Contract, Transfer, TransferSuma, Avatar, Youth) 
		VALUES (" .
		"'" . $this->FirstName . "', '" . $this->LastName . "', " . $this->Reflexes . ", " . $this->OneOnOne . ", " . $this->Handling . ", " . $this->Communication . ", " . $this->Tackling . ", " . $this->Passing . ", " . $this->LongShot . ", " . $this->Shooting . ", " . $this->Heading . ", " . $this->Creativity . ", " . $this->Crossing . ", " . $this->Marking . ", " . $this->TeamWork . ", " . $this->FirstTouch . ", " . $this->Strength . ", " . $this->Speed . ", " . $this->Experience . ", " . $this->Condition . ", " . $this->Form . ", " . $this->Talent . ", " . $this->Aggresivity . ", " . $this->Injury . ", " . $this->Dribbling . ", " . $this->Positioning . ", " . $this->Position . ", " . $this->Nationality . ", " . $this->Rating . ", " . $this->Age . ", " . $this->Wage . ", " . $this->Value . ", " . $this->Contract . ", $transfer, $transfersuma, '".$this->Avatar."',".$this->YOUTH.")";
		mysqli_query($GLOBALS['con'],$sql);
		$PlayerID = mysqli_insert_id($GLOBALS['con']);
		//echo "<br/>$sql<br/>";
		//apeleaza functia ce calculeaza valorile maxime la care poate ajunge jucatorul
		//acest maxim depinde de talent
		$this->SetMaxValues($PlayerID);

		$sql = "INSERT INTO userplayer (UserID, PlayerID, data) VALUES(" . $this->UserID . ", " . $PlayerID . ",'".Date("Y-m-d")."')";
		mysqli_query($GLOBALS['con'],$sql);
		//echo "$sql<br/>";
		$sql = "INSERT INTO moral(playerid, contor1, contor2)
				VALUES($PlayerID,0,0)";
		mysqli_query($GLOBALS['con'],$sql);

		switch($this->Position) {
			case 1: //Goalkeeper
				$post = 1;
				break;
			case 2: //defender DR
				$post = 2;
				break;
			case 3: //defender DC
				$post = 2;
				break;
			case 4: //defender DL
				$post = 2;
				break;
			case 5: //midfielder MR
				$post = 3;
				break;
			case 6: //midfielder MC
				$post = 3;
				break;
			case 7: //midfielder ML
				$post = 3;
				break;
			case 8: //forward FR
				$post = 4;
				break;
			case 9: //forward FC
				$post = 4;
				break;
			case 10: //forward FL
				$post = 4;
				break;
		}

		$ttype = 1; //normal
		//inserare in tabela de antrenament
		//initial, antrenament normal, pe postul lui
		$sql = "INSERT INTO trainerplayer (PlayerID, TrainerID, Post, ttype) VALUES(" . $PlayerID . ", 0, $post, $ttype)";
		mysqli_query($GLOBALS['con'],$sql);

		$pgroup=1;
		if($this->YOUTH==1) $pgroup=2;
		
		//inserare in tabela de lineup
		//initial, nici un jucator nu face parte din formatia de start, se insereaza valoarea 0
		$sql = "INSERT INTO lineup (playerId, userId, post, pgroup) VALUES($PlayerID, ". $this->UserID. ", 0, $pgroup)";
		mysqli_query($GLOBALS['con'],$sql);

		
		//inserare in tabela de percentage
		//initial, are percentage prestabilite
		//ulterior, in functie de momentul in care ajunge la praguri (prag1, prag2), percentagele se realoca
		//portar
		if ($post == 1) {
			$sql = "INSERT INTO percentage (PlayerId, percent, status, Characteristic, redist)
					VALUES
					($PlayerID, ".rand(G_REFLEXES_PERCENT_1, G_REFLEXES_PERCENT_2).", 0, 'Reflexes', 0),
					($PlayerID, ".rand(G_ONEONONES_PERCENT_1,G_ONEONONES_PERCENT_2).", 0, 'OneonOne', 0),
					($PlayerID, ".rand(G_HANDLING_PERCENT_1,G_HANDLING_PERCENT_2).", 0, 'Handling', 0),
					($PlayerID, ".rand(G_COMMUNICATION_PERCENT_1,G_COMMUNICATION_PERCENT_2).", 0, 'Communication', 0),
					($PlayerID, ".rand(G_POSITIONING_PERCENT_1,G_POSITIONING_PERCENT_2).", 0, 'Positioning', 0),
					($PlayerID, ".rand(G_PASSING_PERCENT_1,G_PASSING_PERCENT_2).", 0, 'Passing', 0),
					($PlayerID, ".rand(G_CROSSING_PERCENT_1,G_CROSSING_PERCENT_2).", 0, 'Crossing', 0),
					($PlayerID, ".rand(G_LONGSHOTS_PERCENT_1,G_LONGSHOTS_PERCENT_2).", 0, 'LongShot', 0)";

			mysqli_query($GLOBALS['con'],$sql);
		}

		//fundas
		if ($post == 2) {
			$sql = "INSERT INTO percentage (PlayerId, percent, status, Characteristic, redist)
					VALUES
					($PlayerID, ".rand(D_TACKLING_PERCENT_1,D_TACKLING_PERCENT_2).", 0, 'Tackling', 0),
					($PlayerID, ".rand(D_MARKING_PERCENT_1,D_MARKING_PERCENT_2).", 0, 'Marking', 0),
					($PlayerID, ".rand(D_HEADING_PERCENT_1,D_HEADING_PERCENT_2).", 0, 'Heading', 0),
					($PlayerID, ".rand(D_POSITIONING_PERCENT_1,D_POSITIONING_PERCENT_2).", 0, 'Positioning', 0),
					($PlayerID, ".rand(D_PASSING_PERCENT_1,D_PASSING_PERCENT_2).", 0, 'Passing', 0),
					($PlayerID, ".rand(D_CROSSING_PERCENT_1,D_CROSSING_PERCENT_2).", 0, 'Crossing', 0),
					($PlayerID, ".rand(D_COMMUNICATION_PERCENT_1,D_COMMUNICATION_PERCENT_2).", 0, 'Communication', 0),
					($PlayerID, ".rand(D_FIRSTTOUCH_PERCENT_1,D_FIRSTTOUCH_PERCENT_2).", 0, 'FirstTouch', 0)";

			mysqli_query($GLOBALS['con'],$sql);
		}
		//midfieldas
		if ($post == 3) {
			$sql = "INSERT INTO percentage (PlayerId, percent, status, Characteristic, redist)
					VALUES
					($PlayerID, ".rand(M_PASSING_PERCENT_1,M_PASSING_PERCENT_2).", 0, 'Passing', 0),
					($PlayerID, ".rand(M_CREATIVITY_PERCENT_1,M_CREATIVITY_PERCENT_2).", 0, 'Creativity', 0),
					($PlayerID, ".rand(M_CROSSING_PERCENT_1,M_CROSSING_PERCENT_2).", 0, 'Crossing', 0),
					($PlayerID, ".rand(M_LONGSHOTS_PERCENT_1,M_LONGSHOTS_PERCENT_2).", 0, 'LongShot', 0),
					($PlayerID, ".rand(M_DRIBBLING_PERCENT_1,M_DRIBBLING_PERCENT_2).", 0, 'Dribbling', 0),
					($PlayerID, ".rand(M_POSITIONING_PERCENT_1,M_POSITIONING_PERCENT_2).", 0, 'Positioning', 0),
					($PlayerID, ".rand(M_TACKLING_PERCENT_1,M_TACKLING_PERCENT_2).", 0, 'Tackling', 0),
					($PlayerID, ".rand(M_FIRSTTOUCH_PERCENT_1,M_FIRSTTOUCH_PERCENT_2).", 0, 'FirstTouch', 0),
					($PlayerID, ".rand(M_MARKING_PERCENT_1,M_MARKING_PERCENT_2).", 0, 'Marking', 0)";

			mysqli_query($GLOBALS['con'],$sql);
		}
		//atacant
		if ($post == 4) {
			$sql = "INSERT INTO percentage (PlayerId, percent, status, Characteristic, redist)
					VALUES
					($PlayerID, ".rand(F_SHOOTING_PERCENT_1,F_SHOOTING_PERCENT_2).", 0, 'Shooting', 0),
					($PlayerID, ".rand(F_HEADING_PERCENT_1,F_HEADING_PERCENT_2).", 0, 'Heading', 0),
					($PlayerID, ".rand(F_POSITIONING_PERCENT_1,F_POSITIONING_PERCENT_2).", 0, 'Positioning', 0),
					($PlayerID, ".rand(F_DRIBBLING_PERCENT_1,F_DRIBBLING_PERCENT_2).", 0, 'Dribbling', 0),
					($PlayerID, ".rand(F_FIRSTTOUCH_PERCENT_1,F_FIRSTTOUCH_PERCENT_2).", 0, 'FirstTouch', 0)";


			mysqli_query($GLOBALS['con'],$sql);
		}
	}

	private function SetMaxValues($PlayerID=0) {
		//un SS e atit de talentat, incit poate sa ajunga la orice caract la nivel maxim
		//dar din moemnt ce doar anumite caract au percentage de antrenare,nuva ajunge ca un atacant sa aiba tackling 50
		//de aceea, in vmaxpos voi pune random de coeficient (iar la SS e aproape de 50), dar fara probleme
	
		//will never be
		if ($this->Talent < 21) {
			$coeficient = CST_WNnou;
			$cst_talent = 'CST_TAL_WN';
			$rand=8;
		}
		//one change
		if ($this->Talent > 20 && $this->Talent < 41) {
			$coeficient = CST_OCnou;
			$cst_talent = 'CST_TAL_OC';
			$rand=6;
		}
		//can be
		if ($this->Talent > 40 && $this->Talent < 61) {
			$coeficient = CST_CBnou;
			$cst_talent = 'CST_TAL_CB';
			$rand=5;
		}
		//very talented
		if ($this->Talent > 60 && $this->Talent < 81) {
			$coeficient = CST_VTnou;
			$cst_talent = 'CST_TAL_VT';
			$rand=4;
		}
		//superstar
		if ($this->Talent > 80) {
			$coeficient = CST_SSnou;
			$cst_talent = 'CST_TAL_SS';
			$rand=3;
		}	

		
		$m_Reflexes = rand($coeficient-$rand, $coeficient+1);
		$m_Reflexes = $m_Reflexes>50? 50: $m_Reflexes;
		$m_OneOnOne = rand($coeficient-$rand, $coeficient+1);
		$m_OneOnOne = $m_OneOnOne>50? 50: $m_OneOnOne;
		$m_Handling = rand($coeficient-$rand, $coeficient+1);
		$m_Handling = $m_Handling>50? 50: $m_Handling;
		$m_Communication = rand($coeficient-$rand, $coeficient+1);
		$m_Communication = $m_Communication>50? 50: $m_Communication;
		$m_Tackling = rand($coeficient-$rand, $coeficient+1);
		$m_Tackling = $m_Tackling>50? 50:$m_Tackling;
		$m_Passing = rand($coeficient-$rand, $coeficient+1);
		$m_Passing = $m_Passing>50?50: $m_Passing;
		$m_LongShot = rand($coeficient-$rand, $coeficient+1);
		$m_LongShot = $m_LongShot>50? 50: $m_LongShot;
		$m_Shooting = rand($coeficient-$rand, $coeficient+1);
		$m_Shooting = $m_Shooting>50?50:$m_Shooting;
		$m_Heading = rand($coeficient-$rand, $coeficient+1);
		$m_Heading = $m_Heading>50? 50: $m_Heading;
		$m_Creativity = rand($coeficient-$rand, $coeficient+1);
		$m_Creativity = $m_Creativity>50?50:$m_Creativity;
		$m_Crossing = rand($coeficient-$rand, $coeficient+1);
		$m_Crossing = $m_Crossing>50? 50 : $m_Crossing;
		$m_Marking = rand($coeficient-$rand, $coeficient+1);
		$m_Marking = $m_Marking>50? 50: $m_Marking;
		$m_FirstTouch = rand($coeficient-$rand, $coeficient+1);
		$m_FirstTouch = $m_FirstTouch>50? 50 : $m_FirstTouch;
		$m_Strength = rand($coeficient-$rand, $coeficient+1);
		$m_Strength = $m_Strength>50?50:$m_Strength;
		$m_Speed = rand($coeficient-$rand, $coeficient+1);
		$m_Speed = $m_Speed>50?50:$m_Speed;
		$m_Dribbling = rand($coeficient-$rand, $coeficient+1);
		$m_Dribbling = $m_Dribbling>50? 50: $m_Dribbling;
		$m_Positioning = rand($coeficient-$rand, $coeficient+1);
		$m_Positioning = $m_Positioning>50?50:$m_Positioning;
		
		$sql = "INSERT INTO vmaxpos (
		playerId, reflexes, OneOnOne, Handling, Communication, Tackling, Passing, LongShot, Shooting, Heading, Creativity, Crossing, Marking, FirstTouch, Strength, Speed, Dribbling, Positioning) 
		VALUES ($PlayerID, " .
		$m_Reflexes . ", " . $m_OneOnOne . ", " . $m_Handling . ", " . $m_Communication . ", " . $m_Tackling . ", " . $m_Passing . ", " . $m_LongShot . ", " . $m_Shooting . ", " . $m_Heading . ", " . $m_Creativity . ", " . $m_Crossing . ", " . $m_Marking . ", " . $m_FirstTouch . ", " . $m_Strength . ", " . $m_Speed . ", " . $m_Dribbling . ", " . $m_Positioning . ")";

		mysqli_query($GLOBALS['con'],$sql);
		//echo "<br/>$sql";
	
		//ini table grow
		$sql = "INSERT INTO grow (playerId) VALUES ($PlayerID)";
		mysqli_query($GLOBALS['con'],$sql);
		
		//ini table leap
		$sql = "INSERT INTO leap (playerId, fromLastLeap, cstLeap) 
				VALUES ($PlayerID, 0, '$cst_talent')";
		mysqli_query($GLOBALS['con'],$sql);
		//echo "<br/>$sql";

	
	}


	function EchoPlayer() {
		switch ($this->Position) {
				case 1: $pos = "GK (Goalkeeper)"; $gk="green";$md="grey";$fw="grey";$dffw="grey"; $df="grey"; break;
				case 2: $pos = "DR (Defender - Right)";  $gk="grey";$md="grey";$fw="grey";$dffw="green"; $df="green"; break;
				case 3: $pos = "DC (Defender - Center)"; $gk="grey";$md="grey";$fw="grey";$dffw="green"; $df="green";break;
				case 4: $pos = "DL (Defender - Left)"; $gk="grey";$md="grey";$fw="grey";$dffw="green"; $df="green";break;
				case 5: $pos = "MR (Midfielder - Right)"; $gk="grey";$md="green";$fw="grey";$dffw="grey"; $df="grey";break;
				case 6: $pos = "MC (Midfielder - Center)"; $gk="grey";$md="green";$fw="grey";$dffw="grey"; $df="grey";break;
				case 7: $pos = "ML (Midfielder - Left)"; $gk="grey";$md="green";$fw="grey";$dffw="grey"; $df="grey";break;
				case 8: $pos = "FR (Forward - Right)"; $gk="grey";$md="grey";$fw="green";$dffw="green"; $df="grey";break;
				case 9: $pos = "FC (Forward - Center)"; $gk="grey";$md="grey";$fw="green";$dffw="green"; $df="grey";break;
				case 10: $pos = "FL (Forward - Left)"; $gk="grey";$md="grey";$fw="green";$dffw="green"; $df="grey";break;
		}
		echo "<h3 class=\"mb-30\">";
		if($this->number<>0)
			echo "&nbsp;<font class=\"number\">".$this->number."</font>";
		
		echo $this->FirstName." ".$this->LastName."(".$this->Rating.")</h3>";
		if($this->Transfer == 1) {
			$disabled = "";
			$renunta = "";
			if($this->UserID == $_SESSION['USERID']) $disabled="disabled";
			if($this->UserID != $_SESSION['USERID']) $renunta="disabled";
			echo "<form action=\"index.php?option=viewplayer?uid=$this->UserID&pid=".$_REQUEST['pid']."\" method=\"POST\">";
			echo "<input type=\"hidden\" name=\"option\" value=\"viewplayer\">";
			echo "<input type=\"hidden\" name=\"pid\" value=\"".$_REQUEST['pid']."\">";
			echo "<input type=\"hidden\" name=\"uid\" value=\"$this->UserID\">";
			if($this->TransferDeadline == "0000-00-00 00:00:00") {
				//echo "TEST::".$this->UserID;
				if($this->UserID == 0) {
					$this->TransferSuma = $this->Wage + 1000;
				}
				//aici
				$tsuma = number_format($this->TransferSuma);
				
				echo "<h3>This player is on transfer list! <input type=\"hidden\" name=\"BidValue\" value=\"$this->TransferSuma\"><input type=\"Submit\" name=\"StartBidPlayer\" value=\"Offer $tsuma &euro;\" $disabled class=\"button-2\"></h3>";
				if($this->UserID == 0) {
					//trebuie sa se liciteze salariul
					echo "<h3>Free player. He will play for the team who pays more!</h3>";
				}
			} else {
				$sql = "SELECT p.userid, p.suma, u.TeamName
						FROM playerbid p
						left outer join user u
						on p.userid=u.id
						WHERE p.activ=1 AND p.playerid=$this->PlayerID
						ORDER BY p.suma DESC";
				$res = mysqli_query($GLOBALS['con'],$sql);
				list($bid_userid, $bid_suma, $bid_TeamName) = mysqli_fetch_row($res);
				mysqli_free_result($res);
				$bid_depariat = $bid_suma+1000;
				//echo $this->TransferDeadline . '     ' . Date("Y-m-d H:i:s").'<br/>';
				if($this->TransferDeadline<=Date("Y-m-d H:i:00")) {
					//s-a terminat timpul
					echo "<h3>Player bought by $bid_TeamName with $bid_suma &euro;! Player will join next day.</h3>";
					
				} else {
					$deafi = strlen($bid_TeamName)>10? substr($bid_TeamName,0,10)."...":$bid_TeamName;
					$bsuma = number_format($bid_suma);
					$bdepariat = number_format($bid_depariat);
					$date = date("Y-m-d H:i",strtotime($this->TransferDeadline));
					//echo date('H', $date);
					echo "<h3>Bid started ($deafi - $bsuma &euro;)! Expires on ".$date."!<input class=\"input-1\" type=\"text\" name=\"BidValue\" value=\"$bid_depariat\" $disabled> &euro; <input type=\"Submit\" name=\"BidPlayer\" value=\"Offer\" $disabled class=\"button-2\"></h3>";
					if($this->UserID == 0) {
						//trebuie sa se liciteze salariul
						echo "<h3>Free player. He will join the team who will pay the biggest wage!</h3>";
					}
				}
				}
			echo "</form>";
		}
		if($this->Avatar == "") $ima = "missing_player.jpg";
		else $ima = $this->Avatar;
//		echo "<img src=\"images/missing_player.jpg\" class=\"img-1\" width=\"180\"  align=\"left\">";
		echo "<img src=\"images/$ima\" class=\"img-1\" width=\"180\"  align=\"left\">";
		
		//un jucator inactiv trebuie sa nu aibe echipa, sa nu fie pe lista de transfer, sa nu apara in alegerea de formatie
        if($this->Active == 0)
			echo "<h3>The player is retired!</h3>";
		echo "<img src=\"steaguri/".$this->NationalityName.".png\" width=\"32\">";
		echo "<br/>Country: ". $this->NationalityName . "<br/>";
		echo "Age:".$this->Age." y.o<br/>";
		echo "Position: $pos";
		if($this->Injured == 1) {
			echo " - <font class=\"button-2\">Injured. He will be fit in ".$this->injuryData."!</font><br/>";
		} else echo "<br/>";
		if($this->Training == 1) {
			echo " - <font class=\"button-2\">Sent to re-position. He will be back in ".$this->RespeData."!</font><br/>";
		} else echo "<br/>";
		echo "Wage: ".number_format($this->Wage) . " &euro;/week<br/>";
		echo "Contract: " . $this->Contract . " (seasons)<br/>";
		echo "Value: ".number_format($this->Value) . " &euro;<br/>";
		//echo "Forma: ".$this->Form." %<br/>";
		echo "Form: <img src=\"baragrafica.php?percentage=".$this->Form."\"><br/>";
		//echo "Moral: <img src=\"baragrafica.php?percentage=".$this->Moral."\"><br/>";
		echo "Team: ".$this->Proprietar.'<br/>';
		echo "Weeks at the club: ".$this->weeks.'<br/>';

		//echo "aici.......................".translate('Marking').'<br/>';
		//echo "aici.......................".translate('LongShot').'<br/>';
		$sql = "SELECT characteristic
				FROM loggrows
				WHERE data='".Date("Y-m-d")."' AND playerid=".$this->PlayerID;
		//echo "$sql<br/>";
		$res = mysqli_query($GLOBALS['con'],$sql);
		list($Characteristic) = mysqli_fetch_row($res);
		mysqli_free_result($res);
		$img = '<img src="images/crestere.png" width="12">';
		switch($Characteristic) {
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
	?>
				<table class="tftable" width="100%" cellpadding="1">
					<tr>
						<th><font color="<?php echo $gk; ?>"><?php echo translate('Reflexes'); ?></font></th>
						<th><font color="<?php echo $gk; ?>"><?php echo translate('OneonOne'); ?></font></th>
						<th><font color="<?php echo $gk; ?>"><?php echo translate('Handling'); ?></font></th>
						<th><font color="<?php echo $df; ?>"><?php echo translate('Tackling'); ?></font></th>	
						<th><font color="<?php echo $df; ?>"><?php echo translate('Marking'); ?></font></th>	
						<th><font color="<?php echo $dffw; ?>"><?php echo translate('Heading'); ?></font></th>
						<th><font color="<?php echo $md; ?>"><?php echo translate('LongShot'); ?></font></th>
					</tr>
					<tr class="tr-1">			
						<td><?php ColorIt($this->reflexes); echo $img_ref; ?></td>
						<td><?php ColorIt($this->OneOnOne); echo $img_ooo; ?></td>
						<td><?php ColorIt($this->Handling); echo $img_han; ?></td>	
						<td><?php ColorIt($this->Tackling); echo $img_tack; ?></td>	
						<td><?php ColorIt($this->Marking); echo $img_mark; ?></td>
						<td><?php ColorIt($this->Heading); echo $img_head; ?></td>
						<td><?php ColorIt($this->LongShot); echo $img_long; ?></td>
						
					</tr>
					<tr>
						<th><font color="<?php echo $md; ?>"><?php echo translate('Positioning'); ?></font></th>
						<th><font color="<?php echo $fw; ?>"><?php echo translate('Shooting'); ?></font></th>
						<th><font color="<?php echo $md; ?>"><?php echo translate('FirstTouch'); ?></font></th>
						<th><font color="<?php echo $md; ?>"><?php echo translate('Creativity'); ?>	</font></th>	
						<th><font color="<?php echo $md; ?>"><?php echo translate('Crossing'); ?></font></th>	
						<th><font color="<?php echo $md; ?>"><?php echo translate('Passing'); ?></font></th>
						<th><font color="<?php echo $gk; ?>"><?php echo translate('Communication'); ?></font></th>
					</tr>

					<tr class="tr-2">
						<td><?php ColorIt($this->Positioning); echo $img_pos; ?></td>
						<td><?php ColorIt($this->Shooting); echo $img_shoot; ?></td>
						<td><?php ColorIt($this->FirstTouch); echo $img_first; ?></td>	
						<td><?php ColorIt($this->Creativity); echo $img_crea; ?></td>	
						<td><?php ColorIt($this->Crossing); echo $img_cros; ?></td>
						<td><?php ColorIt($this->Passing); echo $img_pass; ?></td>
						<td><?php ColorIt($this->Communication); echo $img_com; ?></td>
					</tr>
					 	
					<tr>
						<th><?php echo translate('TeamWork'); ?></th>
						<th><?php echo translate('Conditioning'); ?></th>
						<th><?php echo translate('Speed'); ?></th>
						<th><?php echo translate('Experience'); ?></th>	
						<th><?php echo translate('Condition'); ?></th>	
						<th><?php echo translate('Dribbling'); ?></th>
						<th><?php echo translate('Aggresivity'); ?></th>
					</tr>
					<tr>
						<td><?php ColorIt($this->TeamWork); echo $img_team; ?></td>
						<td><?php ColorIt($this->Strength); echo $img_stre; ?></td>
						<td><?php ColorIt($this->Speed); echo $img_speed; ?></td>	
						<td><?php ColorIt($this->Experience); echo $img_exp; ?></td>	
						<td><?php ColorIt($this->Condition); echo $img_cond; ?></td>
						<td><?php ColorIt($this->Dribbling); echo $img_drib; ?></td>
						<td><?php ColorIt($this->Aggresivity); echo $img_agre; ?></td>
					</tr>

				</table>
	
<?php
		echo '<br/><br/>';
		$compensatii = ($this->Wage*7) * $this->Contract/2;
		echo "<form action=\"index.php?option=club\" method=\"POST\">";
		echo "<input type=\"hidden\" name=\"compensatii\" value=\"$compensatii\">";
		echo "<input type=\"hidden\" name=\"uid\" value=\"".$this->UserID."\">";
		echo "<input type=\"hidden\" name=\"pid\" value=\"".$this->PlayerID."\">";
		$renunta = "";
		//echo $this->TransferDeadline.'<br/>';
		if($this->UserID == $_SESSION['USERID'] && $this->TransferDeadline == "0000-00-00 00:00:00"){
			echo "<h3>Get player's talent (aprox., related to the trainer's quality):<input type=\"Submit\" name=\"CerceteazaTalent\" value=\"Check Talent\" class=\"button-22\"></h3>";
		

			echo "<h3>Stop contract:<input type=\"Submit\" name=\"InceteazaContract\" value=\"You will pay ".number_format($compensatii)." &euro;\" class=\"button-22\"></h3>";
		
		}
		echo "</form>";
		$salariunou = ($this->Wage) * 1.35;
		echo "<form action=\"index.php?option=club\" method=\"POST\">";
		echo "<input type=\"hidden\" name=\"salariunou\" value=\"$salariunou\">";
		echo "<input type=\"hidden\" name=\"uid\" value=\"".$this->UserID."\">";
		echo "<input type=\"hidden\" name=\"pid\" value=\"".$this->PlayerID."\">";
		$renunta = "";
		//echo $this->TransferDeadline.'<br/>';
		if($this->UserID == $_SESSION['USERID'] && $this->TransferDeadline == "0000-00-00 00:00:00"){
			echo "<h3>Renew contract:<input type=\"Submit\" name=\"ReinnoiesteContract\" value=\"The wage will be ".number_format($salariunou)." &euro;\" class=\"button-22\"></h3>";
		}
		echo "</form>";

		echo "<br/>";

		echo "<form action=\"index.php?option=club\" method=\"POST\" onSubmit=\"return validatereassign(this);\">";
		echo "<input type=\"hidden\" name=\"uid\" value=\"".$this->UserID."\">";
		echo "<input type=\"hidden\" name=\"pid\" value=\"".$this->PlayerID."\">";
		$renunta = "";
		//echo $this->TransferDeadline.'<br/>';
		if($this->UserID == $_SESSION['USERID'] && $this->TransferDeadline == "0000-00-00 00:00:00"){
			echo "<h3>Change his position! Make him <select name=\"postnou\" class=\"input-2\">";
			for($i=1;$i<11;$i++) {
				switch ($i) {
					case 1: $pos = "GK (Goalkeeper)";break;
					case 2: $pos = "DR (Defender - Right)";break;
					case 3: $pos = "DC (Defender - Center)";break;
					case 4: $pos = "DL (Defender - Left)"; break;
					case 5: $pos = "MR (Midfielder - Right)";break;
					case 6: $pos = "MC (Midfielder - Center)";break;
					case 7: $pos = "ML (Midfielder - Left)";break;
					case 8: $pos = "FR (Forward - Right)";break;
					case 9: $pos = "FC (Forward - Center)";break;
					case 10: $pos = "FL (Forward - Left)";break;
				}
				echo "<option value=\"$i\">$pos";
			}
			echo "</select> &nbsp;";
			$disabled="";
			if($this->Training == 1) $disabled="disabled";
			echo "<input $disabled type=\"Submit\" name=\"reassign\" value=\"Training camp\" class=\"button-22\"></h3>";
		}
		echo "</form>";
		$salariunou = ($this->Wage) * 1.05;
		echo "<form action=\"index.php?option=club\" method=\"POST\">";
		echo "<input type=\"hidden\" name=\"salariunou\" value=\"$salariunou\">";
		echo "<input type=\"hidden\" name=\"uid\" value=\"".$this->UserID."\">";
		echo "<input type=\"hidden\" name=\"pid\" value=\"".$this->PlayerID."\">";
		$renunta = "";
		//echo $this->TransferDeadline.'<br/>';
		if($this->UserID == $_SESSION['USERID'] && $this->TransferDeadline == "0000-00-00 00:00:00"){
			echo "<h3>Renew his contract with one year:<input type=\"Submit\" name=\"ReinnoiesteContract\" value=\"The wage will be ".number_format($salariunou)." &euro;\" class=\"button-22\"></h3>";
		}
		echo "</form>";


		$this->Viewleap();
		$this->ViewTalent();
}


	function EchoPlayerPtMesaj() {
		return $this->FirstName." ".$this->LastName."(".$this->Rating.")</h1><br/>";
}


	function ViewTalent() {
		if($this->UserID == $_SESSION['USERID']) { 
			//echo "<h1>Verificare talent</h1>";
			echo "<table class=\"tftable\">";
			echo "<tr>";
			$sql = "SELECT talent, data
					FROM talent
					WHERE userid=".$this->UserID." AND playerid=".$this->PlayerID;
			$res = mysqli_query($GLOBALS['con'],$sql);
			while(list($talent, $data) = mysqli_fetch_row($res)) {
				echo "<td>$data</td><td>";
				switch($talent) {
					case 1: echo "He will not be a good player!"; break;
					case 2: echo "Small chances to be a good player!"; break;
					case 3: echo "Can be a good player!"; break;
					case 4: echo "He is talented, count on him!"; break;
					case 5: echo "He will be a great player!"; break;
				}
				echo "</td>";
			}
			echo "</tr></table>";
			mysqli_free_result($res);
		}
	}


	function EchoPlayerShort2() {
		
		switch ($this->Position) {
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
		switch ($this->Position) {
				case 1: $poss = 1; break;
				case 2: $poss = 2; break;
				case 3: $poss = 2; break;
				case 4: $poss = 2; break;
				case 5: $poss = 3; break;
				case 6: $poss = 3; break;
				case 7: $poss = 3; break;
				case 8: $poss = 4; break;
				case 9: $poss = 4; break;
				case 10: $poss = 4; break;
		}


		echo "<tr>";
		echo "<td class=\"mark\"><font class=\"number-tricou\">&nbsp;".$pos."&nbsp;</font>&nbsp;</td>";
		echo "<td><img src=\"steaguri/".$this->NationalityName.".png\" width=\"18\" valign=\"middle\"></td><td><a class=\"link-5\" href=\"index.php?option=viewplayer&pid=".$this->PlayerID."&uid=".$_REQUEST['club_id']."\">".$this->FirstName." ".$this->LastName."</a></td>";
		echo "<td>&nbsp;".$this->Age." ani</td>";
		//echo "<div class=\"hr-replace-2\"></div>";
		if($this->Transfer == 1 && $this->TransferDeadline == '0000-00-00 00:00:00') {
			//jucatorul este transferabil
			//apare un T in dreptul lui
			echo "<td><font class=\"number-tricou\">&nbsp;T&nbsp;</font></td>";
		} 
		if($this->Transfer == 1 && $this->TransferDeadline != '0000-00-00 00:00:00') {
			echo "<td><img src=\"images/bagofmoney.png\" width=\"20\"></td>";
		}
		echo "</tr>";

	}


	function EchoPlayerShort() {
		
		switch ($this->Position) {
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
		switch ($this->Position) {
				case 1: $poss = 1; break;
				case 2: $poss = 2; break;
				case 3: $poss = 2; break;
				case 4: $poss = 2; break;
				case 5: $poss = 3; break;
				case 6: $poss = 3; break;
				case 7: $poss = 3; break;
				case 8: $poss = 4; break;
				case 9: $poss = 4; break;
				case 10: $poss = 4; break;
		}

		//echo "AICI";
		echo "<font class=\"number-tricou\">&nbsp;".$pos."&nbsp;</font>&nbsp;";
		if($this->number<>0)
			echo "&nbsp;<font class=\"number\">".$this->number."</font>";
		echo "<img src=\"steaguri/".$this->NationalityName.".png\" width=\"18\" valign=\"middle\"><a class=\"link-5\" href=\"echipa.php?id=".$this->PlayerID."&poss=".$poss."\">".$this->FirstName." ".$this->LastName."</a>";
		echo "&nbsp;".$this->Age." ani";
		if($this->Transfer == 1 && $this->TransferDeadline == '0000-00-00 00:00:00') {
			//jucatorul este transferabil
			//apare un T in dreptul lui
			echo "<font class=\"number-tricou\">&nbsp;T&nbsp;</font>";
		} 
		if($this->Transfer == 1 && $this->TransferDeadline != '0000-00-00 00:00:00') {
			echo "<img src=\"images/bagofmoney.png\" width=\"20\">";
		}
		
		echo "<div class=\"hr-replace-2\"></div>";

	}



	function GetDFWork(){

		//defensive score for the player, no matter he is midfielder or attacker
		//in functie de tactics, de cum joaca compartimentul median, passes si atacks, sunt alte valori disponibile
		//tactics: 1-normal;2-attacking;3-defensive
		//midfield:1=normal;2-ofensiv;3-defensiv
		//atacks:1-mixte;2-laterale;3-centru
		//passes: 1-mixte;2-inalte;3-pe jos
		switch ($this->Position) {
				case 1: 
					$pos = "GK"; 
					$valDF = ($this->Reflexes + $this->OneOnOne + $this->Handling * .99 + $this->Positioning * .7 + $this->Communication * .99) * ($this->Condition/1000 + $this->Form/100);
					break;
				case 2: 
					$valDF = ($this->Tackling + $this->Marking + $this->Heading * .8 + $this->Positioning * .7 + $this->Speed * .3 + $this->FirstTouch *.15 + $this->Communication*.07 + $this->Aggresivity * .17) * ($this->Condition/1000 + $this->Form/100);

					//what kind of passes
					//when playing high passes, it is important more the head game
					switch($this->passes) {
						case 1: break;
						case 2: 
						//high passes, add more factor for heading
						$valDF += ($this->Heading * .8) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//low passes - heading is not important
						$valDF -= ($this->Heading * .5) * ($this->Condition/1000 + $this->Form/100);break;
						default:
						//default
						$valDF -= ($this->Heading * .5) * ($this->Condition/1000 + $this->Form/100);break;
					}
					
					
					break;
				case 3: 
					$valDF = ($this->Tackling + $this->Marking + $this->Heading * .8 + $this->Positioning * .7 + $this->Speed * .3 + $this->FirstTouch *.15 + $this->Communication*.07 + $this->Aggresivity * .17) * ($this->Condition/1000 + $this->Form/100);
					//jocul cu passes
					//when playing high passes, it is important more the head game
					switch($this->passes) {
						case 1: break;
						case 2: 
						//passes inalte, adaug la valoare un supliment de cap
						$valDF += ($this->Heading * .8) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//passes joase - scot heading din calcul
						$valDF -= ($this->Heading * .5) * ($this->Condition/1000 + $this->Form/100);break;
						default:
						//passes joase - scot heading din calcul
						$valDF -= ($this->Heading * .5) * ($this->Condition/1000 + $this->Form/100);break;
					}
					break;
				case 4: 
					$valDF = ($this->Tackling + $this->Marking + $this->Heading * .8 + $this->Positioning * .7 + $this->Speed * .3 + $this->FirstTouch *.15 + $this->Communication*.07 + $this->Aggresivity * .17) * ($this->Condition/1000 + $this->Form/100);
					//jocul cu passes
					//when playing high passes, it is important more the head game
					switch($this->passes) {
						case 1: break;
						case 2: 
						//passes inalte, adaug la valoare un supliment de cap
						$valDF += ($this->Heading * .8) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//passes joase - scot heading din calcul
						$valDF -= ($this->Heading * .5) * ($this->Condition/1000 + $this->Form/100);break;
						default:
						//passes joase - scot heading din calcul
						$valDF -= ($this->Heading * .5) * ($this->Condition/1000 + $this->Form/100);break;
					}
					break;
				case 5: 
					switch($this->tactics) {
						case 1: $valDF = ($this->Tackling * .3 + $this->Marking *.3 + $this->Heading * .1) * ($this->Condition/1000 + $this->Form/100); break;
						case 2: $valDF = ($this->Tackling * .11 + $this->Marking *.11 + $this->Heading * .05) * ($this->Condition/1000 + $this->Form/100); break;
						case 3:
							//tactics defensiva. midfield da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .71 + $this->Marking *.71 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
						default:
							//tactics defensiva. midfield da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .71 + $this->Marking *.71 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
					}
					break;
				case 6: 
					switch($this->tactics) {
						case 1: $valDF = ($this->Tackling * .3 + $this->Marking *.3 + $this->Heading * .1) * ($this->Condition/1000 + $this->Form/100); break;
						case 2: $valDF = ($this->Tackling * .11 + $this->Marking *.11 + $this->Heading * .05) * ($this->Condition/1000 + $this->Form/100); break;
						case 3:
							//tactics defensiva. midfield da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .71 + $this->Marking *.71 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
						default:
							//tactics defensiva. midfield da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .71 + $this->Marking *.71 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
					}
					break;
				case 7: 
					switch($this->tactics) {
						case 1: $valDF = ($this->Tackling * .3 + $this->Marking *.3 + $this->Heading * .1) * ($this->Condition/1000 + $this->Form/100); break;
						case 2: $valDF = ($this->Tackling * .11 + $this->Marking *.11 + $this->Heading * .05) * ($this->Condition/1000 + $this->Form/100); break;
						case 3:
							//defensive tactics. midfield da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .71 + $this->Marking *.71 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
						default:
							//defensive tactics. midfield da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .71 + $this->Marking *.71 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
					}
					break;
				case 8: 
					switch($this->tactics) {
						case 1: $valDF = ($this->Heading * .1) * ($this->Condition/1000 + $this->Form/100); break;
						case 2: 
							//tactics ofensiva. attacker is not so important for defense
							$valDF = ($this->Heading * .05) * ($this->Condition/1000 + $this->Form/100); break;
						case 3:
							//tactics defensiva. atacantul da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .31 + $this->Marking *.31 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
						default:
							//tactics defensiva. atacantul da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .31 + $this->Marking *.31 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
					}
					break;
				case 9: 
					switch($this->tactics) {
						case 1: $valDF = ($this->Heading * .1) * ($this->Condition/1000 + $this->Form/100); break;
						case 2: 
							//tactics ofensiva. attacker is not so important for defense
							$valDF = ($this->Heading * .05) * ($this->Condition/1000 + $this->Form/100); break;
						case 3:
							//tactics defensiva. atacantul da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .31 + $this->Marking *.31 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
						default:
							//tactics defensiva. atacantul da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .31 + $this->Marking *.31 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
					}
					break;
				case 10: 
					switch($this->tactics) {
						case 1: $valDF = ($this->Heading * .1) * ($this->Condition/1000 + $this->Form/100); break;
						case 2: 
							//tactics ofensiva. attacker is not so important for defense
							$valDF = ($this->Heading * .05) * ($this->Condition/1000 + $this->Form/100); break;
						case 3:
							//tactics defensiva. atacantul da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .31 + $this->Marking *.31 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
						default:
							//tactics defensiva. atacantul da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .31 + $this->Marking *.31 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
					}
					break;
		}
	$this->valDF = $valDF;

	//echo "Defensive value of the team: ".$this->valDF.'<br/>';
	return $this->valDF;
	}

	function GetMFWork(){

		switch ($this->Position) {
				case 1: 
					$pos = "GK"; 
					$valMF = 0;
					break;
				case 2: 
					$valMF = ($this->Passing*.3 + $this->Crossing*.3 + $this->LongShot * .1) * ($this->Condition/1000 + $this->Form/100);
					break;
				case 3: 
					$valMF = ($this->Passing*.3 + $this->Crossing*.3 + $this->LongShot * .1) * ($this->Condition/1000 + $this->Form/100);
					break;
				case 4: 
					$valMF = ($this->Passing*.3 + $this->Crossing*.3 + $this->LongShot * .1) * ($this->Condition/1000 + $this->Form/100);
					break;
				case 5: 
					//jocul cu passes
					//when playing high passes, it is important more the head game
					//posesia sufera
					switch($this->passes) {
						case 1: 
							$valMF = ($this->Passing + $this->Crossing + $this->Creativity + $this->LongShot * .7 + $this->Dribbling *.5 + $this->Speed *.3) * ($this->Condition/1000 + $this->Form/100);
							break;
						case 2: 
						//passes inalte, adaug la valoare un supliment de cap
						$valMF = ($this->Passing*.79 + $this->Crossing*1.2 + $this->Creativity*.79 + $this->LongShot * .97 + $this->Dribbling *.2 + $this->Speed *.6) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//passes joase - scot heading din calcul
						$valMF = ($this->Passing*1.1 + $this->Crossing*0.9 + $this->Creativity*1.1 + $this->LongShot * .7 + $this->Dribbling *.5 + $this->Speed *.3) * ($this->Condition/1000 + $this->Form/100);break;
						default:
							$valMF = ($this->Passing + $this->Crossing + $this->Creativity + $this->LongShot * .7 + $this->Dribbling *.5 + $this->Speed *.3) * ($this->Condition/1000 + $this->Form/100);
							break;
					}

					break;
				case 6: 
					//jocul cu passes
					//when playing high passes, it is important more the head game
					//posesia sufera
					switch($this->passes) {
						case 1: 
							$valMF = ($this->Passing + $this->Crossing + $this->Creativity + $this->LongShot * .7 + $this->Dribbling *.5 + $this->Speed *.3) * ($this->Condition/1000 + $this->Form/100);
							break;
						case 2: 
						//passes inalte, adaug la valoare un supliment de cap
						$valMF = ($this->Passing*.79 + $this->Crossing*1.2 + $this->Creativity*.79 + $this->LongShot * .97 + $this->Dribbling *.2 + $this->Speed *.6) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//passes joase - scot heading din calcul
						$valMF = ($this->Passing*1.1 + $this->Crossing*0.9 + $this->Creativity*1.1 + $this->LongShot * .7 + $this->Dribbling *.5 + $this->Speed *.3) * ($this->Condition/1000 + $this->Form/100);break;
						default:
						$valMF = ($this->Passing + $this->Crossing + $this->Creativity + $this->LongShot * .7 + $this->Dribbling *.5 + $this->Speed *.3) * ($this->Condition/1000 + $this->Form/100);
							break;
					}
					break;
				case 7: 
					//jocul cu passes
					//when playing high passes, it is important more the head game
					//posesia sufera
					switch($this->passes) {
						case 1: 
							$valMF = ($this->Passing + $this->Crossing + $this->Creativity + $this->LongShot * .7 + $this->Dribbling *.5 + $this->Speed *.3) * ($this->Condition/1000 + $this->Form/100);
							break;
						case 2: 
						//passes inalte, adaug la valoare un supliment de cap
						$valMF = ($this->Passing*.79 + $this->Crossing*1.2 + $this->Creativity*.79 + $this->LongShot * .97 + $this->Dribbling *.2 + $this->Speed *.6) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//passes joase - scot heading din calcul
						$valMF = ($this->Passing*1.1 + $this->Crossing*0.9 + $this->Creativity*1.1 + $this->LongShot * .7 + $this->Dribbling *.5 + $this->Speed *.3) * ($this->Condition/1000 + $this->Form/100);break;
						default:
						$valMF = ($this->Passing + $this->Crossing + $this->Creativity + $this->LongShot * .7 + $this->Dribbling *.5 + $this->Speed *.3) * ($this->Condition/1000 + $this->Form/100);
							break;
					}
					break;
				case 8: 
					$valMF = ($this->Passing*.3 + $this->LongShot * .1) * ($this->Condition/1000 + $this->Form/100);
					break;
				case 9: 
					$valMF = ($this->Passing*.3 + $this->LongShot * .1) * ($this->Condition/100 + $this->Form/100);
					break;
				case 10: 
					//echo "AA: ". $this->Passing*0.3 . " BB ".$this->LongShot * 0.1 ." ff". $this->Condition/100 ."gg". $this->Form/100;
					$valMF = ($this->Passing*.3 + $this->LongShot * .1) * ($this->Condition/1000 + $this->Form/100);
					break;
		}
	$this->valMF = $valMF;
	echo "MF: ". $this->PlayerID . " $valMF<br/>";
	return $this->valMF;
	}

	function GetFWWork(){
		switch ($this->Position) {
				case 1: 
					$pos = "GK"; 
					$valFW = 0;
					break;
				case 2: 
					$valFW = ($this->Heading*.3 + $this->Positioning*.3 + $this->Crossing *.25) * ($this->Condition/1000 + $this->Form/100);
					break;
				case 3: 
					$valFW = ($this->Heading*.3 + $this->Positioning*.3) * ($this->Condition/1000 + $this->Form/100);
					break;
				case 4: 
					$valFW = ($this->Heading*.3 + $this->Positioning*.3 + $this->Crossing *.25) * ($this->Condition/1000 + $this->Form/100);
					break;
				case 5: 
					switch($this->tactics) {
						case 1: $valFW = ($this->LongShot * .3 + $this->Creativity *.4 + $this->Passing*.4 + $this->Crossing *.3) * ($this->Condition/1000 + $this->Form/100);break;
						case 2: 
						//tactics ofensiva, mai mult aport la atac
						$valFW = ($this->LongShot * .55 + $this->Creativity *.67 + $this->Passing*.67 + $this->Crossing *.67 + $this->Shooting * .44 + $this->Heading * .44) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//tactics defensiva, aport mai slab la atac
						$valFW = ($this->LongShot * .12 + $this->Creativity *.17 + $this->Passing*.17 + $this->Crossing *.17) * ($this->Condition/1000 + $this->Form/100);break;
						default:
						//tactics defensiva, aport mai slab la atac
						$valFW = ($this->LongShot * .12 + $this->Creativity *.17 + $this->Passing*.17 + $this->Crossing *.17) * ($this->Condition/1000 + $this->Form/100);break;
					}
					break;
				case 6: 
					switch($this->tactics) {
						case 1: $valFW = ($this->LongShot * .3 + $this->Creativity *.4 + $this->Passing*.4 + $this->Crossing *.3) * ($this->Condition/1000 + $this->Form/100);break;
						case 2: 
						//tactics ofensiva, mai mult aport la atac
						$valFW = ($this->LongShot * .55 + $this->Creativity *.67 + $this->Passing*.67 + $this->Crossing *.67 + $this->Shooting * .44 + $this->Heading * .44) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//tactics defensiva, aport mai slab la atac
						$valFW = ($this->LongShot * .12 + $this->Creativity *.17 + $this->Passing*.17 + $this->Crossing *.17) * ($this->Condition/1000 + $this->Form/100);break;
						default:
						//tactics defensiva, aport mai slab la atac
						$valFW = ($this->LongShot * .12 + $this->Creativity *.17 + $this->Passing*.17 + $this->Crossing *.17) * ($this->Condition/1000 + $this->Form/100);break;
					}
					break;
				case 7: 
					switch($this->tactics) {
						case 1: $valFW = ($this->LongShot * .3 + $this->Creativity *.4 + $this->Passing*.4 + $this->Crossing *.3) * ($this->Condition/1000 + $this->Form/100);break;
						case 2: 
						//tactics ofensiva, mai mult aport la atac
						$valFW = ($this->LongShot * .55 + $this->Creativity *.67 + $this->Passing*.67 + $this->Crossing *.67 + $this->Shooting * .44 + $this->Heading * .44) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//tactics defensiva, aport mai slab la atac
						$valFW = ($this->LongShot * .12 + $this->Creativity *.17 + $this->Passing*.17 + $this->Crossing *.17) * ($this->Condition/1000 + $this->Form/100);break;
						default:
						//tactics defensiva, aport mai slab la atac
						$valFW = ($this->LongShot * .12 + $this->Creativity *.17 + $this->Passing*.17 + $this->Crossing *.17) * ($this->Condition/1000 + $this->Form/100);break;
					}
					break;
				case 8: 
					$valFW = ($this->Shooting + $this->Heading + $this->Positioning *.7 + $this->Speed * .5 + $this->Dribbling *.4 +$this->FirstTouch *.33) * ($this->Condition/1000 + $this->Form/100);

					//jocul cu passes
					//when playing high passes, it is important more the head game
					switch($this->passes) {
						case 1: 
							break;
						case 2: 
						//passes inalte, adaug la valoare un supliment de cap
						$valFW += ($this->Heading*.49 + $this->FirstTouch * .22) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//passes joase - scot heading din calcul si pun firsttouch
						$valFW -= ($this->Heading*.39 - $this->FirstTouch * .69) * ($this->Condition/1000 + $this->Form/100);break;				
						default:
						//passes joase - scot heading din calcul si pun firsttouch
						$valFW -= ($this->Heading*.39 - $this->FirstTouch * .69) * ($this->Condition/1000 + $this->Form/100);break;				
					}

					break;
				case 9: 
					$valFW = ($this->Shooting + $this->Heading + $this->Positioning *.7 + $this->Speed * .5 + $this->Dribbling *.4) * ($this->Condition/1000 + $this->Form/100);
					//jocul cu passes
					//when playing high passes, it is important more the head game
					switch($this->passes) {
						case 1: 
							break;
						case 2: 
						//passes inalte, adaug la valoare un supliment de cap
						$valFW += ($this->Heading*.49 + $this->FirstTouch * .22) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//passes joase - scot heading din calcul si pun firsttouch
						$valFW -= ($this->Heading*.39 - $this->FirstTouch * .69) * ($this->Condition/1000 + $this->Form/100);break;				
						default:
						//passes joase - scot heading din calcul si pun firsttouch
						$valFW -= ($this->Heading*.39 - $this->FirstTouch * .69) * ($this->Condition/1000 + $this->Form/100);break;				
					}

					break;
				case 10: 
					$valFW = ($this->Shooting + $this->Heading + $this->Positioning *.7 + $this->Speed * .5 + $this->Dribbling *.4) * ($this->Condition/1000 + $this->Form/100);
					//jocul cu passes
					//when playing high passes, it is important more the head game
					switch($this->passes) {
						case 1: 
							break;
						case 2: 
						//passes inalte, adaug la valoare un supliment de cap
						$valFW += ($this->Heading*.49 + $this->FirstTouch * .22) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//passes joase - scot heading din calcul si pun firsttouch
						$valFW -= ($this->Heading*.39 - $this->FirstTouch * .69) * ($this->Condition/1000 + $this->Form/100);break;				
						default:
						//passes joase - scot heading din calcul si pun firsttouch
						$valFW -= ($this->Heading*.39 - $this->FirstTouch * .69) * ($this->Condition/1000 + $this->Form/100);break;				
					}
					break;
		}
	$this->valFW = $valFW;
	//echo "Valoare ofensiva ".$this->FirstName.' '.$this->LastName.": ".$this->valFW.'<br/>';
	return $this->valFW;
	
	}
	
	
}


	function ColorIt($valoare) 
	{

		if($valoare<=20) 
			echo "$valoare";
		if($valoare>20 && $valoare<=35) 
			echo "<font color=\"orange\">$valoare</font>";
		if($valoare>35) 
			echo "<font color=\"green\">$valoare</font>";

	}
?>
