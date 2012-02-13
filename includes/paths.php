<?php
/*This code handles all the path related operations
 */

//Get the server name
$server_name = $_SERVER['SERVER_NAME'];

//Get the path of file
$file_path = $_SERVER['PHP_SELF'];

//Get the path of directory
$file_dir = __DIR__;

//Get the general path of file
$real_url = explode("gERP",$file_path);

//Get the general path of directory
$real_dir = explode("gERP",$file_dir);

//Store the main folder path in a variable
$main_dir = $real_dir[0].'gERP/';

//Store the main folder url in a variable
$main_url = 'http://'.$server_name.$real_url[0].'gERP/';

//Get the path of media folder and store it in the variable
$media_dir = $main_dir.'media/';

//Get the media url and store it in the variable
$media_url = $main_url.'media/';

//Get the folder path of the includes directory
$includes_dir = $main_dir.'includes/';

//Get the url of the includes directory
$includes_url = $main_url.'includes/';

//Get the path of the header and footer directory
$header_footer_dir = $main_dir.'header_footer/';

//Get the url of the header and footer directory
$header_footer_url = $main_url.'header_footer/';

$js_dir = $main_dir.'js/';

$js_url = $main_url.'js/';
?>
