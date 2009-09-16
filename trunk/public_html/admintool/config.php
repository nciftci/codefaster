<?php
/*
##################     CWB  PRO   ########################
############################################################
CWB  PRO		      	Version 1.0
Writed by               GraFX (webmaster@grafxsoftware.com)
Created 03/01/03        Last Modified $Date: 2008-09-20 09:03:38 +0300 (S, 20 sep. 2008) $
Scripts Home:           http://www.grafxsoftware.com
############################################################
File name               config.php
File purpose            Configuration Script
File created by         GraFX (webmaster@grafxsoftware.com)
############################################################
*/// $Id: config.php 10280 2008-09-20 06:03:38Z lvalics $
	include_once("../config.inc.php");
	include_once(INCLUDE_PATH."cls_fast_template.php");
	include_once(INCLUDE_LANGUAGE_PATH.$LANG.".inc.php");
	include_once(INCLUDE_LANGUAGE_PATH.$LANG.".admintool.inc.php");
	include_once(INCLUDE_PATH."connection.php");

	$util=new Authenticate();
	$util->check_authentification();
	
	$st = new String("");
	$all_url_vars = array();
	$all_url_vars=$st->parse_all();
	if (empty($all_url_vars['action']))  $all_url_vars['action'] = "form";

if ($all_url_vars['action']=="index")
{
	$ft = new FastTemplate(ADMIN_TEMPLATE_CONTENT_PATH);
	$ft->define(array("main"=>"template_index.html", "leftmenu"=>"left_menu.html", "content"=>"config_index.html"));
	if(SHOP == 1) $ft->assign("SHOP",1);

	if(!strstr(PHP_OS, 'WIN'))
	{
		if (!is_writable(INDEX_PATH."tmp/extraconfig.inc.php")) $ft->assign("MESSAGE", "<div class=\"mError\">".LANG_CONF_ERROR_NOMOD."</div>");
   			else   $ft->assign("MESSAGE", "<div class=\"mError\">".LANG_CONF_ERROR_SECURITY."</div>");
	}
	else $ft->assign("MESSAGE", "");

	$ft->assign("INDEX_PATH", INDEX_PATH);
	//sidebar
	$sb = new Sidebar ( );
	$ft->assign ( "SIDEBAR", $sb->getSideBar () );
	$ft->multiple_assign_define("LANG_");
	$ft->multiple_assign_define("CONF_");
	$ft->parse("BODY", array("content","main"));
	$ft->showDebugInfo(ERROR_DEBUG);
	$ft->FastPrint();
}
else if ($all_url_vars['action']=="form")
{
	$ft = new FastTemplate(ADMIN_TEMPLATE_CONTENT_PATH);
	$ft->define(array("main"=>"template_index.html", "leftmenu"=>"left_menu.html", "content"=>"config.html"));

	if(!strstr(PHP_OS, 'WIN'))
	{
		if (!is_writable(INDEX_PATH."tmp/extraconfig.inc.php")) $ft->assign("MESSAGE", "<div class=\"mError\">".LANG_CONF_ERROR_NOMOD."</div>");
   			else   $ft->assign("MESSAGE", "<div class=\"mError\">".LANG_CONF_ERROR_SECURITY."</div>");
	}
	else $ft->assign("MESSAGE", "");

	$SQL = "SELECT id,description,name,value,comment,type FROM ".DB_PREFIX."config WHERE pagenr='".$all_url_vars['pagenr']. "' ORDER BY position ASC;";
	$retid = mysql_query($SQL) or die(mysql_error());
	$nr_totalrecords = mysql_num_rows($retid);
	$i=0;
	if ($row = mysql_fetch_array($retid))
	do{
		$id[$i] = $row["id"];
		$description[$i] = $row["description"];
		$name[$i] = $row["name"];
		$value[$i] = $row["value"];
		$comment[$i] = $row["comment"];
		$type[$i] = $row["type"];
		$i++;
	} while ($row = mysql_fetch_array($retid));
	$nr = $i;

	$ft->setPattern(array("LANG_","CONF_"));
	$ft->define_dynamic("conf_list","content");
	for ($i=0;$i<$nr;$i++)
	{
       switch ($type[$i])
	   {
			case 1:// textField
			   $value_str = "<input size=40 type=text name='" . "CNF_".$name[$i] . "' value='" . $value[$i]  . "'>";
			   break;
			case 2:// textArea
			   $value_str = "<textarea cols=50 rows=5 name='" . "CNF_".$name[$i] ."'>". $value[$i]  . "</textarea>";
			   break;
		   case 3:// select Menu
			   $value_str = "<select name='" . "CNF_".$name[$i] . "'>";
			   $options = explode("|", $comment[$i]);
			   for ($j=0; $j<count($options); $j+=2){
			   		$desc = $options[$j+1];
			        $value_str.="<option value='" . $options[$j] . "' " . (($value[$i]==$options[$j])?"SELECTED":"") . ">" . constant($desc);
			   }
			   $value_str.="</select>";
			   break;
			case 4:// textField (for int values)
			   $value_str = "<input size=5 maxlength=5 type=text name='" . "CNF_".$name[$i] . "' value='" . $value[$i]  . "'>";
			   break;
			case 5:// CheckBox
			   $options = explode("|", $comment[$i]);
			   $current_2power = 1;
			   $value_str="";
			   for ($j=0; $j < count($options); $j++){
			          $value_str .=$br."<input type=checkbox name='" . "CNF_CHBX_".$name[$i] . "[]' " . ((( $value[$i] & $current_2power) == $current_2power)?"CHECKED":"") . "> " . constant($options[$j]);
			   }
			   break;
		}//end for
		$ft->assign("CONF_ID",$id[$i]);
		$ft->assign("CONF_DESCRIPTION", constant($description[$i]));
		$ft->assign("CONF_NAME", $name[$i]);
		$ft->assign("CONF_VALUE", $value_str);
		$ft->assign("NR_ROWS",$nr);
		$ft->parse("CONF_LIST",".conf_list");
	}
	if (CONF_PASSWORD=="setup")
	{
		$errormessage="<div class=\"mError\">".LANG_ADMIN_SETUPPASSWORD."</div>";
	}		
	$ft->assign("MESSAGE", $errormessage);
	$ft->assign("INDEX_PATH", INDEX_PATH);
	$ft->clear_dynamic("conf_list");
	//sidebar
	$sb = new Sidebar ( );
	$ft->assign ( "SIDEBAR", $sb->getSideBar () );
	$ft->multiple_assign_define("LANG_");
	$ft->multiple_assign_define("CONF_");
	$ft->parse("BODY", array("content","main"));
	$ft->showDebugInfo(ERROR_DEBUG);
	$ft->FastPrint();
}
else if ($all_url_vars['action'] == "submit")
{
	$nr_rows = $all_url_vars['nr_rows'];

	foreach($all_url_vars as $key =>$value)
	{
		  $ta=explode("CNF_",$key);
		  if(count($ta) && empty($ta[0]))
		  {
			   $SQL = "SELECT type FROM ".DB_PREFIX."config where name='".$ta[1]."'";
			   $retid = mysql_query($SQL) or die(mysql_error());
			   $i=0;
			   if ($row = mysql_fetch_array($retid))
				{
					$type=$row['type'];
					if ($type!=5)
					{
						$SQL =  " UPDATE ".DB_PREFIX."config SET value = \"".$value."\"";
						$SQL .= " where name='".$ta[1]."'";
						$retid = mysql_query($SQL) or die(mysql_error());
					}
					 else
					 {
						   $options = explode("|", $value);
						   $current_2power = 1;
						   $current_value = 0;
						   for ($j=0; $j<count($options); $j++)
						   {
								$current_value |=  (isset($all_url_vars[$key . $current_2power])?$current_2power:0);
								$current_2power *=2;
						   }
							$SQL =  " UPDATE ".DB_PREFIX."config SET value = \"".$current_value."\"";
							$SQL .= " where name='".$ta[1]."'";
							$retid = mysql_query($SQL) or die(mysql_error());
					 }// end else
				   }//end if
		}//end if
	}// end foreach

	$cw = new ConfigWrapper(0);
	$cw->generateConfig(INDEX_PATH."tmp/extraconfig.inc.php");
	header("Location: ".CONF_INDEX_URL."admintool/config.php?action=index");
}
?>