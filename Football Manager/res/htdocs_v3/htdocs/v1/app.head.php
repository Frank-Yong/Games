<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html xmlns:fb="http://ogp.me/ns/fb#">
<head>
<title>My Football Manager</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<meta name="description" content="Football Manager"/>
<meta name="robots" content="index, follow" />
<meta name="keywords" content="football, manager"/>


<meta property="og:title" content="<?php echo $title; ?>" />
<meta property="og:description" content="<?php echo $content; ?>" />
<meta property="og:type" content="website" />
<meta property="og:image" content="http://www.cupaligii.ro/images/cup-trophy.png" />
<meta property="og:url" content="<?php echo $link; ?>" />
<meta property="og:site_name" content="Football Manager" />
<meta property="fb:admins" content="100009240351119" />


<link href="css/style.css" rel="stylesheet" type="text/css" />
<link rel="icon" type="image/png" href="http://cupaligii.ro/favicon-16x16.png">
<meta name="viewport" content="width=device-width">
<script language="javascript" src="calendar/calendar.js"></script>

<script src="functions.js" type="text/javascript"></script>
<script src="menu.js" type="text/javascript"></script>
<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="js/datetime.js"></script>


<link href="css/demo.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.min.js"></script>
<script	src="js/jquery.smint.js" type="text/javascript" ></script>





<script type="text/javascript">
	
$(function () {

    $(".div2").hide();
    
    $(".link1, .link2").bind("click", function () {

      $(".div1, .div2").hide();        
        
      if ($(this).attr("class") == "link1")
      {
        $(".div1").show();
      }
      else 
      { 
        $(".div2").show();
      }
    });

});

$(document).ready( function() {
    $('.subMenu').smint({
    	'scrollSpeed' : 1000
    });
});

var el = document.getElementById('myCoolForm');

el.addEventListener('submit', function(){
    return confirm('Are you sure you want to submit this form?');
}, false);


function validate(form) {
	var locuri = 0, pret=0;
	locuri = parseInt(document.getElementById("s1").value);
	locuri += parseInt(document.getElementById("s2").value);
	locuri += parseInt(document.getElementById("s3").value);
	locuri += parseInt(document.getElementById("s4").value);
	locuri += parseInt(document.getElementById("s5").value);
	locuri += parseInt(document.getElementById("s6").value);
	locuri += parseInt(document.getElementById("s7").value);
	locuri += parseInt(document.getElementById("s8").value);
	pret = locuri*500;
	
        return confirm('Esti sigur ca maresti capacitatea cu ' + locuri + ' locuri? Te va costa '+pret+' euro!');
}

function validateParking(form) {
	var locuri2 = 0, pret2=0;
	locuri2 = parseInt(document.getElementById("locuri").value);
	pret2 = locuri2*200;
	
    return confirm('Esti sigur ca maresti parcarea cu ' + locuri2 + ' locuri? Te va costa '+pret2+' euro!');
}

function validateRespecializare(form) {
	
    return confirm('Esti sigur ca-l trimiti la respecializare? Te va costa 100.000 euro si 2 saptamani nu-l vei putea folosi!');
}


function estimareScor(form) {
	
    return confirm('Esti sigur ca vrei sa estimezi scorul partidei (nu este 100%corect!)? Te va costa 100.000 euro!');
}

</script>

<script language="javascript" src="calendar.js"></script>
</head>
<?php
$sql = "SELECT Count(toID) FROM messages
		WHERE citit=0 AND toID=".$_SESSION['USERID']. " ";
$res = mysql_query($sql);
list($unread)=mysql_fetch_row($res);
mysql_free_result($res);
?>
<body>
<?php
if(!empty($_SESSION['USERID'])) {
	if($_REQUEST['option']!='mecionline'){
		$sql = "INSERT INTO tracking (userid, pagina, data)
				VALUES(".$_SESSION['USERID'].",'".basename($_SERVER["REQUEST_URI"])."', '".Date("Y-m-d H:i:s")."')";
		mysql_query($sql);
	}
}
//echo basename($_SERVER["REQUEST_URI"]);
?>

<div class="wrap ">
	<div class="section sTop">
				<div class="inner">
					
					<h1>MyFM.com</h1>
					<h3>Football Manager</h3>
				</div>
	</div>
	<div class="subMenu" >
	 	<div class="inner">
	 		<a href="#sTop" class="subNavBtn"><img src="images/cup-trophy.png" width="40" class="img-3"></a>
			<a href="index.php" class="subNavBtn">NEWS</a>
			<?php
			if($_SESSION['USERID']>0) { 
				
			?>
	 		<a href="index.php?option=club" class="subNavBtn">CLUB</a>
			<a href="index.php?option=messages" class="subNavBtn">
			<?php
				if($unread>0) {
						echo "<font color=\"lightgreen\">MESSAGES&nbsp;($unread)</font>";
				} else {
						echo "MESSAGES";
				}
			?>
			</a>
			<a href="index.php?option=management" class="subNavBtn">ACTIVITY</a>
			<a href="index.php?option=tactics" class="subNavBtn">TACTICS</a>
			<a href="index.php?option=searchteam" class="subNavBtn">SEARCH</a>
			<a href="index.php?option=logoff" class="subNavBtn">EXIT</a>
			<a href="index.php?option=despre" class="subNavBtn"><img src="images/despre.png" width="25"></a>			
								<?php } else { ?> 				
				<a href="index.php?option=register" class="subNavBtn">JOIN</a>			
				<a href="index.php?option=despre" class="subNavBtn"><img src="images/despre.png" width="25"></a>			
				<a href="index.php?option=parola" class="subNavBtn">RESET&nbsp;PASSWORD</a>			
		<?php } ?>
		
		
				<?php 
	if ($_SESSION['USERID']>0) {
		$user = new user();
		$user->LoginId($_SESSION['USERID']);
		if($user->LastActive <> Date("Y-m-d")) {
			//fac update la lastactive, daca data e diferita
			//daca nu isi da log off, inregistrez acolo doar data de logare, asa ca poate sa fie diferita
			$sactiv = "UPDATE user SET LastActive='".Date("Y-m-d H:i:s")."' WHERE id=".$_SESSION['USERID'];
			mysql_query($sactiv);
		}
		echo "<div class=\"textsus\">Welcome, <a href= \"index.php?option=modificaCont\" class=\"link-3\">".$user->GetManagerName()."</a>!&nbsp;<a href=\"f_index.php\"><img src=\"images/ficon.png\" width=\"19\" border=\"0\"></a>&nbsp;</div>";
	} else {
	?>
	
	<form action="index.php" method="post" class="container-nou">
		<input type="text" name="userName" class="input-nou" placeholder="utilizator"/>
		<input name="password" type="password" class="input-nou" placeholder="parola"/>
		<input name="Login" type="submit" value="LOGIN" class="button-2"/>
	</form>
	<?php } ?>
		</div>
	</div>
	
	
	
