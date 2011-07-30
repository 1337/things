<?php
    require_once ('.things.php');
    
    $new = $gp->Get('new');
    $edit = $gp->Get('edit');
    $id = $gp->Get('id');
    
    if (strlen ($new)  > 0 || 
       (strlen ($edit) > 0 && $id > 0 && GetObjectType ($id) == PAGE)) {
        // this is if new, or old and valid ID
		require_once ("page-edit.php");
    } else {
		// this is actually a deprecated alias
		require_once ("page-view.php");
    }
?>