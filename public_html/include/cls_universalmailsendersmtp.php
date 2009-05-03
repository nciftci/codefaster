<?php
/*
######################    CWB  PRO   #######################
############################################################
UniversalMailSender			$Name:  $
Revision		$Revision: 1.20 $
Author			$Author: zelteto
Created 03/01/03        $Date: 2007/03/14 12:30:59 $
Writed by               GraFX (webmaster@grafxsoftware.com)
Scripts Home:           http://www.grafxsoftware.com
############################################################
File purpose            CLASS MAILSENDER
############################################################
*/

/**
* UniversalMailSender
*
* @author zelteto
* @copyright Copyright GrafxSoftware (c) 2007
* @version $Id: cls_universal_mailsender.php,v 1.20 2007/03/14 12:30:59 zelteto Exp $
* @access public
*/

class UniversalMailSender {
    /**
    * the full email spec of the reciver "test user" <test@something.com>
    *
    * @var mail_to
    */
    protected $mail_to = "";
    /**
    * the full email spec of the sender "test user" <test@something.com>
    *
    * @var mail_from
    */

    protected $mail_from = "";

    /**
    * email of the reciver test@something.com
    *
    * @var mail_to_addr
    */
    protected $mail_to_addr = "";

    /**
    * email of the sender test@something.com
    *
    * @var mail_from_addr
    */
    protected $mail_from_addr = "";
    protected $mail_cc = "";
    protected $mail_bcc = "";
    /**
    * email of the sender test@something.com
    * this is required in some windows mailers and uses
    * the -f param to wrap in the message
    *
    * @var mail_sender
    */
    protected $mail_sender = "";
    protected $mail_error_to = "";
    protected $mail_reply_to = "";
    protected $mail_subject = "Empty Subject";
    protected $mail_xmailer = "UniversalMailSender";

    /**
    * default charset
    *
    * @var mail_charset
    */
    protected $mail_charset = "UTF-8";
    protected $mail_text_message = "Empty Text";
    protected $mail_html_message = "Empty Text";
    protected $mail_body = "";
    protected $mail_header = "";
    protected $mail_send_type;
    protected $mail_debug = "0";
    protected $mail_debug_message = "";
    protected $attachments = array();
    protected $attachments_img = array();
    protected $mixed_boundary;
    protected $related_boundary;
    protected $alternative_boundary;
    protected $error_pointer = 0;
    /**
    * error messages
    *
    * @var errors
    */

    protected $errors = array(1 => 'The mail is not valid',
        2 => 'Could not open file'

        );

    /**
    * mime types for the embended mail
    *
    * @var mime_types
    */

    protected $mime_types = array("ai" => "application/postscript",
        "aif" => "audio/x-aiff",
        "aifc" => "audio/x-aiff",
        "aiff" => "audio/x-aiff",
        "asc" => "text/plain",
        "au" => "audio/basic",
        "avi" => "video/x-msvideo",
        "bcpio" => "application/x-bcpio",
        "bin" => "application/octet-stream",
        "c" => "text/plain",
        "cc" => "text/plain",
        "ccad" => "application/clariscad",
        "cdf" => "application/x-netcdf",
        "class" => "application/octet-stream",
        "cpio" => "application/x-cpio",
        "cpt" => "application/mac-compactpro",
        "csh" => "application/x-csh",
        "css" => "text/css",
        "dcr" => "application/x-director",
        "dir" => "application/x-director",
        "dms" => "application/octet-stream",
        "doc" => "application/msword",
        "drw" => "application/drafting",
        "dvi" => "application/x-dvi",
        "dwg" => "application/acad",
        "dxf" => "application/dxf",
        "dxr" => "application/x-director",
        "eps" => "application/postscript",
        "etx" => "text/x-setext",
        "exe" => "application/octet-stream",
        "ez" => "application/andrew-inset",
        "f" => "text/plain",
        "f90" => "text/plain",
        "fli" => "video/x-fli",
        "gif" => "image/gif",
        "gtar" => "application/x-gtar",
        "gz" => "application/x-gzip",
        "h" => "text/plain",
        "hdf" => "application/x-hdf",
        "hh" => "text/plain",
        "hqx" => "application/mac-binhex40",
        "htm" => "text/html",
        "html" => "text/html",
        "ice" => "x-conference/x-cooltalk",
        "ief" => "image/ief",
        "iges" => "model/iges",
        "igs" => "model/iges",
        "ips" => "application/x-ipscript",
        "ipx" => "application/x-ipix",
        "jpe" => "image/jpeg",
        "jpeg" => "image/jpeg",
        "jpg" => "image/jpeg",
        "js" => "application/x-javascript",
        "kar" => "audio/midi",
        "latex" => "application/x-latex",
        "lha" => "application/octet-stream",
        "lsp" => "application/x-lisp",
        "lzh" => "application/octet-stream",
        "m" => "text/plain",
        "man" => "application/x-troff-man",
        "me" => "application/x-troff-me",
        "mesh" => "model/mesh",
        "mid" => "audio/midi",
        "midi" => "audio/midi",
        "mif" => "application/vnd.mif",
        "mime" => "www/mime",
        "mov" => "video/quicktime",
        "movie" => "video/x-sgi-movie",
        "mp2" => "audio/mpeg",
        "mp3" => "audio/mpeg",
        "mpe" => "video/mpeg",
        "mpeg" => "video/mpeg",
        "mpg" => "video/mpeg",
        "mpga" => "audio/mpeg",
        "ms" => "application/x-troff-ms",
        "msh" => "model/mesh",
        "nc" => "application/x-netcdf",
        "oda" => "application/oda",
        "pbm" => "image/x-portable-bitmap",
        "pdb" => "chemical/x-pdb",
        "pdf" => "application/pdf",
        "pgm" => "image/x-portable-graymap",
        "pgn" => "application/x-chess-pgn",
        "png" => "image/png",
        "pnm" => "image/x-portable-anymap",
        "pot" => "application/mspowerpoint",
        "ppm" => "image/x-portable-pixmap",
        "pps" => "application/mspowerpoint",
        "ppt" => "application/mspowerpoint",
        "ppz" => "application/mspowerpoint",
        "pre" => "application/x-freelance",
        "prt" => "application/pro_eng",
        "ps" => "application/postscript",
        "qt" => "video/quicktime",
        "ra" => "audio/x-realaudio",
        "ram" => "audio/x-pn-realaudio",
        "ras" => "image/cmu-raster",
        "rgb" => "image/x-rgb",
        "rm" => "audio/x-pn-realaudio",
        "roff" => "application/x-troff",
        "rpm" => "audio/x-pn-realaudio-plugin",
        "rtf" => "text/rtf",
        "rtx" => "text/richtext",
        "scm" => "application/x-lotusscreencam",
        "set" => "application/set",
        "sgm" => "text/sgml",
        "sgml" => "text/sgml",
        "sh" => "application/x-sh",
        "shar" => "application/x-shar",
        "silo" => "model/mesh",
        "sit" => "application/x-stuffit",
        "skd" => "application/x-koan",
        "skm" => "application/x-koan",
        "skp" => "application/x-koan",
        "skt" => "application/x-koan",
        "smi" => "application/smil",
        "smil" => "application/smil",
        "snd" => "audio/basic",
        "sol" => "application/solids",
        "spl" => "application/x-futuresplash",
        "src" => "application/x-wais-source",
        "step" => "application/STEP",
        "stl" => "application/SLA",
        "stp" => "application/STEP",
        "sv4cpio" => "application/x-sv4cpio",
        "sv4crc" => "application/x-sv4crc",
        "swf" => "application/x-shockwave-flash",
        "t" => "application/x-troff",
        "tar" => "application/x-tar",
        "tcl" => "application/x-tcl",
        "tex" => "application/x-tex",
        "texi" => "application/x-texinfo",
        "texinfo -  application/x-texinfo",
        "tif" => "image/tiff",
        "tiff" => "image/tiff",
        "tr" => "application/x-troff",
        "tsi" => "audio/TSP-audio",
        "tsp" => "application/dsptype",
        "tsv" => "text/tab-separated-values",
        "txt" => "text/plain",
        "unv" => "application/i-deas",
        "ustar" => "application/x-ustar",
        "vcd" => "application/x-cdlink",
        "vda" => "application/vda",
        "viv" => "video/vnd.vivo",
        "vivo" => "video/vnd.vivo",
        "vrml" => "model/vrml",
        "wav" => "audio/x-wav",
        "wrl" => "model/vrml",
        "xbm" => "image/x-xbitmap",
        "xlc" => "application/vnd.ms-excel",
        "xll" => "application/vnd.ms-excel",
        "xlm" => "application/vnd.ms-excel",
        "xls" => "application/vnd.ms-excel",
        "xlw" => "application/vnd.ms-excel",
        "xml" => "text/xml",
        "xpm" => "image/x-xpixmap",
        "xwd" => "image/x-xwindowdump",
        "xyz" => "chemical/x-pdb",
        "zip" => "application/zip"
        );

    /**
    * UniversalMailSender::UniversalMailSender()
    *
    *
    * 	$univMail= new UniversalMailSender("");
    *
    * 	$univMail->setToAddress( $to_address, $to_name);
    *
    * 	$univMail->setFromAddress($from_address, $from_name);
    *
    * 	$univMail->setSubject($subject);
    *
    *   $univMail->setCharset($charset);
    *
    * 	$univMail->setTextMessage($text_message="");
    *
    * 	$univMail->setHtmlMessage($html_message="");
    *
    * 	$univMail->setType($send_type="");
    *
    *   $univMail->setXmailer($xmailer="");
    *
    * 	$univMail->setSender($sender="");
    *
    * 	$univMail->setErrorTo($error_to="");
    *
    *   $univMail->setDebug($debug);
    *
    * 	$univMail->SendMail();
    *
    * 	$univMail->getDebugMessage();
    *
    * @param string $send_type if the parameter is empty then text mail will be sent othervise html
    */
    public function __construct($send_type = "")
    {
        if (empty($send_type) || $send_type == 1)
            $this->mail_send_type = 1; // text
        else
            $this->mail_send_type = 2; // html

        $this->mixed_boundary = "=-univ_mixed_" . md5(uniqid(rand()));
        $this->related_boundary = "=-univ_related_" . md5(uniqid(rand()));
        $this->alternative_boundary = "univ_alternative_" . md5(uniqid(rand()));

        if (!defined('UNIV_LINE_BREAK')) {
            define('UNIV_LINE_BREAK', (defined("PHP_OS") && !strcmp(substr(PHP_OS, 0, 3), "WIN") ? "\r\n" : "\n"));
        }
    }

    /**
    * UniversalMailSender::valid_email()
    * Simple mail syntax checker.
    *
    * @param mixed $address
    * @return true if email is ok and false othervise
    */
    public function valid_email($address)
    {
        if (ereg('^[a-zA-Z0-9 \._\-]+@([a-zA-z0-9\-]*\.)+[a-zA-Z]+$', $address))
            return true;
        else
            return false;
    }

    /**
    * UniversalMailSender::setToAddress()
    *
    * Set up the reciever address. Can be array or simple string, as well.
    *
    * @param mixed $to_address email address(es)
    * @param mixed $to_name reciever's name(s)
    */
    public function setToAddress($to_address, $to_name)
    {
        $to_address = (array)$to_address;
        $to_name = (array)$to_name;

        foreach($to_address as $key => $value) {
            if ($this->valid_email($value)) {
                $temp = (empty($to_name[$key]))?$value:"$to_name[$key] <$value>";

                $this->mail_to = !empty($this->mail_to) ? $this->mail_to . ", " . $temp : $temp;

                $this->mail_to_addr = !empty($this->mail_to_addr) ? $this->mail_to_addr . ", " . $value : $value;
            } else
                $this->error_pointer = 1;
        }
    }

    /**
    * UniversalMailSender::setFromAddress()
    *
    * Set up the sender address.
    *
    * @param mixed $from_address email address
    * @param mixed $from_name sender's name
    */
    public function setFromAddress($from_address, $from_name)
    {
        if ($this->valid_email($from_address)) {
            $this->mail_from = (empty($from_name))?$from_address:"\"$from_name\" <$from_address>";
            $this->mail_from_addr = $from_address;
            $this->error_pointer = ($this->error_pointer == 1)?0:$this->error_pointer;
        } else
            $this->error_pointer = 1;
    }

    /**
    * UniversalMailSender::setCcAddress()
    *
    * Same as
    *
    * @see UniversalMailSender::setToAddress() just that this is CC.
    * @param mixed $cc_address
    * @param mixed $cc_name
    */
    public function setCcAddress($cc_address, $cc_name)
    {
        $cc_address = (array)$cc_address;
        $cc_name = (array)$cc_name;

        foreach($cc_address as $key => $value) {
            if ($this->valid_email($value)) {
                $temp = (empty($cc_name[$key]))?$value:"$cc_name[$key] <$value>";
                $this->mail_cc = !empty($this->mail_cc) ? $this->mail_cc . ", " . $temp : $value;
            } else
                $this->error_pointer = 1;
        }
    }

    /**
    * UniversalMailSender::setBccAddress()
    *
    * Same as
    *
    * @see UniversalMailSender::setToAddress() just that this is BCC.
    * @param mixed $cc_address
    * @param mixed $cc_name
    */
    public function setBccAddress($bcc_address, $bcc_name)
    {
        $bcc_address = (array)$bcc_address;
        $bcc_name = (array)$bcc_name;

        foreach($bcc_address as $key => $value) {
            if ($this->valid_email($value)) {
                $temp = (empty($bcc_name[$key]))?$value:"$bcc_name[$key] <$value>";
                $this->mail_bcc = !empty($this->mail_bcc) ? $this->mail_bcc . ", " . $temp : $value;
            } else
                $this->error_pointer = 1;
        }
    }

    /**
    * UniversalMailSender::setSubject()
    *
    * Set up the subject
    *
    * @param mixed $subject
    * @return
    */
    public function setSubject($subject)
    {
        if (!empty($subject))
            $this->mail_subject = $subject;
    }

    /**
    * UniversalMailSender::setCharset()
    *
    * Set up the char set. If not set teh default value is used.
    *
    * @param mixed $charset
    * @return
    */
    public function setCharset($charset)
    {
        $this->mail_charset = $charset;
    }

    /**
    * UniversalMailSender::setTextMessage()
    *
    * The message body for the text mail.
    *
    * @param mixed $text_message
    * @return
    */
    public function setTextMessage($text_message)
    {
        if (!empty($text_message))
            $this->mail_text_message = wordwrap(strip_tags(preg_replace('/<br\\s*?\/??>/i', UNIV_LINE_BREAK, $text_message)), 72,UNIV_LINE_BREAK);

    }

    /**
    * UniversalMailSender::setHtmlMessage()
    *
    * The message body for the html mail.
    *
    * @param mixed $html_message
    * @return
    */
    public function setHtmlMessage($html_message)
    {
        if (!empty($html_message))
            $this->mail_html_message = $html_message;
    }

    /**
    * UniversalMailSender::setType()
    *
    * Set up the mail type 1 - text 2- html.
    *
    * @param string $send_type
    * @return
    */
    public function setType($send_type = "")
    {
        if (empty($send_type) || $send_type == 1)
            $this->mail_send_type = 1; // text
        else
            $this->mail_send_type = 2; // html
    }

    /**
    * UniversalMailSender::setXmailer()
    *
    * @param string $xmailer
    * @return
    */
    public function setXmailer($xmailer = "")
    {
        if ($this->valid_email($xmailer)) {
            $this->mail_xmailer = $xmailer;
        } else
            $this->error_pointer = 1;
    }

    /**
    * UniversalMailSender::setSender()
    *
    * In case you need to use the -f param this must
    * be set up. Can be the from mail.
    *
    * @param string $sender
    * @return
    */
    public function setSender($sender = "")
    {
        if ($this->valid_email($sender)) {
            $this->mail_sender = sender;
        } else
            $this->error_pointer = 1;
    }

    /**
    * UniversalMailSender::setErrorTo()
    *
    * Set up the error_to mail.
    *
    * @param string $error_to
    * @return
    */
    public function setErrorTo($error_to = "")
    {
        if ($this->valid_email($error_to)) {
            $this->mail_error_to = error_to;
        } else
            $this->error_pointer = 1;
    }

    /**
    * UniversalMailSender::setReplyTo()
    *
    * Set up the reply_to mail.
    *
    * @param string $reply_to
    * @return
    */
    public function setReplyTo($reply_to = "")
    {
        if ($this->valid_email($reply_to)) {
            $this->mail_reply_to = reply_to;
        } else
            $this->error_pointer = 1;
    }

    /**
    * UniversalMailSender::setDebug()
    *
    * Switch on/off teh debug.
    *
    * @param mixed $debug 1 - on 0 - off
    * @return
    */
    public function setDebug($debug)
    {
        $this->mail_debug = $debug;
    }

    /**
    * UniversalMailSender::clean()
    *
    * Clean up the message headers from enters.
    *
    * @param mixed $text
    * @return
    */
    public function clean($text)
    {
        return str_replace("\n", " ", str_replace("\r\n", " ", $text));
    }

    /**
    * UniversalMailSender::getDebugMessage()
    *
    * @return Returns the headers
    */
    public function getDebugMessage()
    {
        return $this->mail_debug_message;
    }

    /**
    * UniversalMailSender::securityFilter()
    *
    * Security filter for anti mail injection.
    *
    * @param mixed $tag_string
    * @return
    */
    public function securityFilter($tag_string)
    {
        if (strpos($this->mail_from, 'Content-Type:') === false && strpos($this->mail_from, 'Bcc:') === false && strpos($this->mail_from, 'bcc:') === false && strpos($this->mail_from, 'cc:') === false
                )
            return false;
        else
            return true;
    }

    /**
    * UniversalMailSender::SendMail()
    *
    * @return
    */
    public function SendMail()
    {
        // security checkings
        if ($this->securityFilter($this->mail_from) && $this->securityFilter($this->mail_to_addr) && $this->securityFilter($this->mail_cc) && $this->securityFilter($this->mail_bcc) && $this->securityFilter($this->mail_to) && $this->securityFilter($this->mail_sender) && $this->securityFilter($this->mail_error_to) && $this->securityFilter($this->mail_reply_to)
                )
            return "";

//        if (defined("PHP_OS") && !strcmp(substr(PHP_OS, 0, 3), "WIN"))
//            return $this->SendMailWindows($this->mail_to_addr, $this->mail_from_addr, $this->mail_sender, $this->mail_error_to, $this->mail_reply_to, $this->mail_subject, $this->mail_send_type, $this->mail_text_message, $this->mail_html_message);
//        else
            return $this->SendMailLinux();
    }

    /**
    * UniversalMailSender::SendMailWindows()
    *
    * For windows mailers. Not tested !
    *
    * @param mixed $to_address
    * @param mixed $from_address
    * @param mixed $mail_sender
    * @param mixed $mail_error_to
    * @param mixed $mail_reply_to
    * @param mixed $subject
    * @param mixed $send_type
    * @param mixed $mail_text_message
    * @param mixed $mail_html_message
    * @return
    */
    public function SendMailWindows($to_address, $from_address, $mail_sender, $mail_error_to, $mail_reply_to, $subject, $send_type, $mail_text_message, $mail_html_message)
    {
        $headers = "From: $from_address\r\n";
        if (empty($mail_reply_to))
            $headers .= "Reply-To: $from_address\r\n";
        else
            $headers .= "Reply-To: $mail_reply_to\r\n";

        if (empty($mail_sender))
            $headers .= "Sender: $from_address\r\n";
        else
            $headers .= "Sender: $mail_sender\r\n";

        if (empty($mail_error_to))
            $headers .= "Error-To: $from_address\r\n";
        else
            $headers .= "Error-To: $mail_error_to\r\n";

        $headers .= "X-Mailer: <$this->mail_xmailer>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        if ($send_type == 1) {
            $headers .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
            $message = $mail_text_message;
        } else {
            $headers .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";
            $message = $mail_html_message;

        }
        // $extra_headers="-f $mail_from_addr";
        if ($this->mail_debug == 1)
            $this->mail_debug_message = $headers;

        return @mail($this->clean($to_address), $subject, $message, $headers, $extra_headers);
    }

    /**
    * UniversalMailSender::makeHeaders()
    *
    * Puts togheder the mail headers.
    *
    * @return
    */
    public function makeHeaders()
    {
        //$this->mail_header = "Received: from " . $this->mail_xmailer . " ([" . $this->getVar("REMOTE_ADDR") . "]) ";
        //$this->mail_header .= "by " . $this->getVar("SERVER_NAME") . " with HTTP;" . UNIV_LINE_BREAK;
        //$this->mail_header .= "\t" . date("r") . UNIV_LINE_BREAK;





        if (!empty($this->mail_from)) {
            $this->mail_header .= "From: " . $this->mail_from . UNIV_LINE_BREAK;
        }
        if (!empty($this->mail_to))
			$this->mail_header .= "To: " . $this->mail_to . UNIV_LINE_BREAK;

//        if (!empty($this->mail_cc)) {
//            $this->mail_header .= "Cc: " . $this->mail_cc . UNIV_LINE_BREAK;
//        }
//        if (!empty($this->mail_bcc)) {
//            $this->mail_header .= "Bcc: " . $this->mail_bcc . UNIV_LINE_BREAK;
//        }
        // $this->mail_header .= !empty($this->mail_reply_to) ? "Reply-To: " . $this->mail_reply_to . UNIV_LINE_BREAK : "Reply-To: " . $this->mail_from . UNIV_LINE_BREAK;
        $this->mail_header .="Subject:".trim($this->mail_subject). UNIV_LINE_BREAK;
        $this->mail_header .= "Date: " . date("r") . UNIV_LINE_BREAK;
//        $this->mail_header .= "X-Mailer: " . $this->mail_xmailer . UNIV_LINE_BREAK;
//
//        if (!empty($this->mail_from_addr)) {
//            $this->mail_header .= "Return-Path: " . $this->mail_from_addr . UNIV_LINE_BREAK;
//        }

       // $this->mail_header .= "MIME-Version: 1.0" . UNIV_LINE_BREAK;
    }

    /**
    * UniversalMailSender::makeBody()
    *
    * Making the mail body.
    *
    * @return
    */
    public function makeBody()
    {
        $this->makeHeaders();

        if (sizeof($this->attachments) > 0)
            $type_body = $this->mail_send_type + 1;
        else
            $type_body = $this->mail_send_type;

        switch ($type_body) {
            case 1: // just simple text mail
                $this->mail_header .= "Content-Type: text/plain; charset=\"$this->mail_charset\"";
                $this->mail_body = $this->mail_text_message;
                break;
            case 2: // text and html message
                $this->mail_header .= "Content-Type: multipart/alternative;" . UNIV_LINE_BREAK;
                $this->mail_header .= "\tboundary=\"$this->alternative_boundary\"" . UNIV_LINE_BREAK;;
                $this->mail_body .= UNIV_LINE_BREAK . "--" . $this->alternative_boundary . UNIV_LINE_BREAK;
                $this->mail_body .= "Content-Type: text/plain; charset=\"$this->mail_charset\"" . UNIV_LINE_BREAK . UNIV_LINE_BREAK;
                // $this->mail_body .= "Content-Transfer-Encoding: 7bit" . UNIV_LINE_BREAK . UNIV_LINE_BREAK;
                $this->mail_body .= $this->mail_text_message . UNIV_LINE_BREAK . UNIV_LINE_BREAK . UNIV_LINE_BREAK;
                $this->mail_body .= "--" . $this->alternative_boundary . UNIV_LINE_BREAK;
                $this->mail_body .= "Content-Type: text/html; charset=\"$this->mail_charset\"" . UNIV_LINE_BREAK . UNIV_LINE_BREAK;
                // $this->mail_body .= "Content-Transfer-Encoding: 7bit" . UNIV_LINE_BREAK . UNIV_LINE_BREAK;
                $this->mail_body .= $this->mail_html_message . UNIV_LINE_BREAK . UNIV_LINE_BREAK . UNIV_LINE_BREAK;
                $this->mail_body .= "--" . $this->alternative_boundary . "--" . UNIV_LINE_BREAK;
                break;
            case 3:
                $this->mail_header .= "Content-Type: multipart/mixed; boundary=\"$this->mixed_boundary\"";
                $this->mail_body .= "--" . $this->mixed_boundary . UNIV_LINE_BREAK;
                $this->mail_body .= "Content-Type: text/plain" . UNIV_LINE_BREAK;
                $this->mail_body .= "Content-Transfer-Encoding: 7bit" . UNIV_LINE_BREAK . UNIV_LINE_BREAK;
                $this->mail_body .= $this->mail_text_message . UNIV_LINE_BREAK . UNIV_LINE_BREAK;
                foreach($this->attachments as $value) {
                    $this->mail_body .= "--" . $this->mixed_boundary . UNIV_LINE_BREAK;
                    $this->mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . UNIV_LINE_BREAK;
                    $this->mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . UNIV_LINE_BREAK;
                    $this->mail_body .= "Content-Transfer-Encoding: base64" . UNIV_LINE_BREAK . UNIV_LINE_BREAK;
                    $this->mail_body .= $value['content'] . UNIV_LINE_BREAK . UNIV_LINE_BREAK;
                }
                $this->mail_body .= "--" . $this->mixed_boundary . "--" . UNIV_LINE_BREAK;
                break;
            case 4:
                $this->mail_header .= "Content-Type: multipart/mixed; boundary=\"$this->mixed_boundary\"";
                $this->mail_body .= "--" . $this->mixed_boundary . UNIV_LINE_BREAK;
                $this->mail_body .= "Content-Type: multipart/alternative; boundary=\"$this->alternative_boundary\"" . UNIV_LINE_BREAK . UNIV_LINE_BREAK;
                $this->mail_body .= "--" . $this->alternative_boundary . UNIV_LINE_BREAK;
                $this->mail_body .= "Content-Type: text/plain" . UNIV_LINE_BREAK;
                $this->mail_body .= "Content-Transfer-Encoding: 7bit" . UNIV_LINE_BREAK . UNIV_LINE_BREAK;
                $this->mail_body .= $this->mail_text_message . UNIV_LINE_BREAK . UNIV_LINE_BREAK;
                $this->mail_body .= "--" . $this->alternative_boundary . UNIV_LINE_BREAK;
                $this->mail_body .= "Content-Type: text/html; charset=\"$this->charset\"" . UNIV_LINE_BREAK;
                $this->mail_body .= "Content-Transfer-Encoding: 7bit" . UNIV_LINE_BREAK . UNIV_LINE_BREAK;
                $this->mail_body .= $this->mail_html_message . UNIV_LINE_BREAK . UNIV_LINE_BREAK;
                $this->mail_body .= "--" . $this->alternative_boundary . "--" . UNIV_LINE_BREAK . BR;
                foreach($this->attachments as $value) {
                    $this->mail_body .= "--" . $this->boundary_mix . UNIV_LINE_BREAK;
                    $this->mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . UNIV_LINE_BREAK;
                    $this->mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . UNIV_LINE_BREAK;
                    $this->mail_body .= "Content-Transfer-Encoding: base64" . UNIV_LINE_BREAK . UNIV_LINE_BREAK;
                    $this->mail_body .= $value['content'] . UNIV_LINE_BREAK . UNIV_LINE_BREAK;
                }
                $this->mail_body .= "--" . $this->mixed_boundary . "--" . UNIV_LINE_BREAK;
                break;
        }
    }

    /**
    * UniversalMailSender::SendMailLinux()
    *
    * The Linux mail sender.
    *
    * @return
    */
    public function SendMailLinux()
    {
        $this->makeBody();

        if ($this->error_pointer > 0) {
            return false;
        }

        if ($this->mail_debug == 1)
            $this->mail_debug_message = $this->mail_header;


    //*!!!!! IMPORTANT - here should be edited to the smtp server of the user -IMPORTANT  !!!! */
	@include_once("smtp/config_smtp.php");
	include_once("smtp/class.phpmailer.php");
	
	 $Mail = new PHPMailer();
		$Mail->Mailer ="smtp";
		$Mail->SMTPDebug = MAILDEBUG;
        $Mail->Priority = PRIORITY;
        $Mail->Encoding = ENCODING;
        $Mail->CharSet = CONF_CHARSET; 
        $Mail->From = $this->clean($this->mail_from_addr);
        $Mail->FromName = $this->clean($this->mail_from_addr);
        $Mail->Sender = "";
        $Mail->Subject = $this->mail_subject;
        $Mail->Body = "";
        $Mail->AltBody = "";
        $Mail->WordWrap = 0;
        $Mail->Host = HOST;
        $Mail->Port = PORT;
        $Mail->Helo = HELO;
        $Mail->SMTPAuth = SMTPAUTH;
        $Mail->Username = USERNAME;
        $Mail->Password = PASSWORD;

        
        
        switch ($this->mail_send_type) {
            case 1: // just simple text mail
               	$Mail->AltBody = $this->mail_text_message;
                break;
            case 2: // text and html message
               	$Mail->IsHTML(true);
                $Mail->Body = $this->mail_html_message . UNIV_LINE_BREAK ;
        		$Mail->AltBody = $this->mail_text_message;
                
                break;

        }


       $Mail->AddAddress($this->clean($this->mail_to_addr), $this->clean($this->mail_to_addr)); 
        
        $resp=$Mail->Send();
        
        //var_dump($resp);
        //echo $Mail->ErrorInfo;
        return ($resp?"OK":"");
    


    }

    /**
    * UniversalMailSender::openAttachementFile()
    *
    * Open and prepare the attachement.
    *
    * @param mixed $file
    * @return
    */
    public function openAttachementFile($file)
    {
        if (($fp = @fopen($file, 'r'))) {
            $content = fread($fp, filesize($file));
            fclose($fp);
            return $content;
        }
        return false;
    }

    /**
    * UniversalMailSender::getMimeType()
    *
    * Getting the attached file's mime-type.
    *
    * @param mixed $filename
    * @return
    */
    public function getMimeType($filename)
    {
        if (!function_exists('mime_content_type')) {
            $idx = strtolower(end(explode('.', $filename)));
            if (isset($this->mime_types[$idx])) {
                return $this->mime_types[$idx];
            } else {
                return 'application/octet-stream';
            }
        } else
            return @mime_content_type($filename) ;
    }

    /**
    * UniversalMailSender::addAttachementFile()
    *
    * Prepares teh attched files
    *
    * @param mixed $file
    * @param mixed $name
    * @param string $type
    * @return
    */
    public function addAttachementFile($file, $name, $type = "")
    {
        if (($content = $this->openAttachementFile($file))) {
            $this->attachments[] = array('content' => chunk_split(base64_encode($content), 76, UNIV_LINE_BREAK),
                'name' => $name,
                'type' => (empty($type) ? $this->getMimeType($file): $type),
                'embedded' => false
                );
        } else
            $this->error_pointer = 2;
    }

    /**
    * UniversalMailSender::getError()
    *
    * Getting back the error message( if there any)
    *
    * @return
    */
    public function getError()
    {
        return $this->errors[$this->error_pointer];
    }

    /**
    * UniversalMailSender::getVar()
    *
    * Server vars retriving.
    *
    * @param mixed $var
    * @return
    */
    public function getVar($var)
    {
        if (version_compare(phpversion(), "4.1.0", "<")) {
            global $HTTP_SERVER_VARS;
            global $HTTP_ENV_VARS;

            if (!isset($HTTP_SERVER_VARS["REMOTE_ADDR"]))
                return (isset($HTTP_ENV_VARS[$var])?$HTTP_ENV_VARS[$var]:"");
            else
                return $HTTP_SERVER_VARS[$var];
        } else
            return (isset($_SERVER[$var])?$_SERVER[$var]:"");
    }
}

?>