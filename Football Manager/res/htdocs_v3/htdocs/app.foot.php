
 		<div id="footer">
			
			<font face="Arial" color="white" size="-2">
			Copyright &euro; myfm.com</font>
			<br/><br/>

		</div>
</div>

<?php
if(!empty($_SESSION['USERID'])) {
?>
<!--
<div class="footer-nou">
<h3>Ai o problema sau o sugestie? Trimite-ne un mesaj rapid!&nbsp;</h3>
<form action="insMesajToAdmin.php" method="Post">
<input type="hidden" name="fromid" value="<?php echo $_SESSION['USERID']; ?>">
<textarea name="mesajtoadmin" cols="50" rows="3">
</textarea>
<br/>
<input type="Submit" name="TrimiteMesajAdmin" value="Trimite" class="button-2">
</form>
</div>
-->
<?php
}
?>

<div class="newsTicker" height="10">
<marquee scrolldelay="1" scrollamount="5">
<font color="white">
myFM.com ::
<?php
$sql = "SELECT title, text, data FROM news ORDER BY id DESC";
$res = mysqli_query($GLOBALS['con'],$sql);
$i=0;
while(list($n_titlu, $n_text, $n_data) = mysqli_fetch_row($res)) {
	echo "$n_titlu ($n_data): ".strip_tags($n_text);
	$i++;
	if($i>4) break;
}
mysqli_free_result($res);
?>
</font>
</marquee>
</div>



</body>
</html>