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
        
        function purge_orphan_properties () {
            // checks inside the property folder and deletes anything
            // that doesn't correspond to a property.
            foreach (glob (THINGS_PROPS_DIR . "*.{txt,htm,inc,obj}", GLOB_BRACE) as $fn) {
	            $fn = basename ($fn);
	            $sql = "SELECT * FROM `properties` WHERE `value` LIKE '%$fn%'";
	            $res = mysql_query ($sql) or die (mysql_error ());
	            if (mysql_num_rows ($res) == 0) {
	                unlink (THINGS_PROPS_DIR . $fn);
	                echo ("deleted $fn\n");
	            }
	        }
	        echo ("done\n");
        }
     
        function compress_ids ($start = 1, $end = 2) {
            // compresses IDs from $start to $end.
            // for example, if DB object IDs go like 1,2,5,1000000, then
            // new IDs will be 1,2,3,4.
         
            // !! will make like a billion database calls.
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
