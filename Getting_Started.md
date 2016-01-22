# Getting Started #

As mentioned before, the first thing is to finalize the installation.

After launching the genetor page, URL/generator/ on the first page you need to enter the generating data. We recommend to start from an existing MySQL table or from a MySQL connection. URL/generator/index.php?page=sql

The data from SQL will be automatically generated at the next step.

![http://www.grafxsoftware.com/publ_images/codefaster/tableread.png](http://www.grafxsoftware.com/publ_images/codefaster/tableread.png)

The next step is selecting from the available fields each what type it is.
It is important for the PRIMARY KEY to be the first, and default to be selected. The remaining fields have to selected from  the menu, you can select or deselect them (in the administration part) and whether there are compulsory fields (from administration).

![http://www.grafxsoftware.com/publ_images/codefaster/choosefieldtype.png](http://www.grafxsoftware.com/publ_images/codefaster/choosefieldtype.png)

At the next step, if you have selected the texting, you can specify an editor and the type of the editor, basic or advanced.
In the same screen, you have to select what classes should be included. The programming is made so it works ONLY in PHP5, we chose to use the autoload function.

After this step every file will be generated at its location, the administration files in public\_html/admintool/filename.php public\_html/admintool/programtemplates/filename.html, the class in public\_html/cls\_filename.php and the user files in public\_html/filename.php si public\_html/programtemplates/filename.html

There are also launching links.

More important is that in public\_html/languages/filename.txt all the ADMIN and USER variables have been generated for the language file.
This has to be opened and added, the ADMIN ones (LANG\_ADMIN\_X) to the en.admintool.inc.php file and the user ones to en.inc.php.
Normally the texts have to be edited, the generator generates only the name of the file for an easy recognition of the variables.