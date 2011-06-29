<?php
    require_once ('.things.php');
    require_once ('janitor.php');
    
    $a = new Janitor ();
    
    if (!is_null ($a)) { // never
        $a->purge_error_logs ('../../', true);
    }
?>