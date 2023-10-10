<?php
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

include('admin.head.php');

$sql = "select id, Talent from player order by id ASC";
$res = mysql_query($sql);

while(list($pid, $ptalent)=mysql_fetch_row($res)){
	
		//will never be
		if ($ptalent < 21) {
			$coeficient = CST_WNnou;
			$cst_talent = 'CST_TAL_WN';
			$rand=8;
		}
		//one change
		if ($ptalent > 20 && $ptalent < 41) {
			$coeficient = CST_OCnou;
			$cst_talent = 'CST_TAL_OC';
			$rand=6;
		}
		//can become
		if ($ptalent > 40 && $ptalent < 61) {
			$coeficient = CST_CBnou;
			$cst_talent = 'CST_TAL_CB';
			$rand=5;
		}
		//very talented
		if ($ptalent > 60 && $ptalent < 81) {
			$coeficient = CST_VTnou;
			$cst_talent = 'CST_TAL_VT';
			$rand=4;
		}
		//superstar
		if ($ptalent > 80) {
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
		VALUES ($pid, " .
		$m_Reflexes . ", " . $m_OneOnOne . ", " . $m_Handling . ", " . $m_Communication . ", " . $m_Tackling . ", " . $m_Passing . ", " . $m_LongShot . ", " . $m_Shooting . ", " . $m_Heading . ", " . $m_Creativity . ", " . $m_Crossing . ", " . $m_Marking . ", " . $m_FirstTouch . ", " . $m_Strength . ", " . $m_Speed . ", " . $m_Dribbling . ", " . $m_Positioning . ")";
		echo "$sql<br/>";
		mysql_query($sql);
}
mysql_free_result($res);
?>