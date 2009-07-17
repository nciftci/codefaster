<?php
include_once("./config.inc.php");
include_once(INCLUDE_PATH."connection.php");
include_once("./cls_classproduct.php");

echo "Step 1";
//we call here the class with NO ID 
//will be done
//SELECT * FROM table WHERE id='';

$prod=new classProduct("0");

//Here we write this values into the database
//but because no ID was given, will be INSERT a new line.

$prod->setItem("Item A");
$prod->setItemId("item_b");
$prod->setVotes("10");
// this line will call the save from the class
$prod->save();


// END STEP 1

echo "Step 2";
//we call here the class with ID 2
//will be done
//SELECT * FROM table WHERE id='2';

//$prod=new classProduct("2");

//Here we write this values into the database
//because ID was given, will be UPDATE ID 2.
/*
$prod->setItem("Item A");
$prod->setItemId("item_c");
$prod->setVotes("10");
// this line will call the save from the class
$prod->save();
*/

// END STEP 2

echo "Step 3 - BUG, ca face un insert nou, nu update si daca nu exista, ar trebui sa exit, nu sa executre mai departe cu ID()";
//we call here the class with ID 2
//will be done
//SELECT * FROM table WHERE id='2';
$prod=new classProduct("WHERE `votes`='10' AND `itemid`='item_b'");

//Here we write this values into the database
//because ID was given, will be UPDATE ID 2.
$prod->setVotes("100");
// this line will call the save from the class
$prod->save();
//$prod=new classProduct('WHERE `votes`="2"');

// You can delete like this 
//$prod->delete(" WHERE `id` < '1150'");

// You can call UNDO command.
//$prod->undo();

// Nu stiu ce e
//$prod->debug_print_history();

//print($prod->undo());
mysql_close($cid);
?>