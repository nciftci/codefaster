<?php

class Beautify{
	function get_beautify_php($php_file_data){
		$tmpfilename=@tempnam(sys_get_temp_dir(),"class_generator_");
		$handle = @fopen($tmpfilename, "w");
		$result=$php_file_data;
		if ($handle){	
			@fwrite($handle, $php_file_data);
			@fclose($handle);

			$extension="";
			if (stristr(PHP_OS, 'WIN')) { 
				$extension=".exe";
			};

			$outfilename="$tmpfilename.out";
			$tmp=@system("./beautifier/phpCB$extension  --space-after-if --space-after-switch --space-after-while --space-before-start-angle-bracket --space-after-end-angle-bracket --one-true-brace-function-declaration --glue-amperscore --change-shell-comment-to-double-slashes-comment --force-large-php-code-tag --extra-padding-for-case-statement --force-true-false-null-contant-lowercase --align-equal-statements --comment-rendering-style PHPDoc --equal-align-position 50 --padding-char-count 4 $tmpfilename > '$outfilename'",$return);
			@unlink($tmpfilename);
			if ($return==0) {
				$result=$tmp;
				$outhandle = @fopen($outfilename, "r");
				if ($outhandle){
					$result=fread($outhandle,filesize($outfilename));
					fclose($outhandle);
				};
				@unlink($outfilename);
			};
		};
		return $result;
	}
	function beautify_html($html_file_data){
		$tmpfilename=@tempnam(sys_get_temp_dir(),"class_generator_html_");
		$handle = @fopen($tmpfilename, "w");
		$result=$html_file_data;
		$orig_result=$result;
		if ($handle){	
			@fwrite($handle, $html_file_data);
			@fclose($handle);

			$extension="";
			if (stristr(PHP_OS, 'WIN')) { 
				$extension=".exe";
			};

			$outfilename=$tmpfilename.".out";
			$tmp=@system("./beautifier/tidy$extension  -config './beautifier/validator-config.txt' $tmpfilename > $outfilename",$return);
			@unlink($tmpfilename);
			if ($return<127) {
				$result=$tmp;
				$outhandle = @fopen($outfilename, "r");
				if ($outhandle){
					$result=fread($outhandle,filesize($outfilename));
					$delete_array=array( "<html>", "</html>", "<head>", "</head>", "<body>", "</body>", "<title>", "</title>"); 
					foreach($delete_array as $d){
						$result=str_ireplace($d,"",$result);
					};
					fclose($outhandle);
				};
				@unlink($outfilename);
			};
		};
		if (empty($result)) return $orig_result;
		return $result;
	}
};
//TODO: $result_php=trim(str_replace("\n\n\n\n\n","\n",$ft->fetch("BODY")));
?>