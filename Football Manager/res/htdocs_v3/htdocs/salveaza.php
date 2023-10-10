<?php
include('app.conf.php');

	//stergere titulari pentru a introduce altii
	$sql = "DELETE FROM echipastart
			WHERE userId=".$_SESSION['USERID'];
	mysql_query($sql);

	//aflare tactica
	$sql = "SELECT tactica FROM tactica WHERE userid=".$_SESSION['USERID'];
	$restac = mysql_query($sql);
	list($bdtac) = mysql_fetch_row($restac);
	mysql_free_result($restac);

//daca tactica e 4-4-2, atunci a stabilit 2 DC si un DL si un DR
//plus 2 MC si un ML si un MR
// si 2 atacanti FC

//daca a pus 5-4-1 : 3DC-DL-DR+2MC-ML-MR+FC s.a.m..d

	switch($bdtac) {
		//4-4-2
	case 1: $f1=2;$f2=3;$f3=3;$f4=4;$f5=0; $m1=5;$m2=6;$m3=6;$m4=7;$m5=0; $a1=9;$a2=9;$a3=0;break;
		//5-3-2
	case 2: $f1=2;$f2=3;$f3=3;$f4=3;$f5=4; $m1=5;$m2=6;$m3=7;$m4=0;$m5=0; $a1=9;$a2=9;$a3=0;break;
		//3-5-2
	case 3: $f1=3;$f2=3;$f3=3;$f4=0;$f5=0; $m1=5;$m2=6;$m3=6;$m4=6;$m5=7; $a1=9;$a2=9;$a3=0;break;
		//5-4-1
	case 4: $f1=2;$f2=3;$f3=3;$f4=3;$f5=4; $m1=5;$m2=6;$m3=6;$m4=7;$m5=0; $a1=9;$a2=0;$a3=0;break;
		//4-5-1
	case 5: $f1=2;$f2=3;$f3=3;$f4=4;$f5=0; $m1=5;$m2=6;$m3=6;$m4=6;$m5=7; $a1=9;$a2=0;$a3=0;break;
		//4-3-3
	case 6: $f1=2;$f2=3;$f3=3;$f4=4;$f5=0; $m1=5;$m2=6;$m3=7;$m4=0;$m5=0; $a1=8;$a2=9;$a3=10;break;
	}

	while (list($key, $value) = each($_POST['echipa'])) {
		$sql = "INSERT INTO echipastart(playerId, post, meciId, userId)
				VALUES($key, '$value', 1, ".$_SESSION['USERID'].")";
		mysql_query($sql);
	}
	


	?>