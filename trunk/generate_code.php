<?php
/*
######################  CODE GENERATOR  ####################
############################################################
CWB PRO			$Name$
Revision		$Revision: 11849 $
Author			$Author: codefaster $
Created 03/01/03        $Date: 2009-04-02 13:46:08 +0300 (J, 02 apr. 2009) $
Writed by               GraFX (webmaster@grafxsoftware.com)
Scripts Home:           http://www.grafxsoftware.com
############################################################
 */	

include_once("./config.inc.php");
include_once(INCLUDE_PATH."cls_fast_template.php");
include_once(INCLUDE_PATH."cls_string.php");
include_once(INCLUDE_PATH."cls_session.php");
include_once(INCLUDE_PATH."PhpBeautifier.inc.php");

$session      = new MYSession();
$stringutil   = new String();
$all_url_vars = $stringutil->parse_all();
// basic values

if(!isset($all_url_vars['type'])) {
	$all_url_vars['type']="php";
}
//print  $all_url_vars['genfromsql'];

$session->set("name", $all_url_vars['name']);

// classname not specified
if(!isset($all_url_vars['classname'])) {
	if($all_url_vars['type'] == "php" or $all_url_vars['type'] == "php5") {
		$all_url_vars['classname'] = "cls_" . strtolower($all_url_vars['name']) . ".php";
	} else {
		$all_url_vars['classname'] = $all_url_vars['name'] . ".java";
	}
} else {
	$namen=explode(".",$all_url_vars['classname']);
	if($all_url_vars['type']=="php" or $all_url_vars['type'] == "php5") {
		$all_url_vars['classname'] = $namen[0] .".php";
	} else {
		$all_url_vars['classname'] = $namen[0] .".java";
	}
}

$ft = new FastTemplate(TEMPLATE_PATH);
$ft->define(array(
		"main"    => "t_{$all_url_vars['type']}_code_generator.html", 
		"content" => "t_{$all_url_vars['type']}_code_generator.html"
	)
);

$ft->assign("AUTHOR",       $all_url_vars['author']);   
$ft->assign("PROJECT_NAME", $all_url_vars['projectname']);
$ft->assign("DATE",         date("Y:m:d"));
$ft->assign("NAME",         $all_url_vars['name']);
$ft->assign("DBNAME",       $all_url_vars['table']);
$ft->assign("CLASSVAR",     $all_url_vars['fieldnames']);

$history_table = $all_url_vars['history_table'];

if (empty($history_table)) {
	$ft->assign("ENABLE_UNDO",   "false");
	$ft->assign("HISTORY_TABLE", "history_table");
} else {
	$ft->assign("ENABLE_UNDO",   "true");
	$ft->assign("HISTORY_TABLE", $history_table);
}


// getting the variable names
$all_url_vars['fieldnames'] = str_replace(" ", "", $all_url_vars['fieldnames']);

if($all_url_vars['fieldnames'][strlen($all_url_vars['fieldnames'])-1] == ";") { 
	$all_url_vars['fieldnames']=substr($all_url_vars['fieldnames'],0,-1);
}

$fields =explode(";",$all_url_vars['fieldnames_en']);

$fields_en =explode(";",$all_url_vars['fieldnames_en']);

foreach($fields as $key=>$value)
 $tmp_fields[$value]=$fields_en[$key];

//print_r($tmp_fields);exit;
$session->set("language_fields", $tmp_fields);

$all_url_vars['funct_name']=str_replace(" ","",$all_url_vars['funct_name']);

if($all_url_vars['funct_name'][strlen($all_url_vars['funct_name'])-1] == ";") { 
	$all_url_vars['funct_name']=substr($all_url_vars['funct_name'],0,-1);
}

$functions = explode(";", $all_url_vars['funct_name']);
$i = 0;

while(!empty($functions[$i])) {
	$i++;
}

$function_nr = $i;	  

$i=0;
while(!empty($fields[$i])) {
	$i++;
}

$nr = $i;	


$buffer="";
$buffer_insert="";

for ($i = 1; $i < $nr; $i ++) {
  if($fields[$i]==$fields_en[$i])
  {
	$buffer .= "`" . $fields[$i] . "`";
	$buffer_insert .= "`" . $fields[$i] . "`";
  }
  else
  {
    $buffer_insert .= "`" . $fields[$i] . "_{\$this->lang}` ";
	$buffer .= "`" . $fields[$i] . "_{\$this->lang}` AS `" . $fields[$i] ."`";
	}	
		
	if($i <> $nr-1) {
		$buffer.=",";
		$buffer_insert.=",";
	}
}

$ft->assign("LIST", $buffer);
$ft->assign("LIST_IN", $buffer_insert);


if($all_url_vars['type'] == "php") {        

	// for variables
	$ft->define_dynamic("getfunctionsvar", "main");

	for ($i = 0; $i < $function_nr; $i ++) {
		$ft->assign("CLASSVAR",  $all_url_vars['varname']);
		$ft->assign("FUNC_NAME", $functions[$i]);
		$ft->parse("GETFUNCTIONSVAR", ".getfunctionsvar");
	}

	$ft->define_dynamic("setfunctionsvar","main");

	for ($i = 0; $i < $function_nr; $i ++) {
		$ft->assign("CLASSVAR",$all_url_vars['varname']);
		$ft->assign("FUNC_NAME",$functions[$i]);
		$ft->parse("SETFUNCTIONSVAR",".setfunctionsvar");
	}

	// for class
	$ft->define_dynamic("row","main");

	for ($i=0;$i<$nr;$i++) {
		$ft->assign("VARIABLES",$fields[$i]);
		$ft->parse("ROW",".row");
	}

	$ft->assign("IDCLASS",$fields[0]);

	$ft->define_dynamic("value","main");

	for ($i=1;$i<$nr;$i++) {
		$ft->assign("FIELD_NAME",$fields[$i]);
		$ft->parse("VALUE",".value");
	}

	$ft->define_dynamic("nullvalue","main");

	for ($i=1;$i<$nr;$i++) {
		$ft->assign("FIELD_NAME",$fields[$i]);
		$ft->parse("NULLVALUE",".nullvalue");
	}

	$ft->define_dynamic("getfunctions","main");

	for ($i=0;$i<$function_nr;$i++) {
		$ft->assign("VAR_NAME",$fields[$i]);
		$ft->assign("FUNC_NAME",$functions[$i]);
		$ft->parse("GETFUNCTIONS",".getfunctions");
	}

	$ft->define_dynamic("setfunctions","main");

	for ($i = 0; $i < $function_nr; $i++) {
		$ft->assign("VAR_NAME",$fields[$i]);
		$ft->assign("FUNC_NAME",$functions[$i]);
		$ft->parse("SETFUNCTIONS",".setfunctions");
	}

	$buffer="";

	for ($i=1;$i<$nr;$i++) {
		$buffer.="'\".";
		$buffer.="\$this->slashes(\$this->".$fields[$i];
		$buffer.=").\"'";
		if($i<>$nr-1) {
			$buffer.=","; 
		}			  
	}

	$ft->assign("LIST_INSERT",$buffer);
	$buffer="";

	for ($i=1;$i<$nr;$i++) {
		$buffer .= "`";
		if($fields[$i]==$fields_en[$i])
		{
		$buffer .= "$fields[$i]`=";
		}
		else
		{
		$buffer .= "$fields[$i]_{\$this->lang}`=";
		}
		$buffer .= "'\"."; 
		$buffer .= "\$this->slashes(\$this->".$fields[$i];
		$buffer .= ").\"'";

		if($i<>$nr-1) {
			$buffer .= ","; 
		}			  
	}

	$ft->assign("LIST_UPDATE", $buffer);
}
//end php

elseif($all_url_vars['type']=="php5") //php5
{        

	// for variables
	$ft->define_dynamic("getfunctionsvar","main");

	for ($i=0;$i<$function_nr;$i++)
	{
		$ft->assign("CLASSVAR",$all_url_vars['varname']);
		$ft->assign("FUNC_NAME",$functions[$i]);
		$ft->parse("GETFUNCTIONSVAR",".getfunctionsvar");
	}

	$ft->define_dynamic("setfunctionsvar","main");

	for ($i=0;$i<$function_nr;$i++)
	{
		$ft->assign("CLASSVAR",$all_url_vars['varname']);
		$ft->assign("FUNC_NAME",$functions[$i]);
		$ft->parse("SETFUNCTIONSVAR",".setfunctionsvar");
	}

	// for class
	$ft->define_dynamic("row","main");

	for ($i=0;$i<$nr;$i++)
	{
		$ft->assign("TYPE","private");
		$ft->assign("VARIABLES",$fields[$i]);
		$ft->parse(ROW,".row");
	}

	$ft->assign("IDCLASS",$fields[0]);

	$ft->define_dynamic("value","main");

	for ($i=1;$i<$nr;$i++)
	{
		$ft->assign("FIELD_NAME",$fields[$i]);
		$ft->parse("VALUE",".value");
	}

	$ft->define_dynamic("nullvalue","main");

	for ($i=1;$i<$nr;$i++)
	{
		$ft->assign("FIELD_NAME",$fields[$i]);
		$ft->parse("NULLVALUE",".nullvalue");
	}

	$ft->define_dynamic("getfunctions","main");

	for ($i=0;$i<$function_nr;$i++)
	{
		$ft->assign("VAR_NAME",$fields[$i]);
		$ft->assign("FUNC_NAME",$functions[$i]);
		$ft->assign("GETFUNCTIONTYPE","public ");
		$ft->parse("GETFUNCTIONS",".getfunctions");
	}

	$ft->define_dynamic("setfunctions","main");

	for ($i=0;$i<$function_nr;$i++)
	{
		$ft->assign("VAR_NAME",$fields[$i]);
		$ft->assign("FUNC_NAME",$functions[$i]);
		$ft->assign("SETFUNCTIONTYPE","public ");
		$ft->parse("SETFUNCTIONS",".setfunctions");
	}

	$buffer="";

	for ($i=1;$i<$nr;$i++)
	{
		$buffer.="'\".";
		$buffer.="\$this->slashes(\$this->".$fields[$i];
		$buffer.=").\"'";
		if($i<>$nr-1)
			$buffer.=","; 			  
	}

	$ft->assign("LIST_INSERT",$buffer);
	$buffer="";

	for ($i=1;$i<$nr;$i++)
	{
		$buffer.="`";	
		$buffer.="$fields[$i]`=";
		$buffer.="'\".";
		$buffer.="\$this->slashes(\$this->".$fields[$i];
		$buffer.=").\"'";
		if($i<>$nr-1)
			$buffer.=","; 			  
	}

	$ft->assign("LIST_UPDATE",$buffer);
	$ft->assign("SAVEFUNCTIONTYPE","public ");
	$ft->assign("FDTYPE","public");
	$ft->assign("ENABLE_UNDO","true");
}//end php5

else //java 
{
	// for variables
	$ft->define_dynamic("getfunctionsvar","main");

	for ($i=0;$i<$function_nr;$i++)
	{
		$ft->assign("CLASSVAR",$all_url_vars['varname']);
		$ft->assign("FUNC_NAME",$functions[$i]);
		$ft->parse("GETFUNCTIONSVAR",".getfunctionsvar");
	}

	$ft->define_dynamic("setfunctionsvar","main");

	for ($i=0;$i<$function_nr;$i++)
	{
		$ft->assign("CLASSVAR",$all_url_vars['varname']);
		$ft->assign("FUNC_NAME",$functions[$i]);
		$ft->parse("SETFUNCTIONSVAR",".setfunctionsvar");
	}
	// for class

	$ft->define_dynamic("row","main");

	for ($i=0;$i<$nr;$i++)
	{
		$ft->assign("VARIABLES",$fields[$i]);
		if($variable_type[$i]=="string")
			$ft->assign("VARIABLES_TYPE","String");
		else
			$ft->assign("VARIABLES_TYPE","int");

		$ft->parse(ROW,".row");
	}

	$ft->assign("IDCLASS",$fields[0]);

	$ft->define_dynamic("value","main");

	for ($i=1;$i<$nr;$i++)
	{
		$ft->assign("FIELD_NAME",$fields[$i]);
		if($variable_type[$i]=="string")
			$ft->assign("TYPE","String");
		else
			$ft->assign("TYPE","Int");   
		$ft->parse("VALUE",".value");
	}

	$ft->define_dynamic("nullvalue","main");

	for ($i=0;$i<$nr;$i++)
	{
		$ft->assign("FIELD_NAME",$fields[$i]);

		if($variable_type[$i]=="string")
			$ft->assign("FIELD_NAME_VALUE","\"\"");
		else
			$ft->assign("FIELD_NAME_VALUE","0");  

		$ft->parse("NULLVALUE",".nullvalue");
	}

	$ft->define_dynamic("getfunctions","main");

	for ($i=0;$i<$function_nr;$i++)
	{
		$ft->assign("VAR_NAME",$fields[$i]);
		$ft->assign("FUNC_NAME",$functions[$i]);

		if($variable_type[$i]=="string")
			$ft->assign("FUNC_NAME_TYPE","String");
		else
			$ft->assign("FUNC_NAME_TYPE","int");   			


		$ft->parse("GETFUNCTIONS",".getfunctions");
	}

	$ft->define_dynamic("setfunctions","main");

	for ($i=0;$i<$function_nr;$i++)
	{
		$ft->assign("VAR_NAME",$fields[$i]);
		$ft->assign("FUNC_NAME",$functions[$i]);

		if($variable_type[$i]=="string")
			$ft->assign("FUNC_NAME_TYPE","String");
		else
			$ft->assign("FUNC_NAME_TYPE","int");   			

		$ft->parse("SETFUNCTIONS",".setfunctions");
	}

	$ft->define_dynamic("insert","main");

	for ($i=1;$i<$function_nr;$i++)
	{
		$ft->assign("VAR_NAME",$fields[$i]);

		if($variable_type[$i]=="string")
		{
			$ft->assign("ENTER","'");
			$ft->assign("VAR_NAME","su.changeMySql(this.$fields[$i])");

			if($i<>$nr-1) 
				$ft->assign("EXIT","',");
			else
				$ft->assign("EXIT","'");	 
		}
		else
		{
			$ft->assign("ENTER","");
			$ft->assign("VAR_NAME","this.$fields[$i]");

			if($i<>$nr-1) 
				$ft->assign("EXIT",",");
			else
				$ft->assign("EXIT","");	 
		}			

		$ft->parse("INSERT",".insert");
	}


	$ft->define_dynamic("update","main");

	for ($i=1;$i<$function_nr;$i++)
	{
		$ft->assign("VAR_NAME",$fields[$i]);

		if($variable_type[$i]=="string")
		{
			$ft->assign("ENTER","$fields[$i]='");
			$ft->assign("VAR_NAME","su.changeMySql(this.$fields[$i])");

			if($i<>$nr-1) 
				$ft->assign("EXIT","',");
			else
				$ft->assign("EXIT","'");	 
		}
		else
		{
			$ft->assign("ENTER","$fields[$i]=");
			$ft->assign("VAR_NAME","this.$fields[$i]");

			if($i<>$nr-1) 
				$ft->assign("EXIT",",");
			else
				$ft->assign("EXIT","");	 
		}			

		$ft->parse("UPDATE",".update");
	}

}
$ft->parse("BODY", array("content","main"));
//$ft->FastPrint();



$session->set("functions",   $functions);
$session->set("fields",      $fields);
$session->set("author",      $all_url_vars['author']);
$session->set("projectname", $all_url_vars['projectname']);
$session->set("date",        date("Y:m:d"));
$session->set("variable",    $all_url_vars['varname']);
$session->set("tablename",   $all_url_vars['table']);

$result_php=$ft->fetch("BODY");

$fp = fopen(GEN_INCLUDE_PATH.strtolower($all_url_vars['classname']), 'w');

if ($fp){
	fwrite($fp, $result_php);
	fclose($fp);
	
	$beautify = new PhpBeautifier();
	$beautify -> tokenSpace = true;//put space between tokens
	$beautify -> blockLine = true;//put empty lines between blocks of code (if, while etc)
	//$beautify -> optimize = true;//optimize strings (for now), if a double quoted string does not contain variables of special carachters transform it to a single quoted string to save parsing time
	$beautify -> file( GEN_INCLUDE_PATH.strtolower($all_url_vars['classname']), GEN_INCLUDE_PATH.strtolower($all_url_vars['classname']));
	// write phps
	//fwrite($fps, $result_php);
	//fclose($fps);	
};
header('Location: generate_html.php');
?>
