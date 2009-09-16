<?php
/*
CODE GENERATED BY: GRAFXSOFTWARE CODE GENERATOR
http://www.grafxsoftware.com
======================================
PHPFILE MADE BY: Test Developer
DATE: 2009:03:29
PROJECT: Test Product
======================================
*/

include_once ("../config.inc.php");
include_once (INCLUDE_PATH . "cls_fast_template.php");
include_once (INCLUDE_LANGUAGE_PATH . $LANG . ".inc.php");
include_once (INCLUDE_LANGUAGE_PATH . $LANG . ".admintool.inc.php");
include_once (INCLUDE_PATH . "connection.php");
include_once (INCLUDE_PATH . "cls_sidebar.php");

$stringutil = new String ( "" );
$all_url_vars = array ();
$all_url_vars = $stringutil->parse_all ();

/**
 * @author   - Test Developer
 * @desc     - autentication will be called here
 * @vers     - 1.0
 **/
$util = new Authenticate ();
$util->check_authentification ();

/**
 * @desc     - Call of Fast Template, with PATH from defines.inc.php
 **/
$ft = new FastTemplate ( ADMIN_TEMPLATE_CONTENT_PATH );
$ft->define ( array ("main" => "template_index.html", "content" => "index.html" ) );

// static part
$ft->multiple_assign_define ( "LANG_" );
$ft->multiple_assign_define ( "CONF_" );

$sb = new Sidebar ( );


if (file_exists('../installer') && CONF_INDEX_URL !== "INST_URL" && ERROR_DEBUG==0)
{
	$errormessage="<div class=\"mError\">".LANG_ADMIN_INSTALLER."</div>";
}
if (CONF_PASSWORD=="setup")
{
	header ("Location: config.php"); exit;
}	
$ft->assign("MESSAGE", $errormessage);
$ft->assign ( "SIDEBAR", $sb->getSideBar () );
$ft->parse ( "BODY", array ("content", "main" ) );
$ft->showDebugInfo ( ERROR_DEBUG );
$ft->FastPrint ();
?>