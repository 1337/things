<html>
    <head>
         <title>Things Database Setup</title>
         <style type="text/css">
            html, html *, body, body * {
                font-size: 12px;
                font-family: Arial, Helvetica, sans-serif;
            }
            pre {
                font-family: "Lucida Console", Monaco, monospace;
                font-size: 11px;
                background-color:#EEE;
            }
         </style>
    </head>
    <body>
    <?php
        require_once ('../config/environment.php');
        require_once ('../config/mysql_connect.php'); // or any other linking
     
        // add queries to the array
        $sqs = array (
"CREATE TABLE IF NOT EXISTS `objects` (
   `oid` int(20) unsigned NOT NULL auto_increment,
   `type` tinyint(4) NOT NULL default '0',
PRIMARY KEY  (`oid`))
ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 DELAY_KEY_WRITE=1",
"CREATE TABLE IF NOT EXISTS `hierarchy` (
   `parent_oid` int(20) unsigned NOT NULL default '0',
   `child_oid` int(20) unsigned NOT NULL default '0',
UNIQUE KEY `parent_oid` (`parent_oid`,`child_oid`))
ENGINE=MyISAM DEFAULT CHARSET=utf8",
"CREATE TABLE IF NOT EXISTS `properties` (
   `pid` int(20) unsigned NOT NULL auto_increment,
   `oid` int(20) unsigned NOT NULL default '0',
   `name` varchar(255) NOT NULL default '',
   `value` text NOT NULL,
PRIMARY KEY  (`pid`)) 
ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 DELAY_KEY_WRITE=1",
"CREATE TABLE IF NOT EXISTS `types` (
    `tid` int(8) unsigned NOT NULL auto_increment,
    `name` varchar(100) NOT NULL COMMENT 'same as class name',
    PRIMARY KEY  (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16",
"INSERT INTO `types` (`tid`, `name`) VALUES
    (1, 'user'),
    (2, 'group'),
    (3, 'privilege'),
    (4, 'post'),
    (5, 'tag'),
    (6, 'category'),
    (7, 'site'),
    (8, 'section'),
    (9, 'setting'),
    (10, 'page'),
    (11, 'gallery'),
    (12, 'validator'),
    (13, 'ajaxfield'),
    (14, 'ticket'),
    (15, 'dummy')"
        );
        $random_queries = array (
            'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";',
            'CREATE DATABASE `blland_canadensis` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;',
            'USE `blland_canadensis`;');
        if (sizeof ($sqs) > 0) {
            $errors = array ();
            foreach ($sqs as $sql) {
                $result = mysql_query ($sql);
                if (!$result) {
                    $error = mysql_error ();
                    if (strlen ($error) > 0) { // an error has occured
                        $errors[] = array ('SQL'=>$sql, 'ERROR'=>$error);
                    }
                }
            }
            if (sizeof ($errors) > 0) {
                echo ("
                    <p><b>Errors occured during setup.</b><br />
                       You should probably find out why...</p>
                ");
                foreach ($errors as $error) {
                    $e = $error['ERROR'];
                    $f = $error['SQL'];
                    echo ("
                        <p><b>$e</b></p>
                        <pre>$f</pre>
                    ");
                }
            } else {
                echo ("
                        <p>Database setup successful!</p>
                ");
            }
        }
    ?>
    </body>
</html>
