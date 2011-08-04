<?php
    require_once ('.things.php');
 
    $new = $gp->Get('new');
    $edit = $gp->Get('edit');
    $id = $gp->Get('id');
 
    if ($id > 0 && GetObjectType ($id) == PAGE) {
        if (strlen ($new)  > 0 || strlen ($edit) > 0) {
            // this is if new, or old and valid ID
            require_once (PROOT . "views/page-edit.php");
        } else {
            require_once (PROOT . "views/page-view.php");
        }
    }
?>
