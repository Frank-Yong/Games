<?php
require_once('calendar/classes/tc_calendar.php');
?>


              <p class="largetxt"><b>DatePicker with no input box</b></p>
              <table border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td nowrap>Date 3 :</td>
                  <td><?php
					  $myCalendar = new tc_calendar("date5", true, false);
					  $myCalendar->setIcon("calendar/images/iconCalendar.gif");
					  //$myCalendar->setDate(date('d'), date('m'), date('Y'));
					  $myCalendar->setPath("calendar/");
					  $year = Date("Y");
					  $curDate = Date("Y-m-d");
					  $myCalendar->setYearInterval($year, 2020);
					  $myCalendar->dateAllow('2008-05-13', '2020-03-01');
					  $myCalendar->setDateFormat('j F Y');
					  //$myCalendar->setHeight(350);
					  //$myCalendar->autoSubmit(true, "form1");
					  $myCalendar->setAlignment('left', 'bottom');
					  $myCalendar->setSpecificDate(array("2011-04-01", "2011-04-04", "2011-12-25"), 0, 'year');
					  $myCalendar->setSpecificDate(array("2011-04-10", "2011-04-14"), 0, 'month');
					  $myCalendar->setSpecificDate(array("2011-06-01"), 0, '');
					  $myCalendar->writeScript();
					  ?></td>
					  <!--
                  <td><input type="button" name="button" id="button" value="Check the value" onClick="javascript:alert(this.form.date5.value);"></td>
-->