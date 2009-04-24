<?php

define("VERSION", "1.0.0"); //software version
// define where you root is, usually is ... your-path/program-name/
// always use ENDING SLASH
if (!strstr(PHP_OS, 'WIN'))
    define("SEPARATOR", "/");
else
    define("SEPARATOR", "\\");
define("INDEX_PATH", dirname(__FILE__).SEPARATOR);

// define your language file, ex: en; ro; de;
$LANG = "en";

// allowed upload extensions
$ALLOWED_EXT = array(	
	"gif",
	"jpg",
	"png",
	"GIF",
	"JPG",
	"PNG",
	"swf",
	"SWV",
	"flv",
	"FLV"
);

// =======================================================================
// all the rest need to be as it is. Edit this lines only if you know what you are doing
// JavaScript Path
define("TEMPLATE_PATH_JAVASCRIPTS", INDEX_PATH."javascript/");
// define where you programtemplates
define("TEMPLATE_PATH", INDEX_PATH."programtemplates/");
// define where you emailtemplates
define("EMAIL_PATH", INDEX_PATH."emailtemplates/");
// Image URL - not used
define("IMG_CONTENT_URL", CONF_INDEX_URL."images/");
// define where you images path
define("IMG_CONTENT_PATH", INDEX_PATH."images/");
// define where you include path
define("INCLUDE_PATH", INDEX_PATH."include/");
// define where you language path
define("INCLUDE_LANGUAGE_PATH", INDEX_PATH."language/");
// define where you admin programtemplates
define("ADMIN_TEMPLATE_CONTENT_PATH", INDEX_PATH."admintool/programtemplates/");
// define where you admin path
define("ADMIN_URL", CONF_INDEX_URL."admintool/");
// some include file need from inlude directorysame as, but you can change it,
// if you copy include dir into admin directory
define("INCLUDE_ADMIN_PATH", INDEX_PATH."include/");
// images path, images will be stored here, need to be - chmod 777
define("PUBL_IMAGE_PATH", INDEX_PATH."publ_images/");
// images path, images will be stored here, need to be - chmod 777
define("PUBL_IMAGE_URL", CONF_INDEX_URL."publ_images/");
// used for examples in configuration, here place example images
define("CONFIG_IMAGE_PATH", INDEX_PATH."publ_images/config/");
define("CONFIG_IMAGE_URL", CONF_INDEX_URL."publ_images/config/");

// if an error accured on upload images this number will be returned
if(!defined("UPLOAD_ERR_FORM_SIZE")) {
	define("UPLOAD_ERR_FORM_SIZE","2");
}
// FILE UPLOAD
define("FU_CONF_INDEX_PATH",dirname(__FILE__));
define("FU_CONF_INDEX_URL",INDEX_URL);
define("FU_CONF_UPLOADDIR",FU_CONF_INDEX_PATH."/tmp/");
define("FU_CONF_LANGUAGE","en");
// IMAGE CROP
define("IC_CONF_IMAGEDIR",FU_CONF_INDEX_PATH."/public_images/");
define("IC_CONF_IMAGEDIR_THUMBS",FU_CONF_INDEX_PATH."/public_images/thumbs/");
define("IC_CONF_IMAGE_WIDTH",500);
define("IC_CONF_IMAGE_THUMB_HEIGHT",100);
define("IC_CONF_IMAGE_THUMB_WIDTH",100);
//
function __autoload($class_name) {
	$class_name = 'cls_' . strtolower($class_name) . '.php';
         	
	if (file_exists(INCLUDE_PATH . $class_name)) {	
		include_once(INCLUDE_PATH . $class_name);
	} else if (file_exists(INDEX_PATH . $class_name)) {
		include_once(INDEX_PATH . $class_name);
	}
}
 
?>