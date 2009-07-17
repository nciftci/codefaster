<?php
class Captcha
{
	public function __construct()
	{					
		//header("Content-type: image/jpg");
		if(function_exists("imagecreate"))
		{
			$im = @imagecreate(100, 30)	or die("Cannot Initialize new GD image stream");
			$chars1 = range(0, 9);
			$chars2 = range('A','Z');
			$letter = array_merge($chars1,$chars2);
			
			$r = rand(200, 224);
			$g = rand(200, 224);
			$b = rand(200, 224);
			// image bacground color
			imagecolorallocate($im, $r, $g, $b);
			// draw each horizontal with line different color
			for($i=0;$i<2;$i++)
			{
				$r = rand(100, 255);
				$g = rand(100, 255);
				$b = rand(100, 255);
				$col_poly = imagecolorallocate($im, $r, $g, $b);
				imageline($im,0, 10+$i*10,100,10+$i*10,$col_poly);
			}
			// draw each vertical line with different color
			for($i=0;$i<9;$i++)
			{
				$r = rand(100, 255);
				$g = rand(100, 255);
				$b = rand(100, 255);
				$col_poly = imagecolorallocate($im, $r, $g, $b);
				imageline($im,10+$i*10, 0,10+$i*10,50,$col_poly);
			}
			$col = imagecolorallocatealpha($im, 68, 222, 137, 0); // create border color
			imagerectangle($im, 0, 0, 99, 29, $col);// draw border	
			$image_name = "";
			// draw each letter with different color
			for($i=0;$i<5;$i++)
			{
				$j = rand(0,sizeof($letter)-1);
				$r = rand(10, 140);
				$g = rand(10, 140);
				$b = rand(10, 140);
				$text_color = imagecolorallocate($im, $r, $g, $b);				
				@imagettftext($im, 16, rand(0,10),5+$i*18,rand(20,30),$text_color,"publ_images/embosst1.ttf",$letter[$j]);
				$image_name .= $letter[$j];
				// kepnev
			}
			
			$tmpfname = tempnam("tmp", "FOO");	
			$exp = explode("/" ,$tmpfname);
			$exp = array_reverse($exp);
			$tmpfname = $exp[0];
			
			$folder = "tmp/";
			
			$_SESSION["session_imagename"] = $image_name;						
			$_SESSION["session_imagefilenamerandom"] = $folder.$tmpfname.'.jpg';			
			
			imagejpeg($im,$folder.$tmpfname,80);
			rename($folder.$tmpfname,$folder.$tmpfname.'.jpg');		
			@chmod($folder.$tmpfname.'.jpg',0777);
			
			$d = dir($folder);			
			while ($filename = trim($d->read())) {
				if(strpos(strtolower($filename),'.jpg'))
				{ 									
					$file_time = filemtime($folder.$filename);					
					$date = time()-240;					
					if($file_time < $date)
						unlink($folder.$filename);
				}				
			}
			$d->close();		
		}
		else
		{
			$_SESSION["session_imagename"] = "";
		}		
	}
}
?> 