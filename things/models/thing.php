<?php
    require_once ('.things.php');
    require_once ("thing_" . DATABASE_TYPE . ".php");
	
	/* abstract class Thing {
        public $oid;
        private $cache;
        function __construct ($oid);
        function Thing ($oid);
        function Create ();
        function Type ($type_id = 0);
		function GetType ();
		function SetType ($type_id);
		function GetPropsRaw ();
		function GetProps ();
		function GetProp ($name);
		function GetPropFile ($propurl = '', $ext = 'txt');
		function DelPropFiles ($prop = '');
		function SetProp ($prop, $val);
		function SetProps ($what);
		function DelProps ($what);
		function DelPropsAll ();
		function DelAllProps ();
		function GetChildren ($type_id = 0, $order_by = "`child_oid` ASC");
        function SetChildren ($what);
		function SetChild ($what);
        function DelChildren ($child_ids);     
        function DelChild ($child_id);
		function GetParents ($type_id = 0, $order = "ORDER BY `parent_oid` ASC");
        function SetParents ($what);
        function DelParents ($parent_ids);
        function DelParent ($parent_id);
        function DelParentsAll ();
		function DelAllParents ();
        function CreateObject ($type, $props = array ());
        function ChangeID ($nid);
        function Duplicate ();
        function Destroy ();
	} */
	
	/*function _get ($oid) {
		// equivalent to using new Thing ($oid), but much more efficient.
		if (in_array ($oid, TDB::$things)) {
			// if object is in the cache, fire it up
			return TDB::$things[$oid];
		} else {
			if (is_numeric ($oid)) { // -ve: creates object; +ve: returns object
				$obj = new Thing ($oid);
			} elseif (is_string ($oid) && substr ($oid, 0, 1) == '#') { // probably its name then... shouldn't be allowed, but meh
				$obj = FindObject ($oid, substr ($oid, 1));
			}
			if ($obj->oid) { // "found the thing!"
				TDB::$things[$obj->oid] = $obj;
				return $obj;
			} else {
				die ("Cannot find something called '$oid'.");
			}
		}
	}*/
?>