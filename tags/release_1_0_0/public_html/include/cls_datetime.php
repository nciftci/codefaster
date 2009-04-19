<?php

class Datetime {

	// return a date at interval $days from today
	function add_date_days($days)
	{
		return date('Y-m-d', strtotime("+ {$days} day"));
	}

	// return a date at interval $days from $data
	function add_date_days_from($data, $days) 
	{
		return date('Y-m-d', strtotime("+ {$days} day", strtotime($date)));
	}

	// return 1 or 0 if date $date is (or not) between date $first and $second
	function isBetween($date, $first, $second)
	{
		$first  = strtotime($first);
		$second = strtotime($second);
		$date  = strtotime($date);

		if (($date > $first) && ($date < $second)) {
			return true;
		}
		return false;
	}   

	function formatDate($msqldate, $date_type)
	{
		$timestamp = @strtotime($msqldate);

	  	setlocale(LC_TIME, strtolower($LANG)."_".strtoupper($LANG));
		
	  	if ($date_type == "Y-m-d") $new_date = strftime("%Y-%m-%d", $timestamp);
		else if ($date_type == "y-n-d") $new_date = strftime("%y-%m-%d", $timestamp); 
		else if ($date_type == "y-m-d") $new_date = strftime("%y-%m-%d", $timestamp);
		else if ($date_type == "n/d/y") $new_date = strftime("%m/%d/%y", $timestamp);
		else if ($date_type == "d F Y") $new_date = strftime("%d %B %Y", $timestamp); //%d %B %G
		else if ($date_type == "d M y") $new_date = strftime("%d %b %y", $timestamp);
		else if ($date_type == "M d,Y") $new_date = strftime("%b %d, %Y", $timestamp);
		else if ($date_type == "d-M-y") $new_date = strftime("%d-%b-%y", $timestamp);
		else if ($date_type == "dMy")   $new_date = strftime("%d %b %y", $timestamp);
		else if ($date_type == "l, F j, Y - h:i A") $new_date = strftime("%A, %B %d, %Y - %I:%M %p", $timestamp);
        else if ($date_type == "l, F j, Y - H:i")   $new_date = strftime("%A, %B %d, %Y - %H:%M", $timestamp);

		//error_log($msqldate." ".$timestamp." ".$date_type)		;
	  	return $new_date;
	}                           
}

?>