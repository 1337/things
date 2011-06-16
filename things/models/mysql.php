<?php
    // MySQL DAO for Things.
	// In practice, we don't use it much.
    
	require_once (PROOT . "config/config.php");
	    
    class MySQL { // implements DAO {
		public $query; // will be used by BuildQuery
			
		function __construct ($host, $user, $pass, $db = '') {
			// MySQL class must connect on initialization.
			if (@!mysql_connect($host, $user, $pass)) {
				die (get_class () . '::' . __FUNCTION__ . " failed you.");
			}
			if (strlen ($db) > 0) {
				// if user specified a db to select, then select it
				$this->SelectDB ($db);
			}
		}
		
		function MySQL ($host, $user, $pass, $db = '') {
			// PHP4 conpat
			$this->__construct ($host, $user, $pass, $db);
		}
		
		function SelectDB ($db) {
			if (!mysql_select_db ($db)) {
				die ("Cannot select DB");
			}
		}
		
		function Query ($query = '') {
			// execute a query, and/or return its results...
		    // note that this class does not check if a link is already available.
			$return = array ();
			if (strlen ($query) == 0) {
				$query = $this->query;
			}
			$rs = mysql_query ($query);
			switch ($rs) {
			    case false:
				    // whatever it was, the query failed.
    				die ("Query failed: " . mysql_error());
				    break;
				case true:
				    // whatever it was, it succeeded. return thingy.
				    return mysql_affected_rows ();
					break;
				default:
				    // all others.
					while ($row = mysql_fetch_assoc ($rs)) {
						$return [] = $row;
					}
					return $return;
			}
		}
		
		function escape_data ($data) { 
			global $slink;
			if (ini_get('magic_quotes_gpc')) {
				$data = stripslashes($data);
			}
			return mysql_real_escape_string (trim ($data), $slink);
		}
	}
	
    $mysql = new MySQL (SERVER_SERVER, SERVER_USER, SERVER_PASS, SERVER_DB); // <-- use this
?>