<?php
header("Content-type: image/png");


$p = $_GET['percentage']; //(e.g. 20);
if($p==0)$p=1;
$w = 100;
$h = 10;

$nh = ($w*$p)/100; 

$im = imagecreatetruecolor($w, $h);

$color1 = imagecolorallocate($im, 217,217,217);
switch(true) {
   case in_array($p, range(0,20)): //the range from range of 0-20
      $r=255;
   break;
   case in_array($p, range(21,40)): //range of 21-40
      $r=205;
	  break;
   case in_array($p, range(41,60)): //range of 21-40
      $r=145;
	  break;
   case in_array($p, range(61,80)): //range of 21-40
      $r=95;
	  break;
   case in_array($p, range(81,100)): //range of 21-40
      $r=0;
	  break;
}
//$r=200;
$g=255-$r;
$b=0;
$color2 = imagecolorallocate($im, $r,$g,$b);

//bool imagefilledrectangle ( resource $image , int $x1 , int $y1 , int $x2 , int $y2 , int $color )

//background

imagefilledrectangle($im, 0, 0, $w, $h, $color1);

//front
imagefilledrectangle($im, 0, 0, $nh, $h, $color2);

//output the image
imagepng($im);
imagedestroy($im);

?>