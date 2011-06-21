<?php
    require_once ('.things.php'); // need it for some functions
    require_once (PROOT . 'models/thing.php'); // need it for some functions
    require_once (PROOT . 'lib/core.php'); // need it for fetching
    
    class Things {
        // generic Things object collection.
        public $objs;
        public $type;
        
		function __construct ($type = -1) {
            // if a type is specified, Things keeps a reference of all objects of this type.
            if ($type > 0) {
                $this->SetType ($type);
                // $objs = $this->GetObjects ($type);
                $this->SetObjects ();
            }			
		}
		
        function Things ($type = -1) {
			$this->__construct ($type); // PHP 4
        }
        
        function GetType () {
            return $this->type;
        }
        
        function SetType ($type) {
            $type = escape_data ($type);
            $this->type = $type;
            $this->SetObjects (); // fetch+update objects right after type updates. makes sense.
        }
        
        function GetObjects ($refresh = false) {
			// retrieves all objects in this group.
			// since Things don't need to correspond to a particular query, you
			// must use SetObjects to refresh if writes have occurred.
			// setting $refresh to true does the same thing.
			if ($refresh) {
    			$this->SetObjects ();
			}
            return $this->objs;
        }
        
        function GetObjectsTypes () {
            // collects the types and associated objects in the format
            // type_0: [obj_id,obj_id,obj_id], type_1:...
            $types = array (); // new
            foreach ($this->objs as $obj_id) {
                $obj = new Obj ($obj_id);
                $obj_type = $obj->GetType ();
                $types[$obj_type][] = $obj_id;
            }
            return $types;
        }
        
        function AddObjects ($which) {
            // adds $which (oids) to $objs. $which can be both oid or array(oids).
            if (is_array ($which) && sizeof ($which) > 0) { // array (oids)
                foreach ($which as $todel) {
                    $this->AddObjects ($todel); // recursion!
                }
            } else { // oid
                $this->objs[] = $which;
            }
        }
        
        function SetObjectsRaw ($ids) {
            // accepts an array of object IDs and directly treats them as an object group.
            $this->objs = $ids;
            
        }
        
        function SetObjects ($query_more = 'ORDER BY `type` ASC') {
            // you can use ALL_OBJECTS to retrieve all objects in db, regardless of type.
            // might get slow if the number objects reaches the billions.
            // by then, you'll learn to use ORDER BY `oid` DESC LIMIT 10000.
            $type = $this->type;
            if ($type >= 0) {
                if ($type == ALL_OBJECTS) {
                    $query = "SELECT `oid` FROM `objects` $query_more";
                } else {
                    $query = "SELECT `oid` FROM `objects` WHERE `type`='$type' $query_more";
                }
                $sql = mysql_query ($query) or die (mysql_error ());
                if ($sql && mysql_num_rows ($sql) > 0) {
                    $roller = array ();
                    while ($tmp = mysql_fetch_assoc ($sql)) {
                        $roller[] = $tmp['oid'];
                    }
                    $this->objs = $roller; // cache
                    return $roller; 
                } else {
                    // die ("NO!");
                    return array (); // "no objects of this type"
                }
            } else {
                die ("Cannot get objects because of invalid type.");
                return array (); // die ("Invalid type");
            }
        }
        
        function DelObjects ($which) {
            // remove $which (oids) from $objs. $which can be both oid or array(oids).
            if (is_array ($which) && sizeof ($which) > 0) { // array (oids)
                foreach ($which as $todel) {
                    $this->DelObjects ($todel); // recursion!
                }
            } else { // oid
                if (in_array ($which, $this->objs)) {
                    // remove the key that corresponds to the item.
                    unset ($this->objs[array_search ($which, $this->objs)]);
                }
            }
        } function RemoveObjects ($which) { return $this->DelObjects ($which); }
        
        function DelAllObjects () {
            // obliviates all objects in this Things.
            return $this->DelObjects ($this->objs);   
        } function RemoveAllObjects ($which) { return $this->DelAllObjects (); }
        
        function DelObjectsProps ($property_names) {
            // accepts an of names, e.g. array ('views','rating', 'status')
            // and deletes all properties with any of those names.
            // apply given props to all objects in this group.
            if (sizeof ($this->objs) > 0) { // if there are objects
                foreach ($this->objs as $oid) {
                    if (class_exists ('Obj')) {
                        $oobj = new Obj ($oid);
                        $oobj->DelProps ($property_names);
                    } else {
                        die ('Objects not initialised');
                    }
                }
            }            
        }
        
        function SetObjectsProps ($properties) {
            // apply given props to all objects in this group.
            if (sizeof ($this->objs) > 0) { // if there are objects
                foreach ($this->objs as $oid) {
                    if (class_exists ('Obj')) {
                        $oobj = new Obj ($oid);
                        $oobj->SetProps ($properties);
                    } else {
                        die ('Objects not initialised');
                    }
                }
            }
        }
        
        function FindObject ($name) { // wrapper for global FindObject library
            // returns all objects with this name in this type group.
            return FindObject ($name, $this->type);
        }
        
        function FilterByProp ($prop, $propval) {
            // retains only those objects with prop[$prop]=$propval.
            if (sizeof ($this->objs) > 0) {
                $query = "SELECT ua.`oid` 
                            FROM `objects` AS ua,
                                 `properties` AS uc
                           WHERE ua.`oid` = uc.`oid`
                             AMD uc.`name`='$prop'
                             AND uc.`value`='$propval'";
                $sql = mysql_query ($query) or die (mysql_error ());
                $roller = array ();
                if ($sql) {
                    while ($tmp = mysql_fetch_assoc ($sql)) {
                        $roller[] = $tmp['oid'];
                    }
                    // store and return entries that are in both arrays
                    // [all objects of this type] & [all objects with this value]
                    $this->objs = array_intersect ($roller, $this->objs);
                }
            }
        }
		
		function FilterByProps ($props, $any = false) {
			// given an array ('prop_name' => 'val'), remove all objects
			// in this class that does not meet the criteria, depending on 
			// $any = false: if ALL properties match, the object is removed
			// $any = true: if ANY property matches, the object is removed
			if (sizeof ($props) > 0) {
				if ($any) { // if ANY property matches, remove it
					foreach ($props as $prop => $val) {
						$this->FilterByProp ($prop, $val);
	    			}
				} else { // if ALL properties match, remove it
				    foreach ($this->GetObjects () as $oid) {
						$keep = true;
						$obj = new Thing ($oid);
						foreach ($props as $prop => $val) {
							if ($obj->GetProp ($prop) == $val) {
							    $keep = true;
							} else {
								$keep = $keep && false;
							}
						}
						if (!$keep) {		
							// difference = this object
							$this->objs = array_diff ($this->objs, array ($oid));
						}
					}
				}
			}
		}
        
        function FilterByPreg ($prop, $preg) {
            // retains only those objects whose prop[$prop] matches preg.
            // this is very slow.
            // it does not search in FS data, e.g. Post bodies.
            if (sizeof ($this->objs) > 0) {
                foreach ($this->objs as $oid) {
                    $obj = new Thing ($oid);
                    if (is_null ($obj)) { // the object does not exist.
                        $this->DelObjects ($oid); // remove it
                    } else {
                        $val = $obj->GetProp ($prop);
                        if (is_null ($val)) { // the key does not exist.
                            $this->DelObjects ($oid); // remove it
                        } else {
                            if (preg_match ($preg, $val) <= 0) { // no match.
                                $this->DelObjects ($oid);
                            } else {
                                // well done...
                            }
                        }
                    }
                }
            }
        }
        
        function PregReplace ($prop, $preg, $replacement) {
            // performs preg_replace on all child objects' property $prop.
            // it does not search in FS data, e.g. Post bodies.
            if (sizeof ($this->objs) > 0) {
                foreach ($this->objs as $oid) {
                    $obj = new Thing ($oid);
                    if (!is_null ($obj)) { // the object does exist.
                        $val = $obj->GetProp ($prop);
                        if (!is_null ($val)) { // the key does exist.
                            $obj->SetProps (array (
                                $prop => preg_replace ($preg, $replacement, $val)
                            ));
                        }
                    }
                }
            }
        }

    }
?>
