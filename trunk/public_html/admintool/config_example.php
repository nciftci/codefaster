<?php
/*
##################     CWB  PRO   ########################
############################################################
CWB  PRO		      	Version 1.0
Writed by               GraFX (webmaster@grafxsoftware.com)
Created 03/01/03        Last Modified $Date: 2006-10-11 14:16:49 +0300 (Wed, 11 Oct 2006) $
Scripts Home:           http://www.grafxsoftware.com
############################################################
File name               config.php
File purpose            Configuration Script
File created by         GraFX (webmaster@grafxsoftware.com)
############################################################
*/// $Id: config.php 5501 2006-10-11 11:16:49Z iborbely $
	include_once("../config.inc.php");
	include_once(INCLUDE_PATH."cls_fast_template.php");
	include_once(INCLUDE_LANGUAGE_PATH.$LANG.".inc.php");
	include_once(INCLUDE_LANGUAGE_PATH.$LANG.".admintool.inc.php");
	include_once(INCLUDE_PATH."connection.php");
	include_once(INCLUDE_ADMIN_PATH."sidebar.inc.php");
	

	$util=new Util();
	$util->check_authentification();

	$stringutil = new String("");
	$all_url_vars = array();
	$stringutil->setIntVars("configid");
	$all_url_vars = $stringutil->parse_all();

	$SQL = "SELECT `name`,`image`,`help_description`,`url`,`template` FROM `".DB_PREFIX."config` WHERE id = '".$all_url_vars['configid']. "'";
	$retid = mysql_query($SQL) or die(mysql_error());
	if ($row = mysql_fetch_array($retid))
	{
		$name = $row["name"];
		$help_description = $row["help_description"];
		$image = $row["image"];
		$url = $row["url"];
		$template = $row["template"];

	}
	$ft = new FastTemplate(ADMIN_TEMPLATE_CONTENT_PATH);
    $ft->define(array("main"=>"config_example.html"));
	$ft->assign("VARIABLE",$name);
	if($image != "")
		$ft->assign("CONFIMAGE",CONFIG_IMAGE_URL.$image);
	else
		$ft->assign("CONFIMAGE",IMG_CONTENT_URL."spacer.gif");

	$ft->assign("CONFDESCRIPTION",$help_description);
	$ft->assign("CONFURL",$url);
	$ft->assign("CONFTEMPLATE",$template);

	$ft->multiple_assign_define("CONF_");
	$ft->multiple_assign_define("LANG_");
	$ft->assign("SIDEBAR",$sidebar);
	$ft->parse("mainContent", "main");
	$ft->showDebugInfo(ERROR_DEBUG);
	$ft->FastPrint("mainContent");
?>