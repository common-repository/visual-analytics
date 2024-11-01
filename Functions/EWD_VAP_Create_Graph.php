<?php
function EWD_VAP_Create_Graph($Averages) {
$image = imagecreatefrompng(plugin_dir_path( __FILE__ ) . "../images/BlankGraph.png");

if (!is_array($Averages)) {echo "No Events Defined"; exit();}

$blue = imagecolorallocate($image, 0, 0, 205);
$black = imagecolorallocate($image, 0, 0, 0);

$TopY = 20;
$BottomY = 495;
$YArea = $BottomY - $TopY;

$MinX = 78;
$MaxX = 848;
$XArea = $MaxX - $MinX;

$MaxAvg = max($Averages);
if ($MaxAvg > 1000) {$MaxHeight = ceil($MaxAvg/100)*100;}
elseif ($MaxAvg > 250) {$MaxHeight = ceil($MaxAvg/20)*20;}
elseif ($MaxAvg > 100) {$MaxHeight = ceil($MaxAvg/10)*10;}
elseif ($MaxAvg > 20) {$MaxHeight = ceil($MaxAvg/5)*5;}
else {$MaxHeight = max(ceil($MaxAvg/2)*2,1);}

$LengendInterval = round($MaxHeight / 4, 2);

for ($i=0; $i<=4; $i++) {
		$String = strval($LengendInterval*$i);
		$YPlacement = $BottomY - (($YArea/4)*$i)-5;
		imagestring($image, 5, 25, $YPlacement, $String, $black);
}

$Num_Bars = sizeOf($Averages);
$Spacer = max(sizeOf($Averages), 9);
$Counter = 0;
foreach ($Averages as $key => $Average) {
		if ($Spacer != $Num_Bars) {$XStart = $MinX + (($XArea/$Spacer)*$Counter) + 50;}
		else {$XStart = $MinX + (($XArea/$Spacer)*$Counter) + 10;}
		$XEnd = $XStart + ($XArea/$Spacer) - 30;
		$YStart = $BottomY - (($YArea/$MaxHeight) * $Average);
		$YEnd = $BottomY;
		imagefilledrectangle($image, $XStart, $YStart, $XEnd, $YEnd, $blue);
		$AvgString = number_format(round($Average, 2), 2, ".", '') . "";
		imagestring($image, 5, $XStart+6, $YStart-15, $AvgString, $black);
		$Counter++;
		if ($Counter % 2 == 1) {imagestring($image, 5, $XStart-((strlen($key)-6)*3), $BottomY+10, $key, $black);}
		else {imagestring($image, 5, $XStart-((strlen($key)-6)*3), $BottomY+30, $key, $black);}
}

imagepng($image, "../wp-content/plugins/visual-analytics/images/FilledGraph.png");
imagedestroy($image);
}

?>
