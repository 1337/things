<?php
    //if (!ob_start ("ob_gzhandler")) {
    //    ob_start();  // failsafing?
    //}
    //buffer will be compressed, then shortened with template-engine
 
    // require_once('.conf.php'); //all the table definition is in here

    // importing other functions
    // require_once ('.libvb6.php');
    // require_once ('.libdatetime.php');
    // require_once ('.libcookies.php');
    // require_once ('.libsessions.php');
    // require_once ('.template-engine.php');
    // require_once ('.libsettings.php');
    // require_once ('.postings.php');
    // require_once ('.quicksql.php');
    // require_once ('.libcompress.php');
    // require_once ('.libvalidation.php');

    // @include_once('.jquery.php'); //optional 
 
    // functions
    /* function println($what, $hdng = 'p') {
        //MVC version of println
        if ($hdng >= 1 && $hdng <= 6) {
            $heading = 'h' . $hdng;
        } elseif (strlen($hdng) == 0) {
            $heading = 'p';
        } else {
            $heading = $hdng;
        }
     
        //if jquery is present
        if (function_exists ('printinfo_jquery') && $hdng > 4) {
            if($hdng==5) {
                printinfo_jquery($what);
            } elseif ($hdng==6) {
                printerror_jquery($what);
            }
        } else {
            echo("<$heading>$what</$heading>\n");
        }
    }*/
     
    function page() {
        return $_SERVER['PHP_SELF'];
    }

    function site() {
        return OROOT;
    } //if you use HTTPS, do something about it yourself

    function txt2bin($w){for($i=0;$i<strlen($w);$i++){$k .=sprintf("%08d",decbin(ord($w[$i])));}return $k;}
    function bin2txt($w){for($i=0;$i<strlen($w);$i+=8){$k.=chr(bindec(mid($w,$i,8)));}return $k;}
 
    /* function gvar($what) { //get-prioritised var
        if (isset ($_GET[$what])) {
            return $_GET[$what];
        } elseif (isset ($_POST[$what])) {
            return $_POST[$what];
        } else {
            return false;
        }
    }

    function pvar($what) { //post-prioritised var
        if (isset ($_POST[$what])) {
            return $_POST[$what];
        } elseif (isset ($_GET[$what])) {
            return $_GET[$what];
        } else {
            return false;
        }
    }

    function myip() {
        return $_SERVER['REMOTE_ADDR'];
    }

    function ixcape($str) {
        return htmlentities($str, ENT_NOQUOTES);
    }

    if (!function_exists("stripos")) {
        // I love this function, but it's not in PHP4, so
        function stripos($str,$needle,$offset=0) {
            return strpos(strtolower($str),strtolower($needle),$offset);
        }
    } */
?>