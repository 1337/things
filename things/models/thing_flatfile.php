<?php
    require_once ('.things.php');
    require_once (PROOT . 'models/simpledb.php');
    
	// define types. before this, no Things objects can be initiated.
	$db = new SimpleDB (THINGS_OBJECTS_FLATFILE_DB);
	if (sizeof ($db->types) == 0) {
		$db->types = array (
			'user'	=> '1',
			'group' => '2',
			'privilege' => '3',
			'post' => '4',
			'tag' => '5',
			'category' => '6',
			'site' => '7',
			'section' => '8',
			'setting' => '9',
			'page' => '10',
			'gallery' => '11',
			'validator' => '12',
			'ajaxfield' => '13',
			'ticket' => '14',
			'dummy' => '15'
		);
		$db->save ();
    	die ("done!");
	} else {
		$things_types = $db->types;
	}
	
    $things_types['all_objects'] = 9001;
    foreach ($things_types as $name => $value) {
        define ($name, $value);        // positive
        define ("NEW_$name", -$value); // new constants, negative
        $name = strtoupper ($name);
        define ($name, $value);        // positive
        define ("NEW_$name", -$value); // new constants, negative
    }
	
    class Thing {
        // flatfile thing is not PHP4 compatible.
        public $oid;
        private $db;
        
        function __construct ($oid) {
            // if negative, a new object will be created for you automatically, 
            // with the type being the absolute value of $oid (-1 = 1 -> user, -2 = 2 -> group, ...)
            $this->oid = $oid;
            $this->db = new SimpleDB (THINGS_OBJECTS_FLATFILE_DB);
            if ($oid <= 0 && $oid != 0) {
                $oid = $this->Create (); // if object is sure not to exist, create it and update ID
            }
        }
        
        function Create () {
            $type = abs ($this->oid); // convert to TYPE.
			$this->db->objects[$this->oid]['type'] = $type; // replace object with cache
            $this->db->save ();
        }
     
        function GetType () {
            return $this->GetProp ('type');
        }
     
        function SetType ($type_id) {
            $this->SetProp ('type', $type_id);
            return true; // can't fail now
        }

        function GetPropsRaw () {
            // no raw props for flatfiles.
            return false;
        }
     
        function GetProps () {
            $this->db->reload ();
            
            if (array_key_exists ($this->oid, (array) $this->db->objects)) {
                return $this->db->objects[$this->oid];
            } else {
                return null; // this object has no properties.
            }
        }
     
        function GetProp ($name) {
            // retrieve a single value off the Props array. no speed advantage.
            $results = $this->GetProps ();
            if (!is_null ($results) && array_key_exists ($name, $results)) {
                return $results[$name];
            } else {
                return null;
            }
        }

        function SetProp ($prop, $val) {
            return $this->SetProps (array ($prop=>$val));
        }
     
        function SetProps ($what) {
            // an extendable function.
            return $this->SetPropsHelper ($what);
        }

        function SetPropsHelper ($what) {
            // accepts an array ('name'=>'value','name'=>'value') things and write them.
            if (sizeof ($what) > 0) {
                $oid = $this->oid;
                
                ack_r3 ($what); // change all keys to lower case
                $what = array_merge (
                    array ('obj_mtime' => time ()), // mod time
                    $what, // new props
                    (array) $this->GetProps () // old props
                );
                
                $this->db->save ();
                return true;
            }
        }
     
        function DelProps ($what) {
            // accepts an of names, e.g. array ('views','rating', 'status')
            // and deletes all properties with any of those names.
            $oid = $this->oid;
            if ($oid > 0) {
                foreach ($what as $eh) {
                    if ($eh != "type" && $eh != "children") { // type and children are protected.
                        unset ($this->db->objects[$oid][$eh]);
                    }
                }
            }
        }

        function DelPropsAll () {
            // removes all properties of this object.
            // global $mysql;
            $this->DelProps (array_keys ($this->GetProps ()));
        } function DelAllProps () { return $this->DelPropsAll (); }
        
        function GetChildren ($type_id = 0, $order_by = "`child_oid` ASC") {
            // returns all children object IDs associated with this one.
            // if $type_id is supplied, returns only children of that type.
            $oid = $this->oid;
            if ($oid >= 0 && $type_id == 0) {
                return $db->objects[$oid]['children'];
            } elseif ($oid >= 0) {
                $buffer = array ();
                foreach ($this->db->objects[$oid]['children'] as $child) {
                    $ch = new Thing ($child);
                    if ($ch->GetType () == $type_id) {
                        $buffer[] = $child;
                    }
                }
                return $buffer;
            }
            return array (); // everything fails --> return empty array
        }
     
        function SetChildren ($what) {
            // appends a new parent-child relationship into the hierarchy table.
            // accepts array ('child1ID','child2ID',...)
         
            if (!is_array ($what)) {
                $what = array ($what); // a string / int, convert it to string.
            }
            
            $this->db->objects[$this->oid]['children'] = array_merge (
                $what,
                $this->db->objects[$this->oid]['children']
            );
            
            $this->db->save ();
            
            return true;
        } function SetChild ($what) { return $this->SetChildren (array ($what)); }

        function DelChildren ($child_ids) {
            array_remove_values ($this->db->objects[$this->oid]['children'], $child_ids);
        }     
     
        function DelChild ($child_id) {
            $this->DelChildren (array ($child_id));
        }
     
        function GetParents ($type_id = 0) {
            // returns all parent objects associated with this one.
            // if $type_id is supplied, returns only parents of that type.
            $parents = array ();
            foreach ((array) $this->db->objects as $pid => $object) {
                if (array_key_exists ('children', $object) &&
                    in_array ($this->oid, $object['children'])) {
                    $parents[] = $pid;
                }
            }
            return $parents;
        }
     
        function SetParents ($what) {
            // appends a new parent-child relationship into the hierarchy table.
            // accepts array ('parent1::ID','parent2::ID',...)
            foreach ((array) $what as $parent_id) {
                $this->db->objects[$parent_id]['children'] = array_merge (
                    array ($this->oid),
                    $this->db->objects[$parent_id]['children']
                );
            }
            $this->db->save ();
        }

        function DelParents ($parent_ids) {
            // removes hierarchical data where this object is the parent's child.
            // accepts (parent1id, parent2id, ...)
            foreach ((array) $what as $parent_id) {
                array_remove_values (
                    $this->db->objects[$parent_id]['children'],
                    array ($this->oid)
                );
            }
               $this->db->save ();
        }
     
        function DelParent ($parent_id) {
            // might as well
            $this->DelParents (array ($parent_id));
        }
     
        function DelParentsAll () {
            // removes hierarchical data where this object is someone's child.
            // effectively removes all of the object's parents (orphanating?).
            $this->DelParents ($this->GetParents ());
        } function DelAllParents () { return $this->DelParentsAll (); }
     
     
// PERIPHERAL FUNCTIONS

        function CreateObject ($type, $props = array ()) {
            // creates the object. different from the global CreateObject is how
            // this one auto-associates this object as the parent of the one
            // being created.
         
            $noid = CreateObject ($type, $props);
            if ($noid > 0) { // assumed success
                $this->SetChildren (array ($noid));
                return $noid;
            } else {
                return null;         
            }
        }
     
        function ChangeID ($nid) {
            // attempt to change the ID of this object to the new ID.
            // attempt to resolve all references to this object.
         
            /* $query = "SELECT * FROM `objects` WHERE `oid` = '$nid'";
            $sql = mysql_query ($query) or die (mysql_error ());
            if (mysql_num_rows ($sql) == 0) { // target ID does not exist
                $pid = $this->oid;
                $query = "UPDATE `objects` 
                             SET `oid` = '$nid' 
                           WHERE `oid` = '$pid'";
                $sql = mysql_query ($query) or die (mysql_error ());
     
                $query = "UPDATE `hierarchy` 
                             SET `parent_oid` = '$nid' 
                           WHERE `parent_oid` = '$pid'";
                $sql = mysql_query ($query) or die (mysql_error ());
 
                $query = "UPDATE `hierarchy` 
                             SET `child_oid` = '$nid' 
                           WHERE `child_oid` = '$pid'";
                $sql = mysql_query ($query) or die (mysql_error ());
 
                $query = "UPDATE `properties` 
                             SET `oid` = '$nid' 
                           WHERE `oid` = '$pid'";
                $sql = mysql_query ($query) or die (mysql_error ());
                return true;
            } else {
                die ("Failed to reallocate object");
            } */
        }
     
        function Duplicate () {
            // creates a data-identical twin of this object.
            // the new twin will have the same parents and have the same children (!!)
            $new_object = new Thing (0 - $this->GetType ()); // create the object.
         
            $props = $this->GetProps ();
            $new_object->SetProps ($props); // duplicate properties.
         
            $parents = $this->GetParents ();
            $new_object->SetParents ($parents); // duplicate upper hierarchy.
         
            $children = $this->Children ();
            $new_object->SetChildren ($children); // duplicate lower hierarchy.
         
            return $new_object->oid; // return the object ID. Don't lose it!
        }
     
        function Destroy () {
            // removes an object from the database.
            // deletes the object, the relationship with parents, and their children.
            // children of this object will become orphans.
            global $user;
            if (isset ($user) && get_class ($user) == 'User' && 
                class_exists ('Auth') && function_exists ('CheckAuth')) {
                // stop applies only if the auth library is used
                if (!($user->GetChildren ($this->oid) || 
                    CheckAuth ('administrative privilege', true))) {
                    // CustomException ("You just tried to delete something you do not own.");
                    return false;
                }
            }
            unset ($this->db->objects[$this->oid]);
			$this->DelParentsAll (); // then the properties and stuff (no orphaning on crash)
            $this->DelPropsAll ();
        }
     
    }
?>