<?php
include_once("./cls_classproduct.php");

$link=mysql_connect("localhost","root","123456");
if (!$link) die ("Could not connect to the database");
if (!mysql_select_db("test",$link)) die ("Could not use the database");



//$prod=new classProduct(0);


$prod=new classProduct('WHERE `votes`="10"');
//$prod->undo(237);

//$prod->debug_print_history();

//$prod->setItem("Item A3");
//$prod->setItemId("item_b3");
//$prod->setVotes("10000");
//$prod->save();
 
//$prod->delete(" WHERE `id` > '0'");

//$prod->undo();
//// 


$prod->debug_print_history();

//$prod->delete("WHERE id<96");
//$prod->debug_print_history();
//$prod->undo();
//print($prod->undo());
mysql_close($link);
?>

