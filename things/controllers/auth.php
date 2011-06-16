<?php
    @session_start ();
    require_once (PROOT . 'models/gpvar.php'); // just in case
    require_once (PROOT . 'models/user.php'); // just in case
    // responsible for handling the $gp from gpvar.php.

    /*
    function Get ($index = false)
    // priority: SESSION > POST > GET; gets var[$index] if specified
    function Set ($what, $session_only = true) {
    // accepts an array of (name=>val)s and puts it in $_SESSION ONLY.
    */
    class Auth {
        function SuperSecureHash ($what) {
            // returns a hash of $what. Feel free to modify this function
            // PRIOR to deployment.
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
    
    function CheckAuth ($from = '', $to = '/login', $redirect = true) {
        // call CheckAuth () to provide login functionality for that specific page.
        // if user is not logged in, it will be redirected to the $to page if $redirect is true.
        // if user is logged in, privileges will be checked for this page's access.
        // returns true (logged in) and false (not logged in).
        $auth = new Auth ();
        $user = $auth->WhoIsLoggedIn ();
        if (is_null ($user)) {
            // user is not logged in
            if (strlen ($to) > 0) {
                if (strlen ($from) == 0) {
                    $from = $_SERVER['SCRIPT_NAME'];
                }
                if ($redirect) {
                    header ("location: $to?from=$from");
                }
            }
            return false;
        } else { // user is not null == is logged in
            $has_privs = $user->CheckPrivileges (array ());
            return true; // for now, just pretend privilege checks all succeed
        }
    }
    
    function CheckPrivilege ($privnames) {
        // checks the user (required) for privileges.
        // $privnames can be both a string (the privilege name)
        // or an array (many privilege names)
        global $user;
        
        $allow = true; // default to allow
        
        if (isset ($user) && sizeof ($privnames) > 0) {
            foreach ($privnames as $privname) {
                if (strlen ($privname) > 0) {
                    $pid = FindObject ($privname, PRIVILEGE);
                    if (!is_null ($pid) && $pid > 0) { // privilege found
                        $icanhaspriv = $user->GetChildren (PRIVILEGE);
                        if (in_array ($pid, $icanhaspriv)) {
                            $allow = $allow && true;
                        } else {
                            // $allow = false; 
                            return false; // priv not found as user's child? INSTANT FAIL
                        }
                    }
                } else {
                    // if you didn't ask for a privilege check, why did you call me?
                    $allow = $allow && true;
                }
            }
        }
        return $allow;
    }
    
    $auth = new Auth ();
    $auth->IsLoggedIn (); // this just logs the user in (if form was sent)

?>
