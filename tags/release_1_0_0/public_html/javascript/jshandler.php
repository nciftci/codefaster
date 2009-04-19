<?php
/*
######################    CWB  PRO   #######################
############################################################
CWB PRO			$Name$
Revision		$Revision: 5408 $
Author			$Author: iborbely $
Created 03/01/03        $Date: 2006-09-28 14:37:15 +0300 (J, 28 sep. 2006) $
Writed by               GraFX (webmaster@grafxsoftware.com)
Scripts Home:           http://www.grafxsoftware.com
############################################################
File purpose            JAVA SCRIPT VALIDATOR
############################################################
*/

include_once("../config.inc.php");
include_once("../cls_fast_template.php");
if(!isset($_GET["lang"]))
$LANG = $_GET["lang"];
include_once(INCLUDE_LANGUAGE_PATH.$LANG.".inc.php");
include_once(INCLUDE_LANGUAGE_PATH.$LANG.".admin.inc.php");

header('Content-Type: text/javascript');
//error_log(INDEX_PATH."javascript/".$_GET["name"].".js");
if(isset($_GET["name"]))
{
	$ft = new FastTemplate(INDEX_PATH."javascript/");
	$ft->define(array("main"=>$_GET["name"].".js"));
	$ft->multiple_assign_define("LANG_");
	$ft->parse("mainContent", "main");	
	print $ft->fetch("mainContent");
}
else print "";
?>