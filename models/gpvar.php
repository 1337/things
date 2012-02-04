<?php
    @session_start (); // returns FALSE on error
    if (!isset ($_SESSION)) {
        $_SESSION = array (); // what are the chances of SESSION not getting started?
    }
    
    define ('GP_SESSION', 1);
    define ('GP_POST', 2);
    define ('GP_GET', 4);
    define ('GP_COOKIE', 8);
 
    class GPVar {
    // $_GET and $_POST wrappers.
        var $key_cache;
        function GPVar () {
            $key_cache = array ();
        }
 
        function Get ($index = false) {
            // priority: SESSION > POST > GET
            // defaults to returning the entire $_GET or $_POST.
            // if index is anything other than false, it will try to give you the corresponding
            // key in the variable.
            if (sizeof ($this->key_cache) == 0) {
                // fetch keys only if the cache is empty
                // $this->key_cache = array_merge ($_SESSION, $_REQUEST);
                try {
                    // manual order; also handles errors if SESSION is disabled.
                    // $this->key_cache = array_merge ($_SESSION, $_POST, $_GET, $_COOKIE);
                    $this->key_cache = array_merge ($_SESSION, $_POST, $_GET); // cookies removed for now
                } catch (Exception $e) {
                    // $this->key_cache = array_merge ($_POST, $_GET, $_COOKIE);
                    $this->key_cache = array_merge ($_POST, $_GET); // cookies removed for now
                }
                if (get_magic_quotes_gpc ()) { // if magic quotes is turned on
                    $this->key_cache = array_map ('stripslashes', $this->key_cache);
                }
            }
            if ($index !== false) {
                if (array_key_exists ($index, $this->key_cache)) {
                    return $this->key_cache[$index];
                } else {
                    return null;
                }
            } else {
                return $this->key_cache;
            }
        }
     
        function Has ($index) {
            // checks if $key_cache has the key $index.
            return array_key_exists ($index, $this->key_cache);
        }
     
        function Check ($index, $expected) {
            // returns true if $key_cache[$index] == $expected value.
            if (array_key_exists ($index, $this->key_cache)) {
                return ($this->key_cache[$index] == $expected);
            }
            return false;
        }
     
        // function Set ($what, $session_only = true) {
        function Set ($what, $components = GP_SESSION) {
            // accepts an array of (name=>val)s and puts it in $_SESSION ONLY.
            if (sizeof ($what) > 0) {
                foreach ($what as $name => $val) {
                    try {
                        if ($components === false) {
                            $components = 15; // compatibility with existing code
                        }
                        /*if ($components >= GP_COOKIE) {
                            setcookie ($name, $val, time()+60*60*24*30, '/'); // expire in a month
                            $components -= GP_COOKIE;
                        } cookies removed for now (insecure) */
                        if ($components >= GP_GET) {
                            $_GET[$name] = $val;
                            $components -= GP_GET;
                        }
                        if ($components >= GP_POST) {
                            $_POST[$name] = $val;
                            $components -= GP_POST;
                        }
                        if ($components >= GP_SESSION) {
                            $_SESSION[$name] = $val;
                            $components -= GP_SESSION;
                        }
                    } catch (Exception $e) {}
                }
                // $this->key_cache = array (); // flush cache
                $this->key_cache = array_merge ($this->key_cache, $what); // add to cache
            }
        }
     
        function Flush () {
            // removes ALL client-side data.
            foreach (array ($_GET, $_POST, $_REQUEST) as $lgp) {
                foreach (array_keys ($lgp) as $key) {
                    try {
                        unset ($lgp[$key]);
                        $lgp[$key] = '';
                        $lgp[$key] = null;
                    } catch (Exception $e) {}
                }
            }
            try {
                foreach (array_keys ($_COOKIE) as $key) {
                    setcookie ($key, null, time() - 3600); // NULL unsets cookies!!
                }
            } catch (Exception $e) {}
            try {
                // done seperately because $_SESSION might not exist
                foreach (array_keys ($_SESSION) as $key) {
                    try {
                        unset ($_SESSION[$key]);
                    } catch (Exception $e) {}
                }
            } catch (Exception $e) {}

            header ('location: ' . WEBROOT); // return to home page
        }
    }

    $gp = new GPVar (); // used globally
?>