<!--
Un singur eveniment pe zi.
Daca exista meci, trebuie sa nu se poata adauga alt eveniment.
Exista cheie unica pe cimpul data din evenimente


//inregistrez evenimentul si in tabela de requests, astfel incit sa-l procesez in ziua respectiva
//pe cimpul categorie pun Eveniment, iar in detaliu tipul evenimentului, astfel incit sa pot identifica daca e turneu de tineret sau zi libera

-->
<h2>Calendar</h2>
<?php
require_once('calendar/tc_calendar.php');

if(!empty($_REQUEST['sterge'])) {
	//sterg eveniment doar daca este mai mare decit data curenta
	if($_REQUEST['sterge']>Date("Y-m-d")) {
		$sql = "DELETE FROM evenimente WHERE data='".$_REQUEST['sterge']."' AND userid=".$_SESSION['USERID'];
		mysqli_query($GLOBALS['con'],$sql);
		$sql = "DELETE FROM requests WHERE data='".$_REQUEST['sterge']."' AND userid=".$_SESSION['USERID'];
		mysqli_query($GLOBALS['con'],$sql);
	}
}

include('management.head.php');

?>
<br/>
<h2>Events</h2>
<form action="index.php" method="POST">
<table class="tftable">
<tr>
	<td>
	Pick the day:
	</td>
	<td>
		<?php
		
		  $myCalendar = new tc_calendar("date5", true, false);
		  $myCalendar->setIcon("calendar/images/iconCalendar.gif");
		  
		    $datetime = new DateTime('tomorrow');
			$d = $datetime->format('d');
		  
		  $myCalendar->setDate($d, date('m'), date('Y'));
		  $myCalendar->setPath("calendar/");
		  $year = Date("Y");
		  $curDate = Date("Y-m-d");
		  $myCalendar->setYearInterval($year, 2022);
		  $myCalendar->dateAllow('2018-05-13', '2025-03-01');
		  $myCalendar->setDateFormat('j F Y');
		  
		    //blochez datele deja ocupate, dar si data curenta
		  $myCalendar->setSpecificDate(array($curDate),0,'');	

		  $sql = "(SELECT datameci FROM invitatiemeci WHERE accepted=1 AND datameci>'".Date("Y-m-d")."' AND (userId_1=".$_SESSION['USERID']." OR userId_2=".$_SESSION['USERID']."))
				   UNION
				   (SELECT data FROM evenimente WHERE data>'".Date("Y-m-d")."' AND userid=".$_SESSION['USERID'].")";
		  $rescal = mysqli_query($GLOBALS['con'],$sql);
		  while(list($dmeci) = mysqli_fetch_row($rescal)) {
				$myCalendar->setSpecificDate(array($dmeci),0,'');	
		  }
		  mysqli_free_result($rescal);

		  
		  
		  //$myCalendar->setHeight(350);
		  //$myCalendar->autoSubmit(true, "form1");
		  //$myCalendar->setAlignment('left', 'bottom');
		  //$myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
		  //$myCalendar->setSpecificDate(array("2011-04-10", "2011-04-14"), 0, 'month');
		  //$myCalendar->setSpecificDate(array("2011-06-01"), 0, '');
		  $myCalendar->writeScript();
			  
			  //echo("<p>the date value from getDate() at construct time = ".$myCalendar->getDate()."</p>");

		?>
	</td>
	</tr>
<?php
$sql = "SELECT id, nume FROM evenimentetip ORDER BY id ASC";
$res = mysqli_query($GLOBALS['con'],$sql);
while(list($idtip, $numetip) = mysqli_fetch_row($res)) {
?>
	<tr>
	<td>
	<?php echo $numetip; ?>
	</td>	
	<td>
	<input type="radio" name="ev" value="<?php echo $idtip; ?>">
	</td>
	</tr>
<?php } ?>
	<tr><td colspan="2"><input type="Submit" name="SetEveniment" value="Set the event" class="button-2"></td></tr>
</table>
</form>
<br/>
<form action="" method="POST">
Pick a month: <select name="luna" class="select-2">
<?php
for($i=1;$i<13;$i++) {
	$selected = "";
	if($i == Date("m")) $selected = " selected";
	$dateObj   = DateTime::createFromFormat('!m', $i);
	$monthName = $dateObj->format('F');
	echo "<option value=\"$i\" $selected>$monthName";
}	
?>
</select>
<input type="Submit" name="SetLuna" value="Choose" class="button-2">
</form>
<?php

$curmonth = !empty($_REQUEST['luna'])?$_REQUEST['luna']: Date("m");

$sql = "SELECT a.data, b.nume
		FROM evenimente a
		LEFT OUTER JOIN evenimentetip b
		ON a.tip=b.id
		WHERE MONTH(a.data) = '$curmonth' AND a.userid=".$_SESSION['USERID'];
//echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
while(list($data, $tipev) = mysqli_fetch_row($res)) {
	$ev[$data] = $tipev;
}
mysqli_free_result($res);


$sql = "SELECT b.TeamName, c.TeamName, a.gamedate, a.score, d.nume
		FROM gameinvitation a
		LEFT JOIN user b
		ON b.id=a.userId_1
		LEFT JOIN user c
		ON c.id=a.userId_2
		LEFT JOIN competition d
		ON d.id=a.competitionid
		WHERE (b.id=".$_SESSION['USERID'] . ' OR c.id='.$_SESSION['USERID'].
		") AND a.accepted=1 AND MONTH(a.gamedate) = $curmonth";
//echo "$sql<br/>";
$res = mysqli_query($GLOBALS['con'],$sql);
while(list($ech1, $ech2, $dmeci, $sco, $numecomp) = mysqli_fetch_row($res)) {
	if($numecomp == "") $numecomp = "Amical";
	$sc = $dmeci." 13:45">Date("Y-m-d H:i") ? $sc = ":" : $sc=$sco; 
	$ev2[$dmeci] = "<font color=\"link-5\"><i>($numecomp) $ech1 $sc $ech2</i></font>";
}
mysqli_free_result($res);

?>
<table class="tftable">
<tr>
	<th colspan="2">Day</th>
	<th>Day/Event</th>
	<th>Action</th>
</tr>		
<?php

$number = cal_days_in_month(CAL_GREGORIAN, $curmonth, Date("Y")); // 31
for($i=1;$i<=$number;$i++) {
		$dataa = date("Y-m-d",mktime(0, 0, 0, $curmonth, $i, date('Y')));
		echo "<tr><td>$i.</td><td>".date('l', mktime(0, 0, 0, $curmonth, $i, date('Y')))."</td>";
		$deafisat = $ev[$dataa]?"<font color=\"red\">".$ev[$dataa]."</font>":"No event"; 
		$deafisat2 = $ev[$dataa]?"<a href=\"index.php?option=calendar&sterge=$dataa\"><img src=\"images/stergere.png\" width=\"20\"></a>":"";
		if($ev2[$dataa]) echo "<td>".$ev2[$dataa]."</td>"; 
		else echo "<td>$deafisat</td>";
		echo "<td>$deafisat2</td></tr>";
}

?>					
</table>					
