<?php
	function SingleFetch ($query, $column = '') {
        // used to fetch a single row or a single value from a query.
        // to get a single value, specify $column.
        $sql = mysql_query ($query) or die (mysql_error ());
        if ($sql && mysql_num_rows ($sql) > 0) {
            $tmp = mysql_fetch_assoc ($sql);
            if (strlen ($column) > 0) {
                return $tmp[$column];
            } else {
                return $tmp; // if not specified, return whole row.
            }
        } else {
            return null;
        }
    }
 
    function ColumnFetch ($query, $column, $key = '') {
        /* return all values from a single column:
            col[0]=1
            col[1]=4
            col[2]=9, ...
         
            => [1,4,9]
         
            if $key is given, values from that row will be used as key.
         
            col[john] = 1
            col[...
         
         
             */
        $sql = mysql_query ($query) or die (mysql_error ());
        if ($sql && mysql_num_rows ($sql) > 0) {
            $buffer = array ();
            while ($tmp = mysql_fetch_assoc ($sql)) {
                if (array_key_exists ($key, $tmp)) {
                    $buffer[$tmp[$key]] = $tmp[$column];
                } else {
                    $buffer[] = $tmp[$column];
                }
            }
            return $buffer;
        } else {
            return null;
        }
    }
?>