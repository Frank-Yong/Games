<?php
//include('app.conf');
//include('definitions.inc');
//error_reporting(63);

class Trainer {
	private $TrainerID;
	private $Age;
	private $Goalkeeping;
	private $Defence;
	private $Midfield;
	private $Attack;
	private $Country;
	private $Tactical;
	private $Motivation;
	private $Youth;
	private $FirstName;
	private $LastName;
	private $Wage;
	private $Contract;
	private $Bonus;
	private $Poza;
	private $Echipa;
	private $where;

	private $CLiga;
	function __construct($MyLiga=1, $TrainerID=1, $param=1) {
		//$this->GenerateCountry();
		$this->Country = 3;

		if(func_num_args()==2) {
			//se trimit 
			//se doreste doar afisarea, antrenorul existind
			//echo "am intrat la afisare";
			$this->TrainerID=$TrainerID;
			$this->where = "a.id=".$this->TrainerID;
			$this->GetData();
			return;
		}	
		
		if(func_num_args()==3) {
			//afisare antrenor echipa
			$this->TrainerID=$TrainerID;
			$this->where = "d.id=".$_SESSION['USERID'];
			$this->GetData();
			return;
		}		
		
		$this->CLiga = $MyLiga;
		//generare nume antrenor, in functie de tara din care face parte
		$this->GenerateFirstName();
		$this->GenerateLastName();

		$this->Age = rand(T_AGE_START, T_AGE_END);
		$this->GenerateValues();
	}

	private function GenerateValues() {

		$this->Goalkeeping = round(rand(T_VALUE_START,T_VALUE_END)*$this->CLiga);
		//echo $this->Goalkeeping . ' Valoare goal<br/>';
		$this->Defence = round(rand(T_VALUE_START,T_VALUE_END)*$this->CLiga);
		//echo $this->Defence . ' Valoare defence<br/>';
		$this->Midfield = round(rand(T_VALUE_START,T_VALUE_END)*$this->CLiga);
		$this->Attack = round(rand(T_VALUE_START,T_VALUE_END)*$this->CLiga);
		$this->Tactical = round(rand(T_VALUE_START,T_VALUE_END)*$this->CLiga);
		$this->Motivation = round(rand(T_VALUE_START,T_VALUE_END)*$this->CLiga);
		$this->Youth = round(rand(T_VALUE_START,T_VALUE_END)*$this->CLiga);

		$a = array('a'=> $this->Goalkeeping,
				   'b'=> $this>Defence,
				   'c'=> $this->Midfield,
				   'd'=> $this->Attack,
				   'e'=> $this->Motivation,
				   'f'=> $this->Tactical);
		$max = max($a);
		//echo "MAXXX $max<br/>";
		switch($max) {
				case $max<22: $val = 350; break;
				case $max>=22 && $max<36: $val = 420; break;
				case $max>=36: $val = 530; break;
			}
		$salariu = $max*$val;
		
		while(list($k,$v) = each($a)) {
			switch($v) {
				case $v<22: $val = $v*1.5; break;
				case $v>=22 && $v<36: $val = $v*3; break;
				case $v>=36: $val = $v*6; break;
			}
			$salariu += $v*$val;
		}
		$this->Wage = $salariu;

		//$this->Wage = round(($this->Goalkeeping+$this->Defence+$this->Midfield+$this->Attack+$this->Motivation*.6+$this->Tactical*.6+$this->Youth)/7*1000);
		$this->Bonus = $this->Wage*(2.65+rand(50,100)/100);
		$this->Contract = 0;

		$this->WriteData();

	}

	private function GenerateCountry() {
		$sql = "SELECT id FROM country";
		$res = mysqli_query($GLOBALS['con'],$sql);

		$val = rand(0, mysqli_num_rows($res)-1);
		mysqli_data_seek($res, $val);
		$row = mysqli_fetch_assoc($res);
		$this->Country = $row['id'];
		mysqli_free_result($res);
	}

	private function GenerateFirstName() {
		$sql = "SELECT name FROM firstname WHERE country=" . $this->Country;
		$res = mysqli_query($GLOBALS['con'],$sql);

		$val = rand(0, mysqli_num_rows($res)-1);
		mysqli_data_seek($res, $val);
		$row = mysqli_fetch_assoc($res);
		$this->FirstName = $row['name'];
		mysqli_free_result($res);
	}

	private function GenerateLastName() {
		$sql = "SELECT name FROM lastname WHERE country=" . $this->Country;
		$res = mysqli_query($GLOBALS['con'],$sql);

		$val = rand(0, mysqli_num_rows($res)-1);
		mysqli_data_seek($res, $val);
		$row = mysqli_fetch_assoc($res);
		$this->LastName = $row['name'];
		mysqli_free_result($res);
	}

	private function WriteData() {
		
		$poza = 't'.rand(1,5).'.png';
			$sql = "INSERT INTO trainer (
		fname, lname, Goalkeeping, Defence, Midfield, Attack, knowhow, speech, Youth, Wage, Contract, Bonus, Country, pic) 
		VALUES (" .
		"'" . $this->FirstName . "', '" . $this->LastName . "', " . $this->Goalkeeping . ", " . $this->Defence . ", " . $this->Midfield . ", " . $this->Attack . ", " . $this->Tactical . ", " . $this->Motivation . ", " . $this->Youth . ", " . $this->Wage . ", " . $this->Contract . "," . $this->Bonus .", " .$this->Country.",'$poza')";
		//echo "$sql<br/>";
		mysqli_query($GLOBALS['con'],$sql);
		$this->TrainerID = mysqli_insert_id($GLOBALS['con']);


	}

	public function Fire() {
		$sql = "DELETE FROM usertrainer WHERE userid=".$_SESSION['USERID'];
		mysqli_query($GLOBALS['con'],$sql);
		
		$sql = "UPDATE trainer SET Contract=0 WHERE id=".$this->TrainerID;
		mysqli_query($GLOBALS['con'],$sql);
		
		$sql = "UPDATE trainerplayer SET trainerid=0 where trainerid=".$this->TrainerID;
		mysqli_query($GLOBALS['con'],$sql);
	}
	
	public function ReturnID() {
		return $this->TrainerID;
	}
	public function ReturnName() {
		return $this->FirstName.' '.$this->LastName;
	}
	
	private function GetData() {
		$sql = "SELECT a.id, a.fname, a.lname, a.Goalkeeping, a.Defence, a.Midfield, a.Attack, b.name, a.Contract, a.Wage, a.pic, a.Bonus, d.TeamName, a.knowhow, a.Speech
				FROM trainer a
				LEFT JOIN country b
				ON a.Country=b.id
				LEFT JOIN usertrainer c
				ON a.id=c.trainerid
				LEFT JOIN user d
				ON d.id=c.userid
				WHERE ".$this->where;
		//echo "$sql<br/>";
		$res = mysqli_query($GLOBALS['con'],$sql);
		list($this->TrainerID, $this->FirstName, $this->LastName, $this->Goalkeeping, $this->Defence, $this->Midfield, $this->Attack, $this->Country, $this->Contract, $this->Wage, $this->Poza, $this->Bonus, $this->Echipa, $this->Tactical, $this->Motivation) = mysqli_fetch_row($res);
		mysqli_free_result($res);

	}
	
	function EchoTrainer() {
		if($this->TrainerID<1 || $this->TrainerID=="") {
			echo "<table class=\"tftable\"><tr><th>You have no trainer hired!</th></tr></table>";
		} else {
			if($this->Poza == "") $ima = "missing_player.jpg";
			else $ima = $this->Poza;
	//		echo "<img src=\"images/missing_player.jpg\" class=\"img-1\" width=\"180\"  align=\"left\">";
			echo "<img src=\"images/$ima\" class=\"img-1\" width=\"196\"  align=\"left\">";
			?>
			<table class="tf2">
			<tr>
				<th colspan="2"><?php echo "<img src=\"steaguri/".$this->Country.".png\" width=\"18\">";
									  echo $this->FirstName . ' ' . $this->LastName; 
									  ?>
				</th>
			</tr>
			<tr>
				<th><?php echo "Goalkeeping ";  ?></th>
				<td><?php echo ColorIt($this->Goalkeeping);  ?></td>
			</tr>
			<tr>
				<th><?php echo "Defensive ";  ?></th>
				<td><?php echo ColorIt($this->Defence);  ?></td>
			</tr>
			<tr>
				<th><?php echo "Midfield ";  ?></th>
				<td><?php echo ColorIt($this->Midfield);  ?></td>
			</tr>
			<tr>
				<th><?php echo "Attacking ";  ?></th>
				<td><?php echo ColorIt($this->Attack);  ?></td>
			</tr>
			<tr>
				<th><?php echo "Knowhow ";  ?></th>
				<td><?php echo ColorIt($this->Tactical);  ?></td>
			</tr>
			<tr>
				<th><?php echo "Speech ";  ?></th>
				<td><?php echo ColorIt($this->Motivation);  ?></td>
			</tr>
			<tr>
				<th><?php echo "Wage: ".number_format($this->Wage)."&euro;/week";  ?></th>
				<td><?php echo "Bonus: ".number_format($this->Bonus)."&euro;";  ?></td>
			</tr>
			<?php
			if($this->Contract == 1) {
			?>
			<tr>
				<th>Team:</th>
				<td><?php echo $this->Echipa;  ?></td>
			</tr>
			<tr>
			<th colspan="2">
				<form action="index.php" method="POST">
				<input type="hidden" name="trainerid" value="<?php echo $this->TrainerID; ?>">
				<input type="Submit" name="ConcediazaAntrenor" class="button-2" value="Fire him!">
				</form>
			</th>
			</tr>
			<?php } else { ?>
			<tr>
				<th colspan="2">
				<form action="index.php?option=club&antrenor=1" method="POST">
				<input type="hidden" name="trainerid" value="<?php echo $this->TrainerID; ?>">
				<input type="hidden" name="bonus" value="<?php echo $this->Bonus; ?>">
				<input type="hidden" name="salariu" value="<?php echo $this->Wage; ?>">
				<input type="Submit" name="Angajeaza" value="Hire him!" class="button-2">
				</form>
				</th>
			</tr>
			<?php } 
		}
		?>
		</table>
		<?php
	}
}

?>
