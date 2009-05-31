<?php
include_once(INCLUDE_PATH.'cls_file_upload.php');
include_once(INCLUDE_PATH.'cls_image_crop.php');

class Util{
	function make_left_menu($ft,$menuleft,$menuleft,$menuright,$addon_menu,$all_url_vars){
		$ft->assign("ADMIN_URL", ADMIN_URL);
		$ft->multiple_assign_define("LANG_");
		$ft->multiple_assign_define("CONF_");
	}

	function check_authentification(){
		// begin authenticate part
		if(AUTH_TYPE == 1)
		{
			if (!$AUTHENTICATE)
			{
				header( "WWW-Authenticate: Basic realm=\"ADMIN ".CONF_SITE_NAME."\"");
				header( "HTTP/1.0 401 Unauthorized");
				$ft = new FastTemplate(ADMIN_TEMPLATE_CONTENT_PATH);
				$ft->define(array("main"=>"template_firstpage.html", "content"=>"authentication_failed.html"));
				$ft->assign("ADMIN_URL", ADMIN_URL);
				$ft->multiple_assign_define("LANG_");
				$ft->multiple_assign_define("CONF_");
				$ft->parse("BODY", array("content","main"));
				$ft->showDebugInfo(ERROR_DEBUG);
				$ft->FastPrint();
				exit;
			}
		}
		else if(AUTH_TYPE == 2)
		{
			include_once(INCLUDE_PATH.'cls_session.php');
			$sess = new MYSession();
			if (!$sess->get(SESSION_ID))
			{
				$sess->set('session_url_before',$_SERVER['REQUEST_URI']);
				header("Location: login.php");
				exit;
			}
		}// end authenticate part

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

	private function uploadImage($prefix_stored_filename,$input_filename){
		$save_name = $prefix_stored_filename."_".substr(md5("0_".date("Y-m-d H:i:s").$input_filename),0,12);
		$thumbnail_save_name="thumb_".$save_name;

		$fu = new FileUpload ( $_FILES [$input_filename] );
		$fu->setSave_name ( $save_name);
		$fu->setSave_path ( CONF_UPLOADDIR );

		if ($fu->doUpload ()){
			$im_crop = new ImageCrop ( CONF_UPLOADDIR.$fu->getSave_name() );
			$im_crop->setSave_name($thumbnail_save_name);
			$im_crop->setSave_path ( CONF_UPLOADDIR );

			list ( $width, $height ) = $im_crop->getImage_size ();

			//Scale the image if it is greater than the width set above
			if ($width > THUMBNAIL_WIDTH) 
				$im_crop->setSave_scale ( THUMBNAIL_WIDTH / $width );
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
	public function getUploadImagesHtml($n_images,$files_string){
	    $result="";
		for ($i=0;$i<$n_images;$i++){
			$result.="<div><label for='image'>".LANG_ADMIN_PICTURE."</label><input size='10' name='imagefile_".$i."' id='imagefile_".$i."' type='file' title='' />\n";

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
			$result.="<br/><img src='".CONF_UPLOADURL.$image_filename."'/></div>"; 
		};

		$result.="<input name='imagefile_n' id='imagefile_n' type='hidden' value='".$n_images."' />";

	    return $result;
	}

	public function uploadAllImages($prefix_stored_filename){
		$imagefile_n=$_REQUEST['imagefile_n'];
		$result="";
		for ($i=0;$i<$imagefile_n;$i++){
			$filename='imagefile_'.$i;
			$old_image=$_REQUEST['imagefile_old_'.$i];
			$r=$this->uploadImage($prefix_stored_filename,$filename);
			if ($r) {//s-a putut upload
				if (!empty($old_image)){
					@unlink(CONF_UPLOADDIR.$old_image);
					@unlink(CONF_UPLOADDIR."thumb_".$old_image);
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
