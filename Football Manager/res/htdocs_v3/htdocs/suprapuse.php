<?php
header("Content-type: image/png");
define('THUMBNAIL_IMAGE_MAX_WIDTH', 100);
define('THUMBNAIL_IMAGE_MAX_HEIGHT', 100);

$photo_to_paste=$_GET['img'];  //image 321 x 400
$white_image=$_GET['imgstad']; //873 x 622 

$im = imagecreatefromjpeg($white_image);
$condicion = GetImageSize($photo_to_paste); // image format?

if($condicion[2] == 1) //gif
	$im2 = imagecreatefromgif("$photo_to_paste");
if($condicion[2] == 2) //jpg
	$im2 = imagecreatefromjpeg("$photo_to_paste");
if($condicion[2] == 3) //png
	$im2 = imagecreatefrompng("$photo_to_paste");

list($source_image_width, $source_image_height, $source_image_type) = getimagesize($photo_to_paste);

generate_image_thumbnail($im2,$source_image_width, $source_image_height);
$im4 = imagecreatefromjpeg("images/".$_GET['id'].".jpg");

	
imagecopy($im, $im4, 0, 0, 0, 0, imagesx($im4), imagesy($im4));

imagejpeg($im);

imagedestroy($im);
imagedestroy($im2);
imagedestroy($im3);


function generate_image_thumbnail($im21,$source_image_width, $source_image_height)
{

    $source_aspect_ratio = $source_image_width / $source_image_height;
    $thumbnail_aspect_ratio = THUMBNAIL_IMAGE_MAX_WIDTH / THUMBNAIL_IMAGE_MAX_HEIGHT;
    if ($source_image_width <= THUMBNAIL_IMAGE_MAX_WIDTH && $source_image_height <= THUMBNAIL_IMAGE_MAX_HEIGHT) {
        $thumbnail_image_width = $source_image_width;
        $thumbnail_image_height = $source_image_height;
    } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
        $thumbnail_image_width = (int) (THUMBNAIL_IMAGE_MAX_HEIGHT * $source_aspect_ratio);
        $thumbnail_image_height = THUMBNAIL_IMAGE_MAX_HEIGHT;
    } else {
        $thumbnail_image_width = THUMBNAIL_IMAGE_MAX_WIDTH;
        $thumbnail_image_height = (int) (THUMBNAIL_IMAGE_MAX_WIDTH / $source_aspect_ratio);
    }
    $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
    imagecopyresampled($thumbnail_gd_image, $im21, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);
    imagejpeg($thumbnail_gd_image, 'images/'.$_GET['id'].'.jpg', 100);
    imagedestroy($im21);
    imagedestroy($thumbnail_gd_image);
}
?>