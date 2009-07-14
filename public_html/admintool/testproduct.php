<?php
/*
CODE GENERATED BY: GRAFXSOFTWARE CODE GENERATOR
http://www.grafxsoftware.com
======================================
PHPFILE MADE BY: Test Developer
DATE: 2009:07:14
PROJECT: Test Product
======================================
*/
include_once( '../config.inc.php' );
include_once( INCLUDE_PATH . 'connection.php' );
include_once( INCLUDE_PATH . 'cls_fast_template.php' );
include_once( INCLUDE_LANGUAGE_PATH . $LANG . '.inc.php' );
include_once( INCLUDE_LANGUAGE_PATH . $LANG . '.admintool.inc.php' );
$stringutil = new String();
/**
* @author   - Test Developer
* @desc     - Secure the INT variables
* @vers     - 1.0
**/
$stringutil -> setIntVars( 'id' );
$stringutil -> setIntVars( 'termsagree' );
$stringutil -> setIntVars( 'active' );
$all_url_vars = $stringutil -> parse_all();
$all_url_vars[ 'id' ] = $stringutil -> hex_str( $all_url_vars[ 'id' ] );
/**
* @author   - Test Developer
* @desc     - autentication will be called here
* @vers     - 1.0
**/
$util = new Util();
$auth = new Authenticate();
$auth -> check_authentification();
/**
* @desc     - Call of Fast Template, with PATH from defines.inc.php
**/
$ft = new FastTemplate( ADMIN_TEMPLATE_CONTENT_PATH );// for upload image, we define the thumbnail width and heigth
// TODO: to move in other place
$thumb_width = 100;
$thumb_heigth = 75;

switch( $all_url_vars[ 'do' ] )
{
case 'del':
	/**
* @author   - Test Developer
* @desc     - This is the delete section
* @vers     - 1.0
**/
	if( !empty( $all_url_vars[ 'id' ] ) )
	{
		$testproduct = new TestProduct( $all_url_vars[ 'id' ], $LANG );
		$testproduct -> delete( $all_url_vars[ 'id' ] );
		header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
		exit;
	}
	
	break;
case 'activate':
	$testproduct = new TestProduct( $all_url_vars[ 'id' ], $LANG );
	
	if( $testproduct -> getactive() == 1 )
	{
		$testproduct -> setactive( 0 );
	}
	else
	{
		$testproduct -> setactive( 1 );
	}
	
	$testproduct -> save();
	header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
	exit;
	break;
case 'list':
	/**
* @author   - Test Developer
* @desc     - This is the listing section ?do=list
* @vers     - 1.0
* @TODO     - TH row seems not match with TD row.
**/
	$ft -> define( array( 'main' => 'template_index.html', 'content' => 'testproduct.html' ) );
	$Obj = new Listing();
	$Obj -> init_mysql( DB_PREFIX . 'product', array( id, name, categoryid, fileupload, active ) );// set the LIMIT on page, default is 15
	$limit = 15;//the number of page in the pagination bar
	$max = 4;
	$gpos = ( isset( $_GET [ 'page' ] ) ? $_GET[ 'page' ]/$limit : 0 );
	$Obj -> setLimit( $limit );
	$field = array( '{LANG_ADMIN_TESTPRODUCT_ID}', '{LANG_ADMIN_TESTPRODUCT_NAME}', '{LANG_ADMIN_TESTPRODUCT_CATEGORYID}', '{LANG_ADMIN_TESTPRODUCT_FILEUPLOAD}', );
	$Obj -> setFields( $field );
	$offset = $Obj -> getOffset( $gpos*$max );//set link css
	$Obj -> setFirstID( 'id' );
	$Obj -> setStyle( 'gotopage' );//enable active column on listing
	$Obj -> setActivateListing( 1 );//do not remove
	$ft -> assign( 'LISTING', '1' );
	
	if( $Obj -> listPages() != false )
	{
		$ft -> assign( 'CONTENT', $Obj -> listPages() );
		$tp = $_SERVER[ 'PHP_SELF' ] . '?do=list';
		$pagi = new Pagination();
		$pagi -> setNumberItems( $limit );
		$pagi -> setNumberPages( $max );
		$pagi -> setType( 2 );// We have only 1 temnplate "main" so we use that.
		$pagi -> setTemplate( 'content' );// This is the url we want to use in the iteration
		// Please be aweare of the other get variables that need to be kept.
		$pagi -> setUrl( $tp . '&page=' );
		$pagi -> make( $Obj -> getNumRows(), $gpos, $ft );
	}
	else
	{
		$ft -> assign( 'CONTENT', LANG_ADMIN_ERROR_INVALID_ID );
	}
	
	break;
case 'mod':
	/**
* @author   - Test Developer
* @desc     - This is the modification section
* @desc     - Will be called when you click on the modify button in the listing.
* @vers     - 1.0
**/
	$ft -> define( array( 'main' => 'template_index.html', 'content' => 'testproduct.html' ) );
	
	if( !empty( $all_url_vars[ 'id' ] ) )
	{
		if( $_SESSION[ 'err' ] )$ft -> assign( 'ERROR', '<div class="mError">' . $_SESSION[ 'err' ] . '</div>' );
	}// TODO: here we need to add REQUIRED only to assign it. NOT ALL.
	$ft -> assign( 'ID', $_SESSION[ 'id' ] );
	$ft -> assign( 'NAME', $_SESSION[ 'name' ] );
	$ft -> assign( 'DESCRIPTIONSHORT', $_SESSION[ 'descriptionshort' ] );
	$ft -> assign( 'DESCRIPTION', $_SESSION[ 'description' ] );
	$ft -> assign( 'CATEGORYID', $_SESSION[ 'categoryid' ] );
	$ft -> assign( 'ISTHISREAL', $_SESSION[ 'isthisreal' ] );
	$ft -> assign( 'TERMSAGREE', $_SESSION[ 'termsagree' ] );
	$ft -> assign( 'FILEUPLOAD', $_SESSION[ 'fileupload' ] );
	$ft -> assign( 'ACTIVE', $_SESSION[ 'active' ] );
	{
		$testproduct = new TestProduct( $all_url_vars[ 'id' ], $LANG );
		$ft -> assign( 'ID', $stringutil -> str_hex( $testproduct -> getid() ) );
		$ft -> assign( 'NAME', $testproduct -> getname() );
		$ft -> assign( 'DESCRIPTIONSHORT', $testproduct -> getdescriptionshort() );
		$ft -> assign( 'DESCRIPTION', $testproduct -> getdescription() );
		$ft -> assign( 'CATEGORYID', $util -> getDataWithSelectFromArray( $testproduct -> getcategoryid(), $d_categoryid ) );//If is from database, use below. $id_selected,$table,$id_column,$data_column
		//$ft->assign("CATEGORYID",$util->formatDataWithSelect($testproduct->getcategoryid,"DBTABLE","DBFIELD1_ID","DBFIELD2_WHATTOSHOW")    )());
		$ft -> assign( 'ISTHISREAL', $util -> getDataWithSelectFromArray( $testproduct -> getisthisreal(), $d_isthisreal ) );//If is from database, use below. $id_selected,$table,$id_column,$data_column
		//$ft->assign("ISTHISREAL",$util->formatDataWithSelect($testproduct->getisthisreal,"DBTABLE","DBFIELD1_ID","DBFIELD2_WHATTOSHOW")    )());
		$ft -> assign( 'SELCHBOX_TERMSAGREE',( $testproduct -> gettermsagree() == 1?'checked':'' ) );
		$labels = array();
		array_push( $labels, LANG_ADMIN_UPLOAD );//TODO: for multiple upload, need multiple name
		$ft -> assign( 'BROWSE_FILEUPLOAD', $util -> getUploadImagesHtml( 1, $testproduct -> getfileupload(), $labels, FU_CONF_UPLOADURL ) );
		$ft -> assign( 'SELECTEDRD_ACTIVE_1',( $testproduct -> getactive() == 1?'checked':'' ) );
		$ft -> assign( 'SELECTEDRD_ACTIVE_0',( $testproduct -> getactive() == 1?'':'checked' ) );
		$ft -> assign( '{' . strtoupper( 'id' ) . '}', $all_url_vars[ 'id' ] );
	}
	
	break;
case 'save':
	/**
* @author   - Test Developer
* @desc     - This is the save section
* @desc     - When you save an item, will be inserted into database all the fields value.
* @vers     - 1.0
**/
	// TODO: We need here to give back the errors. You can customize it. Ex: check if email, via a function (class).
	$err = 0;
	
	if( empty( $all_url_vars[ 'name' ] ) )
	{
		$err = LANG_ADMIN_TESTPRODUCT_VERIF_NAME;
	}
	
	if( !empty( $err ) )
	{
		$_SESSION[ 'id' ] = $all_url_vars[ 'id' ];
		$_SESSION[ 'name' ] = $all_url_vars[ 'name' ];
		$_SESSION[ 'descriptionshort' ] = $all_url_vars[ 'descriptionshort' ];
		$_SESSION[ 'description' ] = $all_url_vars[ 'description' ];
		$_SESSION[ 'categoryid' ] = $all_url_vars[ 'categoryid' ];
		$_SESSION[ 'isthisreal' ] = $all_url_vars[ 'isthisreal' ];
		$_SESSION[ 'termsagree' ] = $all_url_vars[ 'termsagree' ];
		$_SESSION[ 'fileupload' ] = $all_url_vars[ 'fileupload' ];
		$_SESSION[ 'active' ] = $all_url_vars[ 'active' ];
		$_SESSION[ 'err' ] = $err;
		header( 'Location: ' . $_SERVER[ 'HTTP_REFERER' ] );
	}
	else
	{
		$ft -> define( array( 'main' => 'template_index.html', 'content' => 'testproduct.html' ) );
		
		if( empty( $all_url_vars[ 'id' ] ) )
		{// value is empty, new items will be INSERTed
			$testproduct = new TestProduct( 0, $LANG );
		}
		else
		{// value have ID, all data will be UDPATEd
			$testproduct = new TestProduct( $all_url_vars[ 'id' ], $LANG );
		}
		
		$testproduct -> setid( $all_url_vars[ 'id' ] );
		$testproduct -> setname( $all_url_vars[ 'name' ] );
		$testproduct -> setdescriptionshort( $all_url_vars[ 'descriptionshort' ] );
		$testproduct -> setdescription( $all_url_vars[ 'description' ] );
		$testproduct -> setcategoryid( $all_url_vars[ 'categoryid' ] );
		$testproduct -> setisthisreal( $all_url_vars[ 'isthisreal' ] );
		$testproduct -> settermsagree( isset( $all_url_vars[ 'termsagree' ] )?1:0 );
		$upload_image_check = !empty( $all_url_vars[ 'check' ] );//TODO: De unde vine?
		if( !$upload_image_check )
		{
			$testproduct -> setfileupload( $util -> uploadAllImages( 'TestProduct', FU_CONF_UPLOADDIR ) );
		}
		
		$testproduct -> setactive( $all_url_vars[ 'active' ] );
		$testproduct -> save();
		
		if( $upload_image_check )
		{// TODO review for custom field names and ONLY if upload field
			$fu = new FileUpload( $_FILES[ 'imagefile_0' ] );
			$fu -> setSave_name( 'tmp_' . substr( md5( '0_' . date( 'Y-m-d H:i:s' ) . $_FILES[ 'imagefile_0' ][ 'name' ] ), 0, 12 ) );
			$fu -> setSave_path( FU_CONF_UPLOADDIR );//pre_resize start
			$width = '';
			$height = '';
			$extension = $fu -> getExt();
			$input_image = FU_CONF_UPLOADDIR . $fu -> getSave_name() . '.' . $extension;
			move_uploaded_file( $_FILES[ 'imagefile_0' ][ 'tmp_name' ], $input_image ) ;
			@chmod( $input_image, 0666 );
			list( $width, $height, $type, $attr ) = getimagesize( $input_image );
			$tmp_save_name = $fu -> getSave_name() . '.' . $fu -> getExt();
			$tmp_save_path = FU_CONF_UPLOADDIR . '/' . $tmp_save_name;
			$im_crop = new ImageCrop( $tmp_save_path );
			$im_crop -> setSave_name( $tmp_save_path );
			$im_crop -> setSave_width( $width );
			$im_crop -> setSave_height( $height );
			
			if( $width > 800 )
			{
				$im_crop -> setSave_scale( 800/$width );
			}
			else
			{
				$im_crop -> setSave_scale( 1 );
			}
			
			if( ! $im_crop -> doResize() )
			{
				print_r( $im_crop -> getErrorMessage() );
				exit;
			}//pre_resize end
			$_SESSION[ 'uploaded_file' ] = $tmp_save_name;
			@header( 'Location: ' . $_SERVER[ 'PHP_SELF' ] . '?do=crop_preview' );
			exit;
		}
		
		;
		unset( $_SESSION[ 'err' ] );
		unset( $_SESSION[ 'id' ] );
		unset( $_SESSION[ 'name' ] );
		unset( $_SESSION[ 'descriptionshort' ] );
		unset( $_SESSION[ 'description' ] );
		unset( $_SESSION[ 'categoryid' ] );
		unset( $_SESSION[ 'isthisreal' ] );
		unset( $_SESSION[ 'termsagree' ] );
		unset( $_SESSION[ 'fileupload' ] );
		unset( $_SESSION[ 'active' ] );// TODO: add a return message if file upload not succeed or any other message can happening
		// on error return to modify on success only to listing.
		@header( 'Location: ' . $_SERVER[ 'PHP_SELF' ] . '?do=list' );
		exit;
	}//end else
	break;// TODO move into IF file upload exist, generate, else not.
case 'crop_preview':
	
	if( !empty( $_SESSION[ 'uploaded_file' ] ) )
	{
		$ft -> define( array( 'main' => 'template_index.html', 'content' => 'upload_preview.html' ) );
		$ft -> assign( 'PIC', FU_CONF_UPLOADURL . $_SESSION[ 'uploaded_file' ] );
		$ft -> assign( 'TH_WIDTH', $thumb_width );
		$ft -> assign( 'TH_HEIGTH', $thumb_heigth );
		list( $width, $height, $type, $attr ) = getimagesize( FU_CONF_UPLOADDIR . $_SESSION[ 'uploaded_file' ] );
		$ft -> assign( 'IMG_WIDTH', $width );
		$ft -> assign( 'IMG_HEIGTH', $height );
		$ft -> assign( 'ASPECT_RATIO', '4:3' );
		$ft -> assign( 'PHP_FILE_SAVE', $_SERVER[ 'PHP_SELF' ] );
	}
	else
	{
		$ft -> define( array( 'main' => 'template_index.html', 'content' => 'error.html' ) );
		$ft -> assign( 'ERROR_MESSAGE', 'ERROR OCURED' );
	}
	
	break;
case 'save_crop'://Get the new coordinates to crop the image.
	$x1 = $_POST [ 'x1' ];
	$y1 = $_POST [ 'y1' ];
	$x2 = $_POST [ 'x2' ];
	$y2 = $_POST [ 'y2' ];
	$w = $_POST [ 'w' ];
	$h = $_POST [ 'h' ];
	$thumb_name = FU_CONF_UPLOADDIR . 'thumb_' . $_SESSION[ 'uploaded_file' ];
	$im_crop = new ImageCrop( FU_CONF_UPLOADDIR . $_SESSION[ 'uploaded_file' ] );//Scale the image to the thumb_width set above
	$im_crop -> setSave_scale( $thumb_width / $w );
	$im_crop -> setSave_name( $thumb_name );
	$im_crop -> setSave_width( $w );
	$im_crop -> setSave_height( $h );
	$im_crop -> setStart_width( $x1 );
	$im_crop -> setStart_height( $y1 );
	
	if( ! $im_crop -> doResize() )
	{
		print_r( $im_crop -> getErrorMessage() );//TODO print_r?
		exit;
	}
	
	$im_crop -> setSave_scale( 1 );
	$im_crop -> setSave_name( FU_CONF_UPLOADDIR . $_SESSION[ 'uploaded_file' ] );
	
	if( ! $im_crop -> doResize() )
	{
		print_r( $im_crop -> getErrorMessage() );//TODO print_r?
		exit;
	}
	
	@header( 'Location: ' . $_SERVER[ 'PHP_SELF' ] . '?do=list' );
	exit;
	break;
	default:$ft -> define( array( 'main' => 'template_index.html', 'content' => 'testproduct.html' ) );
	$_SESSION[ 'REFERRER' ] = $all_url_vars[ 'HTTP_REFERER' ];
	die( LANG_ADMIN_ERROR_OCCURED );// add below your own programming.
	// for example if you need a dropdown, you will need to call it here.
}// static part
$ft -> assign( 'TITLE_TAG', '' );
$ft -> assign( 'PUBL_IMAGE_URL', PUBL_IMAGE_URL );
$ft -> assign( 'MAX_FILE_SIZE', MAX_FILE_SIZE );
$ft -> multiple_assign_define( 'LANG_' );
$ft -> multiple_assign_define( 'CONF_' );//sidebar
$sb = new Sidebar();
$ft -> assign( 'SIDEBAR', $sb -> getSideBar() );
$ft -> parse( 'BODY', array( 'content', 'main' ) );
$ft -> showDebugInfo( ERROR_DEBUG );
$ft -> FastPrint();
?>