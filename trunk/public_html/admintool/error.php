<?php

include_once("../config.inc.php");
include_once(INCLUDE_PATH."cls_fast_template.php");
include_once(INCLUDE_LANGUAGE_PATH.$LANG.".inc.php");
include_once(INCLUDE_LANGUAGE_PATH.$LANG.".admintool.inc.php");
include_once(INCLUDE_PATH."connection.php");


$ft = new FastTemplate(ADMIN_TEMPLATE_CONTENT_PATH);
$ft->define(array("main"=>"template_firstpage.html", "content"=>"error.html"));

$ft->assign("ERROR_MESSAGE", $all_url_vars['message']);

$ft->multiple_assign_define("LANG_");
$ft->multiple_assign_define("CONF_");
$ft->parse("BODY", array("content","main"));
$ft->showDebugInfo(ERROR_DEBUG);
$ft->FastPrint();


?>