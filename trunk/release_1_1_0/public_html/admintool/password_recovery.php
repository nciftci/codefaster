<?php
	include_once("../config.inc.php");
	include_once(INCLUDE_PATH.'cls_session.php');
	$sess = new MYSession();
	include_once(INCLUDE_PATH."cls_fast_template.php");
	include_once(INCLUDE_LANGUAGE_PATH.$LANG.".inc.php");
	include_once(INCLUDE_LANGUAGE_PATH.$LANG.".admintool.inc.php");
	include_once(INCLUDE_PATH."connection.php");
	
	$SQL = "SELECT `value` FROM `".DB_PREFIX."config` WHERE `name`='EMAIL_INREG'";
	$retid = mysql_query($SQL) or die(mysql_error());
	if ($row = mysql_fetch_array($retid))
	do{
		$email = $row["value"];
	} while ($row = mysql_fetch_array($retid));
	
	$SQL = "SELECT `value` FROM `".DB_PREFIX."config` WHERE `name`='USER'";
	$retid = mysql_query($SQL) or die(mysql_error());
	if ($row = mysql_fetch_array($retid))
	do{
		$username = $row["value"];
	} while ($row = mysql_fetch_array($retid));
	
	$SQL = "SELECT `value` FROM `".DB_PREFIX."config` WHERE `name`='PASSWORD'";
	$retid = mysql_query($SQL) or die(mysql_error());
	if ($row = mysql_fetch_array($retid))
	do{
		$password = $row["value"];
	} while ($row = mysql_fetch_array($retid));
	
	$to = ""; $subject = ""; $headers = ""; $message ="";
	
	$fte = new FastTemplate(EMAIL_PATH);
	$fte->define(array("main"=>"emailpasswordrecovery.html"));		
	$fte->assign("USERID", $username);
	$fte->assign("PASSWORD", $password);
	$fte->multiple_assign_define("LANG_");
	$fte->multiple_assign_define("CONF_");
	$fte->parse("mainContent", "main");
	$fte->showDebugInfo(ERROR_DEBUG);
	$message = $fte->fetch("mainContent");

	// se trimite scrisoarea
	$univMail= new UniversalMailSender("2");
	// setting up the to address
	$univMail->setToAddress($email,$username);
	// setting up from address
	$univMail->setFromAddress($email, $username);
	// setting up subject
	$univMail->setSubject($all_url_vars['newslettersubject']);
	// setting up from text message if the type is text message
	$univMail->setTextMessage($message);
	// setting up from text message if the type is html message
	$univMail->setHtmlMessage($message);
	// sending the mail; returns an error message
	$ok=$univMail->SendMail();
	
	header("Location: login.php?message=".LANG_ADMIN_PASSWORD_SENTSUCCESSFULLY);
?>