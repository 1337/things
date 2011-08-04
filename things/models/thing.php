<?php
    // used to sync items and/or provide singleton-like behaviour when two identical IDs are created
    class Thing {
        // generic Things object.
        public $oid;
        private $cache;
        
        function __construct ($oid) {
            // if negative, a new object will be created for you automatically, 
            // with the type being the absolute value of $oid (-1 = 1 -> user, -2 = 2 -> group, ...)
            // parent::__construct ($oid);
            
            $this->oid = $oid;
            $this->cache = array ();
            
            if ($oid <= 0 && $oid != 0) {
                $oid = $this->Create (); // if object is sure not to exist, create it and update ID
            } elseif ($oid == 0) { // invalid - there is no object type 0
                die ('Error: cannot create Thing of index 0');
            }
            
            $thing_stack[$oid] = $this;
        }
        
        function __destruct () {
            
        }
        
        function Thing ($oid) {
            // PHP4 compat.
            $this->__construct();
            register_shutdown_function(array($this,"__destruct"));
        }
        
        function Create () {
            // an extendable function.
            return $this->CreateHelper ();
        }
        
        function CreateHelper () {
            // global $mysql;
            $type = abs ($this->oid); // convert to TYPE.
            $query = "INSERT INTO `objects` (`type`) VALUES ('$type')";
            $sql = mysql_query ($query) or die (mysql_error ());
            if ($sql) {
                $oid = mysql_insert_id ();
                // attempt to delete all old orphans linking to this object because that's impossible.
                $query = "DELETE FROM `hierarchy` WHERE `parent_oid`='$oid' OR `child_oid`='$oid'";
                $sql = mysql_query ($query) or die ("Orphanage cleanup failed");
                // $mysql->Query ("DELETE FROM `hierarchy` WHERE `parent_oid`='$oid' OR `child_oid`='$oid'");
                $this->oid = $oid; // update ID for this object.
                return $oid;
            }    
        }
        
        function Type ($type_id = 0) { // gsetter
            if ($type_id == 0) { // no type ID is supplied
                return $this->GetType ();
            } else { // a type ID is supplied
                return $this->SetType ($type_id);
            }
        }
        
        function GetType () {
            $oid = $this->oid;
            if (!isset ($this->cache['type']) || $this->cache['type'] <= 0) {
                $this->cache['type'] = SingleFetch ("SELECT `type` FROM `objects` WHERE `oid`='$oid'", "type");
            }
            return $this->cache['type'];
        }
        
        function SetType ($type_id) {
            // an extendable function.
            return $this->SetTypeHelper ($type_id);
        }
            
        function SetTypeHelper ($type_id) {
            global $mysql;
            if (ObjectTypeExists ($type_id)) {
                $oid = $this->oid;
                $sql = mysql_query ("UPDATE `objects` SET `type`='$type_id' WHERE `oid`='$oid'");
                // $mysql->Query ("UPDATE `objects` SET `type`='$type_id' WHERE `oid`='$oid'");
                if ($sql) {
                    $this->cache['type'] = $type_id; // update cache
                    return $sql;
                }
            }
            return null;
        }

        function GetPropsRaw () {
            // retrieves all properties associated with that object, in table format.
            /* array
              0 => 
                array
                  'pid' => string '1' (length=1)
                  'oid' => string '1' (length=1)
                  'name' => string 'name' (length=4)
                  'value' => string 'Administrator' (length=13)
              1 => 
                array
                  'pid' => string '2' (length=1)
                  'oid' => string '1' (length=1)
                  'name' => string 'Password' (length=8)
                  'value' => string '123456' (length=6) */
            $oid = $this->oid;
            $sql = mysql_query ("SELECT * FROM `properties` WHERE `oid`='$oid' ORDER BY `pid` ASC")
                    or die (mysql_error ());
            if ($sql && mysql_num_rows ($sql) > 0) {
                $roller = array ();
                $recon = array ();
                while ($row = mysql_fetch_assoc ($sql)) {
                        $val = $row['value'];
                        if (strlen ($val) > 0 &&
                            substr ($val, 0, strlen (URL_PROP)) == URL_PROP) {
                            // detect URL-based property, and replace it with the property value
                            $fn = $this->GetPropFile ($val);
                            if (file_exists ($fn)) {
                                $contents = implode('', file($fn));
                                $row['value'] = $contents;
                            } else {
                                $row['value'] = "$val"; // if file not found, property is nothing
                            }
                        } else {
                            // retain original value
                        }
                    $roller[] = $row;
                }
                return $roller;
            } else {
                return null;
            }
        }
        
        function GetProps () {
            // transform GetPropsRaw output to PHP array format;
            /* array
              'name' => string 'Administrator' (length=13)
              'Password' => string '123456' (length=6)
              'email' => string 'lpppppl@gmail.com' (length=17)
              'type' => string '1' (length=1) */
            if (!isset ($this->cache['props']) || sizeof ($this->cache['props']) == 0) {
                $props = $this->GetPropsRaw ();
                if (is_null ($props)) {
                    return null;
                } else {
                    $roller = array ();
                    foreach ($props as $prop) {
                        // higher ID properties will overwrite existing ones
                        $roller[strtolower ($prop['name'])] = $prop['value'];
                    }
                    $this->cache['props'] = $roller;
                    return $roller;
                }
            } else {
                // cache found, return it
                return $this->cache['props'];
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
        
        function GetPropFile ($propurl = '', $ext = 'txt') {
            // if no propurl is provided, find an available, writable file.
            //     it will not come with prop://.
            // if a propurl is provided (e.g. prop://ddsdads), 
            //     resolve it and turn it into a full file name.
            
            // does not check if file actually exists.
            $was_at = getcwd ();
            chdir (THINGS_PROPS_DIR); // change to the props folder
            
            if (strlen ($propurl) == 0) {
                do {
                    $randchars = substr (md5(time().rand()), 0, 10); // pick random string
                    $filename = "$randchars.$ext";
                } while (file_exists ($filename) == true);
                return $filename; // '' => prop://dcea2b9e04.txt
            } else {
                return THINGS_PROPS_DIR . substr ($propurl, strlen (URL_PROP)); // prop://dcea2b9e04.txt => '/var/www/things/props/dcea2b9e04.txt
            }
        }
        
        function DelPropFiles ($prop = '') {
            // deletes property files associated with this object.
            // if $prop (type string) is given, will only delete the file for that property.
            $oid = $this->oid;
            if (strlen ($prop) > 0) {
                $query = "SELECT `pid`, `value` FROM `properties` 
                           WHERE `oid`='$oid'
                             AND `name`='$prop'";
            } else {
                $query = "SELECT `pid`, `value` FROM `properties` 
                           WHERE `oid`='$oid'";            
            }
            $sql = mysql_query ($query) or die (mysql_error ());
            if (mysql_num_rows ($sql) > 0) { // at least one row represents existing property
                while ($temp_row = mysql_fetch_assoc ($sql)) {
                    if (substr ($temp_row['value'], 0, strlen (URL_PROP)) == URL_PROP) {
                        $filename = $this->GetPropFile ($temp_row['value']);
                        if (strlen ($filename) > 0 && file_exists (THINGS_PROPS_DIR . $filename)) {
                            unlink (THINGS_PROPS_DIR . $filename); // try to remove expired property files for this property
                        }
                    }
                }
            }
        }
        
        function SetProp ($prop, $val) {
            // wrap wrap wrap.
            return $this->SetPropsHelper (array ($prop=>$val));
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

                foreach ($what as $name => $value) {
                    $name = escape_data ($name);    
                    // value is escaped later if prop is written into db                
                    if (strlen ($value) > 256) { // 256 is the URL threshold
                        // delete previous property file
                        $this->DelPropFiles ($name);
                        // store this inside a text file instead...
                        $filename = $this->GetPropFile (); // 14b8f2791e.txt
                        try {
                            $fh = fopen (THINGS_PROPS_DIR . '/' . $filename, 'w');
                            fwrite ($fh, $value);
                            fclose ($fh);
                            $value = URL_PROP . $filename; // swap out the value for this. prop://14b8f2791e.txt
                        } catch (Exception $e) {
                            $value = escape_data ($value); // failed, pretend nothing happened
                        }
                    } else {
                        $value = escape_data ($value);
                    }
                    $query = "SELECT `pid`, `value` FROM `properties` 
                               WHERE `oid`='$oid'
                                 AND `name`='$name'";
                    $sql = mysql_query ($query) or die (mysql_error ());
                    if (mysql_num_rows ($sql) == 0) { // no existing key
                        $query = "INSERT INTO `properties` (`oid`,`name`,`value`)
                                       VALUES ('$oid','$name','$value')";
                    } else { // at least one row represents existing property
                        $query = "UPDATE `properties` SET `value`='$value' 
                                   WHERE `oid`='$oid' AND `name`='$name'";
                    }
                    $sql = mysql_query ($query) or die (mysql_error ());
                }
                
                // $this->cache['props'] = array_merge ($this->cache['props'], $what); // update cache
                $this->cache['props'] = array (); // flush cache
                return $sql;
            }
        }
        
        function DelProps ($what) {
            // global $mysql;
            // accepts an of names, e.g. array ('views','rating', 'status')
            // and deletes all properties with any of those names.
            $oid = $this->oid;
            if ($oid > 0) {
            
                $query = "DELETE FROM `properties` WHERE `oid`='$oid' AND `name` IN (";
                foreach ($what as $eh) {
                    
                    $this->DelPropFiles ($eh); // delete all property files associated with this property
    
                    $eh = escape_data ($eh);
                    $query .= "'$eh',";
                    
                    // clear entry in cache
                    $this->cache['props'][$eh] = null;
                }
                $query = substr ($query, 0, strlen ($query) -1) . ')';
                $sql = mysql_query ($query) or die (mysql_error ());
                // $mysql->Query ($query);
            }
        }

        function DelPropsAll () {
            // removes all properties of this object.
            // global $mysql;
            $oid = $this->oid;
            if ($oid > 0) {
                $this->DelPropFiles (); // delete all property files associated with this object
                
                $query = "DELETE FROM `properties`
                                 WHERE `oid`='$oid'";
                $sql = mysql_query ($query) or die (mysql_error ());
                
                $this->cache['props'] = null;
                return $sql;
            }
        } function DelAllProps () { return $this->DelPropsAll (); }
        
        function GetChildren ($type_id = 0, $order_by = "`child_oid` ASC") {
            // returns all children object IDs associated with this one.
            // if $type_id is supplied, returns only children of that type.
            $oid = $this->oid;
            if ($oid > 0) {
                $children = array ();
                if ($type_id > 0) {
                    $query = "SELECT ua.`child_oid` 
                                FROM `hierarchy` as ua, `objects` as ub
                               WHERE ua.`parent_oid` = '$oid'
                                 AND ua.`child_oid` = ub.`oid`
                                 AND ub.`type` = '$type_id'
                            ORDER BY $order_by";
                } else { // no $typeid supplied
                    $query = "SELECT `child_oid` FROM `hierarchy`
                               WHERE `parent_oid` = '$oid'
                            ORDER BY $order_by";
                }
                $sql = mysql_query ($query) or die ("Error 330: " . mysql_error ());
                if ($sql && mysql_num_rows ($sql) > 0) {
                    $roller = array ();
                    while ($tmp = mysql_fetch_assoc ($sql)) {
                        $roller[] = $tmp['child_oid'];
                    }
                    return $roller;
                }
            }
            return array (); // everything fails --> return empty array
        }
        
        function SetChildren ($what) {
            // appends a new parent-child relationship into the hierarchy table.
            // accepts array ('child1ID','child2ID',...)
            
            if (!is_array ($what)) {
                $what = array ($what); // a string / int, convert it to string.
            }
            
            if (sizeof ($what) > 0) {
                $oid = $this->oid;
                if ($oid > 0) {
                    foreach ($what as $child) {
                        $child = escape_data ($child);
                        $query = "SELECT `parent_oid` FROM `hierarchy` 
                                    WHERE `child_oid`='$child'
                                      AND `parent_oid`='$oid'";
                        $sql = mysql_query ($query) or die (mysql_error ());
                        if (mysql_num_rows ($sql) == 0) { // no existing key
                            $query = "INSERT INTO `hierarchy` (`parent_oid`,`child_oid`)
                                                         VALUES ('$oid','$child')";
                            $sql = mysql_query ($query) or die ("Error 362: " . mysql_error ());
                        } // else: already there, do nothing
                    }
                    return $sql;
                }
            } else {
                return true; // inserting nothing is a success
            }
        } function SetChild ($what) { return $this->SetChildren (array ($what)); }

        function DelChildren ($child_ids) {
            // removes hierarchical data of some of this object's children.
            // accepts (parent1id, parent2id, ...)
            $oid = $this->oid;
            if ($oid > 0 && sizeof ($child_ids) > 0) {
                $query = "DELETE FROM `hierarchy`
                                 WHERE `parent_oid`='$oid'
                                  AND `child_oid` IN (";
                foreach ($child_ids as $eh) {
                    $eh = escape_data ($eh);
                    $query .= "'$eh',";
                }
                $query = substr ($query, 0, strlen ($query) -1) . ')'; // remove last comma, then add )
                $sql = mysql_query ($query) or die ("Error 385: " . mysql_error () . " | " . $query);
                return $sql;
            }
        }        
        
        function DelChild ($child_id) {
            // might as well
            $this->DelChildren (array ($child_id));
        }
        
        function GetParents ($type_id = 0, $order = "ORDER BY `parent_oid` ASC") {
            // there are no limits to the number of parents.
            // returns all parent objects associated with this one.
            // if $type_id is supplied, returns only parents of that type.
            $oid = $this->oid;
            if (isset ($this->cache['parents']) && sizeof ($this->cache['parents']) > 0) {
                return $this->cache['parents'];
            } else {
                if ($oid > 0) {
                    $children = array ();
                    if ($type_id > 0) {
                        $query = "SELECT ua.`parent_oid`
                                     FROM `hierarchy` as ua, `objects` as ub
                                    WHERE ua.`child_oid` = '$oid'
                                      AND ua.`parent_oid` = ub.`oid`
                                      AND ub.`type` = '$type_id' $order";
                    } else {
                        $query = "SELECT `parent_oid` FROM `hierarchy`
                                    WHERE `child_oid` = '$oid' $order";
                    }
                    $sql = mysql_query ($query) or die (mysql_error ());
                    if ($sql && mysql_num_rows ($sql) > 0) {
                        $roller = array ();
                        while ($tmp = mysql_fetch_assoc ($sql)) {
                            $roller[] = $tmp['parent_oid'];
                        }
                        $this->cache['parents'] = $roller;
                        return $roller;
                    }
                }
                $this->cache['parents'] = array ();
                return array ();
            }
        }
        
        function SetParents ($what) {
            // appends a new parent-child relationship into the hierarchy table.
            // accepts array ('parent1::ID','parent2::ID',...)
            /*global $user;
			if (isset ($user) && get_class ($user) == 'User' && 
			    class_exists ('Auth') && function_exists ('CheckAuth')) {
				// stop applies only if the auth library is used
				if (!($user->GetChildren ($this->oid) || 
				    CheckAuth ('administrative privilege', true))) {
					// CustomException ("You just tried to move parentage of something you do not own.");
					return false;
				}
			}*/
            if (sizeof ($what) > 0) {
                $oid = $this->oid;
                $this->cache['parents'] = array (); // flush cache
                foreach ($what as $parent) {
                    $parent = escape_data ($parent);
                    $query = "SELECT `child_oid` FROM `hierarchy` 
                                WHERE `parent_oid`='$parent'
                                  AND `child_oid`='$oid'";
                    $sql = mysql_query ($query) or die (mysql_error ());
                    if (mysql_num_rows ($sql) == 0) { // no existing key
                        $query = "INSERT INTO `hierarchy` (`parent_oid`,`child_oid`)
                                                     VALUES ('$parent','$oid')";
                        $sql = mysql_query ($query) or die (mysql_error ());
                    } // else: already there, do nothing
                }
                return $sql;
            } else {
                return true; // inserting nothing is a success
            }
        }

        function DelParents ($parent_ids) {
            // removes hierarchical data where this object is the parent's child.
            // accepts (parent1id, parent2id, ...)
            $oid = $this->oid;
            if ($oid > 0) {
                $this->cache['parents'] = array (); // flush cache
                $query = "DELETE FROM `hierarchy`
                                 WHERE `child_oid`='$oid'
                                  AND `parent_oid` IN (";
                foreach ($parent_ids as $eh) {
                    $eh = escape_data ($eh);
                    $query .= "'$eh',";
                }
                $query = substr ($query, 0, strlen ($query) -1) . ')';
                $sql = mysql_query ($query) or die (mysql_error ());
                return $sql;
            }
        }
        
        function DelParent ($parent_id) {
            // might as well
            $this->DelParents (array ($parent_id));
        }
        
        function DelParentsAll () {
            // removes hierarchical data where this object is someone's child.
            // effectively removes all of the object's parents (orphanating?).
            $oid = $this->oid;
            if ($oid > 0) {
                $this->cache['parents'] = array (); // flush cache
                $query = "DELETE FROM `hierarchy`
                                 WHERE `child_oid`='$oid'";
                $sql = mysql_query ($query) or die (mysql_error ());
                return $sql;
            }
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
            
            $query = "SELECT * FROM `objects` WHERE `oid` = '$nid'";
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
            }
        }
        
        function Duplicate () {
            // creates a data-identical twin of this object.
            // the new twin will have the same parents and have the same children (!!)
            // the new twin WILL inherit URL_PROPs, but not duplicates of their values
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
			$oid = $this->oid;
			$query = "DELETE FROM `objects`
							 WHERE `oid`='$oid'";
			$sql = mysql_query ($query) or die (mysql_error ()); // delete object first
			$this->DelParentsAll (); // then the properties and stuff (no orphaning on crash)
			$this->DelPropsAll ();
        }
        
    }
?>
