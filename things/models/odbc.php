<?php
    // ODBC DAO for Things.
	// In practice, we don't use it much.
    
	require_once (PROOT . "config/config.php");
	
    class ODBC { // implements DAO {
		public $query; // will be used by BuildQuery
		public $con; // connection
		public $rs; // query identifier (might as well make it public)
		
		function __construct ($dbn, $user, $pass) {
			// MySQL class must connect on initialization.
			if (@!$this->con = odbc_connect ($dbn, $user, $pass)) {
				die (get_class () . '::' . __FUNCTION__ . " failed you.");
			}
		}
		
		function ODBC ($dbn, $user, $pass) {
			// PHP4 conpat
			$this->__construct ($dbn, $user, $pass);
		}
		
		function SelectDB ($db) {
		    // ODBC does not select DB.
			// http://ca2.php.net/manual/en/function.odbc-connect.php
		}
		
		function Query ($query = '') {
			// execute a query, and/or return its results...
		    // note that this class does not check if a link is already available.
			$return = array ();
			if (strlen ($query) == 0) {
				$query = $this->query;
			}
			$this->rs = odbc_exec ($this->con, $query);
			switch ($this->rs) {
			    case false:
				    // whatever it was, the query failed.
    				die ("Query failed: " . odbc_error());
				    break;
				case true:
				    // whatever it was, it succeeded. return thingy.
				    return odbc_num_rows ();
					break;
				default:
				    // all others.
					while ($row = odbc_fetch_array ($this->rs)) {
						$return [] = $row;
					}
					return $return;
			}
		}	
	}
	
    $db = new ODBC (SERVER_SERVER, SERVER_USER, SERVER_PASS); // <-- use this
?>