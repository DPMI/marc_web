<?
require("config.inc");
header("Content-type: image/png");

$im=@imagecreatefromjpeg("http://127.0.0.1/huvud-rubrik-inga.jpg");
$textColor=imagecolorallocate($im, 255,255,255);
$bgColor=imagecolorallocate($im, 0,0,0);

if(!$im) {
  $im = imagecreate(700,41);
  $bgc = imagecolorallocate($im, 255,255, 255);
  $tc = imagecolorallocate($im, 0, 0 ,0);
  imagefilledrectangle($im, 0,0,700,41,$bgc);
  imagestring($im,1,5,5, "Error loading file", $tc);

} else {
 imagestring($im, 5, 600, 20, "$projectName", $textColor);
//imagetftext($im, 20, 0, 600,20, $textColor, "/path/arial.ttf","MP");

}

imagepng($im);
?>

