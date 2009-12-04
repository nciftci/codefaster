<?php

if ( (ERROR_DEBUG==2) && (in_array($_SERVER['REMOTE_ADDR'],explode(",",DEBUG_ALLOWED_IPS))) ) {
	define("USE_NOP_DEBUG",true);
} else {
	define("USE_NOP_DEBUG",false);
};

if (USE_NOP_DEBUG){
	if (DEBUG_MODE=="firephp"){
		require_once('fb.php');
		//ob_start();

		$firephp_logger = FirePHP::getInstance(true);
	};
};

function debug($vars){
	if (!USE_NOP_DEBUG) return;
	
	for ($i = 0; $i < func_num_args(); ++$i) {
		$param = func_get_arg($i);
		debug_array($param);
	};
}
function debug_bt(){	
	if (!USE_NOP_DEBUG) return;
	debug_array(debug_backtrace());
}

function debug_line(){
	if (!USE_NOP_DEBUG) return;
	$bt=debug_backtrace();
	$bt=$bt[0];
	$line=$bt['line'];
	$file=$bt['file'];
	debug_array("FILE=$file   LINE=$line");
};


function debug_array($array){
	if (!USE_NOP_DEBUG) return;
	switch (DEBUG_MODE){
		case "firephp":
			$firephp_logger->log($param);
			break;
		case "errorlog":
			error_log(print_r($param,true));
			break;
	};
};

?>
