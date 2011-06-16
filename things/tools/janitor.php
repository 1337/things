<?php    
    class Janitor extends Things {
        function purge_objects_with_no_properties () {
            // straightforward.
            foreach ($this->objs as $obj) {
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
        }
        
        function purge_orphaned_relations () {
            // hierarchies with null parent and child
        }
        
        function purge_property_files () {
            // no url_prop found for any files found in props path
        }

        function purge_error_logs ($path = '.') {
            // delete all files named error_log in the specified folder.
            require_once (dirname (__FILE__) . '/../controllers/filesearch.class.php'); // local import
            $a = new FileSearch ();
            $files = $a->FileNameSearch ('error_log', $path);
            if (sizeof ($files) > 0) {
                foreach ($files as $file) {
                    unlink ($file);
                    // echo ("unlinked $file\n");
                }
            }
        }
    }
?>
