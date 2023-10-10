<?php
error_reporting(63);
include('../app.conf.php');
include('../player.php');
include('../UserStadium.php');
include('../trainer.php');

/////////////////////////////////////////////////////////
//FIRST: put in app.conf next season. Variable: $_SEASON
/////////////////////////////////////////////////////////


//insert users in league
//tablel leagueuser
$sql = "INSERT INTO leagueuser(competitionid,userid,season) 
		SELECT 0, id, ".$_SESSION['_SEASON']." 
		FROM user";
echo "$sql<br/>";
mysqli_query($GLOBALS['con'],$sql);

//Process season


//1. Check sponsors - decrease period in table sponsoriuser
			//if period becomes 0, set activ=0
			//if period > 0, aadd value from price ("pret") to funds of the team


$sql = "SELECT id, pret, perioada, userid
		FROM sponsoriuser
		WHERE activ=1";
		
$res = mysqli_query($GLOBALS['con'],$sql);
while(list($spid, $pret,$perioada,$userid) = mysqli_fetch_row($res)) {
	if($perioada==1) {
		//by decreasing, it becomes inactive (activ=0) => appears on the screen as 'Ask for sponsors'
		$sql = "UPDATE sponsoriuser SET activ=0, perioada=0 where id=$spid";
		mysqli_query($GLOBALS['con'],$sql);
		
	} elseif($perioada>1) {
		$sql = "UPDATE sponsoriuser SET perioada=perioada-1 WHERE id=$spid";
		mysqli_query($GLOBALS['con'],$sql);
		
		$sql = "UPDATE user SET Funds=Funds+$pret WHERE id=$userid";
		mysqli_query($GLOBALS['con'],$sql);
	}
	
}
mysqli_free_result($res);

//process players
//reset morale and form
//increase age + decrease contract period
//if user is not active in the last 2 weeks, i don't decrease period of contract, because then i will have to generate more players of the team
//also, do not increase age for youth team, to remain like it is for this inactive user
//if contract is 0, update to userplayer, by setting userid=0 and changes in the player table to have it on transfer list Transfer=1
$sql = "SELECT id, age, contract, transfer, moral, form
		FROM player";

$res = mysqli_query($GLOBALS['con'],$sql);
while(list($pid, $page, $pcontract, $ptransfer, $pmoral, $pform) = mysqli_fetch_row($res)) {
	if($page<=17) {
		$sql = "UPDATE lineup SET pgroup=2 WHERE playerid=$pid";
		mysqli_query($GLOBALS['con'],$sql);
		echo "$sql<br/>";
	}
	if($page==18) {
		//end of contract
		//check the 2 weeks activity period, as mentioned before
		
		$sss = "SELECT  b.LastActive
				FROM userplayer a
				LEFT OUTER JOIN user b
				ON a.userid=b.id
				WHERE a.playerid=$pid";
		$rrr = mysqli_query($GLOBALS['con'],$sss);
		list($d_activ) = mysqli_fetch_row($rrr);
		mysqli_free_result($rrr);

		$dEnd  = date('Y-m-d H:i:s');
		$date1 = new DateTime($d_activ);
		$date2 = new DateTime($dEnd);

		$diff = $date2->diff($date1);
		
		$zileinactiv = $diff->days;
		
		//only if the user has activity in the last 2 weeks goes inside
		if($zileinactiv<14) {
			$sql = "UPDATE player SET youth=0 WHERE id=$pid";
			mysqli_query($GLOBALS['con'],$sql);
			echo "$sql<br/>";
			//player becomes available for first team (older than 18)
			$sql = "UPDATE lineup SET pgroup=1,post=0 WHERE playerid=$pid";
			mysqli_query($GLOBALS['con'],$sql);
		}
	}
	$activstatus = 1;
	if($page==33) {
		$sql = "UPDATE player SET active=0 WHERE id=$pid";
		mysqli_query($GLOBALS['con'],$sql);
		$pcontract=1;
		$activstatus = 0;
		echo "$sql<br/>";
	}
	if($pcontract<=1) {
		//contract ends
		//same check, for 2 weeks activity for user
		$sss = "SELECT  b.LastActive
				FROM userplayer a
				LEFT OUTER JOIN user b
				ON a.userid=b.id
				WHERE a.playerid=$pid";
		$rrr = mysqli_query($GLOBALS['con'],$sss);
		list($d_activ) = mysqli_fetch_row($rrr);
		mysqli_free_result($rrr);

		$dEnd  = date('Y-m-d H:i:s');
		$date1 = new DateTime($d_activ);
		$date2 = new DateTime($dEnd);

		$diff = $date2->diff($date1);
		
		$zileinactiv = $diff->days;
		
		//doar daca e jucator cu activitate il trec prin procedura
		if($zileinactiv<14) {
			//check bids on the player
			$sql = "UPDATE playerbid SET activ=0 WHERE playerid=$pid";
			mysqli_query($GLOBALS['con'],$sql);
			
			//inactive player doesn't have to be put on transfer list
			if($activstatus == 1)
				$sql = "UPDATE player SET moral=100, form=100, age=age+1, contract=0, transfer=1, transfersuma=0, transferdeadline='0000-00-00 00:00:00' WHERE id=$pid";
			else
				$sql = "UPDATE player SET moral=100, form=100, age=age+1, contract=0, transfer=0, transfersuma=0, transferdeadline='' WHERE id=$pid";
				
			mysqli_query($GLOBALS['con'],$sql);
			
			$sql = "UPDATE userplayer SET userid=0 WHERE playerid=$pid";
			mysqli_query($GLOBALS['con'],$sql);

			$sql = "UPDATE trainerplayer SET trainerid=0 WHERE playerid=$pid";
			mysqli_query($GLOBALS['con'],$sql);
		
			//delete bids to avoid confusion
			$sql = "DELETE FROM playerbid WHERE playerid=$pid";
			mysqli_query($GLOBALS['con'],$sql);
		

			$sql = "UPDATE lineup SET userid=0, post=0 WHERE playerid=$pid";
			mysqli_query($GLOBALS['con'],$sql);
		} //zile inactivitate user

	} elseif($pcontract>1) {
		$sql = "UPDATE player SET moral=100, form=100, age=age+1, contract=contract-1 WHERE id=$pid";
		mysqli_query($GLOBALS['con'],$sql);
	}
}
mysqli_free_result($res);
		


?>