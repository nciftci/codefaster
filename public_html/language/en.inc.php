<?php
@include ("en.extra.inc.php");
@include ("en.pagination.inc.php");

define("CONF_CHARSET","iso-8859-1");
header("Content-Type: text/html; charset=".CONF_CHARSET);

define("LANG_NOID_ERROR_MESSAGE","Error occured. Please contact us.");
define("LANG_YES", "Yes");
define("LANG_NO", "No");
define("LANG_REGISTER_UNLOADPAGE", "Are you sure you want to go away from this page?");
define("LANG_PICTURE_NOTVALID","THAT IS NOT A VALID IMAGE\nPlease load an image with an extention of one of the following:\n\n");
?>