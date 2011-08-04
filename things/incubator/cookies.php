<?php
    // --> setcookie() must be called before any output is sent to the browser

    function sookie($name,$val) {
        setcookie($name, $val, time()+120,"/");
    }
    function gookie($name) {
        return @$_COOKIE[$name];
    }
    function dookie($name) {
        // this "a year and a second" value is recommended in
        // http://php.net/manual/en/function.setcookie.php
        setcookie($name, "", time()-31536001,"/");
    }
?>