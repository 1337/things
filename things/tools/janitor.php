<?php
    require_once ('.things.php');

    class Janitor extends Things {
        function purge_objects_with_no_properties () {
            // straightforward.
            foreach ($this->objs as $obj_id) {
                $obj = new Thing ($obj_id);
                if (sizeof ($obj->GetProps ()) == 0) {
                    $obj->Destroy ();
                }
            }
        }
     
        function purge_orphans () {
            // destroys all objects which have no parents and no children.
            // since objects do not require hierarchical data to make sense,
            // it is not advised to create such a function.
        }
     
        function purge_properties_with_no_objects () {
            // props with null owner
            foreach ((array) $this->SetObjects () as $obj_id) {
                $obj = new Thing ($obj_id);
                $children = $obj->GetChildren (); // remove incorrect child references
                foreach ($children as $child_id) {
                    if (!ObjectExists ($child_id)) {
                        $obj->DelChildren (array (
                            $child_id
                        ));
                    }
                }

                $parents = $obj->GetParents (); // remove incorrect parent references
                foreach ($parents as $parent_id) {
                    if (!ObjectExists ($parent_id)) {
                        $obj->DelParents (array (
                            $parent_id
                        ));
                    }
                }
            }
        }
     
        function purge_orphaned_relations () {
            // hierarchies with null parent and child
        }
     
        function purge_property_files () {
            // no url_prop found for any files found in props path
        }

        function purge_error_logs ($path = '.', $verbose = false) {
            // delete all files named error_log in the specified folder.
            require_once (dirname (dirname (__FILE__)) . '/controllers/filesearch.class.php'); // local import
            $a = new FileSearch ();
            $files = $a->FileNameSearch ('error_log', $path);
            if (sizeof ($files) > 0) {
                foreach ($files as $file) {
                    unlink ($file);
                    if ($verbose) {
                        echo ("unlinked $file\n");
                    }
                }
            }
        }

        function replace_tabs ($path = '.', $verbose = false) {
            // replaces all tabs found in php files in a given folder.
            require_once (dirname (dirname (__FILE__)) . '/controllers/filesearch.class.php'); // local import

            $a = new FileSearch ();
            $files = $a->FileNameSearch ('.php', $path);
            if (sizeof ($files) > 0) {
                foreach ($files as $file) {
                    $fc = file_get_contents ($file);
                    if (strlen ($fc) > 0) {
                        if (strpos ($fc, chr (9)) !== false) {
                            $fc = str_replace (chr (9), '    ', $fc);
                            // file_set_contents ($file, $fc);
                            if ($verbose) {
                                echo ("processed $file\n");
                            }
                        }
                    }
                }
            }
        }
     
        function purge_backup_files ($path = '.', $verbose = false) {
            // delete all files named error_log in the specified folder.
            require_once (dirname (dirname (__FILE__)) . '/controllers/filesearch.class.php'); // local import
            $a = new FileSearch ();
            $files = $a->FileNameSearch ('.+\.b\d+\.bak', $path);
            if (sizeof ($files) > 0) {
                foreach ($files as $file) {
                    unlink ($file);
                    if ($verbose) {
                        echo ("unlinked $file\n");
                    }
                }
            }
        }
        
		function find_lost_properties () {
            // checks database to see if any property begins with URL_PROP (meaning the file is no longer there)
            $a = new Things (ALL_OBJECTS);
			foreach ($a->GetRealObjects () as $obj) {
				foreach ($obj->GetProps () as $prop => $val) {
					// print ($val);
					if (substr ($val, 0, strlen (URL_PROP)) == URL_PROP) {
						$oid = $obj->oid;
						echo ("<p><a href='../../object/$oid'>#$oid.$prop -> $val</a></p>");
					}
				}
			}
			echo ("done\n");
        }

		function find_orphan_properties ($return = false) {
            // checks inside the property folder and deletes anything
            // that doesn't correspond to a property.
            foreach (glob (THINGS_PROPS_DIR . "*.{txt,htm,inc,obj}", GLOB_BRACE) as $fn) {
	            $fn = basename ($fn);
	            $sql = "SELECT * FROM `properties` WHERE `value` LIKE '%$fn%'";
	            $res = mysql_query ($sql) or die (mysql_error ());
	            if (mysql_num_rows ($res) == 0) {
	                $items[] = $fn;
	            }
	        }
			if ($return) {
	            return $fn;
			} else {
				foreach ((array) $items as $item) {
					echo ("<p><a href='../props/$item'>$item</a></p>");
				}
			}
        }
		
        function purge_orphan_properties () {
            // checks inside the property folder and deletes anything
            // that doesn't correspond to a property.
            foreach ($this->find_orphan_properties (true) as $fn) {
				unlink (THINGS_PROPS_DIR . $fn);
				echo ("deleted $fn\n");
            }
	        echo ("done\n");
        }
     
        function compress_ids ($start = 2, $end = 2) {
            // compresses IDs from $start to $end.
            // for example, if DB object IDs go like 1,2,5,1000000, then
            // new IDs will be 1,2,3,4.
         
            // !! will make like a billion database calls.
			if ($start < 2) {
			    die ("Minimum is 2");
			}
			if ($end <= $start) {
				die ("Param 2 must be larger than param 1");
			}
            $i = $start;
            while (true) {
                if (ObjectExists ($i) && !ObjectExists ($i - 1)) {
                    $object_i = new Thing ($i);
                    $object_i->ChangeID ($i - 1);
                    echo ("Changed object #$i to #" . ($i - 1) . "\n");
                    $i -= 1; // because current object ID decreased by 1, we will move the pointer with it
                    ob_flush ();
                } else {
                    // happens when
                    // - object $i does not exist.
                    // - both objects $i and $i - 1 exist.
                    $i += 1;
                }
                if ($i > $end) {
                    break;
                }
            }
        }
     
        function dummy ($string) {
            echo ($string);
        }
    }
 
    if (isset ($_GET['func'])) {
        // call any function using ?func=purge_error_logs
        $a = new Janitor ();
        $b = @explode ('|', $_GET['params']);
        call_user_func_array (array ($a, $_GET['func']), $b);
    }
?>
