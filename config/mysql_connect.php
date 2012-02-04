<?php
    require_once ("environment.php"); //all the table definition is in here

//connection
    $slink = mysql_connect (SERVER_SERVER, 
                            SERVER_USER, 
                            SERVER_PASS) or die(":" . mysql_error()); // connnect to server
    $link = mysql_select_db(SERVER_DB, $slink) or die("::" . mysql_error()); // connect to database
?>