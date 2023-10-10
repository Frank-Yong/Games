
<link href="calendar/calendar.css" rel="stylesheet" type="text/css" />
<?php
require_once('calendar/tc_calendar.php');
?>
<p><b>DatePicker with no input box</b></p>
              <table border="0" cellspacing="0" cellpadding="2">
                <tr>
                  <td nowrap>Date 3 :</td>
                  <td valign=top><?php
	  $myCalendar = new tc_calendar("date3", true, false);
	  $myCalendar->setIcon("calendar/images/iconCalendar.gif");
	  $myCalendar->setPath("calendar/");
	  $myCalendar->setYearInterval(1945, date('Y'));
	  $myCalendar->dateAllow('2008-05-13', date('Y-m-d'));
	  $myCalendar->setAlignment('left', 'bottom');
	  //$myCalendar->autoSubmit(true, "form1");
	  $myCalendar->writeScript();
	  ?></td>
<!-- START OF: Needed for checking the selected value - not needed in your own script -->
                  <td><input type="button" name="button3" id="button3" value="<?php echo(L_CHK_VAL); ?>" onClick="javascript:alert(this.form.date3.value);" class="font"></td>
<!-- END OF: Needed for checking the selected value - not needed in your own script -->
                </tr>
              </table>
              <ul>
                <li>No default date</li>
                <li>Show the calendar icon </li>
                <li>Set year navigate from 1945 to current year</li>
                <li>Allow date selectable from 13 May 2008 to today</li>
                <li>Allow to navigate other dates from above</li>
                <li>Date input box set to false</li>
              </ul>
              <p><b>Code:</b></p>
              <pre>&lt;?php<br />	  $myCalendar = new tc_calendar(&quot;date3&quot;, <b>true, false</b>);<br />	  $myCalendar-&gt;<b>setIcon(&quot;calendar/images/iconCalendar.gif&quot;)</b>;<br />	  $myCalendar-&gt;setPath(&quot;calendar/&quot;);<br />	  $myCalendar-&gt;setYearInterval(1945, date(&quot;Y&quot;));<br />	  $myCalendar-&gt;dateAllow(&quot;2008-05-13&quot;, date(&quot;Y-m-d&quot;));<br />	  $myCalendar->setAlignment(&quot;left&quot;, &quot;bottom&quot;);<br />	  $myCalendar-&gt;writeScript();<br />?&gt;</pre>
             