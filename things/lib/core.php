<?php
    // things core
    // functions and stuff

    if (count(get_included_files()) <= 1) {
        // this file must be included.
        die ();
    }
    
	function DefaultTo () {
        // successively checks all supplied variables and returns the
		// first one that isn't null or empty or false or not set
		// (but 0 is valid and will be returned)
		$args = func_get_args ();
		$argv = func_num_args ();
		for ($i = 0; $i < $argv; $i ++) {
			if (! (is_null ($args[$i]) ||
			       $args[$i] === '' ||
				   $args[$i] === false ||
				   !isset ($args[$i]))) {
			    return $args[$i];
		    }
		}
		return (!isset ($wat) || $wat == '' || $wat == null) ? $wut : $wat;
	}
	
    function WriteAccessHash () {
        // this is actually a generic hashing algorithm.
        // add as many arguments as you like, and we will add them to the game.
        $vars = func_get_args ();
        $salt = md5 ("write");
        foreach ($vars as $var) {
            $salt = sha1 ($salt . $var);
        }
        return substr ($salt, 0, 8);
    }
    
    function println($what, $hdng = 'p') {
        if ($hdng >= 1 && $hdng <= 6) {
            $heading = 'h' . $hdng;
        } elseif (strlen($hdng) == 0) {
            $heading = 'p';
        } else {
            $heading = $hdng;
        }        
        echo("<$heading>$what</$heading>\n");
    }

    function CollapseArray ($arr) {
        // array (1,2, 'hello', "haha", '\'') --> '1','2','hello','haha','\''
        // char 11 is used as a separator. It is device control 1.
        // any modern string with that character is just plain stupid.
        return str_replace (chr (11), 
                             "', '", 
                             "'" . implode (chr (11), array_map ("addslashes", $arr)) . "'");
    }
    
    function MergeFirst () {
        // first array takes precedence.
        // print_r (array ('thing'=>'2') + array ('thing'=>'3'));"
        // Array ( [thing] => 2 )
        $empty = array ();
        foreach (func_get_args () as $arg) {
            $empty = $empty + $arg;
        }
        return $empty;
    }
    
    function MergeLast () {
        // last array takes precedence.
        // print_r (array_merge (array ('thing'=>'2'), array ('thing'=>'3')));"
        // Array ( [thing] => 3 )
        $empty = array ();
        foreach (func_get_args () as $arg) {
            $empty = array_merge ($empty, $arg);
        }
        return $empty;
    }
    
    function SingleFetch ($query, $column = '') {
        // used to fetch a single row or a single value from a query.
        // to get a single value, specify $column.
        $sql = mysql_query ($query) or die (mysql_error ());
        if ($sql && mysql_num_rows ($sql) > 0) {
            $tmp = mysql_fetch_assoc ($sql);
            if (strlen ($column) > 0) {
                return $tmp[$column];
            } else {
                return $tmp; // if not specified, return whole row.
            }
        } else {
            return null;
        }
    }
    
    function ColumnFetch ($query, $column, $key = '') {
        /* return all values from a single column:
            col[0]=1
            col[1]=4
            col[2]=9, ...
            
            => [1,4,9]
			
			if $key is given, values from that row will be used as key.
			
			col[john] = 1
			col[...
			
			
			 */
        $sql = mysql_query ($query) or die (mysql_error ());
        if ($sql && mysql_num_rows ($sql) > 0) {
            $buffer = array ();
            while ($tmp = mysql_fetch_assoc ($sql)) {
				if (array_key_exists ($key, $tmp)) {
                    $buffer[$tmp[$key]] = $tmp[$column];
				} else {
					$buffer[] = $tmp[$column];
				}
            }
            return $buffer;
        } else {
            return null;
        }
    }
    
    function ack_r3 (&$array, $case=CASE_LOWER, $flag_rec=false) {
        // found here, no owner: http://php.net/manual/en/function.array-change-key-case.php
        $array = array_change_key_case ($array, $case);
        if ($flag_rec) {
            foreach ($array as $key => $value) {
                if (is_array ($value)) {
                    ack_r3 ($array[$key], $case, true);
                }
            }
        }
    }

    function GetTypeId ($name) {
        global $things_types;
        if (array_key_exists (strtolower ($name), $things_types)) {
            return $things_types[strtolower ($name)];
        } else {
            return null; // no ID for this type! you made it up.
        }
    }
    
    function GetTypeName ($tid) {
        // enter a type ID (see above for what you've defined) and get its name.
        global $things_types;
        $tmp = array_flip ($things_types);
        if (array_key_exists ($tid, $tmp)) {
            return $tmp[$tid];
        } else {
            return "NULL";
        }
    }
    
    function GetObjectByName ($name) {
        // you can't refer to an object by name because names can be duplicates.
    }
    
    function GetObjectType ($oid) {
        // wrapper.
        $ob = new Thing ($oid);
        return $ob ->GetType ();
    }

    function CreateObject ($type_id, $props = array ()) {
        // creates an object in the database. $type does nothing in this generic class.
        // if $props (name=>val, name=>val...) is specified, the object will 
        // be created with these properties.
        // returns the object ID.
        $type_id = escape_data ($type_id);
        try {
            $type = GetTypeName ($type_id);
            $a = new $type (-$type_id);
            if (is_array ($props) && sizeof ($props) > 0) {
                $a->SetProps ($props);
            }
            return $a->oid;
        } catch (Exception $e) {
            die ("CreateObject: you're doing it wrong");
            return null;
        }
    }

    function FindObject ($name, $type) {
        // returns an object ID depending on the name and type supplied.
        // name is stored as properties->name='name'
        // you can use this function to check if a duplicate object exists.
        $name = escape_data ($name);
        $type = escape_data ($type);
        if (strlen ($name) > 0 && strlen ($type) > 0 ) {
            $query = "SELECT ua.`oid` FROM `objects` as ua, `properties` as ub
                       WHERE ua.`type`='$type'
                         AND ua.`oid`=ub.`oid`
                         AND ub.`name`='name'
                         AND ub.`value`='$name'
                       LIMIT 1";
            $sql = mysql_query ($query) or die (mysql_error ());
            if ($sql && mysql_num_rows ($sql) > 0) {
                $tmp = mysql_fetch_assoc ($sql);
                return $tmp['oid']; // return object ID.
            } else {
                return null; // no object found
            }
        } else {
            // searching for an impossible value --> NOTHING.
            return null;
            // die ("FindObject: you're doing it wrong");
        }
    }
    
    function ObjectExists ($oid) {
        $query = "SELECT `oid` FROM `objects`";
        $sql = mysql_query ($query) or die (mysql_error ());
        return (mysql_num_rows ($sql) >= 1);
    }
    
    function ObjectTypeExists ($tid) {
        // enter a type ID to see if it is a valid Things type.
		// requires this script to get past the last line.
        global $things_types;
		return in_array ($tid, $things_types);
    }

    function CustomException ($what = 'Something terrible happened.') {
        // likely the case that some serious shit has occurred and the page
        // needs to stop running
        
        die ('
        <html>
            <head>
                <style type="text/css">
                    #infopane {
                        background:#eeece0;
                        border: 3px #C65 solid;
                        border-radius:5px;
                        display:block;
                        font-family: Calibri, Tahoma, Arial, sans-serif;
                        height:200px;
                        left:50%;
                        margin:-120px auto auto -220px;
                        padding:20px;
                        position:absolute;
                        top:50%;
                        width:400px;
                    }
                    #infopane h1 {
                        text-align:center;
                    }
                    #flexfield {
                        height:100px;
                    }
                </style>
            </head>
            <body>
                <div id="infopane">
                    <h1>Oops.</h1>
                    <div id="flexfield">' . $what . '</div>
                    <hr />
                    <div>Report this problem to the site administrator.</div>
                </div>
            </body>
        </html>');
    }

    // define types. before this, no Things objects can be initiated.
    $things_types = ColumnFetch ("SELECT * FROM `types`", 'tid', 'name');
	$things_types['all_objects'] = 9001;
    foreach ($things_types as $name=>$value) {
        define ($name, $value);        // positive
        define ("NEW_$name", -$value); // new constants, negative
        $name = strtoupper ($name);
        define ($name, $value);        // positive
        define ("NEW_$name", -$value); // new constants, negative
    }
?>
