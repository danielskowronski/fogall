<?php
	header('Content-Type: image/jpeg');

	if(isset($_GET['file']) ? $_GET['file'] : '')
		{
		$filename = '../'.str_replace('./galleries', 'galleries/', $_GET['file']);
		}

	list($width, $height) = getimagesize($filename);
	$new_height = 160;
	$new_width  = floor($width * ($new_height / $height));

	if(strstr($filename, '.png') || strstr($filename, '.PNG'))
		{
		$image = imagecreatefrompng($filename);
		}
	else if(strstr($filename, '.jpg') || strstr($filename, '.JPG') || strstr($filename, '.jpeg') || strstr($filename, '.JPEG'))
		{
		$image = imagecreatefromjpeg($filename);
		}
	else if(strstr($filename, '.gif') || strstr($filename, '.GIF'))
		{
		$image = imagecreatefromgif($filename);
		}

	$image_p = imagecreatetruecolor($new_width, $new_height) or die('Cannot initialize new GD image stream.');
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

	function addThumb($filename)
		{
		$filename    = array_reverse(explode('.', $filename));
		$filename[0] = 'smpgthumb.'.$filename[0];
		$filename    = implode('.', array_reverse($filename));
		return $filename;
		}

	imagejpeg($image_p, null, 70);
	imagejpeg($image_p, addThumb($filename), 70);
	imagedestroy($image_p);
?>