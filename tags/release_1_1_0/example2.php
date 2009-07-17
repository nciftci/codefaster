<?php


include_once("./cls_classproduct.php");

$link=mysql_connect("localhost","root","123456");
if (!$link) die ("Could not connect to the database");
if (!mysql_select_db("test",$link)) die ("Could not use the database");



 // prepare
	
$prod=new classProduct(0);
$prod->setItem("Item A");
$prod->setItemId("item_a");
$prod->setVotes("10");
$prod->save();

$prod=new classProduct(0);
$prod->setItem("Item B");
$prod->setItemId("item_b");
$prod->setVotes("20");
$prod->save();

$prod=new classProduct(0);
$prod->setItem("Item C");
$prod->setItemId("item_c");
$prod->setVotes("30");
$prod->save();

$prod=new classProduct(0);
$prod->setItem("Item D");
$prod->setItemId("item_d");
$prod->setVotes("40");
$prod->save();


$prod->debug_print_history();

exit;
	


$prod=new classProduct(11);
$prod=new classProduct('WHERE `votes`="10"');


$prod->setItem("Item C");
$prod->setItemId("item_c");
$prod->setVotes("60");


$prod->save();
print_r($prod);

mysql_close($link);
?>

