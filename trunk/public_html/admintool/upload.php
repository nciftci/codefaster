<?php
session_start();
include_once( '../config.inc.php' );
include_once( INCLUDE_PATH . 'connection.php' );
include_once( INCLUDE_PATH . 'cls_fast_template.php' );
include_once( INCLUDE_LANGUAGE_PATH . $LANG . '.inc.php' );
include_once( INCLUDE_LANGUAGE_PATH . $LANG . '.admintool.inc.php' );
include_once(INCLUDE_PATH . "cls_image_crop.php");
//include_once(INCLUDE_PATH . "cls_description.php");

$stringutil = new String();
$all_url_vars = $stringutil->parse_all();
$thumb_name = $_SESSION["path"]."thumb_".$_SESSION["uploaded_file"];
$thumb_width = 100;
$thumb_heigth = 75;
$ft = new FastTemplate( ADMIN_TEMPLATE_CONTENT_PATH );
if(MULTILANGUAGE == 0) $ft->assign("MULTILANGUAGE",1);
if(SHOP == 1) $ft->assign("SHOP",1);


if(empty($all_url_vars["action"]))
    $ft->define(array("main"=>"template_index.html", "content"=>"upload.html"));
else if($all_url_vars["action"]=="preview")
{
    if(!empty($_SESSION["uploaded_file"]))
    {
        $ft->define(array("main"=>"template_index.html", "content"=>"upload_preview.html"));
        $ft->assign("PIC",$_SESSION["url"].$_SESSION["uploaded_file"]);

        $ft->assign("TH_WIDTH",$thumb_width);
        $ft->assign("TH_HEIGTH",$thumb_heigth);

        list($width, $height, $type, $attr) = getimagesize($_SESSION["path"].$_SESSION["uploaded_file"]);

        $ft->assign("IMG_WIDTH",$width);
        $ft->assign("IMG_HEIGTH",$height);
        $ft->assign("ASPECT_RATIO","4:3");
    }
    else
    {
        $ft->define(array("main"=>"template_index.html", "content"=>"error.html"));
        $ft->assign("ERROR_MESSAGE","ERROR OCURED");

    }
}
else if ($all_url_vars["action"]== "save" && isset ($all_url_vars["upload_thumbnail"] ))
    {
    //	print CONF_INDEX_URL."tmp/".$_SESSION["uploaded_file"];

        //Get the new coordinates to crop the image.
        $x1 = $_POST ["x1"];
        $y1 = $_POST ["y1"];
        $x2 = $_POST ["x2"];
        $y2 = $_POST ["y2"];
        $w = $_POST ["w"];
        $h = $_POST ["h"];
    //	print $y1;
    //	error_log(print_r($_POST,true));
        $im_crop = new ImageCrop ($_SESSION["path"].$_SESSION["uploaded_file"] );

        //Scale the image to the thumb_width set above
        $im_crop->setSave_scale ( $thumb_width / $w );

        $im_crop->setSave_name($thumb_name);
        $im_crop->setSave_width ( $w );
        $im_crop->setSave_height ( $h );
        $im_crop->setStart_width($x1);
        $im_crop->setStart_height($y1);

        if (! $im_crop->doResize ())
        {

            print_r ( $im_crop->getErrorMessage () );
            exit;
        }

        $im_crop->setSave_scale (1);
        $im_crop->setSave_name($_SESSION["path"].$_SESSION["uploaded_file"]);
        if (! $im_crop->doResize ())
        {

            print_r ( $im_crop->getErrorMessage () );
            exit;
        }

        switch ($_SESSION["module"])
        {
            case "category":
                $cat = new Category($_SESSION["id"]);
                $cat->setImage($_SESSION["uploaded_file"]);
                $cat->save();
                break;
            case "vendor":
                $vend = new Vendor($_SESSION["id"]);
                $vend->setLogo($_SESSION["uploaded_file"]);
                $vend->save();
                break;

            default:
                break;
        }
        $module = $_SESSION["module"];
        unset($_SESSION["uploaded_file"]);
        unset($_SESSION["id"]);
        unset($_SESSION["module"]);
        unset($_SESSION["path"]);
        unset($_SESSION["url"]);

        header ( "Location: ".$module.".php?do=list" );
        exit;

    }

else
{
    $ft->define(array("main"=>"template_index.html", "content"=>"error.html"));
    $ft->assign("ERROR_MESSAGE","ERROR OCURED");
}

$ft -> assign( 'TITLE_TAG', '' );
$ft->multiple_assign_define("LANG_");
$ft->multiple_assign_define("CONF_");
$sb = new Sidebar();
$ft -> assign( 'SIDEBAR', $sb -> getSideBar() );
$ft -> parse( 'BODY', array( 'content', 'main' ) );
$ft -> showDebugInfo( ERROR_DEBUG );
$ft -> FastPrint();

?>