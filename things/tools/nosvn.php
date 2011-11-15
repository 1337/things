<?php
    // http://www.lateralcode.com/remove-svn-php/
    header('Content-type: text/plain');
    set_time_limit (600); // execute for 10 minutes ("way too much")
    
    function delTree($dir) {
    
       $files = glob($dir . '*', GLOB_MARK);
       foreach ($files as $file) {
           if (substr($file, -1) == '/')
               delTree($file);
           else
               unlink($file);
       }
       if (is_dir($dir))
        
           rmdir($dir);
    }
    
    function removeSVN($dir) {
       echo "Searching: $dir\n\t";
    
       $flag = false;
       $svn = $dir . '.svn';
       if (is_dir($svn)) {
           if (!chmod($svn, 0755))
               echo "File permissions could not be changed.\n\t";
           delTree($svn);
           if (is_dir($svn))
               echo "Failed to delete $svn due to file permissions.";
           else
               echo "Successfully deleted $svn from the file system.";
           $flag = true;
       }
       echo "\n\n";
       $handle = opendir($dir);
       while (false !== ($file = readdir($handle))) {
           if ($file == '.' || $file == '..')
            
               continue;
           if (is_dir($dir . $file))
            
               removeSVN ($dir . $file . '/');
       }
    }
    
    removeSVN ('../../');
?>