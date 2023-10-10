<?php
include('..\app.conf.php');
include('admin.head.php');


if(isset($_REQUEST['Login'])) {
	//dupa login normal
	$username = $_REQUEST['userName'];
	$password = $_REQUEST['password'];

	$sql = "SELECT id FROM user WHERE username = '".$username."' AND password = '".md5($password)."'";
	echo "$sql<br/>";
	$res = mysqli_query($GLOBALS['con'],$sql);
	list($id) = mysqli_fetch_row($res);

	echo "IDDDD: $id<br/>";
	if($id>0) {
		$_SESSION['USERID'] = $id;
		echo "<br/>All good, you can start<br/>";
	}
	mysqli_free_result($res);

}

?>