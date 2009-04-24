<?php
/*
CODE GENERATOR BY: GRAFXSOFTWARE CODE GENERATOR
http://www.grafxsoftware.com
======================================
FILE MADE BY: Valics Lehel
PROJECT: CodeFaster
DESCRIPTION: This file will create all the HTML and PHP 
file, except the class.
======================================
*/
include_once ("./config.inc.php");
//include_once (INDEX_PATH . "public_html/config.inc.php");
//include_once (INDEX_PATH . "public_html/include/connection.php");
include_once (INCLUDE_PATH . "cls_xml.php");
include_once (INCLUDE_PATH . "cls_fast_template.php");
include_once (INCLUDE_PATH . "cls_string.php");
include_once (INCLUDE_PATH . "cls_session.php");
include_once (INCLUDE_PATH . "PhpBeautifier.inc.php");

$stringutil = new String ( );
$session = new MYSession ( );
$xml = new Xml ( );

$all_url_vars = $stringutil->parse_all ();

$action = $all_url_vars ["action"];
$items = $all_url_vars ['items'];

/**
 * @author   - Kiss Szilard
 * @desc     - 
 * @vers     - 1.0
 **/
$functions = $session->get ( "functions", $functions );
$fields = $session->get ( "fields", $fields );
$projectname = $session->get ( "projectname" );
$author = $session->get ( "author" );
$date = $session->get ( "date" );
$name = $session->get ( "name" );
$classvar = $session->get ( "classvar" );

/**
 * @author   - Valics Lehel
 * @desc     - Lower and upper the project name (projectname field). 
 * @ex       - testproduct, TESTPRODUCT
 * @vers     - 1.0
 **/
$NAME = strtolower ( $session->get ( "name" ) );
$NAMEUPPER = strtoupper ( $session->get ( "name" ) );

/**
 * @author   - Pavel 
 * @desc     - Validation rules, LANG variables for ADMIN
 * @vers     - 1.0
 **/
$_validation_rules = array ('mix' => "#_LANG_ADMIN_VALIDATION_MIN_#", 'max' => "#_LANG_ADMIN_VALIDATION_MAX_#", 'mixlength' => "#_LANG_ADMIN_VALIDATION_MINLENGTH_#", 'maxlength' => "#_LANG_ADMIN_VALIDATION_MAXLENGTH_#", 'email' => "#_LANG_ADMIN_VALIDATION_EMAIL_#", 'required' => "#_LANG_ADMIN_VALIDATION_REQUIRED_#" );

if (empty ( $fields )) {
	echo "Error on fields";
	exit ();
}

$ft = new FastTemplate ( TEMPLATE_PATH );
$fthtml = new FastTemplate ( TEMPLATE_PATH );

if ($action == "continue_selection") {
	if (empty ( $items )) {
		echo "Error on items";
		exit ();
	}
	
	$ft->define ( array ("main" => "template_body.html", "content" => "html2.html" ) );
	$ft->define_dynamic ( "dbitem", "content" );
	$ft->define_dynamic ( "dbactivelisting", "content" );
	// set to session which will be listed
	$session->set ( "listing", $all_url_vars ['listing'] );
	// set to session which will be required from checkbox
	$session->set ( "required", $all_url_vars ['required'] );
	// set to session which values will be the require IF is set
	$session->set ( "requiredset", $all_url_vars ['requiredset'] );

} elseif ($action == "generate_html") {
	$items = $session->get ( "items" );
	if (empty ( $items )) {
		echo "Error on items";
		exit ();
	}
	$ft->define ( array ("main" => "template_body.html", "content" => "html3.html" ) );
	$fthtml->define ( array ("main" => "t_html_generator.html", "content" => "t_html_generator.html", "main_user" => "t_html_user_generator.html" ) );
	$fthtml->define_dynamic ( "formelements", "main" );

} else {
	$ft->define ( array ("main" => "template_body.html", "content" => "html1.html" ) );
	$ft->define_dynamic ( "dbitem", "content" );
}

$k = 0;
/**
 * @author   - Kiss Szilard, Valics Lehel, Elteto Zoltan, Pavel, Paul Nasca
 * @desc     - Starting to create the form for the ADMIN
 * @vers     - 1.0
 **/
foreach ( $fields as $field ) {
	$ft->assign ( "NDBITEM", $k );
	$ft->assign ( "DBITEM", $field );
	$item = $items [$field];
	
	$fthtml->define_dynamic ( "fckelements", "main" );
	
	if ($action == "generate_html") {
		
		$fielddata = "";
		// FIELDNAME to UPPERCASE, Use $strupperfield in other places.
		$strupperfield = strtoupper ( $field );
		
		$required_all = $session->get ( "required" );
		$requiredset_all = $session->get ( "requiredset" );
		$required = $required_all [$field];
		
		$requiredsets = array ();
		if ($requiredset_all [$field]) {
			$requiredsets = explode ( ',', $requiredset_all [$field] );
		}
		
		// reset the class and abbr value.
		$class_value = "";
		$abbr_value = "";
		
		if ($required) {
			$requiredsets [] = "required";
			// By marking up abbreviations you can give useful information to browsers, 
			// spellcheckers, screen readers, translation systems and search-engines.
			$abbr_value = "<abbr title='#_LANG_ADMIN_{$NAMEUPPER}_REQUIRED_#'>*</abbr>";
		}
		
		$validate_rules = array ();
		$validate_messages = array ();
		
		/**
		 * @author   - Pavel
		 * @desc     - Create the required message and classes fot the jQuery form.
		 * @vers     - 1.0
		 **/
		if ($requiredsets) {
			foreach ( $requiredsets as $requiredset ) {
				list ( $rule, $condition ) = explode ( ":", trim ( $requiredset ) );
				
				if (isset ( $_validation_rules [$rule] )) {
					$validate_rules [] = $rule . ":" . ($condition ? $condition : "true");
					$validate_messages [] = $rule . ":'" . $_validation_rules [$rule] . "'";
				}
			}
			
			if ($validate_rules) {
				$validate_rules = implode ( ", ", $validate_rules );
				$validate_messages = implode ( ", ", $validate_messages );
				$class_value = "{" . $validate_rules . ", messages: {" . $validate_messages . "}}";
			}
		}
		// FCK editor selection
		if ($all_url_vars ["item_textarea"] [$field] == "with_editor" || $all_url_vars ["item_textarea"] [$field] == "with_advanced_editor") {
			$class_value .= " fck";
		}
		
		if ($class_value) {
			$class_value = 'class="' . $class_value . '"';
		}
		
		// each form element DIV LABEL START and END
		$divstart = "<div><label for='{$field}'>#_LANG_ADMIN_{$NAMEUPPER}_{$strupperfield}_# {$abbr_value}</label>";
		$divend = "</div>";
		
		switch ($item) {
			// generate form hidden element
			case 'hidden' :
				$fielddata = "<input name='{$field}' id='{$field}' type='hidden' value='#_" . strtoupper ( $field ) . "_#' />";
				break;
			
			// generate form radio button element	
			case 'radio' :
				$fielddata = $divstart . "<input name=\"" . $field . "\" id=\"" . $field . "\" value=\"1\" type=\"radio\" title=\"#_LANG_ADMIN_" . $NAMEUPPER . "_" . "VERIF_" . $strupperfield . "_#\" $class_value #_SELECTEDRD_" . strtoupper ( $field ) . "_1_# />#_LANG_ADMIN_" . $NAMEUPPER . "_YES_#";
				$fielddata .= "<input name=\"" . $field . "\" id=\"" . $field . "\" value=\"0\" type=\"radio\" #_SELECTEDRD_" . strtoupper ( $field ) . "_0_# />#_LANG_ADMIN_" . $NAMEUPPER . "_NO_#" . $divend;
				break;
			
			// generate form dropdown element	
			case 'dropdown' :
				//TODO size="5" multiple="multiple"
				$fielddata = $divstart . "<select name=\"" . $field . "\" id=\"" . $field . "\" title=\"#_LANG_ADMIN_" . $NAMEUPPER . "_VERIF_" . $strupperfield . "_#\" " . $class_value . ">
					<option value=\"0\">(#_LANG_ADMIN_" . $NAMEUPPER . "PLEASECHOOSE" . $strupperfield . "_#)</option>
					#_" . $strupperfield . "_#</select>" . $divend;
				break;
			
			// generate form textfield element
			case 'textfield' :
				//TODO type="password"
				$fielddata = $divstart . "<input name=\"" . $field . "\" id=\"" . $field . "\" type=\"text\" value=\"#_" . strtoupper ( $field ) . "_#\" title=\"#_LANG_ADMIN_" . $NAMEUPPER . "_VERIF_" . $strupperfield . "_#\" " . $class_value . " />" . $divend;
				break;
			
			// generate form textarea element
			case 'textarea' :
				if ($all_url_vars ["item_textarea"] [$field] != "without_editor")
					$fck_toolbar = "";
				if ($all_url_vars ["item_textarea"] [$field] == "with_editor")
					$fck_toolbar = "{toolbar:'Basic'}";
				if ($all_url_vars ["item_textarea"] [$field] == "with_advanced_editor")
					$fck_toolbar = "{toolbar:'Default'}";
				
				$fthtml->assign ( "FCK_FIELD", $field );
				$fthtml->assign ( "FCK_TOOLBAR", $fck_toolbar );
				$fthtml->parse ( "FCKELEMENTS", ".fckelements" );
				
				$fielddata = $divstart . "<textarea name=\"" . $field . "\" id=\"" . $field . "\" " . $class_value . " title=\"#_LANG_ADMIN_" . $NAMEUPPER . "_VERIF_" . $strupperfield . "_#\" cols=\"50\" rows=\"6\" style=\"width:600px;\">#_" . $strupperfield . "_#</textarea>" . $divend;
				break;
			
			// generate form browse (upload) element
			case 'browse' :
				$fielddata = $divstart . "<input name=\"" . $field . "\" id=\"" . $field . "\" type=\"file\" title=\"#_LANG_ADMIN_" . $NAMEUPPER . "_VERIF_" . $strupperfield . "_#\" " . $class_value . " /><br /><img src=\"#_PUBL_IMAGE_URL_##_" . $strupperfield . "_#\" />" . $divend;
				break;
			
			// generate form checkbox element
			case 'checkbox' :
				$fielddata = $divstart . "<input name=\"" . $field . "\" id=\"" . $field . "\" type=\"checkbox\" value=\"1\"title=\"#_LANG_ADMIN_" . $NAMEUPPER . "_VERIF_" . $strupperfield . "_#\" " . $class_value . " #_SELCHBOX_" . strtoupper ( $field ) . "_# />" . $divend;
				break;
		}
		// end form elements
		

		$fthtml->assign ( "FORMELEMENT", $fielddata ); // in a DYNAMIC BLOCK will generate all the form data. t_html_generator.html
		//$fthtml->assign ( "FIELDNAME", $field ); //seems not used anymore, lower case fieldnames.
		//$fthtml->assign ( "LANG_FIELDNAME", "#_LANG_ADMIN_" . $NAMEUPPER . "_" . strtoupper ( $field ) . "_#" );
		$fthtml->assign ( "AUTHOR", $author );
		$fthtml->assign ( "DATE", $date );
		$fthtml->assign ( "PROJECT_NAME", $projectname ); // with space
		$fthtml->assign ( "PROJECT_BASE_FILENAME", $session->get ( "name" ) ); // same as PROJECT_NAME but NO SPACE
		$fthtml->assign ( "PROJECT_BASE_FILENAME_LOWER", $NAME ); // same as PROJECT_NAME but lowercase, use when no alteration required.
		$fthtml->assign ( "PROJECT_NAME_HTML", "#_LANG_ADMIN_" . $NAMEUPPER . "_#" ); // LANG_ADMIN_MAINNAME
		$fthtml->assign ( "PROJECT_NAME_HELP", "#_LANG_ADMIN_" . $NAMEUPPER . "_HELP_#" ); //help legend 
		$fthtml->assign ( "PROJECT_GO", "#_LANG_ADMIN_SUBMIT_#" ); //button for GO - SUBMIT
		$fthtml->assign ( "CONF_INDEX_URL", "#_CONF_INDEX_URL_#" ); //full URL for some elements like Javascript CSS etc.
		$fthtml->assign ( "CONTENTEND", "\n<!-- END##IF -->\n" ); //content for listing
		$fthtml->parse ( "FORMELEMENTS", ".formelements" );
	
	} elseif ($action == "continue_selection") { //on step 3 the editor choose and radion button. TODO: choose button radio name
		switch ($item) {
			case 'hidden' :
				$data = "";
				break;
			case 'radio' :
				$data = "yes/no";
				break;
			case 'checkbox' :
				$data = "";
				break;
			case 'dropdown' :
				$data = "";
				break;
			case 'textfield' :
				$data = "";
				break;
			case 'textarea' :
				$data = "	
					<select name='item_textarea[$field]'>
						<option value='without_editor'>Without editor</option> 
						<option value='with_editor'>With editor</option> 
						<option value='with_advanced_editor'>With advanced editor</option> 
					</select>
				";
				break;
			case 'browse' :
				$data = "";
				break;
		}
		;
		$ft->assign ( "ITEMSELECT", $data );
		$ft->assign ( "ITEMACTIVE", $field );
		
		$ft->parse ( "DBACTIVELISTING", ".dbactivelisting" );
		$ft->parse ( "DBITEM", ".dbitem" );
	} else {
		$ft->assign ( "DBITEM_TYPE", $field );
		$ft->parse ( "DBITEM", ".dbitem" );
	}
	$k = $k + 1;
}

if ($action == "continue_selection") {
	$session->set ( "items", $items );
}

//Write the module SQL insert.
//TODO: to write directly in the database
$str = "LANG_ADMIN_" . $NAMEUPPER;
$SQLTOPRINT = "INSERT INTO `modules` (`module_name` ,`availability` ,`position`,`filename` ,`extra_menu`) VALUES ( '$str', '1', '0','{$NAME}.php','') ON DUPLICATE KEY UPDATE `module_name`= '$str';";

/// show the links at the end of the job.
$ft->assign ( "LINKS", "
	<li>CLASS cls_{$NAME}.php</li>
	<li>ADMIN ADD/MODIFY <a href='public_html/admintool/{$NAME}.php?do=list' target='_blank'><strong>{$NAME}.php</strong></a></li>
	<li>HTML FILE ADMIN {$NAME}.html</li>
	<li>LANGUAGE VARIABLES {$NAME}.txt</li>
	<li>USER HOME PAGE example, ID 1 must exist. <a href='public_html/{$NAME}.php/{$NAME}/1/' target='_blank'><strong>{$NAME}.php/{$NAME}/1/</strong></a></li>
	<li>HTML FILE USER {$NAME}.html</li>
	<li>XML FILE <a href='schema/{$NAME}.xml' target='_blank'><strong>{$NAME}.xml</strong></a></li>
	<li><strong>Don't forget to INSERT the following SQL line (add prefix if you need):</strong><br /><code>{$SQLTOPRINT}</code></li>
" );

$ft->parse ( "BODY", array ("content", "main" ) );
$ft->FastPrint ();

if ($action == "generate_html") {
	
	/**
	 * @author   - Elteto Zoltan
	 * @desc     - Parse the #_SOMETHING_# to replace with {SOMETHING} and generate the HTML files
	 * @vers     - 1.0
	 **/
	$fp = fopen ( GEN_ADMIN_PRGTEMPLATES_PATH . $NAME . '.html', 'w' );
	if ($fp) {
		$fthtml->parse ( "MAIN", array ("main" ) );
		$outhtml = $fthtml->fetch ( "MAIN" );
		$outhtml = str_replace ( "#_", "{", $outhtml );
		$outhtml = str_replace ( "_#", "}", $outhtml );
		$outhtml = str_replace ( "##", "", $outhtml );
		fwrite ( $fp, $outhtml );
		fclose ( $fp );
	}
	
	$fp = fopen ( GEN_USER_PRGTEMPLATES_PATH . $NAME . ".html", 'w' );
	if ($fp) {
		$fthtml->define_dynamic ( "user_formelements", "main_user" );
		foreach ( $items as $key => $it ) {
			$fthtml->assign ( "USER_FORMELEMENT", "#_" . strtoupper ( $key ) . "_#" );
			$fthtml->assign ( "LANG_USER_FORMELEMENT", "#_LANG_{$NAMEUPPER}_" . strtoupper ( $key ) . "_#" );
			$fthtml->parse ( 'USER_FORMELEMENTS', ".user_formelements" );
		}
		$fthtml->assign ( "PROJECT_USER_NAME_HTML", "#_LANG_{$NAMEUPPER}_#" ); // LANG_MAINNAME
		$fthtml->assign ( "PROJECT_USER_NAME_HELP", "#_LANG_{$NAMEUPPER}_HELP_#" ); // help, description
		

		$fthtml->parse ( "MAIN", array ("main_user" ) );
		$outhtmluser = $fthtml->fetch ( "MAIN" );
		$outhtmluser = str_replace ( "#_", "{", $outhtmluser );
		$outhtmluser = str_replace ( "_#", "}", $outhtmluser );
		$outhtmluser = str_replace ( "##", "", $outhtmluser );
		fwrite ( $fp, $outhtmluser );
		fclose ( $fp );
		
		$beautify = new PhpBeautifier ( );
		$beautify->tokenSpace = true; //put space between tokens
		$beautify->blockLine = true; //put empty lines between blocks of code (if, while etc)
		//$beautify -> optimize = true;//optimize strings (for now), if a double quoted string does not contain variables of special carachters transform it to a single quoted string to save parsing time
		$beautify->file ( GEN_USER_PRGTEMPLATES_PATH . $NAME . ".html", GEN_USER_PRGTEMPLATES_PATH . $NAME . ".html" );
	
	} // end HTML generation
	

	$select_what = "";
	$listing = $session->get ( "listing" );
	
	/**
	 * @author   - Elteto Zoltan
	 * @desc     - 
	 * @vers     - 1.0
	 **/
	if (! empty ( $listing )) {
		//active_select
		// if we have an active member selected juts put it at the end of the listing
		if (! empty ( $all_url_vars ["active_select"] )) {
			if (in_array ( $all_url_vars ["active_select"], array_keys ( $listing ) ))
				unset ( $listing [$all_url_vars ["active_select"]] );
			
			$tmp_fld = $listing;
			$listing [$all_url_vars ["active_select"]] = "on";
		} else
			$tmp_fld = $listing;
		
		$select_what = implode ( ', ', array_keys ( $listing ) );
		
		$flds = "array(";
		foreach ( $tmp_fld as $key => $it ) {
			$flds .= '"#_LANG_ADMIN_' . $NAMEUPPER . "_" . strtoupper ( $key ) . '_#",';
		}
		if (! empty ( $all_url_vars ["active_select"] ))
			$flds .= '" ");';
		else
			$flds .= ');';
	
	}
	
	/**
	 * @author   - Pavel
	 * @desc     - Include to autoload some classes but commented out.
	 * @vers     - 1.0
	 **/
	//__autload
	$otherinclude = "";
	
	$modules = $all_url_vars ["mods"];
	foreach ( ( array ) $modules as $module ) {
		$otherinclude .= "//$" . strtolower ( $module ) . "= new " . $module . "();\r\n";
	}
	
	if ($select_what == "") {
		$select_what = "*";
	}
	
	/**
	 * @author   - Elteto Zoltan, Valics Lehel
	 * @desc     - Generate the PHP files
	 * @vers     - 1.0
	 **/
	$ftphp = new FastTemplate ( TEMPLATE_PATH );
	$ftphp->define ( array ("main" => "t_php_generator.html", "main_user" => "t_php_user_generator.html" ) );
	
	$ftphp->assign ( "PHPELEMENT", $fielddata );
	//$ftphp->assign ( "FIELDNAME", $field ); //seems not used anymore.
	$ftphp->assign ( "AUTHOR", $author );
	$ftphp->assign ( "DATE", $date );
	$ftphp->assign ( "NAME", $name );
	$ftphp->assign ( "CLASSVAR", $classvar );
	$ftphp->assign ( "PROJECT_NAME", $projectname );
	$ftphp->assign ( "PROJECT_BASE_FILENAME_UPPER", $NAMEUPPER );
	$ftphp->assign ( "PROJECT_BASE_FILENAME", $session->get ( "name" ) );
	$ftphp->assign ( "PROJECT_NAME_HTML", "#_LANG_ADMIN_HELP_" . strtoupper ( $field ) . "_#" ); // fieldset name
	$ftphp->assign ( "SELECT_WHAT", $select_what );
	$ftphp->assign ( "OTHER_INCLUDE_MODULES", $otherinclude );
	$ftphp->assign ( "FILENAME", $NAME . ".html" );
	$ftphp->assign ( "CLS", $session->get ( "name" ) );
	$ftphp->assign ( "VARR", "$" . $session->get ( "variable" ) );
	$ftphp->assign ( "FUNC_NAME1ELEMENT", $fields [0] );
	$ftphp->assign ( "FLDS", $flds );
	$ftphp->assign ( "TBL_NAME", $session->get ( "tablename" ) );
	$ftphp->assign ( "PROJECT_USER_BASE_FILENAME", $session->get ( "name" ) );
	
	//activate or not
	$ftphp->assign ( "ISACTIVATED", ! empty ( $all_url_vars ["active_select"] ) );
	$ftphp->assign ( "ACTIVE_ELEMENT", $all_url_vars ["active_select"] );
	
	$ftphp->define_dynamic ( "phpelements", "main" );
	$ftphp->define_dynamic ( "get_elements", "main" );
	$ftphp->define_dynamic ( "set_elements", "main" );
	$ftphp->define_dynamic ( "formelements", "main_user" );
	$ftphp->define_dynamic ( "errformelements", "main" );
	$ftphp->define_dynamic ( "errmodformelements", "main" );
	$ftphp->define_dynamic ( "unsetformelements", "main" );
	$ftphp->define_dynamic ( "langerrformelements", "main" );
	// for required fields in PHP error.
	$tmp_required = $session->get ( "required" );
	
	foreach ( $items as $key => $it ) {
		if ($it == "hidden" || $it == "checkbox" || $it == "radio" || $it == "select") {
			$ftphp->assign ( "FUNC_NAME", $key );
			$ftphp->parse ( 'PHPELEMENTS', ".phpelements" );
		}
		
		foreach ( $items as $key2 => $it2 ) {
			$ftphp->assign ( "IS" . strtoupper ( $it2 ), ($it2 == $it) ? 1 : 0 );
			$ftphp->assign ( "IS" . strtoupper ( $it2 ) . "_SAVE", ($it2 == $it) ? 1 : 0 );
		}
		// for required fields in PHP error.
		if (isset ( $tmp_required [$key] )) {
			
			$ftphp->assign ( "FORM_ELEMS_REQ", $key );
			$ftphp->assign ( "FORM_ELEMS_REQ_UPPER", strtoupper ( $key ) );
			$ftphp->parse ( "LANGFORMELEMENTS", ".langerrformelements" );
		}
		
		$ftphp->assign ( "FORM_ELEMS", $key );
		$ftphp->assign ( "FORM_ELEMSUPPER", strtoupper ( $key ) );
		//$ftphp->assign ( "FORM_ELEMUPPER", strtoupper ( $field ) );
		$ftphp->parse ( "GET_ELEMENTS", ".get_elements" );
		$ftphp->parse ( "SET_ELEMENTS", ".set_elements" );
		$ftphp->parse ( "FORMELEMENTS", ".formelements" );
		$ftphp->parse ( "ERRFORMELEMENTS", ".errformelements" );
		$ftphp->parse ( "ERRMODFORMELEMENTS", ".errmodformelements" );
		$ftphp->parse ( "UNSETFORMELEMENTS", ".unsetformelements" );
	
	}
	
	$ftphp->parse ( "BODY", array ("main" ) );
	
	$outphp = $ftphp->fetch ( "BODY" );
	$outphp = str_replace ( "#_", "{", $outphp );
	$outphp = str_replace ( "_#", "}", $outphp );
	
	if ($fp = fopen ( GEN_ADMIN_PATH . $NAME . ".php", 'w' )) {
		fwrite ( $fp,  $outphp  );
		fclose ( $fp );
		
		$beautify = new PhpBeautifier ( );
		$beautify->tokenSpace = true; //put space between tokens
		$beautify->blockLine = true; //put empty lines between blocks of code (if, while etc)
		//$beautify->optimize = true; //optimize strings (for now), if a double quoted string does not contain variables of special carachters transform it to a single quoted string to save parsing time
		$beautify->file ( GEN_ADMIN_PATH . $NAME . ".php", GEN_ADMIN_PATH . $NAME . ".php" );
	
	}
	
	$ftphp->parse ( "BODY", array ("main_user" ) );
	
	$outphp = $ftphp->fetch ( "BODY" );
	$outphp = str_replace ( "#_", "{", $outphp );
	$outphp = str_replace ( "_#", "}", $outphp );
	
	if ($fp = fopen ( GEN_USER_PATH . $NAME . ".php", 'w' )) {
		fwrite ( $fp, $outphp );
		fclose ( $fp );
	}
	
	/**
	 * @author   - Pavel
	 * @desc     - XML Generation, SCHEMA fles
	 * @vers     - 1.0
	 **/
	$xml_data = array ('name' => $name, 'projectname' => $projectname, 'author' => $author, 'date' => $date, 'classvar' => $classvar, 'modules' => $modules, 'functions' => $functions );
	
	foreach ( $fields as &$field ) {
		$field = array ('name' => $field, 'type' => isset ( $items [$field] ) ? $items [$field] : '', 'required' => isset ( $required_all [$field] ) ? $required_all [$field] : '', 'requiredset' => isset ( $requiredset_all [$field] ) ? $requiredset_all [$field] : '', 'listing' => isset ( $listing [$field] ) ? $listing [$field] : '' );
	}
	$xml_data ['fields'] = $fields;
	
	if ($fpxml = fopen ( XML_PATH . $NAME . ".xml", 'w' )) {
		fwrite ( $fpxml, $xml->saveArray ( $xml_data ) );
		fclose ( $fpxml );
	}
	
	/**
	 * @author   - Kiss Szili
	 * @desc     - LANG_ text generation with DEFINE.
	 * @vers     - 1.0
	 **/
	if ($fptxt = fopen ( GEN_LANGUAGE_PATH . $NAME . ".txt", 'w' )) {
		$data = $outhtml . $outhtmluser;
		$outxt = "";
		$i = 0;
		while ( $a !== false ) {
			$a = strpos ( $data, "{LANG" );
			$cor = substr ( $data, $a );
			$b = strpos ( $cor, "}" );
			$dupl = 0;
			
			for($k = 0; $k < $i; $k ++) {
				if ($dt [$k] == substr ( $cor, 1, $b - 1 )) {
					$dupl = 1;
				}
			}
			
			if (! $dupl && $a !== false) {
				$dt [$i] = substr ( $cor, 1, $b - 1 );
				$outxt .= "define('{$dt[$i]}', '{$session->get("name")}');\n";
				$i ++;
			}
			
			$data = substr ( $cor, $b + 2 );
		}
		
		fwrite ( $fptxt, $outxt );
		fclose ( $fptxt );
	}

}
?>