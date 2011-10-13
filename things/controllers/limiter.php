<?php
    require_once ('.things.php');
    require_once (PROOT . 'lib/datetime.php');

    /*  if no cookies set, make cookie, give 15 minutes, pass
        if cookie is set, check time
            if gte 15 mins, pass, set cookie time
            if lt 15 mins, block
     
        cookie name format $_COOKIE['site_name_md5'] = last time
        must be md5 because $_COOKIE keys cannot have strange symbols
    */
 
    $reset_period = 7200; // seconds between each allowed load

    function GetSiteHash ($site) {
        // code refactoring
        return substr (md5 ($site), 0, 8);
    }
 
    function LoadSite ($site) {
        global $gp;
        $gp->Set (
            array (
                GetSiteHash ($site) => time ()
            ), 
            false
        ); // set cookie time
        header ("location: http://$site"); // pass
    }
 
    if ($gp->Has ('site')) { // check if site is registered
        $site = $gp->Get ('site');
        if (array_key_exists (GetSiteHash ($site), $_COOKIE)) {
            $sid = GetSiteHash ($site);
            $last_accessed_time = intval ($_COOKIE[$sid]);
            $now_time = time ();
            $time_waited = $now_time - $last_accessed_time;
            if ($time_waited > $reset_period) {
                // reset period exceeded = allow page again
                LoadSite ($site);
            } else {
                echo ("Wait for another " . human_time_diff ($reset_period, $time_waited, '') . " to view the page.");
                render ();
            }
        } else {
            LoadSite ($site);
        }
    }
?>
