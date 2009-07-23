<?php

include_once("./config.inc.php");
include_once(INCLUDE_PATH."cls_fast_template.php");
include_once(INCLUDE_PATH."cls_string.php");
include_once (INCLUDE_PATH . "cls_xml.php");
include_once (INCLUDE_PATH . "cls_session.php");


$stringutil = new String();
$session = new MYSession ( );

$field_types = array(
    "primary",
    "key",
    "index",
    "unique",
    "fulltext",
    "foreign",
    "check"
);

$all_url_vars = $stringutil->parse_all();

$ft = new FastTemplate(TEMPLATE_PATH);
$ft->define(array(
    "main"    => "template_body.html",
    "content" => "generate.html"
    )
);

if (!empty($all_url_vars['table'])) {
    $ft->assign("TABLE", $firsttok);
    $all_url_vars['table'] = $stringutil->changeBRtoEnter($all_url_vars['table']);
    $all_url_vars['table'] = strtolower($all_url_vars['table']);

    $tok = explode(",", $all_url_vars['table']);

    //print_r($tok);
    //print $stringutil->changeBRtoEnter($all_url_vars['table']); exit;

    $toknr    = sizeof($tok);
    $fpos     = strpos($tok[0], "(");
    $firsttok = substr($tok[0], 0, $fpos);
    $firsttok = strtolower($firsttok);
    $firsttok = str_replace(" ", "", $firsttok);
    $firsttok = str_replace("createtable", "", $firsttok);

    if($firsttok[0]=="`") {
        $firsttok=substr($firsttok ,1, strlen($firsttok));
    }
    if($firsttok[strlen($firsttok) - 1] == "`") {
        $firsttok=substr($firsttok, 0, strlen($firsttok) -1);
    }
    $ft->assign("DEV_NAME","Test Developer");
    $ft->assign("PROJECT_NAME","Test Product");
    $ft->assign("CLASS_NAME","TestProduct");
    $ft->assign("VAR_NAME","testproduct");
    $ft->assign("DISABLE_SOME_FIELDS",'disable="disabled"');

    //FIELDS
    $fields = "";

    for($i = 0; $i < $toknr; $i ++) {
        if($i==0) {
            $buffer  = substr($tok[0], $fpos + 1);
            $fbuffer = explode(" ", ltrim($buffer));
        } else {
            $fbuffer = explode(" ",ltrim($tok[$i]));
        }

        $fbnr  = sizeof($fbuffer);
        $z     = 0;


        while(empty($fbuffer[$z]) or $fbuffer[$z] == "`") {
            $z++;
        }

        $field=$fbuffer[$z];

        if($field[0] == "`") {
            $field=substr($field, 1 ,strlen($field));
        }

        if($field[strlen($field) - 1] == "`") {
            $field = substr($field, 0, strlen($field) - 1);
        }

        if(!in_array($field, $field_types)) {
            $fields .= $field . ";";
        }
    }    
    $example    = "";


    for($i = 0; $i < $tokenr-1; $i++) {
        $example.=ucwords($tokexample[$i]);
        $example.=";";
    }

}else{

    
    $xmlfilename=$all_url_vars['xmlfile'];
    if (!empty($xmlfilename)) {
        $xml=new Xml();
        $xml->setXMLFile(INDEX_PATH."/schema/".$xmlfilename);
        $xmlarray=$xml->loadArray();
        $session->set('XMLDATA',$xmlarray);
        
//        print(__FILE__." : ".__LINE__.'<br> <pre>');var_dump($_SESSION);print('</pre><br><br>');exit;//TODO: remove print $_SESSION
        
        $fields="";
        $example="";
        foreach ($xmlarray['data'] as $item) {
            if (!empty($fields)) $fields.=";";
            $fields.=$item['name'];
            if (!empty($example)) $example.=";";
            $example.=$item['function'];
        };
        $ft->assign("DEV_NAME",$xmlarray['name']);
        $ft->assign("PROJECT_NAME",$xmlarray['project_name']);
        $ft->assign("CLASS_NAME",$xmlarray['class_name']);
        $ft->assign("VAR_NAME",$xmlarray['var_name']);
        $ft->assign("TABLE", $xmlarray['table_name']);
        $ft->assign("DISABLE_SOME_FIELDS",' ');
    }else{
        header("Location: index.php");
        exit;
    }
};


$ft->assign("DBFIELDS_EN",$fields);

$fields_noen=str_replace("_en","",$fields);
$ft->assign("DBFIELDS",$fields_noen);

$tokexample = explode(";", $fields_noen);
$tokenr     = sizeof($tokexample);

$ft->assign("EXAMPLE",$example);
$ft->parse("BODY", array("content", "main"));

print $ft->fetch("BODY");


?>