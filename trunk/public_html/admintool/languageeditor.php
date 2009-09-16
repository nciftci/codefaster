<?php
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
	 
    $input_dir=INDEX_PATH."language/";
    $input_files=array("en.inc.php","en.extra.inc.php");
    $output_file="en.extra.inc.php";

    $ft = new FastTemplate(ADMIN_TEMPLATE_CONTENT_PATH);
    $ft->define(array("main"=>"template_index.html", "content"=>"languageeditor.html"));
    
    $language_data=array();
    foreach ($input_files as $filename) {
        $input_filename=$input_dir.$filename;
        $file = fopen($input_filename,"r");
        if ($file){
            while (!feof($file) ) {
                $line=trim(fgets($file));
                if (stripos($line,"define")===0) {//if line contains "define"
                    if (preg_match('/define\(\"(.*?)\",\s*\"(.*)\"\)/i', $line, $matches)) {
                        $name=$matches[1];
                        $value=$matches[2];

                        $language_data[$name]=$value;
                    };

                   }
            };
            fclose($file);
        };
    };



    if (!empty($all_url_vars['language_text'])){//do the changes
        $edited_text=$all_url_vars['language_text'];
        $file_array=array();

        $file = fopen($input_dir.$output_file,"r");
        $inserted=false;
        if ($file){
            while (!feof($file) ) {
                $line=fgets($file);
                if ((!$inserted)&&(stripos(trim($line),"define")===0)){
                    $inserted=true;
                    foreach ($edited_text as $lang_key => $lang_value){
					   $lv=htmlspecialchars($lang_value);
                       $file_array[]="define(\"$lang_key\",\"$lv\");\n";
                    };
                }
                foreach ($edited_text as $lang_key => $lang_value){
                      if (strpos($line,"\"$lang_key\"")!==false){
                           $line=null;
                           break;
                      };
                };
                if ($line) $file_array[]=$line;
            };
            fclose($file);
        };
        
        $output_filename=$input_dir.$output_file;
        $file = fopen($output_filename,"w");
        if (!$file){
            die("Error writing output file : ".$output_filename);
        };
        foreach ($file_array as $line){
            fwrite($file,$line);
        };
        
        fclose($file);
        header("Location: languageeditor.php");
        exit;
    }

    if (empty($all_url_vars['language_checkbox'])) {
        $mode="first_page";
    } else {
        $mode="edit_page";
        $new_language_data=array();
        foreach ($all_url_vars['language_checkbox'] as $lang_key => $lang_value){
            $new_language_data[$lang_key]=$language_data[$lang_key];
        };
        $language_data=$new_language_data;

    };

    $ft->define_dynamic("row","content");
    foreach ($language_data as $lang_key => $lang_value){
        $ft->assign("LANGUAGE_KEY",$lang_key);

        if ($mode=="first_page") {
            $ft->assign("LANGUAGE_VALUE",htmlspecialchars_decode($lang_value));
            $ft->assign("LANGUAGE_CHECKBOX","<input type='checkbox' id='$lang_key' name='language_checkbox[$lang_key]' />");
        } else {
            $ft->assign("LANGUAGE_VALUE","<input type='text' id='$lang_key' name='language_text[$lang_key]' value='".htmlspecialchars_decode($lang_value)."' size='60' />");
            $ft->assign("LANGUAGE_CHECKBOX","");
        }
        $ft->parse("ROW",".row");
    };

	$ft->multiple_assign_define("LANG_");	
	$ft->multiple_assign_define("CONF_");	
	$ft->assign ( "SIDEBAR", $sb->getSideBar ());
	$ft->parse("BODY", array("content","main"));
	$ft->showDebugInfo(ERROR_DEBUG);
	$ft->FastPrint();

?>