<?php
    /*  PHP-MySQL settings library
        Requires: user name, $un
        Usage:  savesetting('age',5);
                $age=getsetting('age')['value'];
    */

    require_once('.functions.php');
    require_once('.connect.php');

    function savesetting($optionname,$value,$default='',$ss_uid=-1) {
        /*  This function returns a triplet
            user name
            user id
            the value that got written */
                 
        global $user; // limit saving to only current user
        global $users_table, $options_table;
        if (isset($user) && $ss_uid<0) { //no uid presupplied
            //DO NOT MOVE THIS LINE OUT OF THE FUNCTION.
            // (you'll crash htmlout)
            require_once('.auth.php'); //because $user is used later

            $ss_uid=$user->prop('uid');
        } elseif (!$user && $ss_uid<0) {
            die("Attempting to save settings to no one");
        }
     
        $value=escape_data($value);
        $default=escape_data($default);
        if (!isset($value) || !$value) $value=$default; // writing defaults
     
        // why the hell do I need to use "backticks" here?!
        /* $q984="INSERT INTO $options_table (`userid`, `option`, `value`)
                    VALUES ('$ss_uid', '$optionname', '$value')";
        $qx984=mysql_query($q984); */
     
        // updated to INSERT IF NOT EXISTS type query for mysql 4+
        $qx984 = mysql_query("UPDATE $options_table
                              SET `value`= '$value'
                              WHERE `userid`='$ss_uid'
                              AND `option`='$optionname'");
        if (mysql_affected_rows()==0) {
            $qx984 = mysql_query("INSERT INTO $options_table (`userid`, `option`, `value`)
                                  VALUES ('$ss_uid', '$optionname', '$value')");
        }
     
        if($qx984) { //success
            return array('username' => $user->prop('username'), 
                         'userid' => $ss_uid, 
                         'value' => $value); //cool? can reuse this id as well
        } else {
            die("Cannot save settings: " . mysql_error());
        }
    }
 
    function getsetting($optionname,$default='',$ss_uid=-1) {
        /*  This function returns a triplet
            user name
            user id
            the value that got written */

        global $user; // limit saving to only current user
        global $users_table, $options_table;
     
        if ($ss_uid < 0) { //no uid presupplied
            //DO NOT MOVE THIS LINE OUT OF THE FUNCTION.
            // (you'll crash htmlout)
            require_once ('.auth.php'); //because $user is used later
            getAuth ();
            $ss_uid=$user->prop('uid');
        }     
        // why the hell do I need to use "backticks" here?!
        $q984="SELECT value FROM $options_table
                WHERE userid='$ss_uid' 
                AND `option`='$optionname' 
                ORDER BY id DESC LIMIT 1"; //last line is for a single return
        $qx984=mysql_query($q984);
        if($qx984) { //success
            $qr984=mysql_fetch_assoc($qx984);
            $value=$qr984['value'];
            if (!isset($value) || !$value) $value=$default; // writing defaults
            return array('username' => '', 
                         'userid' => $ss_uid, 
                         'value' => $value); //cool? can reuse this id as well
        } else {
            die("Cannot save settings: " . mysql_error());
        }
    }
 
    function getusersettings($ss_uid=-1) {
        global $user; // limit saving to only current user
        global $users_table, $options_table;

        if (isset($user) && $ss_uid<0) { //no uid presupplied
            $ss_uid=$user->prop('uid');
        } elseif (!$user && $ss_uid<0) {
            die("Attempting to read settings from no one");
        }

        $q = "SELECT * FROM $options_table 
              WHERE userid='" . $ss_uid . "'
              ORDER BY id DESC"; //desc because == more recent
        $qx = mysql_query($q);
        if ($qx) {
            $arm = array(); //declare empty array
            if (mysql_num_rows ($qx) <= 0) {
                println ("No results.", $fail);
            } else {
                while ($qr = mysql_fetch_assoc ($qx)) {
                    $arm[] = $qr['option']; //add item to array
                }
                return $arm;
            }
        }
    }
?>
