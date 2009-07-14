<?php
include_once(INCLUDE_PATH.'cls_file_upload.php');
include_once(INCLUDE_PATH.'cls_image_crop.php');

class Util{
	function make_left_menu($ft,$menuleft,$menuleft,$menuright,$addon_menu,$all_url_vars){
		$ft->assign("ADMIN_URL", ADMIN_URL);
		$ft->multiple_assign_define("LANG_");
		$ft->multiple_assign_define("CONF_");
	}

	/**************************************************************/
	/*          ADD YOUR CUSTOM FUNCTIONS BELOW                   */
	/**************************************************************/


	public function formatDataWithSelect($id_selected,$table,$id_column,$data_column)
	{
		$SQL = "SELECT `$id_column`,`$data_column` FROM `".DB_PREFIX."$table` ORDER BY `$data_column` ASC";
		$retid = mysql_query($SQL);
		if (!$retid) { echo( mysql_error()); }
		$i=0;
		$id=array();
		$data=array();
		if ($row = mysql_fetch_array($retid))
			do{
				$id[$i] = $row[$id_column];
				$data[$i] = $row[$data_column];
				$i++;
			}while($row = mysql_fetch_array($retid));
		$nr_datas = $i;

		$buffer = "";
		for ($i=0;$i<$nr_datas;$i++)
		{
			$buffer .= "<option value=\"";
			$buffer .= $id[$i];
			$id_i=$id[$i];
			if ("$id_i"=="$id_selected")
				$buffer .= "\" selected>";
			else
				$buffer .= "\">";
			$buffer .= $data[$i];
			$buffer .= "</option>";
		}
		
		return $buffer;
	}//end formatDataWithSelect

	public function getDataWithSelectFromArray($id_selected, $array_data){
		$c="";

		$array_data_sorted=$array_data;
		ksort($array_data_sorted);

		foreach($array_data_sorted as $key => $value){
			if("$id_selected"=="$key")
				$sel="selected=\"selected\"";
			else 
				$sel="";
			$c.="<option value=\"".($key)."\" $sel>".$value."</option>\n";
		};

		return $c;
	}

	public function formatCountryForSelect($id_selected)
	{
		$SQL = " SELECT id,country FROM country ORDER BY country ASC";

		$retid = mysql_query($SQL);
		if (!$retid) { echo( mysql_error()); }
		$i=0;
		if ($row = mysql_fetch_array($retid))
			do{
				$id[$i] = $row["id"];
				$country[$i] = $row["country"];
				$i++;
			}while($row = mysql_fetch_array($retid));
		$nrcountries = $i;

		$buffer = "";
		for ($i=0;$i<$nrcountries;$i++)
		{
			if ($id[$i]==$id_selected) $selected="selected";
			else $selected="";
			$buffer .= "<option $selected value=\"";
			$buffer .= $id[$i];
			$buffer .= "\">";
			$buffer .= $country[$i];
			$buffer .= "</option>";
		}
		return $buffer;
	}//end formatCountryForSelect

	public function formatStateForSelect($id_country)
	{
		$SQL = "SELECT id,state FROM state WHERE `id_country`='$id_country' ORDER BY state ASC";
		$retid = mysql_query($SQL);
		if (!$retid) { echo( mysql_error()); }
		$i=0;
		if ($row = mysql_fetch_array($retid))
			do{
				$id[$i] = $row["id"];
				$state[$i] = $row["state"];
				$i++;
			}while($row = mysql_fetch_array($retid));
		// numarul total al stirilor
		$nrstates = $i;
		$buffer = "";
		for ($i=0;$i<$nrstates;$i++)
		{
			$buffer .= "<option value=\"";
			$buffer .= $id[$i];
			$buffer .= "\">";
			$buffer .= $state[$i];
			$buffer .= "</option>";
		}
		return $buffer;
	}//end formatStateForSelect

//
//
///**
// *
// * @param <string> $prefix_stored_filename
// * @param <string> $input_filename
// * @return <string>  $name of the stored filename
// */
//	private function uploadImage($prefix_stored_filename,$input_filename){
//		$save_name = $prefix_stored_filename."_".substr(md5("0_".date("Y-m-d H:i:s").$input_filename),0,12);
//		$thumbnail_save_name="thumb_".$save_name;
//
//
//
//		$fu = new FileUpload ( $_FILES [$input_filename] );
//		$fu->setSave_name ( $save_name);
//		$fu->setSave_path ( FU_CONF_UPLOADDIR );
//
//
//		if ($fu->doUpload ()){
//			$im_crop = new ImageCrop ( FU_CONF_UPLOADDIR.$fu->getSave_name() );
//			$im_crop->setSave_name($thumbnail_save_name);
//			$im_crop->setSave_path ( FU_CONF_UPLOADDIR );
//
//			list ( $width, $height ) = $im_crop->getImage_size ();
//
//			//Scale the image if it is greater than the width set above
//
//			if ($width > THUMBNAIL_WIDTH)
//				$im_crop->setSave_scale ( THUMBNAIL_WIDTH / $width );
//			else
//				$im_crop->setSave_scale ( 1 );
//
//			$im_crop->setSave_width ( $width );
//			$im_crop->setSave_height ( $height );
//
//			if (! $im_crop->doResize ())
//			{
//				print_r ( $im_crop->getErrorMessage () );
//                                print("x_x");
//				exit;
//			}
//
//			return $fu->getSave_name();
//		}else{
//                        print_r($fu->error_messages);print("__");exit;
//			return false;
//		};
//
//	}
//
//        /**
//         *
//         * @param <string> $files_string the string which contains information about uploaded images
//         * @return <string>  HTML code for image upload
//         */
//
//	public function getUploadImagesHtml($files_string){
//	    $result="";
//            $n_images=1;
//		for ($i=0;$i<$n_images;$i++){
//			$result.="<div><label for='image'>".LANG_ADMIN_PICTURE."</label><input size='10' name='imagefile_".$i."' id='imagefile_".$i."' type='file' title='' />\n";
//
//			$image_filename="spacer.gif";
//			if (!empty($files_string)){
//				$tmppos=strpos($files_string,";");
//				if ($tmppos===false){
//					$end=strlen($files_string);
//				}else{
//					$end=$tmppos;
//				};
//				$original_image_filename=substr($files_string,0,$end);
//				$image_filename="thumb_".substr($files_string,0,$end);
//				$files_string=substr($files_string,$end+1);
//
//				$result.="<input name='imagefile_old_".$i."' type='hidden' id='imagefile_old_".$i."' value='".$original_image_filename."'/>";
//			};
//			$result.="<br/><img src='".FU_CONF_UPLOADURL.$image_filename."'/></div>";
//		};
//
//		$result.="<input name='imagefile_n' id='imagefile_n' type='hidden' value='".$n_images."' />";
//
////                       print_r($result);exit;
//
//
//	    return $result;
//	}
//
///**
// *
// * @param <string> $prefix_stored_filename
// * @return <string>
// */
//
//	public function uploadAllImages($prefix_stored_filename){
//		$imagefile_n=1;//$_REQUEST['imagefile_n'];
//            	$result="";
//		for ($i=0;$i<$imagefile_n;$i++){
//			$filename='imagefile_'.$i;
//			$old_image=$_REQUEST['imagefile_old_'.$i];
//			$r=$this->uploadImage($prefix_stored_filename,$filename);
//			if ($r) {//the upload was possible
//				if (!empty($old_image)){
//					@unlink(FU_CONF_UPLOADDIR.$old_image);
//					@unlink(FU_CONF_UPLOADDIR."thumb_".$old_image);
//				};
//			}else{
//				if (!empty($old_image)){
//					$r=$old_image;
//				};
//			};
//
//			if ($r){
//				if (!empty($result)) $result.=";";
//				$result.=$r;
//			};
//		};
//		return $result;
//	}



	public function getUploadImagesHtml($n_images,$files_string,$labels,$image_path){
	    $result="";
		for ($i=0;$i<$n_images;$i++){
			$result.="<div><label for='image'>".$labels[$i]."</label><input name='imagefile_".$i."' id='imagefile_".$i."' type='file' title='' />\n";

			$image_filename="spacer.gif";
			if (!empty($files_string)){
				$tmppos=strpos($files_string,";");
				if ($tmppos===false){
					$end=strlen($files_string);
				}else{
					$end=$tmppos;
				};
				$original_image_filename=substr($files_string,0,$end);
				$image_filename="thumb_".substr($files_string,0,$end);
				$files_string=substr($files_string,$end+1);

				$result.="<input name='imagefile_old_".$i."' type='hidden' id='imagefile_old_".$i."' value='".$original_image_filename."'/>";
			};
			$result.="<br/><a href='#' onclick='return showDialog()' class=''modal'><div style=\"display: none;\" id=\"bigpicture\" title=\"".LANG_ADMIN_BIGIMAGE."\"><img src='".$image_path.$original_image_filename."' /></div><img src='".$image_path.$image_filename."' /></a></div>";
		};
		//
        $result.="<div><label for='check'>".LANG_ADMIN_CHECK_TO_EDIT."</label><input name='check' id='check' type='checkbox' value='1'/></div>";
		$result.="<input name='imagefile_n' id='imagefile_n' type='hidden' value='".$n_images."' />";

	    return $result;
	}

	private function uploadImage($prefix_stored_filename,$input_filename,$path){
		$save_name = $prefix_stored_filename."_".substr(md5("0_".date("Y-m-d H:i:s").$input_filename),0,12);
		$thumbnail_save_name="thumb_".$save_name;

		$fu = new FileUpload ( $_FILES [$input_filename] );
		$fu->setSave_name ( $save_name);
		$fu->setSave_path ( $path );
		if ($fu->doUpload ()){
			$im_crop = new ImageCrop ( $path.$fu->getSave_name() );
			$im_crop->setSave_name($path.$thumbnail_save_name.".". $fu->getExt ());
			//$im_crop->setSave_path ( CATEGORY_IMAGE_PATH );

			list ( $width, $height ) = $im_crop->getImage_size ();

			//Scale the image if it is greater than the width set above
			if ($width > IC_CONF_IMAGE_THUMB_WIDTH)
				$im_crop->setSave_scale ( IC_CONF_IMAGE_THUMB_WIDTH / $width );
			else
				$im_crop->setSave_scale ( 1 );

			$im_crop->setSave_width ( $width );
			$im_crop->setSave_height ( $height );

			if (! $im_crop->doResize ())
			{
				print_r ( $im_crop->getErrorMessage () );
				exit;
			}

			return $fu->getSave_name();
		}else{
			return false;
		};

	}

	public function uploadAllImages($prefix_stored_filename, $path){
		$imagefile_n=$_REQUEST['imagefile_n'];
		$result="";
		for ($i=0;$i<$imagefile_n;$i++){
			$filename='imagefile_'.$i;
			$old_image=$_REQUEST['imagefile_old_'.$i];
			$r=$this->uploadImage($prefix_stored_filename,$filename,$path);
			if ($r) {//s-a putut upload
				if (!empty($old_image)){
					@unlink($path.$old_image);
					@unlink($path."thumb_".$old_image);
				};
			}else{
				if (!empty($old_image)){
					$r=$old_image;
				};
			};

			if ($r){
				if (!empty($result)) $result.=";";
				$result.=$r;
			};
		};
		return $result;
	}



}
?>
