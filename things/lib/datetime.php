<?php

    // changed fed
    function smktime($mo, $day, $year) {
        //shorthand. replace triple leading 0s wit this
        // returns unix timestamp
        return mktime(0, 0, 0, $mo, $day, $year);
    }
    
    function alt_date($date, $sec, $min, $ho, $mon, $day, $year) {
        // returns unix timestamp
        $newdate = mktime(date('H', $date) + $ho, 
                          date('i', $date) + $min, 
                          date('s', $date) + $sec, 
                          date('m', $date) + $mon, 
                          date('d', $date) + $day, 
                          date('Y', $date) + $year);
        return $newdate;
    }
    
    function salt_date($date, $mon, $day, $year) {
        // another shorthand, sir
        // returns unix timestamp.
        return alt_date($date, 0, 0, 0, $mon, $day, $year);
    }
    
    function second ($date) {
        return date('s', $date);
    }

    function minute ($date) {
        return date('i', $date);
    }

    function hour ($date) {
        return date('G', $date);
    }

    function day($date) {
        return date('d', $date);
    }
    
    function month($date) {
        return date('m', $date);
    }
    
    function month_name($date) {
        return date('F', $date);
    }
    
    function year($date) {
        return date ('Y', $date);
    }
    
    function break_date ($date) {
        // date breakdown.
        return array ('year' => year ($date),
                      'month' => month ($date),
                      'day' => day ($date));
    }
    
    function last_day_of_month($month, $year) {
        for ($i = 1; $i < 32; $i++) {
            // loop until the day number decreases ("new month")
            $newj = date("d", smktime($month, $i, $year));
            if ($newj < $oldj) {
                break;
            }
            $oldj = $newj;
        }
        return $i - 1;
    }
    
    // for public good
    function php_date_to_mysql_datetime ($date) {
        return date('Y-m-d H:i:s',$date);
    }
    
    function mysql_datetime_to_php_date ($datetime) {
        return strtotime($datetime);
    }
    
    function time_diff_from_now ($datetime) {
        // accepts php date.
        // returns some relative time from now.
        
        // get time now in unix seconds since epoch.
        $now_u = date ("U");
        // get time (datetime) in unix seconds since epoch.
        $datetime_u = mktime (hour ($datetime), 
                            minute ($datetime), 
                            second ($datetime), 
                             month ($datetime), 
                               day ($datetime), 
                              year ($datetime));
        return $now_u - $datetime_u;
    }
    
	function plural ($num) {
        // helper for adding "s"s to the end of "hour" , "day", etc.
        if ($num != 1) {
            return "s";
        }
    }
	
    function human_time_diff ($datetime) {
        // mod of http://snipplr.com/view/4912/relative-time/
        $diff = time_diff_from_now ($datetime);
        if ($diff<60) {
            return $diff . " second" . plural($diff) . " ago";
        }
        $diff = round($diff/60);
        if ($diff<60) {
            return $diff . " minute" . plural($diff) . " ago";
        }
        $diff = round($diff/60);
        if ($diff<24) {
            return $diff . " hour" . plural($diff) . " ago";
        }
        $diff = round($diff/24);
        if ($diff<7) {
            return $diff . " day" . plural($diff) . " ago";
        }
        $diff = round($diff/7);
        if ($diff<4) {
            return $diff . " week" . plural($diff) . " ago";
        }
        if ($datetime > 0) { // it'll return the epoch if not
            return "on " . date("F j, Y", strtotime($datetime));
        }
    }
    