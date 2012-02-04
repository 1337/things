<?php
    @session_start ();
    require_once (PROOT . 'models/gpvar.php'); // just in case
    require_once (PROOT . 'models/user.php'); // just in case
    // responsible for handling the $gp from gpvar.php.

    class Auth {
        function SuperSecureHash ($what) {
            return sha1 ($what);
        }
     
        function RemoteWriteHash ($user) {
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
 
    function CheckAuth ($required_privs = array (), $from = '', $to = '/login', $redirect = true) {
        // call CheckAuth () to provide login functionality for that specific page.
        // if user is not logged in, it will be redirected to the $to page if $redirect is true.
        // if user is logged in, privileges will be checked for this page's access.
        // returns true (logged in) and false (not logged in).
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
                    $from = $_SERVER['SCRIPT_NAME'];
                }
                if ($redirect) {
                    header ("location: $to?from=$from");
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
 
    $auth = new Auth ();
    $auth->IsLoggedIn (); // this just logs the user in (if form was sent)

?>

<?php
    include_once (PROOT . 'lib/CAS.php');
    phpCAS::client (CAS_VERSION_2_0,'cas.uwaterloo.ca',443,'/cas');
    phpCAS::setCasServerCACert ('/etc/pki/tls/certs/globalsignchain.crt');
    phpCAS::forceAuthentication ();
 
    if (isset ($_GET['q'])) {
        $user_name = $_GET['q'];
    } else {
        $user_name = phpCAS::getUser ();
    }
     
    $ad = ldap_connect ("ldap://uwldap.uwaterloo.ca");
    $bd = ldap_bind ($ad,"","")
            or die("Couldn't bind to AD!");
    $result = ldap_search ($ad, "dc=UWATERLOO, dc=CA", "(&(uid=" . $user_name . "))");
    // (&(objectCategory=group)(member=cn=Jim Smith,ou=Sales,ou=West,dc=UWATERLOO,dc=CA))
    if ($result) {
        $entries = ldap_get_entries ($ad, $result);
        echo ("<!--\n");
        print_r ($entries);
        echo ("\n-->"); 
        $get_props = array ('givenname', 'sn', 'cn', 'mail', 'maillocaladdress', 'ou', 'labeleduri', 'objectclass');
        for ($i = 0; $i < $entries['count']; $i++) {
            foreach ($get_props as $prop) {
                unset ($entries[$i][$prop]['count']);
                $user[$prop] = implode (', ', $entries[$i][$prop]);
            }
            echo ("<pre>");
            print_r ($user);
            echo ("</pre>");
            if ($entries[$i]['dn']) {
                $results2 = ldap_search ($ad, "(&(" . $entries[$i]['dn'] . ")(objectcategory=group))");
                // $results2 = ldap_search ($ad, "(objectclass=group)");
                echo ("<p>" . $entries[$i]['dn'] . "</p>");
                if ($results2) {
                    $entries2 = ldap_get_entries($ad, $results2);
                    print_r ($entries2);
                } else {
                    echo ("<p>No group info from auth</p>");
                }
            }
        }
    } else {
        echo ("no result");
    }
    ldap_close ($ad);
?>