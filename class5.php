<?php 
session_start();

function code($width,$height){
	header('content-type:image/png');
	//bg画板资源
	$bg=imagecreatetruecolor($width, $height);
	//bg画板颜色
	$bg_color=imagecolorallocate($bg, mt_rand(0,255) , mt_rand(0,255), mt_rand(0,255));
	//字体颜色
	$text_color=imagecolorallocatealpha($bg, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255), mt_rand(0,50));
	//重合颜色与画板
	imagefilledrectangle($bg, 0, 0, $width, $height, $bg_color);
	//验证码个数
	$code_num=floor($width/50);
	//验证码内容
	$num=['min'=>48,'max'=>57];
	$lower=['min'=>97,'max'=>122];
	$upper=['min'=>65,'max'=>90];
	$arr=[$num,$lower,$upper];
	$fanye='';
	for ($i=0; $i <$code_num ; $i++) { 
		$str=$arr[mt_rand(0,2)];
		$end=chr(mt_rand($str['min'],$str['max']));
		$fanye.=$end;
		imagettftext($bg, mt_rand(10,20), mt_rand(-35,36), 25+50*$i-mt_rand(0,15), mt_rand($height/2-8,$height/2+10), $text_color, '../font/DroidSansFallback_6.ttf', $end);
	}
	$_SESSION=['fanye'=>$fanye];
	for ($i=0; $i < $width*2; $i++) { 
		$point=imagecolorallocatealpha($bg, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255), mt_rand(0,50));
		imagesetpixel($bg, mt_rand(0,$width), mt_rand(0,$height), $point);

	}
	 imagepng($bg);
	 imagedestroy($bg);
}


code(200,50);




 ?>