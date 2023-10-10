<h1>Trimite mesaj</h1>
<form action="index.php" method="POST">
Catre: <input type="text" name="toname" value="<?php echo $_REQUEST['toname']; ?>" class="input-1">
<input type="hidden" name="to" value="<?php echo $_REQUEST['to']; ?>">
<input type="hidden" name="option" value="messages">
<br/>
Subiect: <input type="text" name="subiect" size="20" class="input-1">
<br/>
Mesaj:<br/>
<textarea name="mesaj" rows="5" cols="40" class="textarea-1"></textarea>
<br/>
<input type="Submit" name="TrimiteMesaj" value="Trimite mesaj" class="button-2"> 
</form>