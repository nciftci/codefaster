<?php
/*
######################      ARTICLE     ####################
############################################################
ARTMAN			$Name:  $cls_upload
Revision		$Revision: 1.1 $
Author			$Author: iborbely $
Created 06/10/25        $Date: 2006/11/01 15:24:45 $
Writed by               GraFX (webmaster@grafxsoftware.com)
Scripts Home:           http://www.grafxsoftware.com
############################################################
*/
class Upload
{
	function UploadImage($imagename,$ext,$index,$image_type,$image_width,$image_path)
	{
		$error_message = "";
		switch(strtolower($ext)) {
			case 'png':
					if (!function_exists("imagecreatefrompng")){$error_message = "error";}
				break;
			case 'jpg':
			case 'jpeg':
			case 'jpe':
					if (!function_exists("imagecreatefromjpeg")){$error_message = "error";}
				break;
			case 'gif':
					if (!function_exists("imagecreatefromgif")){$error_message = "error";}
				break;
			default:
				$error_message = "";
				break;
		} // switch
		if($error_message == "")
		{
			if(isset($_FILES))
				$HTTP_POST_FILES = $_FILES;
			$HTTP_POST_FILES[$image_type]["name"] = $imagename;
			//upload the photo
			if (($HTTP_POST_FILES[$image_type]["name"]!=""))
			{
				$pos = strrpos($HTTP_POST_FILES[$image_type]["name"],".");
				if ($pos>0)
				{
					$upload_error = "";
					$image = date("U").".".substr($HTTP_POST_FILES[$image_type]["name"],$pos+1);
					if(!move_uploaded_file($HTTP_POST_FILES[$image_type]["tmp_name"],$image_path.$image))
					{
						$upload_error = "uploaderror";
					}
				}
				else
				{
					$image = "";
				}
			}
			if($upload_error == "")
			{
				$fileinfo = pathinfo($image_path.$image);
				$fontsize = 3;
				switch(strtolower($fileinfo['extension'])) {
					 case 'png':
							 $ih = imagecreatefrompng($image_path.$image);
						 break;
					 case 'jpg':
					 case 'jpeg':
					 case 'jpe':
							 $ih = imagecreatefromjpeg($image_path.$image);
						 break;
					 case 'gif':
							 $ih = imagecreatefromgif($image_path.$image);
						 break;
					 default:
						 $ih=-1;
						 break;
				}
				if ($ih!=-1)
				{
					$COPYRIGHT_TEXT = "";
					imagealphablending($ih, true);
					$color = imagecolorallocatealpha($ih, 255, 210, 80, 90);
					$x = (imagesx($ih) - strlen($COPYRIGHT_TEXT) * imagefontwidth($fontsize)) / 2;
					$y = imagesy($ih) - imagefontheight($fontsize) * 1.2;
					if(imagesx($ih)<$image_width) $image_width = imagesx($ih);// ha kisebb a kep merete mint a megadott meret
					imagestring($ih, $fontsize, $x, $y, $COPYRIGHT_TEXT, $color);
					$ih1 = imagecreatetruecolor($image_width, round(($image_width*imagesy($ih))/imagesx($ih)));
					imagecopyresampled($ih1, $ih, 0, 0, 0, 0, $image_width, round(($image_width*imagesy($ih))/imagesx($ih)), imagesx($ih),imagesy($ih));
					$ol_image = $image;
					$image = $index.".".$ext;// image_name
					switch(strtolower($fileinfo['extension'])) {
					case 'png':
						imagepng($ih1,$image_path.$image, 100);
						break;
					case 'jpg':
					case 'jpeg':
					case 'jpe':
						imagejpeg($ih1,$image_path.$image, 100);
						break;
					case 'gif':
						imagegif($ih1,$image_path.$image);
						break;
					default:
						imagejpeg($ih1,$image_path.$image, 100);
						break;
					}
					@unlink($image_path.$ol_image);
				}
				//take care of old picture
				if (isset($request["old_image"]))
				{
					if ($image==""){
						$image = $request["old_image"];
					}else{//delete old image
						@unlink($image_path.$request["old_image"]);
					}
				}
				return $image;
			}
			else
			{
				return $upload_error;
			}
		}// if contor == 0
		else
		{
			return $error_message;
		}
	}// Upload function

	function ResizeWatermarkImage($image_width)
	{
	   	$image = "watermark.jpg";
	   	$ext = "jpg";
		$error_message = "";
		switch(strtolower($ext)) {
			case 'png':
					if (!function_exists("imagecreatefrompng")){$error_message = "error";}
				break;
			case 'jpg':
			case 'jpeg':
			case 'jpe':
					if (!function_exists("imagecreatefromjpeg")){$error_message = "error";}
				break;
			case 'gif':
					if (!function_exists("imagecreatefromgif")){$error_message = "error";}
				break;
			default:
				$error_message = "";
				break;
		} // switch

		if($error_message == "")
		{
			$fileinfo = pathinfo(IMG_CONTENT_PATH.$image);
			$fontsize = 3;
			switch(strtolower($fileinfo['extension'])) {
				 case 'png':
						 $ih = imagecreatefrompng(IMG_CONTENT_PATH.$image);
					 break;
				 case 'jpg':
				 case 'jpeg':
				 case 'jpe':
						 $ih = imagecreatefromjpeg(IMG_CONTENT_PATH.$image);
					 break;
				 case 'gif':
						 $ih = imagecreatefromgif(IMG_CONTENT_PATH.$image);//IMG_CONTENT_PATH
					 break;
				 default:
					 $ih=-1;
					 break;
			}
			if ($ih!=-1)
			{
				$COPYRIGHT_TEXT = "";
				imagealphablending($ih, true);
				$color = imagecolorallocatealpha($ih, 255, 0, 0, 50);
				$x = (imagesx($ih) - strlen($COPYRIGHT_TEXT) * imagefontwidth($fontsize)) / 2;
				$y = imagesy($ih) - imagefontheight($fontsize) * 1.2;
				if(imagesx($ih)<$image_width) $image_width = imagesx($ih);// ha kisebb a kep merete mint a megadott meret
				imagestring($ih, $fontsize, $x, $y, $COPYRIGHT_TEXT, $color);
				$ih1 = imagecreatetruecolor($image_width, round(($image_width*imagesy($ih))/imagesx($ih)));
				imagecopyresampled($ih1, $ih, 0, 0, 0, 0, $image_width, round(($image_width*imagesy($ih))/imagesx($ih)), imagesx($ih),imagesy($ih));
				$ol_image = $image;
				$image = "resizedwatermark.".$ext;// image_name
				switch(strtolower($fileinfo['extension'])) {
				case 'png':
					imagepng($ih1,IMG_CONTENT_PATH.$image, 100);
					break;
				case 'jpg':
				case 'jpeg':
				case 'jpe':
					imagejpeg($ih1,IMG_CONTENT_PATH.$image, 100);
					break;
				case 'gif':
					imagegif($ih1,IMG_CONTENT_PATH.$image);
					break;
				default:
					imagejpeg($ih1,IMG_CONTENT_PATH.$image, 100);
					break;
				}
			}
			return $image;
		}
		else
		{
			return $error_message;
		}
	}// ResizeWatermarkImage function
}
?>