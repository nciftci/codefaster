<?php


function generateMonth($date) {

	$date=strftime("%Y-%m-%d",strtotime($date));
	
	// date argument should be YYYY-MM-DD
	
	  $date_arr=explode("-",$date);
	  $year=$date_arr[0];
	  $month=$date_arr[1];
	  $day=$date_arr[2];

			
		//	UNIX timestamp 
			$datetimestamp = mktime (0,0,0,$month,$day,$year);	
			

			
		//	starting day
			$datestartingday= date ("w",mktime (0,0,0,$month,1,$year));
		
	    // how many days are in this month
	    
	        $dayofmonth=date ("t",$datetimestamp);
			
	        //print $datestartingday." ".	$dayofmonth;
	
	        $month_generate=array();
	        
	  
	    // first week of the month
		
	    $week_count=0;
	    
	    $week_generate=prepareWeekArray();
	    $start_index=$datestartingday;
	    
	    $week_nr=ceil(($dayofmonth+$datestartingday)/7);
        //print "<br>ss".$dayofmonth." ".$datestartingday." ".$week_nr."<br>";
	    $i=1;
	    for ($week_count=0; $week_count<$week_nr; $week_count++)         
          {
		      for($j=$start_index;$j<7;$j++) 
		       if($i<=$dayofmonth) 
		      		$week_generate[$j]=$i++;
		    
		      $month_generate[$week_count]= $week_generate;
		      
		      $week_generate=prepareWeekArray();
		      $start_index=0;
	      
          }
		  
	return   $month_generate;     

}
 
function prepareWeekArray(){
$temp_ar=array();	
for ($i=0; $i<7; $i++) 
$temp_ar[$i]="";

return $temp_ar;
}




function getMonth($index){

	if($index>0)
	 $index--;
	 

	$arr=explode("|",LANG_CALENDAR_MONTHNAMES);
	
	return $arr[$index];

}

function getMonthShort($index){
	
	if($index>0)
	 $index--;
	 

	$arr=explode("|",LANG_CALENDAR_MONTHNAMES_ABREW);
	
	return $arr[$index];

}

function getDay($index){
	$arr=explode("|",LANG_CALENDAR_DAYNAMES);
	
	return $arr[$index];
}

function getDayShort($index){
	$arr=explode("|",LANG_CALENDAR_DAYNAMES_ABREW);
	
	return $arr[$index];
}

function formatMonthd($date){

  $arr=generateMonth($date);
  

  
  $page = new FastTemplate(CALENDAR_TEMPLATE_PATH);
  
  $page->define(array("main"=>"template_month.html"));
  $page->define_dynamic("header","main");
  
  $page->assign("MONTH_NAME",getMonth(getMonthFromDate($date)));
  
  for($i=0;$i<7;$i++)
  {
  $page->assign("TITLE",getDayShort($i));
  $page->parse(HEADER,".header");
  }
  
  $page->define_dynamic("week","main");

  
  
  for($i=0;$i<count($arr);$i++)
  {
   
   $ft = new FastTemplate(CALENDAR_TEMPLATE_PATH);
   $ft->define(array("main"=>"template_month_days.html"));
   $ft->define_dynamic("row","main");
  
   for($j=0;$j<count($arr[$i]);$j++)
   {
    $ft->assign("TITLE",$arr[$i][$j]);
	$ft->assign("CLASS",CALENDAR_TEXT_NORMAL);
    $ft->parse(ROW,".row");   
   
   }
  
   $page->assign("WEEK_DAYS",$ft->fetch());
   $page->parse(WEEK,".week");
  
  }
  
  $page->parse(MAIN, "main");
 // $page->FastPrint(MAIN);
 return  $header=$page->fetch();




}

function genLink($ara,$date){
	$temp=array();
	while (list($key, $value) = each($ara)) 
	{
		if($key=='d') 
		   $temp[]=$key.'='.$date;
		else 
		$temp[]=$key.'='.$value;
		
	}
	return implode('&',$temp);
}

function formatMonth($date,$datearray){

  $arr=generateMonth($date);
 
  $page = new FastTemplate(CALENDAR_TEMPLATE_PATH);
  
  $page->define(array("main"=>"template_month.html"));
  $page->define_dynamic("header","main");
  
  $page->assign("MONTH_NAME",getMonth(getMonthFromDate($date)));
  
  for($i=0;$i<7;$i++)
  {
  $page->assign("TITLE",getDayShort($i));
  $page->parse(HEADER,".header");
  }
  
  $page->define_dynamic("week","main");

  
  
  for($i=0;$i<count($arr);$i++)
  {
   
   $ft = new FastTemplate(CALENDAR_TEMPLATE_PATH);
   $ft->define(array("main"=>"template_month_days.html"));
   $ft->define_dynamic("row","main");
  
   for($j=0;$j<count($arr[$i]);$j++)
   {
    $ft->assign("TITLE",$arr[$i][$j]);
    $pattern=getYearFromDate($date).getMonthFromDate($date).(($arr[$i][$j]<10?"0".$arr[$i][$j]:$arr[$i][$j]));
    //print $date.'<br>';
    
    
    
    
    
    $ft->assign("LINK",CALENDAR_LINK.'?'.genLink($_GET,$pattern));
	if(in_array($pattern,$datearray))
	$ft->assign("CLASS",CALENDAR_TEXT_ERROR);
	else
	$ft->assign("CLASS",CALENDAR_TEXT_NORMAL);
    $ft->parse(ROW,".row");   
   
   }
  
   $page->assign("WEEK_DAYS",$ft->fetch());
   $page->parse(WEEK,".week");
  
  }
  
  $page->parse(MAIN, "main");
 // $page->FastPrint(MAIN);
 return  $header=$page->fetch();




}






function chDate($date){

return strftime("%Y%m%d",strtotime($date));


}

function shiftDay($date,$number)
{
if($number>0)
$number="+".$number;

return strftime("%Y%m%d",strtotime($number." day ".$date));

}


function shiftMonth($date,$number)
{
if($number>0)
$number="+".$number;

return strftime("%Y%m%d",strtotime($number." month ".$date));

}

function shiftYear($date,$number){
if($number>0)
$number="+".$number;
/*
print "<br>";
print strftime("%Y%m%d",strtotime($number." year ".$date));
print "<br>";
print $number." year ".$date;
print "<br>";
print "<br>";
*/
return strftime("%Y%m%d",strtotime($number." year ".$date));
}

function getYearFromDate($date){
return strftime("%Y",strtotime($date));

}

function getMonthFromDate($date){
return strftime("%m",strtotime($date));

}

function getDayFromDate($date){
return strftime("%d",strtotime($date));

}

function getNow(){
return strftime("%Y%m%d",strtotime("now"));

}


function monthFromDateIndex($date,$i){

if($i<10)
$i="0".$i;

return getYearFromDate($date).$i.getDayFromDate($date);

}

function generateDates($date1,$date2){
$array_dates=array();
if(strtotime($date2)>=strtotime($date1)) 
{
	array_push($array_dates,$date1);
	while($date1!=$date2){
	$date1=shiftDay($date1,1);
	array_push($array_dates,$date1);
    }
	return $array_dates;
}
else
return "";

}

 ?>