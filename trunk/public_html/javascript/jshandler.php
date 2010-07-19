<?php
/*
#################    STAR RATING SYSTEM   ##################
############################################################
CWB PRO			$Name: release_2_0_0alpha13052005 $
Revision		$Revision: 14501 $
Author			$Author: lvalics $
Created 03/01/02        $Date: 2010-04-16 15:35:20 +0300 (Fri, 16 Apr 2010) $
Writed by               GraFX (it@grafx.ro)
Scripts Home:           http://www.grafxsoftware.com
############################################################
File purpose            JAVA SCRIPT VALIDATOR
############################################################
*/
$allowed_js=array(
	"general_script.js"
);

include_once("../config.inc.php");
include_once("../cls_fast_template.php");
if(!isset($_GET["lang"]))
$LANG=$_GET["lang"];
include_once(INCLUDE_LANGUAGE_PATH.$LANG.".inc.php");
include_once(INCLUDE_LANGUAGE_PATH."$LANG.admin.inc.php");

$name=$_GET["name"];

if (!in_array($name.".js",$allowed_js)){
	$error="Error: The js filename '$name' doesn't exists in the allowed list.";
	error_log($error);
	echo $error;
	exit;
};

if(isset($name))
{
$ft = new FastTemplate(INDEX_PATH."javascript/");
$ft->define(array("main"=>$name.".js"));
$ft->assign("CSS","");
$ft->multiple_assign_define("CONF_");
$ft->multiple_assign_define("LANG_");
$ft->multiple_assign_define("CONF_");
$ft->parse("mainContent", "main");
print $ft->fetch("mainContent");
}
else
print "";
?>