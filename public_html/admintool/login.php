<?php
include_once("../config.inc.php");
include_once(INCLUDE_PATH.'cls_session.php');
$sess = new MYSession();
include_once(INCLUDE_PATH."cls_fast_template.php");
include_once(INCLUDE_LANGUAGE_PATH.$LANG.".inc.php");
include_once(INCLUDE_LANGUAGE_PATH.$LANG.".admintool.inc.php");
include_once(INCLUDE_PATH."connection.php");

$stringutil = new String("nope");
$all_url_vars = array();
$all_url_vars = $stringutil->parse_all();

//$util=new Util();
//$util->check_authentification();

if(empty($all_url_vars['action']))
{
	// unset session
	$url = $sess->get('session_url_before');
	session_destroy();
	//$sess = new MYSession();
	$sess->set('session_url_before',$url);
	$ft = new FastTemplate(ADMIN_TEMPLATE_CONTENT_PATH);
	$ft->define(array("main"=>"template_firstpage.html", "content"=>"login.html"));
	$ft->assign("ADMIN_URL", ADMIN_URL);
	$ft->assign("MESSAGE", $all_url_vars['message']);
	$ft->multiple_assign_define("LANG_");
	$ft->multiple_assign_define("CONF_");
	$ft->parse("BODY", array("content","main"));
	$ft->showDebugInfo(ERROR_DEBUG);
	$ft->FastPrint();
}
else
{
	if(($all_url_vars['userid'] == USER) && ($all_url_vars['password'] == PASSWORD))
	{
		$sess->set(SESSION_ID,SESSION_ID);
		if ($sess->get('session_url_before'))
			header("Location: ".$sess->get('session_url_before'));
		else
			header("Location: ".ADMIN_URL);
	}
	else
	{
		$ft = new FastTemplate(ADMIN_TEMPLATE_CONTENT_PATH);
		$ft->define(array("main"=>"template_firstpage.html", "content"=>"login.html"));
		$ft->assign("ADMIN_URL", ADMIN_URL);
		$ft->assign("MESSAGE", LANG_ADMIN_FAILED_LOGIN);
		$ft->multiple_assign_define("LANG_");
		$ft->multiple_assign_define("CONF_");
		$ft->parse("BODY", array("content","main"));
		$ft->showDebugInfo(ERROR_DEBUG);
		$ft->FastPrint();
	}
}
?>