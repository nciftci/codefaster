<?php
// +------------------------------------------------------------------------+
// | cls_image_crop                                                         |
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
 * Class ImageCrop
 *
 * @version   1.0
 * @author    GraFX Software Solutions webmaster@grafxsoftware.com
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright GraFX Software Solutions
 */

class ImageCrop {
	/**
	 * Uploaded image filename - /home/local/htdocs/tmp/test.jpg
	 *
	 * @access public
	 * @var string
	 */
	var $image;
	
	/**
	 * Uploaded image file's basename - test.jpg
	 *
	 * @access public
	 * @var string
	 */
	var $image_name;
	
	/**
	 * Uploaded image file's extension - jpg
	 *
	 * @access public
	 * @var string
	 */
	var $image_ext;
	
	/**
	 * Uploaded image file's extension - jpg
	 *
	 * @access public
	 * @var string
	 */
	var $image_path;
	
	/**
	 * Uploaded image file's filesize - the return of getimagesize()
	 *
	 * @access public
	 * @var array
	 */
	var $image_size;
	
	/**
	 * The allowed mimetypes
	 *
	 * @access public
	 * @var array
	 */
	var $mime_allowed;
	
	/**
	 * The saved file's name - saved.jpg
	 *
	 * @access public
	 * @var string
	 */
	var $save_name;
	
	/**
	 * The saved file's mimetype - image/jpg
	 *
	 * @access public
	 * @var string
	 */
	var $save_mime;
	
	/**
	 * The saved file's extension - jpg
	 *
	 * @access public
	 * @var string
	 */
	var $save_ext;
	
	/**
	 * The saved file's path - /home/local/htdocs/public_images/thumbs/
	 *
	 * @access public
	 * @var string
	 */
	var $save_path;
	
	/**
	 * The saved file's width - 500
	 *
	 * @access public
	 * @var integer
	 */
	var $save_width;
	
	/**
	 * The saved file's height - 500
	 *
	 * @access public
	 * @var integer
	 */
	var $save_height;
	
	/**
	 * The saved file's scale - 1
	 *
	 * @access public
	 * @var integer
	 */
	var $save_scale;
	
	/**
	 * The saved file's jpg_quality - this is only for jpg default 90
	 *
	 * @access public
	 * @var integer
	 */
	var $save_jpg_quality;
	
	/**
	 * The saved file's start_width - if we have cropping
	 *
	 * @access public
	 * @var integer
	 */
	var $start_width;
	
	/**
	 * The saved file's start_height - if we have cropping
	 *
	 * @access public
	 * @var integer
	 */
	var $start_height;
	
	/**
	 * Set true if the source file should be deleted - default false
	 *
	 * @access public
	 * @var boolean
	 */
	var $del_old_file;
	
	/**
	 * Error message container
	 *
	 * @access public
	 * @var array
	 */
	var $error_messages;
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public Constructor
	 * @desc     - image croping for uploaded files and not only
	 * @param    - $image - full image path of the image 
	 * @return   - void
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function ImageCrop($image = "") {
		
		$this->error_messages = array ();
		
		if (! in_array ( "gd", get_loaded_extensions () ))
			$this->error_messages [] = IC_MESSAGE_GD_NOT_LOADED;
		else {
			
			$tmp_gd = gd_info ();
			
			if (isset ( $tmp_gd ["GIF Create Support"] ) && $tmp_gd ["GIF Create Support"] == 1 && function_exists ( "imagecreatefromgif" ))
				$this->mime_allowed ["image/gif"] = "gif";
			
			//XXX: in php 5.3.0 function gd_info returns JPEG Suppor instead of JPG Support in the earlier versions
			if (((isset ( $tmp_gd ["JPG Support"] ) && $tmp_gd ["JPG Support"] == 1) || ($tmp_gd ["JPEG Support"]) && $tmp_gd ["JPEG Support"] == 1) && function_exists ( "imagecreatefromjpeg" )) {
				$this->mime_allowed ["image/jpg"] = "jpg";
				$this->mime_allowed ["image/jpeg"] = "jpg";
				$this->mime_allowed ["image/pjpeg"] = "jpg";
			}
			
			if (isset ( $tmp_gd ["PNG Support"] ) && $tmp_gd ["PNG Support"] == 1 && function_exists ( "imagecreatefrompng" )) {
				$this->mime_allowed ["image/png"] = "png";
				$this->mime_allowed ["image/x-png"] = "png";
			}
			
			//TODO:[WBMP Support] => 1,[XPM Support] =>,[XBM Support] => 1
			

			//Split the source image into path name and ext	
			$this->image = $image;
			
			if (file_exists ( $this->image )) {
				
				$pathinf = pathinfo ( $this->image );
				
				$this->image_name = $pathinf ["basename"];
				$this->image_ext = $pathinf ["extension"];
				$this->image_path = $pathinf ["dirname"];
				
				$this->save_width = "";
				$this->save_height = "";
				$this->save_scale = 1;
				$this->save_jpg_quality = 90;
				$this->start_width = "";
				$this->start_height = "";
				
				$this->save_name = "thumb_" . $this->image_name;
				$this->save_mime = $this->getMimeFromExtension ( $this->image_ext );
				$this->save_ext = $this->image_ext;
				$this->save_path = $this->image_path;
				$this->del_old_file = false;
				$this->image_size = getimagesize ( $this->image );
			} else
				$this->error_messages [] = IC_MESSAGE_FILE_NOT_EXISTS;
		
		}
	
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - returns the mime type for the specified extension
	 * @param    - $extension extension name ex:jpg, gif, bmp etc...
	 * @return   - returns the mimetype or empty string if not match was found
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getMimeFromExtension($extension) {
		$extension = strtolower ( $extension );
		$key = array_search ( $extension, $this->mime_allowed );
		
		return (is_bool ( $key ) && $key == false ? "" : $key);
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - is cheking if teh required mimetype is in the allowed ones
	 * @param    - $mime string the mimetype we want to search for
	 * @return   - boolean the response of the search
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function checkMime($mime) {
		
		$mime = strtolower ( $mime );
		
		if (! array_key_exists ( $mime, $this->mime_allowed )) {
			$this->error_messages [] = IC_MESSAGE_FILE_MIME_NOT_ALLOWED;
			return false;
		}
		
		return true;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - the actual function for resize - croping
	 * @param    - 
	 * @return   - boolean true if everything was ok and the image was saved, false othervise
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function doResize() {
		
		if (sizeof ( $this->error_messages ) > 0)
			return false;
		
		$imageMime = image_type_to_mime_type ( $this->image_size [2] );
		
		if (! $this->checkMime ( $imageMime ))
			return false;
		
		$image_width = ceil ( $this->save_width * $this->save_scale );
		$image_height = ceil ( $this->save_height * $this->save_scale );
		
		$save_image = imagecreatetruecolor ( $image_width, $image_height );
		
		switch ($this->image_size [2]) {
			case IMAGETYPE_GIF :
				$src_im = @imagecreatefromgif ( $this->image );
				break;
			case IMAGETYPE_JPEG :
				$src_im = @imagecreatefromjpeg ( $this->image );
				break;
			case IMAGETYPE_PNG :
				$src_im = @imagecreatefrompng ( $this->image );
				break;
			default :
				$src_im = false;
		}
		
		if (! $src_im) {
			
			$this->error_messages [] = IC_MESSAGE_FILE_NEW_IMAGE_COULD_NOT_BE_CREATED;
			return false;
		
		}
		
		//error_log($this->start_width." ".$this->start_height." ".$image_width." ".$image_height." ".$this->save_width." ".$this->save_height);
		$save_name=$this->save_path.$this->save_name;

		//TODO:gd >2 imagecopyresampled imagecopyresized
		if (empty ( $this->start_width ) && empty ( $this->start_height )) 
			imagecopyresampled ( $save_image, $src_im, 0, 0, 0, 0, $image_width, $image_height, $this->save_width, $this->save_height );
		else
			imagecopyresampled ( $save_image, $src_im, 0, 0, $this->start_width, $this->start_height, $image_width, $image_height, $this->save_width, $this->save_height );
		
		switch ($this->image_size [2]) {
			case IMAGETYPE_GIF :
				$res = @imagegif ( $save_image, $save_name );
				break;
			case IMAGETYPE_JPEG :
				$res = @imagejpeg ( $save_image, $save_name, $this->save_jpg_quality );
				break;
			case IMAGETYPE_PNG :
				$res = @imagepng ( $save_image, $save_name );
				break;
			default :
				$res = false;
		}
		
		if (! $res) {
			$this->error_messages [] = IC_MESSAGE_FILE_NEW_IMAGE_COULD_NOT_BE_CREATED;
			return false;
		
		}
		
		chmod ( $save_name, 0777 );
		
		if ($this->del_old_file)
			if (! $this->deleteFile ()) {
				$this->error_messages [] = IC_MESSAGE_FILE_OLD_IMAGE_COULD_NOT_BE_DELETED;
				return false;
			}
		
		return true;
	
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - getter for del_old_file
	 * @param    - 
	 * @return   - $this->del_old_file
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getDel_old_file() {
		return $this->del_old_file;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - getter for image
	 * @param    - 
	 * @return   - $this->image
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getImage() {
		return $this->image;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - getter for image_ext
	 * @param    - 
	 * @return   - $this->image_ext
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getImage_ext() {
		return $this->image_ext;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - getter for image_name
	 * @param    - 
	 * @return   - $this->image_name
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getImage_name() {
		return $this->image_name;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - getter for image_path
	 * @param    - 
	 * @return   - $this->image_path
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getImage_path() {
		return $this->image_path;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - getter for image_size
	 * @param    - 
	 * @return   - $this->image_size
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getImage_size() {
		return $this->image_size;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - getter for mime_allowed
	 * @param    - 
	 * @return   - $this->mime_allowed
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getMime_allowed() {
		return $this->mime_allowed;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - getter for save_ext
	 * @param    - 
	 * @return   - $this->save_ext
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getSave_ext() {
		return $this->save_ext;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - getter for save_height
	 * @param    - 
	 * @return   - $this->save_height
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getSave_height() {
		return $this->save_height;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - getter for save_jpg_quality
	 * @param    - 
	 * @return   - $this->save_jpg_quality
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getSave_jpg_quality() {
		return $this->save_jpg_quality;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - getter for save_mime
	 * @param    - 
	 * @return   - $this->save_mime
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getSave_mime() {
		return $this->save_mime;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - getter for save_name
	 * @param    - 
	 * @return   - $this->save_name
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getSave_name() {
		return $this->save_name;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - getter for save_path
	 * @param    - 
	 * @return   - $this->save_path
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getSave_path() {
		return $this->save_path;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - getter for save_scale
	 * @param    - 
	 * @return   - $this->save_scale
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getSave_scale() {
		return $this->save_scale;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - getter for save_width
	 * @param    - 
	 * @return   - $this->save_width
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getSave_width() {
		return $this->save_width;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - getter for start_height
	 * @param    - 
	 * @return   - $this->start_height
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getStart_height() {
		return $this->start_height;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - getter for start_width
	 * @param    - 
	 * @return   - $this->start_width
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getStart_width() {
		return $this->start_width;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - setter for $this->del_old_file - if the source file needs to be deleted, set it true( default is false)
	 * @param    - boolean $del_old_file
	 * @return   - void
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function setDel_old_file($del_old_file) {
		$this->del_old_file = $del_old_file;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - setter for $this->image
	 * @param    - string $image
	 * @return   - void
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function setImage($image) {
		$this->image = $image;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - setter for $this->image_ext
	 * @param    - string $image_ext
	 * @return   - void
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function setImage_ext($image_ext) {
		$this->image_ext = $image_ext;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - setter for $this->image_name
	 * @param    - string $image_name
	 * @return   - void
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function setImage_name($image_name) {
		$this->image_name = $image_name;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - setter for $this->image_path
	 * @param    - string $image_path
	 * @return   - void
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function setImage_path($image_path) {
		$this->image_path = $image_path;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - setter for $this->image_size
	 * @param    - string $image_size
	 * @return   - void
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function setImage_size($image_size) {
		$this->image_size = $image_size;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - setter for $this->mime_allowed
	 * @param    - string $mime_allowed
	 * @return   - void
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function setMime_allowed($mime_allowed) {
		$this->mime_allowed = $mime_allowed;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - setter for $this->save_ext
	 * @param    - string $save_ext
	 * @return   - void
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function setSave_ext($save_ext) {
		$this->save_ext = $save_ext;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - setter for $this->save_height
	 * @param    - string $save_height
	 * @return   - void
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function setSave_height($save_height) {
		$this->save_height = $save_height;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - setter for $this->save_jpg_quality - this is only for jpg
	 * @param    - string $save_jpg_quality
	 * @return   - void
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function setSave_jpg_quality($save_jpg_quality) {
		$this->save_jpg_quality = $save_jpg_quality;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - setter for $this->save_mime
	 * @param    - string $save_mime
	 * @return   - void
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function setSave_mime($save_mime) {
		$this->save_mime = $save_mime;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - setter for $this->save_name
	 * @param    - string $save_name
	 * @return   - void
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function setSave_name($save_name) {
		$this->save_name = $save_name;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - setter for $this->save_path
	 * @param    - string $save_path
	 * @return   - void
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function setSave_path($save_path) {
		$this->save_path = $save_path;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - setter for $this->save_scale
	 * @param    - string $save_scale
	 * @return   - void
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function setSave_scale($save_scale) {
		$this->save_scale = $save_scale;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - setter for $this->save_width
	 * @param    - string $save_width
	 * @return   - void
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function setSave_width($save_width) {
		$this->save_width = $save_width;
	}
	
	/**
	 * @param unknown_type $start_height
	 */
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - rewrite for the hrefs in the page, it is only called from rewrite_src_path function
	 * @param    - $matches is an array with $contents from the earlier preg_replace
	 * @return   - $content which is the rewritten template content
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function setStart_height($start_height) {
		$this->start_height = $start_height;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - setter for $this->start_width
	 * @param    - string $start_width
	 * @return   - void
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function setStart_width($start_width) {
		$this->start_width = $start_width;
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - error message getter
	 * @param    - 
	 * @return   - mixed returns an array of error messages or empty string if there are no errors
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function getErrorMessage() {
		return (sizeof ( $this->error_messages ) > 0 ? implode ( "<br>", $this->error_messages ) : "");
	}
	
	/**
	 * @author   - GraFX Software Solutions webmaster@grafxsoftware.com
	 * @type     - public
	 * @desc     - delete the specified $this->image file or the $file parameter's one
	 * @param    - string $file - the file we want to delete
	 * @return   - boolean true if success, false othervise
	 * @vers     - 1.0
	 * @Mod by   -
	 * @Mod vers -
	 **/
	function deleteFile($file = "") {
		if (empty ( $file ))
			$file = $this->image;
		
		if (file_exists ( $file ) && unlink ( $file )) {
			$this->error_messages [] = IC_MESSAGE_FILE_COULD_NOT_DELETE;
			return true;
		} else
			return false;
	}

}
?>