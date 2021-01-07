<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

/* crypto*/
$active_group = "vnoc";
$active_record = TRUE;

// $db['vnoc']['username'] = 'fromcowork';
// $db['vnoc']['password'] = 'sul0dk0h4';
// $db['vnoc']['database'] = 'domaindi_managedomain';
// $db['vnoc']['hostname'] = '184.107.215.234';

$db['vnoc']['username'] = 'maida';
$db['vnoc']['password'] = 'vschool3030';
$db['vnoc']['database'] = 'domaindi_managedomain';
$db['vnoc']['hostname'] = 'vnocdb.cyh3tjizziz6.us-west-2.rds.amazonaws.com';

$db['vnoc']['dbdriver'] = 'mysqli';
$db['vnoc']['dbprefix'] = '';
$db['vnoc']['pconnect'] = FALSE;
$db['vnoc']['db_debug'] = TRUE;
$db['vnoc']['cache_on'] = TRUE;
$db['vnoc']['cachedir'] = '';
$db['vnoc']['char_set'] = 'utf8';
$db['vnoc']['dbcollat'] = 'utf8_general_ci';
$db['vnoc']['swap_pre'] = '';
$db['vnoc']['autoinit'] = FALSE;
$db['vnoc']['stricton'] = FALSE;

/* crypto*/
$active_group = "crypto";
$active_record = TRUE;

$db['crypto']['hostname'] = '34.210.47.172';
//$db['crypto']['hostname'] = '54.148.216.196';
$db['crypto']['username'] = 'cryptoco_maida';
$db['crypto']['password'] = 'school3030';
$db['crypto']['database'] = 'cryptoco_crypto';
$db['crypto']['dbdriver'] = 'mysqli';
$db['crypto']['dbprefix'] = '';
$db['crypto']['pconnect'] = FALSE;
$db['crypto']['db_debug'] = TRUE;
$db['crypto']['cache_on'] = FALSE;
$db['crypto']['cachedir'] = '';
$db['crypto']['char_set'] = 'utf8';
$db['crypto']['dbcollat'] = 'utf8_general_ci';
$db['crypto']['swap_pre'] = '';
$db['crypto']['autoinit'] = FALSE;
$db['crypto']['stricton'] = FALSE;


$db['referrals']['hostname'] = '72.55.131.165';
$db['referrals']['username'] = 'referral_maida';
$db['referrals']['password'] = 'bing2k';
$db['referrals']['database'] = 'referral_program';
$db['referrals']['dbdriver'] = 'mysqli';
$db['referrals']['dbprefix'] = '';
$db['referrals']['pconnect'] = FALSE;
$db['referrals']['db_debug'] = TRUE;
$db['referrals']['cache_on'] = FALSE;
$db['referrals']['cachedir'] = '';
$db['referrals']['char_set'] = 'utf8';
$db['referrals']['dbcollat'] = 'utf8_general_ci';
$db['referrals']['swap_pre'] = '';
$db['referrals']['autoinit'] = FALSE;
$db['referrals']['stricton'] = FALSE;

$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'site42he_dev';
$db['default']['password'] = 'school3030';
$db['default']['database'] = 'site42he_service';
//$db['default']['dbdriver'] = 'mysql';
$db['default']['dbdriver'] = 'mysqli';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */