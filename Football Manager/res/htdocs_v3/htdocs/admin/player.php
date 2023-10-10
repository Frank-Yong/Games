<?php
include('definitions.inc');

//trebuie introdus un coeficient de tara
//fotbalistii din Olanda vor fi mult mai buni decit unii din Somalia




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
	public $Saptamini;
	public $Proprietar;
	private $Moral;
	private $Training;
	
	
	private $NationalityName;
	private $Numar;
	
	private $CoeficientTara; //pentru a putea diferentia jcuatorii
	//un jucator olandez e mult mai bun decit unul din Letonia
	//in tabelul country, exista cimpul coeficient, cu valoare de la 1-100 pt fiecare tara introdusa
	//acest coeficient trebuie luat in seama la generarea jucatorului

	//valori individuale pentru fiecare jucator in parte
	private $valDF; 
	private $valMF;
	private $valFW;
	//valori finale pt echipa de joc
	private $vdf; 
	private $vmf;
	private $vfw;

	//nu o sa fie scrise in BD
	private $CAge;
	private $CLiga;
	private $YOUTH;
	private $Avatar;


	private $Tactica;
	private $Mijlocul;
	private $Atacuri;
	private $Pase;
	
	private $RespePost;
	private $RespeData;
	
	private $Accidentare; //din tabelul accidentare
	private $AccidentareData; //din tabelul accidentare
	
	private $Accidentat; //dar tabelul Player
	
	//$Youth = 1 -jucator de tineret
	function Player($user, $PlayerID=0, $MyCountry=3, $Youth=0, $MyPosition=2, $MyLiga=1) {
		$this->UserID = $user;
		$this->Country = $MyCountry;
		$this->CLiga = $MyLiga;
		$this->PlayerID = $PlayerID;

		if(func_num_args()==2) {
			//se trimit doar $user si $PlayerID
			//se doreste doar afisarea, jucatorul existind
			$this->GetData();
			return;
		}

		//generare nume jucator, in functie de tara din care fac parte
		$this->GenerateFirstName();
		//dupa ce se apeleaza functia de mai sus, e disponibil si coeficientul de tara -> trebuie folosit la generare
		$this->GenerateLastName();
		if($Youth==1) {
			$this->YOUTH = 1;
			$this->Age = rand(Y_AGE_START, Y_AGE_END);
			//coeficient de virsta
			//un junior nu poate sa aiba aceleasi caracteristici ca un senior
			//coeficientul de junior este mai mic (70%)
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
		$this->Tactica = 1;
		$this->Mijlocul = 1;
		$this->Atacuri = 1;
		$this->Pase = 1;
		
		$sql = "SELECT a.fname, a.lname, a.reflexes, a.OneOnOne, a.Handling, a.Communication, a.Tackling, a.Passing, a.LongShot, a.Shooting, a.Heading, a.Creativity, a.Crossing, a.Marking, a.TeamWork, a.FirstTouch, a.Strength, a.Speed, a.Experience, a.Conditioning, a.Form, a.Talent, a.Aggresivity, a.Injury, a.Dribbling, a.Positioning, a.Position, a.Nationality, a.Rating, a.Age, a.Wage, a.Value, a.Contract, b.name, a.Transfer, a.TransferSuma, a.TransferDeadline, c.saptamini, d.TeamName, a.Avatar, c.Numar, f.tactica, f.mijlocul, f.atacuri, f.pase, a.Moral, c.userid, a.training, g.post, g.data, a.youth, h.id, h.data, a.Accidentat  
		FROM player a 
		LEFT JOIN userplayer c
		ON a.id=c.PlayerID
		LEFT JOIN user d
		ON c.UserID=d.id
		LEFT JOIN tactica f
		ON d.id=f.userid
		LEFT JOIN country b
		ON a.Nationality=b.id
		LEFT JOIN respecializare g
		ON g.playerid=a.id
		LEFT JOIN accidentare h
		ON h.playerid=a.id
		WHERE a.id=".$this->PlayerID;
		//echo "$sql<br/>";
		$res = mysql_query($sql);
		list($this->FirstName, $this->LastName, $this->reflexes, $this->OneOnOne, $this->Handling, $this->Communication, $this->Tackling, $this->Passing, $this->LongShot, $this->Shooting, $this->Heading, $this->Creativity, $this->Crossing, $this->Marking, $this->TeamWork, $this->FirstTouch, $this->Strength, $this->Speed, $this->Experience, $this->Condition, $this->Form, $this->Talent, $this->Aggresivity, $this->Injury, $this->Dribbling, $this->Positioning, $this->Position, $this->Nationality, $this->Rating, $this->Age, $this->Wage, $this->Value, $this->Contract, $this->NationalityName, $this->Transfer, $this->TransferSuma, $this->TransferDeadline, $this->Saptamini, $this->Proprietar, $this->Avatar, $this->Numar, $this->Tactica, $this->Mijlocul, $this->Atacuri, $this->Pase, $this->Moral, $this->UserID, $this->Training, $this->RespePost, $this->RespeData, $this->YOUTH, $this->Accidentare, $this->AccidentareData, $this->Accidentat)=mysql_fetch_row($res);
		
		mysql_free_Result($res);
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

		//coeficientul de tara e de la 1-100. Pt a avea o valoare pozitiva petru tarile bune, impart la 60
		$coe = $this->CoeficientTara/60;
		
		$this->Creativity = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Crossing = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Dribbling = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->FirstTouch = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Handling = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Heading = round(rand(D_HEADING_START*$this->CAge,D_HEADING_END*$this->CAge)*$this->CLiga*$coe);
		$this->LongShot = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Marking = round(rand(D_MARKING_START*$this->CAge,D_MARKING_END*$this->CAge)*$this->CLiga*$coe);
		$this->OneOnOne = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Passing = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Positioning = round(rand(D_POSITIONING_START*$this->CAge,D_POSITIONING_END*$this->CAge)*$this->CLiga*$coe);
		$this->Reflexes = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Shooting = round(rand(D_REST_START*$this->CAge,D_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Tackling = round(rand(D_TACKLING_START*$this->CAge,D_TACKLING_END*$this->CAge)*$this->CLiga*$coe);
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
		$coe = $this->CoeficientTara/60;
		
		$this->Communication = round(rand(G_COMMUNICATION_START*$this->CAge,G_COMMUNICATION_END*$this->CAge)*$this->CLiga*$coe);
		$this->Creativity = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Crossing = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Dribbling = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->FirstTouch = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Handling = round(rand(G_HANDLING_START*$this->CAge,G_HANDLING_END*$this->CAge)*$this->CLiga*$coe);
		$this->Heading = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->LongShot = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Marking = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->OneOnOne = round(rand(G_ONEONONE_START*$this->CAge,G_ONEONONE_END*$this->CAge)*$this->CLiga*$coe);
		$this->Passing = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Positioning = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Reflexes = round(rand(G_REFLEXES_START*$this->CAge,G_REFLEXES_END*$this->CAge)*$this->CLiga*$coe);
		$this->Shooting = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Tackling = round(rand(G_REST_START*$this->CAge,G_REST_END*$this->CAge)*$this->CLiga*$coe);
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
		
		//coeficientul de tara e de la 1-100. Pt a avea o valoare pozitiva pentru tarile bune, impart la 60
		$coe = $this->CoeficientTara/60;
		
		$this->Communication = round(rand(G_COMMUNICATION_START*$this->CAge,G_COMMUNICATION_END*$this->CAge)*$this->CLiga*$coe);
		$this->Creativity = round(rand(M_CREATIVITY_START*$this->CAge,M_CREATIVITY_END*$this->CAge)*$this->CLiga*$coe);
		$this->Crossing = round(rand(M_CROSSING_START*$this->CAge,M_CROSSING_END*$this->CAge)*$this->CLiga*$coe);
		$this->Dribbling = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->FirstTouch = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Handling = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Heading = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->LongShot = round(rand(M_LONGSHOT_START*$this->CAge,M_LONGSHOT_END*$this->CAge)*$this->CLiga*$coe);
		$this->Marking = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->OneOnOne = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Passing = round(rand(M_PASSING_START*$this->CAge,M_PASSING_END*$this->CAge)*$this->CLiga*$coe);
		$this->Positioning = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Reflexes = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Shooting = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Tackling = round(rand(M_REST_START*$this->CAge,M_REST_END*$this->CAge)*$this->CLiga*$coe);
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
		
		//coeficientul de tara e de la 1-100. Pt a avea o valoare pozitiva pentru tarile bune, impart la 60
		$coe = $this->CoeficientTara/60;
		
		$this->Communication = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Creativity = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Crossing = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Dribbling = round(rand(F_DRIBBLING_START*$this->CAge,F_DRIBBLING_END*$this->CAge)*$this->CLiga*$coe);
		$this->FirstTouch = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Handling = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Heading = round(rand(F_HEADING_START*$this->CAge,F_HEADING_END*$this->CAge)*$this->CLiga*$coe);
		$this->LongShot = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Marking = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->OneOnOne = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Passing = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Positioning = round(rand(F_POSITIONING_START*$this->CAge,F_POSITIONING_END*$this->CAge)*$this->CLiga*$coe);
		$this->Reflexes = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Shooting = round(rand(F_SHOOTING_START*$this->CAge,F_SHOOTING_END*$this->CAge)*$this->CLiga*$coe);
		$this->Tackling = round(rand(F_REST_START*$this->CAge,F_REST_END*$this->CAge)*$this->CLiga*$coe);
		$this->Talent = rand(1,100);
	}

	private function GenerateFirstName() {
		//poza jucator
		$p=rand(1,35);
		$this->Avatar = "p$p.png";
		
		$sql = "SELECT a.country, a.name, b.coeficient 
				FROM firstname a
				LEFT OUTER JOIN country b
				ON a.country=b.id";// WHERE country=" . $this->Country;
		$res = mysql_query($sql);

		$val = rand(0, mysql_num_rows($res)-1);
		mysql_data_seek($res, $val);
		$row = mysql_fetch_assoc($res);
		$this->FirstName = $row['name'];
		$this->Nationality = $row['country'];
		$this->CoeficientTara = $row['coeficient'];
		mysql_free_result($res);
	}


	private function GenerateLastName() {
		$sql = "SELECT name FROM lastname WHERE country=" . $this->Nationality;
		$res = mysql_query($sql);

		$val = rand(0, mysql_num_rows($res)-1);
		mysql_data_seek($res, $val);
		$row = mysql_fetch_assoc($res);
		$this->LastName = $row['name'];
		mysql_free_result($res);
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

	private function ViewCresteri() {
		$sql = "SELECT caracteristica, data FROM logcresteri WHERE playerid=".$this->PlayerID. " ORDER BY data DESC";
		$res = mysql_query($sql);
		$i=0;
		while(list($caracteristica, $data) = mysql_fetch_row($res)) {
			if($i==0) {
				echo "<h1>Cresteri dupa antrenament! (max.10)</h1>";
				echo "<table class=\"tftable\">";
				echo "<tr>";
			}
			if($i%2==0 && $i<>0) echo "<tr>";
			switch($caracteristica) {
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
				case 'Passing': $deafisat='Pase'; break;
				case 'Creativity': $deafisat='Creativitate'; break;
				case 'Conditioning': $deafisat='Conditie fizica'; break;
				case 'Aggresivity': $deafisat='Agresivitate'; break;
				case 'Experience': $deafisat='Experienta'; break;
				case 'Strength': $deafisat='Rezistenta'; break;
			}
			echo "<th>$data</th>";
			echo "<td>$deafisat</td>";
			$i++;
			if($i%2==0) echo "</tr>";
			if($i==10)break;
		}
		if($i%2==1) {
			echo "<th></th><td></td></tr></table>";
		} else {
			echo "</table>";
		}
		mysql_free_result($res);
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
		mysql_query($sql);
		$PlayerID = mysql_insert_id();
		//echo "<br/>$sql<br/>";
		//apeleaza functia ce calculeaza valorile maxime la care poate ajunge jucatorul
		//acest maxim depinde de talent
		$this->SetMaxValues($PlayerID);

		$sql = "INSERT INTO userplayer (UserID, PlayerID, data) VALUES(" . $this->UserID . ", " . $PlayerID . ",'".Date("Y-m-d")."')";
		mysql_query($sql);

		$sql = "INSERT INTO moral(playerid, contor1, contor2)
				VALUES($PlayerID,0,0)";
		mysql_query($sql);

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

		$tip = 1; //normal
		//inserare in tabela de antrenament
		//initial, antrenament normal, pe postul lui
		$sql = "INSERT INTO trainerplayer (PlayerID, TrainerID, Post, Tip) VALUES(" . $PlayerID . ", 0, $post, $tip)";
		mysql_query($sql);

		$grupa=1;
		if($this->YOUTH==1) $grupa=2;
		
		//inserare in tabela de echipastart
		//initial, nici un jucator nu face parte din formatia de start, se insereaza valoarea 0
		$sql = "INSERT INTO echipastart (playerId, userId, post, grupa) VALUES($PlayerID, ". $this->UserID. ", 0, $grupa)";
		mysql_query($sql);

		
		//inserare in tabela de procente
		//initial, are procente prestabilite
		//ulterior, in functie de momentul in care ajunge la praguri (prag1, prag2), procentele se realoca
		//portar
		if ($post == 1) {
			$sql = "INSERT INTO procente (PlayerId, Procent, Stadiu, Caracteristica, Redistribuite)
					VALUES
					($PlayerID, ".rand(G_REFLEXES_PERCENT_1, G_REFLEXES_PERCENT_2).", 0, 'Reflexes', 0),
					($PlayerID, ".rand(G_ONEONONES_PERCENT_1,G_ONEONONES_PERCENT_2).", 0, 'OneonOne', 0),
					($PlayerID, ".rand(G_HANDLING_PERCENT_1,G_HANDLING_PERCENT_2).", 0, 'Handling', 0),
					($PlayerID, ".rand(G_COMMUNICATION_PERCENT_1,G_COMMUNICATION_PERCENT_2).", 0, 'Communication', 0),
					($PlayerID, ".rand(G_POSITIONING_PERCENT_1,G_POSITIONING_PERCENT_2).", 0, 'Positioning', 0),
					($PlayerID, ".rand(G_PASSING_PERCENT_1,G_PASSING_PERCENT_2).", 0, 'Passing', 0),
					($PlayerID, ".rand(G_CROSSING_PERCENT_1,G_CROSSING_PERCENT_2).", 0, 'Crossing', 0),
					($PlayerID, ".rand(G_LONGSHOTS_PERCENT_1,G_LONGSHOTS_PERCENT_2).", 0, 'LongShot', 0)";

			mysql_query($sql);
		}

		//fundas
		if ($post == 2) {
			$sql = "INSERT INTO procente (PlayerId, Procent, Stadiu, Caracteristica, Redistribuite)
					VALUES
					($PlayerID, ".rand(D_TACKLING_PERCENT_1,D_TACKLING_PERCENT_2).", 0, 'Tackling', 0),
					($PlayerID, ".rand(D_MARKING_PERCENT_1,D_MARKING_PERCENT_2).", 0, 'Marking', 0),
					($PlayerID, ".rand(D_HEADING_PERCENT_1,D_HEADING_PERCENT_2).", 0, 'Heading', 0),
					($PlayerID, ".rand(D_POSITIONING_PERCENT_1,D_POSITIONING_PERCENT_2).", 0, 'Positioning', 0),
					($PlayerID, ".rand(D_PASSING_PERCENT_1,D_PASSING_PERCENT_2).", 0, 'Passing', 0),
					($PlayerID, ".rand(D_CROSSING_PERCENT_1,D_CROSSING_PERCENT_2).", 0, 'Crossing', 0),
					($PlayerID, ".rand(D_COMMUNICATION_PERCENT_1,D_COMMUNICATION_PERCENT_2).", 0, 'Communication', 0),
					($PlayerID, ".rand(D_FIRSTTOUCH_PERCENT_1,D_FIRSTTOUCH_PERCENT_2).", 0, 'FirstTouch', 0)";

			mysql_query($sql);
		}
		//mijlocas
		if ($post == 3) {
			$sql = "INSERT INTO procente (PlayerId, Procent, Stadiu, Caracteristica, Redistribuite)
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

			mysql_query($sql);
		}
		//atacant
		if ($post == 4) {
			$sql = "INSERT INTO procente (PlayerId, Procent, Stadiu, Caracteristica, Redistribuite)
					VALUES
					($PlayerID, ".rand(F_SHOOTING_PERCENT_1,F_SHOOTING_PERCENT_2).", 0, 'Shooting', 0),
					($PlayerID, ".rand(F_HEADING_PERCENT_1,F_HEADING_PERCENT_2).", 0, 'Heading', 0),
					($PlayerID, ".rand(F_POSITIONING_PERCENT_1,F_POSITIONING_PERCENT_2).", 0, 'Positioning', 0),
					($PlayerID, ".rand(F_DRIBBLING_PERCENT_1,F_DRIBBLING_PERCENT_2).", 0, 'Dribbling', 0),
					($PlayerID, ".rand(F_FIRSTTOUCH_PERCENT_1,F_FIRSTTOUCH_PERCENT_2).", 0, 'FirstTouch', 0)";


			mysql_query($sql);
		}
	}

	private function SetMaxValues($PlayerID=0) {
		//un SS e atit de talentat, incit poate sa ajunga la orice caract la nivel maxim
		//dar din moemnt ce doar anumite caract au procente de antrenare,nuva ajunge ca un atacant sa aiba tackling 50
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
		//can become
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

		mysql_query($sql);

		//initializare tabel Cresteri
		$sql = "INSERT INTO cresteri (playerId) VALUES ($PlayerID)";
		mysql_query($sql);
		
		//initializare tabel Salt
		$sql = "INSERT INTO salt (playerId, delasalt, cstsalt) 
				VALUES ($PlayerID, 0, '$cst_talent')";
		mysql_query($sql);

	
	}


	function EchoPlayer() {
		switch ($this->Position) {
				case 1: $pos = "GK (Goalkeeper)"; $gk="green";$md="white";$fw="white";$dffw="white"; $df="white"; break;
				case 2: $pos = "DR (Defender - Right)";  $gk="white";$md="white";$fw="white";$dffw="green"; $df="green"; break;
				case 3: $pos = "DC (Defender - Center)"; $gk="white";$md="white";$fw="white";$dffw="green"; $df="green";break;
				case 4: $pos = "DL (Defender - Left)"; $gk="white";$md="white";$fw="white";$dffw="green"; $df="green";break;
				case 5: $pos = "MR (Midfielder - Right)"; $gk="white";$md="green";$fw="white";$dffw="white"; $df="white";break;
				case 6: $pos = "MC (Midfielder - Center)"; $gk="white";$md="green";$fw="white";$dffw="white"; $df="white";break;
				case 7: $pos = "ML (Midfielder - Left)"; $gk="white";$md="green";$fw="white";$dffw="white"; $df="white";break;
				case 8: $pos = "FR (Forward - Right)"; $gk="white";$md="white";$fw="green";$dffw="green"; $df="white";break;
				case 9: $pos = "FC (Forward - Center)"; $gk="white";$md="white";$fw="green";$dffw="green"; $df="white";break;
				case 10: $pos = "FL (Forward - Left)"; $gk="white";$md="white";$fw="green";$dffw="green"; $df="white";break;
		}
		echo "<h1>";
		if($this->Numar<>0)
			echo "&nbsp;<font class=\"numar\">".$this->Numar."</font>";
		
		echo $this->FirstName." ".$this->LastName."(".$this->Rating.")</h1>";
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
				
				echo "<h3>Acest jucator este pe lista de transferuri! <input type=\"hidden\" name=\"BidValue\" value=\"$this->TransferSuma\"><input type=\"Submit\" name=\"StartBidPlayer\" value=\"Ofera $tsuma &euro;\" $disabled class=\"button-2\"></h3>";
				if($this->UserID == 0) {
					//trebuie sa se liciteze salariul
					echo "<h3>Jucator liber de transfer. Va fi achizitionat de cine ofera salariul saptamanal cel mai mare!</h3>";
				}
			} else {
				$sql = "SELECT p.userid, p.suma, u.TeamName
						FROM playerbid p
						left outer join user u
						on p.userid=u.id
						WHERE p.activ=1 AND p.playerid=$this->PlayerID
						ORDER BY p.suma DESC";
				$res = mysql_query($sql);
				list($bid_userid, $bid_suma, $bid_TeamName) = mysql_fetch_row($res);
				mysql_free_result($res);
				$bid_depariat = $bid_suma+1000;
				//echo $this->TransferDeadline . '     ' . Date("Y-m-d H:i:s").'<br/>';
				if($this->TransferDeadline<=Date("Y-m-d H:i:s")) {
					//s-a terminat timpul
					echo "<h3>Jucator cumparat de $bid_TeamName cu $bid_suma &euro;! Jucatorul se va alatura lotului ziua urmatoare.</h3>";
					
				} else {
					$deafi = strlen($bid_TeamName)>10? substr($bid_TeamName,0,10)."...":$bid_TeamName;
					$bsuma = number_format($bid_suma);
					$bdepariat = number_format($bid_depariat);
					echo "<h3>Pariere inceputa ($deafi - $bsuma &euro;)! Expira pe ".$this->TransferDeadline."!<input class=\"input-1\" type=\"text\" name=\"BidValue\" value=\"$bid_depariat\" $disabled> &euro; <input type=\"Submit\" name=\"BidPlayer\" value=\"Ofera\" $disabled class=\"button-2\"></h3>";
					if($this->UserID == 0) {
						//trebuie sa se liciteze salariul
						echo "<h3>Jucator liber de transfer. Va fi achizitionat de cine ofera salariul saptamanal cel mai mare!</h3>";
					}
				}
				}
			echo "</form>";
		}
		if($this->Avatar == "") $ima = "missing_player.jpg";
		else $ima = $this->Avatar;
//		echo "<img src=\"images/missing_player.jpg\" class=\"img-1\" width=\"180\"  align=\"left\">";
		echo "<img src=\"images/$ima\" class=\"img-1\" width=\"180\"  align=\"left\">";
		
		echo "<img src=\"steaguri/".$this->NationalityName.".png\" width=\"32\">";
		echo "<br/>Tara: ". $this->NationalityName . "<br/>";
		echo "Varsta:".$this->Age." ani<br/>";
		echo "Pozitie: $pos";
		if($this->Accidentat == 1) {
			echo " - <font class=\"button-2\">Accidentat. Se reface in ".$this->AccidentareData."!</font><br/>";
		} else echo "<br/>";
		if($this->Training == 1) {
			echo " - <font class=\"button-2\">Trimis la specializare. Se intoarce in ".$this->RespeData."!</font><br/>";
		} else echo "<br/>";
		echo "Salariu: ".number_format($this->Wage) . " &euro;/saptamanal<br/>";
		echo "Perioada contract: " . $this->Contract . " (sezoane)<br/>";
		echo "Valoare: ".number_format($this->Value) . " &euro;<br/>";
		//echo "Forma: ".$this->Form." %<br/>";
		echo "Forma: <img src=\"baragrafica.php?percentage=".$this->Form."\"><br/>";
		//echo "Moral: <img src=\"baragrafica.php?percentage=".$this->Moral."\"><br/>";
		echo "Echipa la care evolueaza: ".$this->Proprietar.'<br/>';
		echo "Saptamani la club: ".$this->Saptamini.'<br/>';

		//echo "aici";
		$sql = "SELECT caracteristica
				FROM logcresteri
				WHERE data='".Date("Y-m-d")."' AND playerid=".$this->PlayerID;
		$res = mysql_query($sql);
		list($caracteristica) = mysql_fetch_row($res);
		mysql_free_result($res);
		$img = '<img src="images/crestere.png" width="12">';
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
	?>
				<table class="tftable" width="100%" cellpadding="1">
					<tr>
						<th><font color="<?php echo $gk; ?>">Reflexe</font></th>
						<th><font color="<?php echo $gk; ?>">Unu la unu</font></th>
						<th><font color="<?php echo $gk; ?>">Manevrare</font></th>
						<th><font color="<?php echo $df; ?>">Deposedare</font></th>	
						<th><font color="<?php echo $df; ?>">Marcaj</font></th>	
						<th><font color="<?php echo $dffw; ?>">Jocul cu capul</font></th>
						<th><font color="<?php echo $md; ?>">Suturi de la distanta</font></th>
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
						<th><font color="<?php echo $md; ?>">Pozitionare</font></th>
						<th><font color="<?php echo $fw; ?>">Sut</font></th>
						<th><font color="<?php echo $md; ?>">Atingere</font></th>
						<th><font color="<?php echo $md; ?>">Creativitate</font></th>	
						<th><font color="<?php echo $md; ?>">Lansari</font></th>	
						<th><font color="<?php echo $md; ?>">Pase</font></th>
						<th><font color="<?php echo $gk; ?>">Comunicatie</font></th>
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
						<th>Joc de echipa</th>
						<th>Rezistenta</th>
						<th>Viteza</th>
						<th>Experienta</th>	
						<th>Conditie fizica</th>	
						<th>Dribling</th>
						<th>Agresivitate</th>
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
			echo "<h3>Afla talentul jucatorului (aproximativ, in functie de valoare antrenor):<input type=\"Submit\" name=\"CerceteazaTalent\" value=\"Interogare talent\" class=\"button-22\"></h3>";
		

			echo "<h3>Inceteaza contractul cu jucatorul:<input type=\"Submit\" name=\"InceteazaContract\" value=\"Compensatii de ".number_format($compensatii)." &euro;\" class=\"button-22\"></h3>";
		
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
			echo "<h3>Reinnoieste contract cu un sezon:<input type=\"Submit\" name=\"ReinnoiesteContract\" value=\"Salariu va fi ".number_format($salariunou)." &euro;\" class=\"button-22\"></h3>";
		}
		echo "</form>";

		echo "<br/>";

		echo "<form action=\"index.php?option=club\" method=\"POST\" onSubmit=\"return validateRespecializare(this);\">";
		echo "<input type=\"hidden\" name=\"uid\" value=\"".$this->UserID."\">";
		echo "<input type=\"hidden\" name=\"pid\" value=\"".$this->PlayerID."\">";
		$renunta = "";
		//echo $this->TransferDeadline.'<br/>';
		if($this->UserID == $_SESSION['USERID'] && $this->TransferDeadline == "0000-00-00 00:00:00"){
			echo "<h3>Respecializare! Fa-l <select name=\"postnou\" class=\"input-2\">";
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
			echo "<input $disabled type=\"Submit\" name=\"Respecializare\" value=\"Trimite la training\" class=\"button-22\"></h3>";
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
			echo "<h3>Reinnoieste contract cu un sezon:<input type=\"Submit\" name=\"ReinnoiesteContract\" value=\"Salariu va fi ".number_format($salariunou)." &euro;\" class=\"button-22\"></h3>";
		}
		echo "</form>";


		$this->ViewCresteri();
		$this->ViewTalent();
}

	function ViewTalent() {
		if($this->UserID == $_SESSION['USERID']) { 
			echo "<h1>Verificare talent</h1>";
			echo "<table class=\"tftable\">";
			echo "<tr>";
			$sql = "SELECT talent, data
					FROM talent
					WHERE userid=".$this->UserID." AND playerid=".$this->PlayerID;
			$res = mysql_query($sql);
			while(list($talent, $data) = mysql_fetch_row($res)) {
				echo "<th>$data</th><td>";
				switch($talent) {
					case 1: echo "Acest jucator nu va ajunge jucator bun!"; break;
					case 2: echo "Acest jucator are sanse mici sa ajunga jucator bun!"; break;
					case 3: echo "Acest jucator poate ajunge jucator bun!"; break;
					case 4: echo "Acest jucator este foarte talentat!"; break;
					case 5: echo "Acest jucator este un superstar!"; break;
				}
				echo "</td>";
			}
			echo "</tr></table>";
			mysql_free_result($res);
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
		echo "<td class=\"mark\"><font class=\"numar-tricou\">&nbsp;".$pos."&nbsp;</font>&nbsp;</td>";
		echo "<td><img src=\"steaguri/".$this->NationalityName.".png\" width=\"18\" valign=\"middle\"></td><td><a class=\"link-5\" href=\"index.php?option=viewplayer&pid=".$this->PlayerID."&uid=".$_REQUEST['club_id']."\">".$this->FirstName." ".$this->LastName."</a></td>";
		echo "<td>&nbsp;".$this->Age." ani</td>";
		//echo "<div class=\"hr-replace-2\"></div>";
		if($this->Transfer == 1 && $this->TransferDeadline == '0000-00-00 00:00:00') {
			//jucatorul este transferabil
			//apare un T in dreptul lui
			echo "<td><font class=\"numar-tricou\">&nbsp;T&nbsp;</font></td>";
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
		echo "<font class=\"numar-tricou\">&nbsp;".$pos."&nbsp;</font>&nbsp;";
		if($this->Numar<>0)
			echo "&nbsp;<font class=\"numar\">".$this->Numar."</font>";
		echo "<img src=\"steaguri/".$this->NationalityName.".png\" width=\"18\" valign=\"middle\"><a class=\"link-5\" href=\"echipa.php?id=".$this->PlayerID."&poss=".$poss."\">".$this->FirstName." ".$this->LastName."</a>";
		echo "&nbsp;".$this->Age." ani";
		if($this->Transfer == 1 && $this->TransferDeadline == '0000-00-00 00:00:00') {
			//jucatorul este transferabil
			//apare un T in dreptul lui
			echo "<font class=\"numar-tricou\">&nbsp;T&nbsp;</font>";
		} 
		if($this->Transfer == 1 && $this->TransferDeadline != '0000-00-00 00:00:00') {
			echo "<img src=\"images/bagofmoney.png\" width=\"20\">";
		}
		
		echo "<div class=\"hr-replace-2\"></div>";

	}



	function GetDFWork(){

		//returneaza valoarea defensiva a jucatorului, indiferent de pozitie
		//in functie de tactica, de cum joaca compartimentul median, pase si atacuri, sunt alte valori disponibile
		//tactica: 1-normala;2-ofensiva;3-defensiva
		//mijlocul:1=normal;2-ofensiv;3-defensiv
		//atacuri:1-mixte;2-laterale;3-centru
		//pase: 1-mixte;2-inalte;3-pe jos
		switch ($this->Position) {
				case 1: 
					$pos = "GK"; 
					$valDF = ($this->Reflexes + $this->OneOnOne + $this->Handling * .99 + $this->Positioning * .7 + $this->Communication * .99) * ($this->Condition/1000 + $this->Form/100);
					break;
				case 2: 
					$valDF = ($this->Tackling + $this->Marking + $this->Heading * .8 + $this->Positioning * .7 + $this->Speed * .3 + $this->FirstTouch *.15 + $this->Communication*.07 + $this->Aggresivity * .17) * ($this->Condition/1000 + $this->Form/100);

					//jocul cu pase
					//cind se joaca cu pase inalte, importanta sporita capata jocul de cap
					switch($this->Pase) {
						case 1: break;
						case 2: 
						//pase inalte, adaug la valoare un supliment de cap
						$valDF += ($this->Heading * .8) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//pase joase - scot heading din calcul
						$valDF -= ($this->Heading * .5) * ($this->Condition/1000 + $this->Form/100);break;
						
					}
					
					
					break;
				case 3: 
					$valDF = ($this->Tackling + $this->Marking + $this->Heading * .8 + $this->Positioning * .7 + $this->Speed * .3 + $this->FirstTouch *.15 + $this->Communication*.07 + $this->Aggresivity * .17) * ($this->Condition/1000 + $this->Form/100);
					//jocul cu pase
					//cind se joaca cu pase inalte, importanta sporita capata jocul de cap
					switch($this->Pase) {
						case 1: break;
						case 2: 
						//pase inalte, adaug la valoare un supliment de cap
						$valDF += ($this->Heading * .8) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//pase joase - scot heading din calcul
						$valDF -= ($this->Heading * .5) * ($this->Condition/1000 + $this->Form/100);break;
						
					}
					break;
				case 4: 
					$valDF = ($this->Tackling + $this->Marking + $this->Heading * .8 + $this->Positioning * .7 + $this->Speed * .3 + $this->FirstTouch *.15 + $this->Communication*.07 + $this->Aggresivity * .17) * ($this->Condition/1000 + $this->Form/100);
					//jocul cu pase
					//cind se joaca cu pase inalte, importanta sporita capata jocul de cap
					switch($this->Pase) {
						case 1: break;
						case 2: 
						//pase inalte, adaug la valoare un supliment de cap
						$valDF += ($this->Heading * .8) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//pase joase - scot heading din calcul
						$valDF -= ($this->Heading * .5) * ($this->Condition/1000 + $this->Form/100);break;
						
					}
					break;
				case 5: 
					switch($this->Tactica) {
						case 1: $valDF = ($this->Tackling * .3 + $this->Marking *.3 + $this->Heading * .1) * ($this->Condition/1000 + $this->Form/100); break;
						case 2: $valDF = ($this->Tackling * .11 + $this->Marking *.11 + $this->Heading * .05) * ($this->Condition/1000 + $this->Form/100); break;
						case 3:
							//tactica defensiva. mijlocul da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .71 + $this->Marking *.71 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
					}
					break;
				case 6: 
					switch($this->Tactica) {
						case 1: $valDF = ($this->Tackling * .3 + $this->Marking *.3 + $this->Heading * .1) * ($this->Condition/1000 + $this->Form/100); break;
						case 2: $valDF = ($this->Tackling * .11 + $this->Marking *.11 + $this->Heading * .05) * ($this->Condition/1000 + $this->Form/100); break;
						case 3:
							//tactica defensiva. mijlocul da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .71 + $this->Marking *.71 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
					}
					break;
				case 7: 
					switch($this->Tactica) {
						case 1: $valDF = ($this->Tackling * .3 + $this->Marking *.3 + $this->Heading * .1) * ($this->Condition/1000 + $this->Form/100); break;
						case 2: $valDF = ($this->Tackling * .11 + $this->Marking *.11 + $this->Heading * .05) * ($this->Condition/1000 + $this->Form/100); break;
						case 3:
							//tactica defensiva. mijlocul da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .71 + $this->Marking *.71 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
					}
					break;
				case 8: 
					switch($this->Tactica) {
						case 1: $valDF = ($this->Heading * .1) * ($this->Condition/1000 + $this->Form/100); break;
						case 2: 
							//tactica ofensiva. atacantul nu prea are treaba cu apararea
							$valDF = ($this->Heading * .05) * ($this->Condition/1000 + $this->Form/100); break;
						case 3:
							//tactica defensiva. atacantul da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .31 + $this->Marking *.31 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
					}
					break;
				case 9: 
					switch($this->Tactica) {
						case 1: $valDF = ($this->Heading * .1) * ($this->Condition/1000 + $this->Form/100); break;
						case 2: 
							//tactica ofensiva. atacantul nu prea are treaba cu apararea
							$valDF = ($this->Heading * .05) * ($this->Condition/1000 + $this->Form/100); break;
						case 3:
							//tactica defensiva. atacantul da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .31 + $this->Marking *.31 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
					}
					break;
				case 10: 
					switch($this->Tactica) {
						case 1: $valDF = ($this->Heading * .1) * ($this->Condition/1000 + $this->Form/100); break;
						case 2: 
							//tactica ofensiva. atacantul nu prea are treaba cu apararea
							$valDF = ($this->Heading * .05) * ($this->Condition/1000 + $this->Form/100); break;
						case 3:
							//tactica defensiva. atacantul da randament mai bun in aparare, decit in mod normal
							$valDF = ($this->Tackling * .31 + $this->Marking *.31 + $this->Heading * .55) * ($this->Condition/1000 + $this->Form/100); break;
					}
					break;
		}
	$this->valDF = $valDF;

	//echo "Valoare defensiva: ".$this->valDF.'<br/>';
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
					//jocul cu pase
					//cind se joaca cu pase inalte, importanta sporita capata jocul de cap
					//posesia sufera
					switch($this->Pase) {
						case 1: 
							$valMF = ($this->Passing + $this->Crossing + $this->Creativity + $this->LongShot * .7 + $this->Dribbling *.5 + $this->Speed *.3) * ($this->Condition/1000 + $this->Form/100);
							break;
						case 2: 
						//pase inalte, adaug la valoare un supliment de cap
						$valMF = ($this->Passing*.79 + $this->Crossing*1.2 + $this->Creativity*.79 + $this->LongShot * .97 + $this->Dribbling *.2 + $this->Speed *.6) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//pase joase - scot heading din calcul
						$valMF = ($this->Passing*1.1 + $this->Crossing*0.9 + $this->Creativity*1.1 + $this->LongShot * .7 + $this->Dribbling *.5 + $this->Speed *.3) * ($this->Condition/1000 + $this->Form/100);break;
						
					}

					break;
				case 6: 
					//jocul cu pase
					//cind se joaca cu pase inalte, importanta sporita capata jocul de cap
					//posesia sufera
					switch($this->Pase) {
						case 1: 
							$valMF = ($this->Passing + $this->Crossing + $this->Creativity + $this->LongShot * .7 + $this->Dribbling *.5 + $this->Speed *.3) * ($this->Condition/1000 + $this->Form/100);
							break;
						case 2: 
						//pase inalte, adaug la valoare un supliment de cap
						$valMF = ($this->Passing*.79 + $this->Crossing*1.2 + $this->Creativity*.79 + $this->LongShot * .97 + $this->Dribbling *.2 + $this->Speed *.6) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//pase joase - scot heading din calcul
						$valMF = ($this->Passing*1.1 + $this->Crossing*0.9 + $this->Creativity*1.1 + $this->LongShot * .7 + $this->Dribbling *.5 + $this->Speed *.3) * ($this->Condition/1000 + $this->Form/100);break;
						
					}
					break;
				case 7: 
					//jocul cu pase
					//cind se joaca cu pase inalte, importanta sporita capata jocul de cap
					//posesia sufera
					switch($this->Pase) {
						case 1: 
							$valMF = ($this->Passing + $this->Crossing + $this->Creativity + $this->LongShot * .7 + $this->Dribbling *.5 + $this->Speed *.3) * ($this->Condition/1000 + $this->Form/100);
							break;
						case 2: 
						//pase inalte, adaug la valoare un supliment de cap
						$valMF = ($this->Passing*.79 + $this->Crossing*1.2 + $this->Creativity*.79 + $this->LongShot * .97 + $this->Dribbling *.2 + $this->Speed *.6) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//pase joase - scot heading din calcul
						$valMF = ($this->Passing*1.1 + $this->Crossing*0.9 + $this->Creativity*1.1 + $this->LongShot * .7 + $this->Dribbling *.5 + $this->Speed *.3) * ($this->Condition/1000 + $this->Form/100);break;
						
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
	//echo "MF: $valMF<br/>";
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
					switch($this->Tactica) {
						case 1: $valFW = ($this->LongShot * .3 + $this->Creativity *.4 + $this->Passing*.4 + $this->Crossing *.3) * ($this->Condition/1000 + $this->Form/100);break;
						case 2: 
						//tactica ofensiva, mai mult aport la atac
						$valFW = ($this->LongShot * .55 + $this->Creativity *.67 + $this->Passing*.67 + $this->Crossing *.67 + $this->Shooting * .44 + $this->Heading * .44) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//tactica defensiva, aport mai slab la atac
						$valFW = ($this->LongShot * .12 + $this->Creativity *.17 + $this->Passing*.17 + $this->Crossing *.17) * ($this->Condition/1000 + $this->Form/100);break;
					}
					break;
				case 6: 
					switch($this->Tactica) {
						case 1: $valFW = ($this->LongShot * .3 + $this->Creativity *.4 + $this->Passing*.4 + $this->Crossing *.3) * ($this->Condition/1000 + $this->Form/100);break;
						case 2: 
						//tactica ofensiva, mai mult aport la atac
						$valFW = ($this->LongShot * .55 + $this->Creativity *.67 + $this->Passing*.67 + $this->Crossing *.67 + $this->Shooting * .44 + $this->Heading * .44) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//tactica defensiva, aport mai slab la atac
						$valFW = ($this->LongShot * .12 + $this->Creativity *.17 + $this->Passing*.17 + $this->Crossing *.17) * ($this->Condition/1000 + $this->Form/100);break;
					}
					break;
				case 7: 
					switch($this->Tactica) {
						case 1: $valFW = ($this->LongShot * .3 + $this->Creativity *.4 + $this->Passing*.4 + $this->Crossing *.3) * ($this->Condition/1000 + $this->Form/100);break;
						case 2: 
						//tactica ofensiva, mai mult aport la atac
						$valFW = ($this->LongShot * .55 + $this->Creativity *.67 + $this->Passing*.67 + $this->Crossing *.67 + $this->Shooting * .44 + $this->Heading * .44) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//tactica defensiva, aport mai slab la atac
						$valFW = ($this->LongShot * .12 + $this->Creativity *.17 + $this->Passing*.17 + $this->Crossing *.17) * ($this->Condition/1000 + $this->Form/100);break;
					}
					break;
				case 8: 
					$valFW = ($this->Shooting + $this->Heading + $this->Positioning *.7 + $this->Speed * .5 + $this->Dribbling *.4 +$this->FirstTouch *.33) * ($this->Condition/1000 + $this->Form/100);

					//jocul cu pase
					//cind se joaca cu pase inalte, importanta sporita capata jocul de cap
					switch($this->Pase) {
						case 1: 
							break;
						case 2: 
						//pase inalte, adaug la valoare un supliment de cap
						$valFW += ($this->Heading*.49 + $this->FirstTouch * .22) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//pase joase - scot heading din calcul si pun firsttouch
						$valFW -= ($this->Heading*.39 - $this->FirstTouch * .69) * ($this->Condition/1000 + $this->Form/100);break;				
					}

					break;
				case 9: 
					$valFW = ($this->Shooting + $this->Heading + $this->Positioning *.7 + $this->Speed * .5 + $this->Dribbling *.4) * ($this->Condition/1000 + $this->Form/100);
					//jocul cu pase
					//cind se joaca cu pase inalte, importanta sporita capata jocul de cap
					switch($this->Pase) {
						case 1: 
							break;
						case 2: 
						//pase inalte, adaug la valoare un supliment de cap
						$valFW += ($this->Heading*.49 + $this->FirstTouch * .22) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//pase joase - scot heading din calcul si pun firsttouch
						$valFW -= ($this->Heading*.39 - $this->FirstTouch * .69) * ($this->Condition/1000 + $this->Form/100);break;				
					}

					break;
				case 10: 
					$valFW = ($this->Shooting + $this->Heading + $this->Positioning *.7 + $this->Speed * .5 + $this->Dribbling *.4) * ($this->Condition/1000 + $this->Form/100);
					//jocul cu pase
					//cind se joaca cu pase inalte, importanta sporita capata jocul de cap
					switch($this->Pase) {
						case 1: 
							break;
						case 2: 
						//pase inalte, adaug la valoare un supliment de cap
						$valFW += ($this->Heading*.49 + $this->FirstTouch * .22) * ($this->Condition/1000 + $this->Form/100);break;
						case 3:
						//pase joase - scot heading din calcul si pun firsttouch
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
