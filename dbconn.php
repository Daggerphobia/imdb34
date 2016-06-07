<?php 
define('DB_HOST', 'localhost'); 
define('DB_NAME', 'movie'); 
//define('DB_USER','varfinz_admin'); 
//define('DB_PASSWORD','wIf30m&3'); 
define('DB_USER','root'); 
define('DB_PASSWORD',''); 
$con=@mysql_connect(DB_HOST,DB_USER,DB_PASSWORD) or die("Failed to connect to MySQL: " . mysql_error()); 
$db=mysql_select_db(DB_NAME,$con) or die("Failed to connect to MySQL: " . mysql_error()); 

mysql_set_charset('utf8');
?>
