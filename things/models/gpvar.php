<?php
    @session_start (); // returns FALSE on error
    if (!isset ($_SESSION)) {
        $_SESSION = array (); // what are the chances of SESSION not getting started?
    }
    
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
                    $this->key_cache = array_merge ($_SESSION, $_POST, $_GET, $_COOKIE);
                } catch (Exception $e) {
                    $this->key_cache = array_merge ($_POST, $_GET, $_COOKIE);
                }
                if (get_magic_quotes_gpc ()) { // if magic quotes is turned on (O\'Reilly)
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
        
        function Set ($what, $session_only = true) {
            // accepts an array of (name=>val)s and puts it in $_SESSION ONLY.
            if (sizeof ($what) > 0) {
                foreach ($what as $name=>$val) {
                    // echo ($name);
                    // echo ($val);
                    try {
                        $_SESSION[$name] = $val;
                        // echo ("Session win");
                    } catch (Exception $e) { 
                        // can sort of mean "I did nothing to SESSION"
                        $session_only = false;
                        // echo ("Session fail");
                    }
                    if (!$session_only) { // if you think it makes sense...
                        // die ("'" . $name . "," . $val . "'");
                        $_POST[$name] = $val;
                        $_GET [$name] = $val;
                        setcookie ($name, $val, time()+60*60*24*30, '/'); // expire in a month
                    }
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
            
            /*var_dump ($_GET);
            var_dump ($_POST);
            var_dump ($_COOKIE);
            var_dump ($_REQUEST);
            die ();*/
            
            header ('location: ' . WEBROOT); // return to home page
        }
    }
    
    $gp = new GPVar (); // used globally
?>
