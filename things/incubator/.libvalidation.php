<?php
    function isalphanumeric($what) {
        return !(preg_match('/[^A-Za-z0-9]/', $what));
    } //function isalphanumeric($what)

    function isurl($url) {
        //http://www.blog.highub.com/regular-expression/php-regex-regular-expression/php-regex-validating-a-url/
        $pattern = '/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/';
        return preg_match($pattern, $url);
    } //function isurl($url)

    function url_exists ($url) {
        //dan at sudonames dot com
        $hdrs = @get_headers($url);
        return is_array($hdrs) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/', $hdrs[0]) : false;
    } //function url_exists($url)

    function isemail ($email) {
        //return !eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
        // this came from http://www.codewalkers.com/c/a/Miscellaneous/Email-Validation-with-PHP/1/
        //   /[a-z0-9_\-]+@[a-z0-9_\-\.]+\.[a-z]{0,7}/i
        // if (eregi("^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]", $email)) {
        if (!preg_match ("/[a-z0-9_\-]+@[a-z0-9_\-\.]+\.[a-z]+/i",$email)) {
            return false;
        } //if (eregi("^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]", $email))
        list($Username, $Domain) = explode ("@", $email);
        if (getmxrr($Domain, $MXHost)) {
            return true;
        } //if (getmxrr($Domain, $MXHost))
        else {
            if (@fsockopen($Domain, 25, $errno, $errstr, 30)) {
                return true;
            } //if (@fsockopen($Domain, 25, $errno, $errstr, 30))
            else {
                return false;
            } //else
        } //else
    } //function isemail($email)
?>