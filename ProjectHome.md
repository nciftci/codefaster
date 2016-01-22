Code Faster is a PHP Framework, based on Fast Template PHP template engine. Will create all necessary files to have a project ready and run ... or almost ready :-).

## Features ##
  * Create class file
  * Create administrator PHP and HTML template file (list,add,modify,delete,activate)
  * Create user file
  * jQuery UI for administrator
  * jQuery Validator for generated forms
  * jQuery Colorize for tables
  * CK Editor for administrator
  * Configuration pages for administrator
  * Additional classes included (Mail, Calendar, FTP, WordCleaner, Captcha,  Pagination etc.)

## News ##
**17.06.2010** - pagination separated into a single file, one CSS.

**08.06.2010** - cls\_listing enhancement with drop down filtering

**25.04.2010** - 'try' ending in names bug fixed - it was caused by the beautyfier

**25.04.2010** - Multilanguage support introduced.

**25.02.2010** - XML generation schema parsing bug, imagecrop name fix, image delete bug fix, limitation page bugfix

**19.12.2009** - GIF files, instead ICO files in listing. Column sort order in generated PHP files, connection.php SET\_NAMES to force default character set.

**14.12.2009** - Date JQuery module/ImageCrop module updated/UtilClass - formatDataWithSelect updated.

**04.12.2009** - Debug tool (FirePHP) was added.

**05.10.2009** - In admintool. if default username/password (admin/setup) detected, redirect to config page.

**25.09.2009** - New features on the road! New CKEditor. Much faster and reliable. cls\_listing now have filtering on columns and sorting. A few other small features, what we will mention in CHANGELOG. If you are curious, get the source code from SVN. Estimated release date for 1.2.0: October 2009

**17.07.2009** - Release of 1.1.0, we want to hear your feedback. We hope we will release a few new video in a few days with the new features.

**14.07.2009** - cls\_listing modified again. Now can list from database connection, objects push (ex: Web Services), from array ...

- Upload image with crop is almost done. We can now generate upload field, and after upload the image, will redirect to crop function. Then create a small and a large image, based on settings.

**29.05.2009** - cls\_listing modified. Now can replace a column with a value from database or array.
Ex:
`$Obj->setReplaceColumnIdFromDatabase("id_hotel","hotels","id","name");`

will do a SELECT id\_hotel FROM hotels where id will be replaced by name

`$Obj->setReplaceColumnIdFromArray("level",$d_levels);`

from an array almost the same like from database

$d\_levels = array(-99 => "Level 0",
  1. => "Level 1",
> 2 => "Level 2",
> 3 => "Level 3");

cls\_util - a few new functions.
  * function formatDataWithSelect
  * function getDataWithSelectFromArray
  * function formatCountryForSelect
  * function formatStateForSelect
  * function uploadImage
  * function getUploadImagesHtml
  * function uploadAllImages

**29.04.2009** - We updated the code to be multilanguage ready. The idea is to have possibility to use unlimited languages on the final generated website. There are some rules to follow, but not so hard. The multilanguage fieldname MUST be named fieldname\_en, fielname\_fr, fieldname\_hu.

**19.04.2009** - We have generated some video tutorials, just need to finish it. It will be good to check it for start.