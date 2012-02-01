<?php
header("Content-Type: image/png");
$text = $_GET['txt'];
$im = @imagecreate(110, 20)
    or die("Cannot Initialize new GD image stream");
$background_color = imagecolorallocate($im, 0, 0, 0);
$text_color = imagecolorallocate($im, 184, 198, 224);
imagestring($im, 10, 40, 4,  $text, $text_color);
imagepng($im);
imagedestroy($im);
?>
