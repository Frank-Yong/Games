<link href="calendar/calendar.css" rel="stylesheet" type="text/css" />
<?php
require_once('calendar/tc_calendar.php');

//inainte de a afisa calendarul, trebuie sa tai zilele deja ocupate

$sql = "SELECT TeamName FROM user WHERE id=".$_REQUEST['club_id'];
$res = mysqli_query($GLOBALS['con'],$sql);
list($Oponent) = mysqli_fetch_row($res);
mysqli_free_result($res);
?>
<h2>Play friendly game!</h2>
<br/></br/>
<form action="index.php" method="post"/>
<input type="hidden" name="club_id" value="<?php echo $_REQUEST['club_id']; ?>"/>
<table class="tftable"  width="600" cellpadding="1">
<tr>
<th>
Data
</th>
<th>
Stadion
</th>
</tr>
<tr>
<td colspan="2">Ask <?php echo $Oponent; ?> to play a friendly game!</td>
</tr>
<tr>
<td>
<?php
//define("L_LANG", "en_US");

  	  $myCalendar = new tc_calendar("date5", true);
	  $myCalendar->setDate(date('d'), date('m'), date('Y'));
	  $myCalendar->setPath("calendar/");
	  $myCalendar->setYearInterval(2020, 2024);
	  $myCalendar->dateAllow('1890-01-01', '2080-05-01', false);
	  $myCalendar->showWeeks(true);
	  $myCalendar->setSpecificDate(array("2011-01-01", "2011-04-14", "2011-12-25"), 0, 'year');

	  $myCalendar->setTimezone("Europe/Bucharest"); //Australia/Melbourne, Asia/Tokyo, America/Montreal
  
  //blochez datele deja ocupate, dar si data curenta
  $myCalendar->setSpecificDate(array($curDate),0,'');	

  $sql = "(SELECT gamedate FROM gameinvitation WHERE accepted=1 AND gamedate>'".Date("Y-m-d")."' AND (userId_1=".$_SESSION['USERID']." OR userId_2=".$_SESSION['USERID']."))
		   UNION
		   (SELECT data FROM evenimente WHERE data>'".Date("Y-m-d")."' AND userid=".$_SESSION['USERID'].")";
  $rescal = mysqli_query($GLOBALS['con'],$sql);
  while(list($dmeci) = mysqli_fetch_row($rescal)) {
		$myCalendar->setSpecificDate(array($dmeci),0,'');	
  }
  mysqli_free_result($rescal);
  

	  //$myCalendar->setTheme('theme3');

  
  
//		$myCalendar->setSpecificDate(array('2015-04-28'),0,'');
		/*
  					  $myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
					  $myCalendar->setSpecificDate(array("2011-04-10", "2011-04-14"), 0, 'month');
					  $myCalendar->setSpecificDate(array("2011-06-01"), 0, '');
		*/
  //$myCalendar->setHeight(350);
  //$myCalendar->autoSubmit(true, "form1");
  //$myCalendar->setAlignment('left', 'bottom');
  $myCalendar->writeScript();
	  
	  //echo("<p>the date value from getDate() at construct time = ".$myCalendar->getDate()."</p>");

?>
</td>
<td>
<?php
$sql = "SELECT a.stadiumId, b.name 
		FROM user a 
		LEFT OUTER JOIN stadium b 
		ON a.stadiumId=b.id 
		WHERE a.id IN (". $_REQUEST['club_id'].", ".$_SESSION['USERID'].")";
$res = mysqli_query($GLOBALS['con'],$sql);
echo "Stadium:<select name=\"stadion\" class=\"select-1\">";
while(list($stadiumId, $stadiumName)=mysqli_fetch_row($res)) {
	echo "<option value=\"$stadiumId\"/>$stadiumName";
}
echo "</select>";
mysqli_free_result($res);
?>
</td>
</tr>
<tr>
<td colspan="2" align="right">
<input type="Submit" name="SetAmical" value="Send invitation" class="button-2"/>
</td>
</tr>
</table>
</form>