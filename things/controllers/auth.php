<?php
    @session_start ();
    require_once (PROOT . 'models/gpvar.php'); // just in case
    require_once (PROOT . 'models/user.php'); // just in case
	require_once (PROOT . 'models/gpvar.php'); // just in case
    
    class Auth {
        function SuperSecureHash ($what) {
            // returns a hash of $what. Feel free to modify this function
            // prior to deployment.
            return sha1 ($what);
        }
     
        function RemoteWriteHash ($user) {
            // $user can represent both the name of a site or a user.
         
        }
     
        function IsLoggedIn () {
            // checks if user is logged in.
            global $gp, $user;
            $vars = $gp->Get ();
            if ($gp->Has ('loginName') && strlen ($vars['loginName']) > 0) {
                // this means nothing up to this point. you are either logged in 
                // or you are looking forward to logging in.
                $gp->Set (array ('loginName' => $vars['loginName']), false); // false means "save to cookie too"
                if ($gp->Has ('loginPassword')) {
                    // if loginPassword exists, it must be from a login page.
                    // user is trying to log in.
                    $pwhash = $this->SuperSecureHash ($vars['loginPassword']);
                    // die ("I can has $pwhash");
                    $gp->Set (array ('loginPWHash'=>$pwhash), false); // false means "save to cookie too"
                    $vars = $gp->Get (); // get again
                    // return true;
                }
                if (array_key_exists ('loginPWHash', $vars)) {
                    // if there is already a hash, that means the user is already
                    // logged in, going to another page.
                    // just because loginPWHash exists, it does not mean the
                    // user is authenticated. must check hash against DB.
                    $your_hash = $vars['loginPWHash'];
                    try {
                        $my_user_id = FindObject ($vars['loginName'], USER);
                        if (!is_null ($my_user_id) && $my_user_id > 0) {
                            $my_user = new User ($my_user_id);
                            $my_hash = $my_user->GetProp ('password'); // assumes hash exists
                            if ($your_hash === $my_hash) {
                                // congrats!
                                $user = new User ($my_user_id); // global - use it
                                return true; 
                            }
                        }
                    } catch (Exception $e) {
                        // fails
                    }
                }
            }
            return false; // all other cases = flop
        }
     
        function WhoIsLoggedIn () {
            // returns the active $user object.
            global $gp;
            $vars = $gp->Get ();
            if ($this->IsLoggedIn ()) { // first check if someone IS logged in
                $user_id = FindObject ($vars['loginName'], USER);
                $user = new User ($user_id);
                return $user;
            }
            return null;
        }
             
        function Logout () {
            global $gp;
            $gp->Flush ();
        }
    }
 


    function CheckAuth ($required_privs = array (), $from = '', $to = 'login', $redirect = true) {
        // call CheckAuth () to provide login functionality for that specific page.
        // if user is not logged in, it will be redirected to the $to page if $redirect is true.
        // if user is logged in, privileges will be checked for this page's access.
        // returns true (logged in) and false (not logged in).
		global $gp;
        
		$auth = new Auth ();
        $user = $auth->WhoIsLoggedIn ();
        // $user = $this->WhoIsLoggedIn ();
     
        if (!is_array ($required_privs)) {
            $required_privs = array ($required_privs); // convert to array if given just a name
        }
     
        if (is_null ($user)) {
            // user is not logged in
            if (strlen ($to) > 0) {
                if (strlen ($from) == 0) {
                    $from = $gp->Get('redirected_from');//$_SERVER['SCRIPT_NAME'];
                }
                if ($redirect) {
                    header ("location: " . WEBROOT . "$to?from=$from");
                }
            }
            return false;
        } else { // user is not null == is logged in
            $has_privs = true; // default
            if (sizeof ($required_privs) > 0) {
                $has_privs = $user->CheckPrivileges ($required_privs);
                if (!$has_privs) {
                    if ($redirect) {
                        // reject request
                        header ("location: $to?from=$from");
                    }
                    return false; // and give a false if no redirect.
                }
            }
            return $has_privs; // by now, $has_privs must be true
        }
    }
 
    /*function CheckPrivilege ($privids = array ()) {
        // checks the user (required) for privileges.
        // $privnames can be both a string (the privilege name)
        // or an array (many privilege names)
        global $user;
     
        $allow = true; // default to allow
     
        if (isset ($user) && sizeof ($privids) > 0) {
            foreach ($privids as $privid) {
                if (!is_int ($privid)) {
                    $privid = FindObject ($privid, PRIVILEGE); // find the ID for the name
                }
                if (!is_null ($privid)) {
                    $has_priv = $user->CheckPrivilege ($privid);
                    if (!$has_priv) {
                        return false;
                    } else {
                        $allow = $allow && true;
                    }
                }
            }
        }
        return $allow;
    }*/

    $auth = new Auth ();
    $auth->IsLoggedIn (); // this just logs the user in (if form was sent)
?>
