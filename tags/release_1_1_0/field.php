<?php

include_once("config.inc.php");
include_once(INCLUDE_PATH."cls_fast_template.php");
include_once(INCLUDE_PATH."cls_string.php");

session_start();

$stringutil   = new String();
$all_url_vars = $stringutil->parse_all();

$ft = new FastTemplate(TEMPLATE_PATH);
$ft->define(array(
		"main"    => "template_body.html", 
		"content" => "generate.html"
	)
);

if ($all_url_vars["action"] == "add") {
	$position   = strpos($_SESSION['dbuffer'], ";");
	$firstfield = substr($_SESSION['dbuffer'], 0, $position);

	if($all_url_vars["fields"] != $firstfield) {
		$goodbuffer = $all_url_vars["fields"] . ";";

		$tok   = explode(";", $_SESSION['dbuffer']);
		$toknr = sizeof($tok);

		for($i = 0; $i < $toknr; $i ++) {
			if(!empty($tok[$i])) {
				if($tok[$i] == $all_url_vars["fields"]) {
					$goodbuffer .= "";
				} else {
					$goodbuffer .=$tok[$i] . ";";
				}
			}			
		}
	} else if($all_url_vars["fields"]==$firstfield) {
		$goodbuffer=$_SESSION['dbuffer'];
	}
	
	$_SESSION['dbfields'] = $goodbuffer;

	$tokexample = explode(";", $goodbuffer);
	$tokenr     = sizeof($tokexample);

	$example="";
	for($i = 0; $i < $tokenr - 1; $i ++) {
		$example .= ucwords($tokexample[$i]) . ";";
	}

	$ft->assign("EXAMPLE",$example);
	$ft->assign("TABLE",    $_SESSION['table']);
	$ft->assign("DBFIELDS", $goodbuffer);
} 

$ft->parse("BODY", array("content", "main"));
print $ft->fetch("BODY");

?>