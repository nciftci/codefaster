<?php

$scanned_directories=array("programtemplates/","css/");

$allowed_extensions="html|htm|css|bak";


include_once ("../config.inc.php");
include_once (INCLUDE_PATH . "cls_fast_template.php");
include_once (INCLUDE_LANGUAGE_PATH . $LANG . ".inc.php");
include_once (INCLUDE_LANGUAGE_PATH . $LANG . ".admintool.inc.php");
include_once (INCLUDE_PATH . "connection.php");
include_once (INCLUDE_PATH . "cls_sidebar.php");

$stringutil = new String("");
$all_url_vars = array();
$all_url_vars = $stringutil->parse_all();

/**
 * @author   - Test Developer
 * @desc     - autentication will be called here
 * @vers     - 1.0
 **/
$util = new Authenticate ();
$util->check_authentification ();

$sb = new Sidebar ();

$action = $all_url_vars["action"];
$template_name = $all_url_vars["template_name"];

if($action != "mod")
{
	$files_array=array();
	//kiolvassuk a template directoryt
	foreach ($scanned_directories as $dir){
		$i=0;
		$d = dir(INDEX_PATH.$dir);
		while (false !== ($entry = $d->read())) {
			$templ[$i] = $dir.$entry; 
			$i++;
		}
		$d->close();	
		sort($templ);// rendezes			
		$files_array=array_merge($files_array,$templ);
	};
	
	$size = sizeof($files_array);	

	$buffer = "";
	for($i=0;$i<$size;$i++){
		$filename=$files_array[$i];
		
		if (preg_match("/(".$allowed_extensions.")$/i",$filename)==0) continue;
		$buffer .= "<option value=\"".$i."\">".$filename."</option>\n";
	};

	if($template_name!="")
		$temp_source = @file_get_contents(INDEX_PATH.$files_array[$template_name]);
	
	$temp_source = str_replace("{","#_",$temp_source);
	$temp_source = str_replace("}","_#",$temp_source);
	$temp_source = str_replace("textarea","textarea#",$temp_source);
	
	$temp_source = str_replace("BEGIN DYNAMIC BLOCK","BDYB",$temp_source);
	$temp_source = str_replace("END DYNAMIC BLOCK","EDYB",$temp_source);
	
	$ft = new FastTemplate(ADMIN_TEMPLATE_CONTENT_PATH);
	$ft->define(array("main"=>"template_index.html", "content"=>"templateeditor.html"));
	
	$variable_text = @file_get_contents(INDEX_PATH."admintool/variables.txt");// segitseg, szovegek	
	$ft->assign("VARIABLE_TEXT",$variable_text);
	
	if($all_url_vars["msg"] == 1)
		$ft->assign("SAVED_MSG",1);
	else
		$ft->assign("SAVED_MSG",0);
		
	if(strpos($files_array[$template_name],".bak")===false)
		$ft->assign("BAK_FILE",0);
	else
		$ft->assign("BAK_FILE",1);
		
	$ft->assign("TEMPLATE_NAMES",$buffer);
	$ft->assign("TEMPLATE_NAME",$files_array[$template_name]);
	$ft->assign("TEMPLATE_DESCRIPTION",$temp_source);	
	
	$ft->multiple_assign_define("LANG_");	
	$ft->multiple_assign_define("CONF_");	
	$ft->assign ( "SIDEBAR", $sb->getSideBar ());
	$ft->parse("BODY", array("content","main"));
	$ft->showDebugInfo(ERROR_DEBUG);
	$ft->FastPrint();
}
else
{
	$template_name = $all_url_vars["t_name"];
	$template_source = $_REQUEST["template_source"];

	$template_source = str_replace("#_","{",$template_source);
	$template_source = str_replace("_#","}",$template_source);
	$template_source = str_replace("textarea#","textarea",$template_source);
	
	$template_source = str_replace("BDYB","BEGIN DYNAMIC BLOCK",$template_source);
	$template_source = str_replace("EDYB","END DYNAMIC BLOCK",$template_source);
		
	$template_source = html_entity_decode($template_source);
	
	rename(INDEX_PATH.$template_name, INDEX_PATH.$template_name.".bak");
	
	$outputfile = INDEX_PATH.$template_name;

	if(!empty($template_source))
	{
		$fp = fopen($outputfile, "w+");

		if (!fwrite($fp, $template_source)) {
			print "Cannot write to file ($outputfile)";
			fclose($fp);
			exit;
		}		
		fclose($fp);
	}
	header("Location: ".CONF_ADMIN_URL."templateeditor.php?msg=1");
	exit;
}
?>
