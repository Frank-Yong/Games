 		<div id="footer">
			
			<font face="Arial" color="white" size="-2">
			Copyright &euro; 2020 MyFM.com</font>
			<br/><br/>

		</div>
</div>

<?php
if(!empty($_SESSION['USERID'])) {
}
?>

<div class="newsTicker" height="10">
<marquee scrolldelay="1" scrollamount="5">
<font color="white">
MyFM.com ::
<?php
$sql = "SELECT titlu, text, data FROM news ORDER BY id DESC";
$res = mysql_query($sql);
$i=0;
while(list($n_titlu, $n_text, $n_data) = mysql_fetch_row($res)) {
	echo "$n_titlu ($n_data): ".strip_tags($n_text);
	$i++;
	if($i>1) break;
}
mysql_free_result($res);
?>
</font>
</marquee>
</div>



</body>
</html>