# Installation Instructions #

The package contains 2 parts.
1. Generator, that can be found in the main directory (root directory)
2. The location where the files are generated /public\_html/

After downloading the program, you need to put it on a site that has Apache and MySQL installed.
In the main directory you DO NOT need to setup anything, but in the **public\_html** directory you have to edit the **config.inc.php** file and you have to setup the way (URL) to the program and the details of the database.

The database will be used to test the generated files, so here, you need to have default tables created, that can be found in the **/public\_html/installer/mysql.sql**
Finally, in the same folder you can save your own tables that are created.

After the database data are set and entered the default settings from the SQL files, you can use the generator.

Ex: **http://www.yourdomain.com/generator/** and in the end the generated files will be at **http://www.yourdomain.com/generator/public_html/**

After the testing all the content will be moved from public\_html/ to http://www.yourdomain.com/ and the configuration file will be reconfigured and the site will be functional. Of course do not expect that the site will be fully functional, you still will need to do some programming :-)