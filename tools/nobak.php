<?php
    require_once ('.things.php');
    require_once ('janitor.php');
    set_time_limit (600); // execute for 10 minutes ("way too much")
 
    $a = new Janitor ();
 
    if (!is_null ($a)) { // never
        $a->purge_backup_files ('../../', true);
    }
?>