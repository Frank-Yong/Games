<!doctype html>
<html class="no-js" lang="zxx">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>My Football Manager</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="manifest" href="site.webmanifest">
		<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

		<!-- CSS here -->
            <link rel="stylesheet" href="assets/css/bootstrap.min.css">
            <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
            <link rel="stylesheet" href="assets/css/ticker-style.css">
            <link rel="stylesheet" href="assets/css/flaticon.css">
            <link rel="stylesheet" href="assets/css/slicknav.css">
            <link rel="stylesheet" href="assets/css/animate.min.css">
            <link rel="stylesheet" href="assets/css/magnific-popup.css">
            <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
            <link rel="stylesheet" href="assets/css/themify-icons.css">
            <link rel="stylesheet" href="assets/css/slick.css">
            <link rel="stylesheet" href="assets/css/nice-select.css">
            <link rel="stylesheet" href="assets/css/style.css">
   </head>

   <body>
       
    <!-- Preloader Start -->
    <!-- <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="assets/img/logo/logo.png" alt="">
                </div>
            </div>
        </div>
    </div> -->
    <!-- Preloader Start -->
<?php
$sql = "SELECT Count(toID) FROM messages
		WHERE citit=0 AND toID=".$_SESSION['USERID']. " ";
$res = mysql_query($sql);
list($unread)=mysql_fetch_row($res);
mysql_free_result($res);
?>

    <header>
        <!-- Header Start -->
       <div class="header-area">
            <div class="main-header ">
                <div class="header-top black-bg d-none d-md-block">
                   <div class="container">
                       <div class="col-xl-12">
                            <div class="row d-flex justify-content-between align-items-center">
                                <div class="header-info-left">
                                    <ul>     
                                        <li><img src="assets/img/icon/header_icon1.png" alt="">34ºc, Sunny </li>
                                        <li><img src="assets/img/icon/header_icon1.png" alt="">Tuesday, 18th June, 2019</li>
                                    </ul>
                                </div>
                                <div class="header-info-right">
                                    <ul class="header-social">    
                                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                       <li> <a href="#"><i class="fab fa-pinterest-p"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                       </div>
                   </div>
                </div>
                <div class="header-mid d-none d-md-block">
                   <div class="container">
                        <div class="row d-flex align-items-center">
                            <!-- Logo -->
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <div class="logo">
                                    <a href="index.php"><img src="assets/img/logo/logo.png" alt=""></a>
                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-9 col-md-9">
                                <div class="header-banner f-right ">
                                    <img src="images/top-game.jpg" alt="">
                                </div>
                            </div>
                        </div>
                   </div>
                </div>
               <div class="header-bottom header-sticky">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-xl-10 col-lg-10 col-md-12 header-flex">
                                <!-- sticky -->
                                    <div class="sticky-logo">
                                        <a href="index.php"><img src="assets/img/logo/logo.png" alt=""></a>
                                    </div>
                                <!-- Main-menu -->
                                <div class="main-menu d-none d-md-block">
                                    <nav>                  
                                        <ul id="navigation">    
													<li><a href="index.php" class="subNavBtn">NEWS</a></li>
													<?php if($_SESSION['USERID']>0) { ?>
													<li><a href="index.php?option=club">CLUB</a></li>
													<li><a href="index.php?option=messages" class="subNavBtn"></li>
													<?php
														if($unread>0) {
																echo "<font color=\"lightgreen\">MESSAGES&nbsp;($unread)</font>";
														} else {
																echo "MESSAGES";
														}
													?>
													</a>
													<li><a href="index.php?option=management" class="subNavBtn">ACTIVITY</a></li>
													<li><a href="index.php?option=tactics" class="subNavBtn">TACTICS</a></li>
													<li><a href="index.php?option=searchteam" class="subNavBtn">SEARCH</a></li>
													<li><a href="index.php?option=logoff" class="subNavBtn">EXIT</a></li>
													<li><a href="about.php"><img src="images/despre.png" width="25"></a></li>			
																		<?php } else { ?> 				
														<li><a href="index.php?option=register">JOIN</a></li>			
														<li><a href="about.php"><img src="images/despre.png" width="25"></a></li>			
														<li><a href="index.php?option=parola" class="subNavBtn">RESET&nbsp;PASSWORD</a></li>			
												<?php } ?>
												
												
														<?php 
											if ($_SESSION['USERID']>0) {
												
												$user = new user();
												$user->LoginId($_SESSION['USERID']);
												if($user->LastActive <> Date("Y-m-d")) {
													$sactiv = "UPDATE user SET LastActive='".Date("Y-m-d H:i:s")."' WHERE id=".$_SESSION['USERID'];
													mysql_query($sactiv);
												}
												echo "<li><a href= \"index.php?option=modificaCont\" class=\"subNavBtn\">".$user->GetManagerName()."</a></li>";
											} else {
											?>
											
											<form action="index.php" method="post">
												<input type="text" name="userName" class="input-nou" placeholder="user"/>
												<input name="password" type="password" class="input-nou" placeholder="password"/>
												<input name="Login" type="submit" value="LOGIN" class="button-2"/>
											</form>
											<?php } ?>
                                        </ul>
                                    </nav>
                                </div>
                            </div>             
                            <div class="col-xl-2 col-lg-2 col-md-4">
                                <div class="header-right-btn f-right d-none d-lg-block">
                                    <i class="fas fa-search special-tag"></i>
                                    <div class="search-box">
                                        <form action="#">
                                            <input type="text" placeholder="Search">
                                            
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Mobile Menu -->
                            <div class="col-12">
                                <div class="mobile_menu d-block d-md-none"></div>
                            </div>
                        </div>
                    </div>
               </div>
            </div>
       </div>
        <!-- Header End -->
    </header>

	
	
