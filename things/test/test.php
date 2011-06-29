<?php
    /*require_once ('things/config/mysql_connect.php');
	
    $query = "SELECT * FROM `properties` WHERE `value` LIKE 'prop://%'";
	
	$pending_queries = array ();
	
	$sql = mysql_query ($query);
	if ($sql) {
		while ($row = mysql_fetch_assoc ($sql)) {
			$pending_queries[] = sprintf (
			    "UPDATE `properties` SET `value`='%s' WHERE `oid` = '" . $row['oid'] . "' AND `name`='%s'",
				'prop://' . basename ($row['value']),
				$row['name']
			);
		}
	}
	print_r ($pending_queries);
	
	foreach ($pending_queries as $q) {
		mysql_query ($q);
	}
	
	function dl ($u,$d=''){$f=fopen($d?$d:basename($u),'w');fwrite($f,file_get_contents($u));fclose($f);}
	
	dl ('http://i.imgur.com/LS75g.jpg', 'lol.jpg');*/
	
	echo ("<pre>");
	
	echo ('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME']);
	echo ("<br />");
	
	$webroot = "http://" . $_SERVER['SERVER_NAME'] . substr (dirname (dirname (realpath (__FILE__))), strlen ($_SERVER['DOCUMENT_ROOT']));
	echo ($webroot);
	echo ("<br />");
	echo ("<br />");
	echo ("<br />");
	echo ("<br />");
	
	
	var_dump ($_SERVER);
	echo ("</pre>");
?>