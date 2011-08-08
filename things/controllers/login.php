<?php
    require_once ('.things.php');
 
    if ($auth->IsLoggedIn ()) {
        if (!$gp->Has ('from') || strlen ($gp->Get ('from')) == 0) { // directive to return to a page - see CheckAuth in auth.php
            $gp->Set (array ('from'=> WEBROOT . 'menu'));
        }
        header ('location: ' . $gp->Get ('from'));
        exit ();
    }
 
    require (PROOT . 'views/login.php');
?>
