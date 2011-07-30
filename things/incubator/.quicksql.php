<?php
    /*  QuickSQL library
        Perform highly inefficient SQL queries very easily.
    */
    
    require_once('.functions.php');
    require_once('.connect.php');
    
    /*  quick sql
        - Might not return anything (INSERT, DELETE, UPDATE)
        - Returns only the first row (SELECT)
        - results are not cached. Do not call repeatedly or in a loop
          (your sysadmin will kick your ass)
    */
    function qsql ($what, $verbose=false) {
        $q=@mysql_query($what);
        if($q) {
            if(substr(strtoupper($what),0,6)=="SELECT") {
                if(mysql_num_rows($q)>0) {
                    $qr=mysql_fetch_assoc($q); //select first now.
                    return $qr; //return it.
                } else {
                    return NULL;
                }
            } else {
                return $q; //if ins/del/upd, return the result
            }
        } else {
            if ($verbose) {
                // die(mysql_error()); //you asked for it
                return mysql_error(); //you asked for it
            }
        }
    }

    function asql ($query, $verbose = false) {
        // compatible, "array-based returns" quicksql
        /* [
             [ ... ]  
             [ ... ]
             [ ... ]
           ]
        */    
        $q = mysql_query ($query);
        if ($q) {
            $results = array();
            if (substr (strtoupper ($query),0,6) == "SELECT") {
                // sort it
                if (mysql_num_rows ($q) > 0) {
                    while ($qr = mysql_fetch_assoc ($q)) {
                        $results[] = $qr; //copy it to main array
                    }
                    return $results; //return every result
                } else {
                    return $results; //no results
                }
            } else {
                return $q; // the result
            }
        } else {
            if($verbose) {
                die(mysql_error()); //you asked for it
            }
        }
    }
    
?>