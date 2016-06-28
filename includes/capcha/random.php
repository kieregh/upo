<?php session_start();

//if (empty($_SESSION['rand_code'])) {
    $str = "";
    $length = 0;
    for ($i = 0; $i < 3; $i++) {
        // this numbers refer to numbers of the ascii table (small-caps)
        $str .= chr(rand(97, 122)); 
		$str .= chr(rand(49, 57)); 
    }   
	$_SESSION['rand_code'] = $str; 
	
//}


$imgX = 100;
$imgY = 17;
$image = imagecreatetruecolor(93, 34);
//$randColor = generateRandomColor("");
$red = rand(1, 128);
$green = rand(1, 128);
$blue = rand(1, 128);
$backgr_col = imagecolorallocate($image, 255,255,255);
$border_col = imagecolorallocate($image, 255,255,255);
$text_col = imagecolorallocate($image, $red,$green,$blue);

imagefilledrectangle($image, 0, 0, 120, 35, $backgr_col);
imagerectangle($image, 0, 0, 119, 34, $border_col);

$font = "./georgiai.ttf"; // it's a Bitstream font check www.gnome.org for more
$font_size = 18;
$angle = 0;
$box = imagettfbbox($font_size, $angle, $font, $str);
$x = (int)($imgX - $box[4]) / 2;
$y = (int)($imgY - $box[5]) / 2;
imagettftext($image, $font_size, $angle, $x, $y, $text_col, $font, $_SESSION['rand_code']);

header("Content-type: image/png");
imagepng($image);
?>
<script type="text/javascript" language="javascript">
alert('hi');
document.contact.fname.value=<?=$str;?>;
</script>
<?php
imagedestroy ($image);
function generateRandomColor($rgb)
{
    $red = rand(1, 256);
    $green = rand(1, 256);
    $blue = rand(1, 256);

    if (! empty($rgb))
    {
        $red = ($red + $rgb['red']) / 2;
        $green = ($green + $rgb['green']) / 2;
        $blue = ($blue + $rgb['blue']) / 2;
    }

    $color = "{$red}, {$green}, {$blue}";

    return $color;
}
?>
