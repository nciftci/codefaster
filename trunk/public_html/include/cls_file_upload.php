<?php
// +------------------------------------------------------------------------+
// | cls_file_upload                                                         |
// +------------------------------------------------------------------------+
// | Copyright (c) GraFX Software Solutions 2009. All rights reserved.      |
// | Version       1.0                                                    |
// | Last modified 21/03/2009                                               |
// | Email         webmaster@grafxsoftware.com                              |
// | Web           http://www.grafxsoftware.co                              |
// +------------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify   |
// | it under the terms of the GNU General Public License version 2 as      |
// | published by the Free Software Foundation.                             |
// |                                                                        |
// | This program is distributed in the hope that it will be useful,        |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of         |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the          |
// | GNU General Public License for more details.                           |
// |                                                                        |
// | You should have received a copy of the GNU General Public License      |
// | along with this program; if not, write to the                          |
// |   Free Software Foundation, Inc., 59 Temple Place, Suite 330,          |
// |   Boston, MA 02111-1307 USA                                            |
// |                                                                        |
// | Please give credit on sites that use class.upload and submit changes   |
// | of the script so other people can use them as well.                    |
// | This script is free to use, don't abuse.                               |
// +------------------------------------------------------------------------+
//


/**
 * Class FileUpload
 *
 * @version   1.0
 * @author    GraFX Software Solutions webmaster@grafxsoftware.com
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright GraFX Software Solutions
 */

class FileUpload {
	/**
	 * Uploaded filename - /home/local/htdocs/tmp/test.jpg
	 *
	 * @access public
	 * @var string
	 */	
	var $name;
	
	/**
	 * Uploaded file's extension - jpg
	 *
	 * @access public
	 * @var string
	 */	
	var $ext;

	/**
	 * Uploaded file's mimetype - image/jpg
	 *
	 * @access public
	 * @var string
	 */	
	var $mime;
	
	/**
	 * Uploaded file's tempname - DF2F.tmp
	 *
	 * @access public
	 * @var string
	 */	
	var $tmpname;

	/**
	 * Uploaded file's max_allowed - the max number of Kb
	 *
	 * @access public
	 * @var string
	 */		
	var $max_allowed;
	var $max_filesize;
	
	var $overwrite;
	
	var $mime_allowed;
	var $mime_forbidden;
	
	var $save_path;
	var $save_name;
	
	var $error_messages;
	
	function FileUpload($file_field) {
		
		$this->error_messages = array ();
		
		//checking the php settings
		

		if (strtoupper ( trim ( ini_get ( 'file_uploads' ) ) ) == "OFF")
			$this->error_messages [] = FU_MESSAGE_UPLOAD_OFF;
			
		//max allowed in KB usually there is in MB ex: 2M 
		$val = strtoupper ( trim ( ini_get ( 'upload_max_filesize' ) ) );
		if (! empty ( $val )) {
			$measure = substr ( $val, - 1 );
			$this->max_allowed = substr ( $val, 0, - 1 );
			switch ($measure) {
				case 'M' :
					$this->max_allowed *= 1024;
					break;
				case 'G' :
					$this->max_allowed *= 1048576;
					break;
			
			}
		
		} else
			$this->error_messages [] = FU_MESSAGE_UPLOAD_FILESIZE;
		
		switch ($file_field ["error"]) {
			case UPLOAD_ERR_INI_SIZE : //1
				$this->error_messages [] = FU_MESSAGE_UPLOAD_FILESIZE;
				break;
			case UPLOAD_ERR_FORM_SIZE : //2
				$this->error_messages [] = FU_MESSAGE_UPLOAD_FORM_SIZE;
				break;
			case UPLOAD_ERR_PARTIAL : //3
				$this->error_messages [] = FU_MESSAGE_UPLOAD_PARTIAL;
				break;
			case UPLOAD_ERR_NO_FILE : //4
				$this->error_messages [] = FU_MESSAGE_UPLOAD_NO_FILE;
				break;
			case 6 : //UPLOAD_ERR_NO_TMP_DIR Value: 6; Missing a temporary folder. 
				$this->error_messages [] = FU_MESSAGE_UPLOAD_NO_TMP_DIR;
				break;
			case 7 : //UPLOAD_ERR_CANT_WRITE Introduced in PHP 5.1.0. 
				$this->error_messages [] = FU_MESSAGE_UPLOAD_CANT_WRITE;
				break;
			case 8 : //UPLOAD_ERR_EXTENSION Introduced in PHP 5.2.0. 
				$this->error_messages [] = FU_MESSAGE_UPLOAD_EXTENSION;
				break;
		}
		
		//user defined filesize check
		$this->max_filesize = 1;
		
		$this->name = $file_field ["name"];
		
		//extension
		

		if (strpos ( $this->name, "." ) !== false) {
			$tmp = explode ( ".", $this->name );
			$this->ext = array_pop ( $tmp );
		
		} else
			$this->error_messages [] = FU_MESSAGE_UPLOAD_NO_EXTENSION;
		
		$this->mime = $file_field ["type"];
		$this->size = $file_field ["size"];
		$this->tmpname = $file_field ["tmp_name"];
		
		
		$this->mime_allowed = array ("image/*", "video/*" );
		$this->mime_forbidden = array ("text/*" );
		
		$this->overwrite = true;
	
	}
	
	function checkMime() {
		
		$all = array ();
		$forb = array ();
		
		foreach ( $this->mime_allowed as $value )
			$all [] = strtoupper ( $value );
		
		foreach ( $this->mime_forbidden as $value )
			$forb [] = strtoupper ( $value );
		
		if (empty ( $this->mime )) {
			$this->error_messages [] = FU_MESSAGE_UPLOAD_MIMETYPE_EMPTY;
			return false;
		} else {
			if (strpos ( $this->mime, "/" ) !== false) {
				$uppermime = strtoupper ( $this->mime );
				$tmp_spl = explode ( "/", $uppermime );
				
				if (sizeof ( $forb ) > 0)
					
					if (in_array ( $uppermime, $forb ) || in_array ( $tmp_spl [0] . "/*", $forb )) {
						$this->error_messages [] = FU_MESSAGE_UPLOAD_MIMETYPE_NOT_ALLOWED;
						return false;
					
					}
				
				if (sizeof ( $all ) > 0)
					if (in_array ( $uppermime, $all ) || in_array ( $tmp_spl [0] . "/*", $all )) {
						return true;
					} else {
						$this->error_messages [] = FU_MESSAGE_UPLOAD_MIMETYPE_NOT_ALLOWED;
						return false;
					
					}
			
			} else
				$this->error_messages [] = FU_MESSAGE_UPLOAD_MIMETYPE_UNKNOWN;
		
		}
	
	}
	
	function doUpload() {
		
		if ($file_field ["size"] > $this->max_filesize * 1024) {
			$this->error_messages [] = FU_MESSAGE_UPLOAD_FILESIZE_LARGE;
			return false;
		}
		
		if (! $this->checkMime ())
			return false;
		
		if (empty ( $this->save_path )) {
			$this->error_messages [] = FU_MESSAGE_UPLOAD_NO_SAVE_PATH;
			return false;
		
		} else {
			
			if (empty ( $this->save_name )){
				$this->save_name = $this->name;
			}
			else
				$this->save_name = $this->save_name . "." . $this->ext;
			
			if (! $this->overwrite && file_exists ( $this->save_path . "/" . $this->save_name )) {
				$this->error_messages [] = FU_MESSAGE_UPLOAD_FILE_OVERWRITE;
				return false;
			
			}
			
			if (move_uploaded_file ( $this->tmpname, $this->save_path . "/" . $this->save_name ))
				return true;
			else {
				$this->error_messages [] = FU_MESSAGE_UPLOAD_CANT_WRITE;
				return false;
			
			}
		
		}
	
	}
	
	function getErrorMessage() {
		return (sizeof ( $this->error_messages ) > 0 ? implode ( "<br>", $this->error_messages ) : "");
	}
	
	/**
	 * @return unknown
	 */
	function getExt() {
		return $this->ext;
	}
	
	/**
	 * @return unknown
	 */
	function getMime_allowed() {
		return $this->mime_allowed;
	}
	
	/**
	 * @return unknown
	 */
	function getMax_filesize() {
		return $this->max_filesize;
	}
	
	/**
	 * @return unknown
	 */
	function getMime_forbidden() {
		return $this->mime_forbidden;
	}
	
	/**
	 * @return unknown
	 */
	function getSave_name() {
		return $this->save_name;
	}
	
	/**
	 * @return unknown
	 */
	function getSave_path() {
		return $this->save_path;
	}
	
	/**
	 * @param unknown_type $mime_allowed
	 */
	function setMime_allowed($mime_allowed) {
		$this->mime_allowed = $mime_allowed;
	}
	
	/**
	 * @param unknown_type $max_filesize
	 */
	function setMax_filesize($max_filesize) {
		$this->max_filesize = $max_filesize;
	}
	
	/**
	 * @param unknown_type $mime_forbidden
	 */
	function setMime_forbidden($mime_forbidden) {
		$this->mime_forbidden = $mime_forbidden;
	}
	
	/**
	 * @param unknown_type $save_name
	 */
	function setSave_name($save_name) {
		$this->save_name = $save_name;
	}
	
	/**
	 * @param unknown_type $save_path
	 */
	function setSave_path($save_path) {
		$this->save_path = $save_path;
	}

}
?>