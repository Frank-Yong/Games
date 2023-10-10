<?php
$_CHECK_LOGIN = 0;
include ("../app.conf.php");

include("admin.head.php");

?>
<form action="index.php" method = "POST">
User: <input type="text" name="userName" size="20">
<br/>
Pass: <input type="password" name="password" size="20">
<br/>
<input type="Submit" name="Login" value="Login">
</form>