<?php
    /*  VB6 Compat Library
        Copyleft Brian Lai
        functions that need not be implemented:
            trim
            ltrim
            rtrim
    */
 
    function left($str,$pos) {
        return substr($str,0,$pos);
    }

    function first ($str, $fit = 100) {
        // wrapper for left with ellipses 
        if (strlen ($str) > $fit) {
            $str = left ($str, $fit - 3) . "...";
        } 
        return $str;
    }
     
    function right($str,$len) {
        return substr($str,strlen($str)-$len,$len);
    }
 
    function last( $str, $fit = 100 ) {
        // wrapper for right with ellipses
        if (strlen ($str) > $fit) {
            $str = right ($str,$fit - 3) . "...";
        } 
        return $str;
    }

    function mid($str,$start,$len) {
        $len=($len==0)?strlen($str):$len;
        return substr($str,$start,$len);
    }
 
    function instr($str,$get,$casesensitive=false) {
        $res = ($casesensitive)? strpos($str,$get)
                                :stripos($str,$get);
        return ($res === false)?-1:$res;
    } 
 
    function instrrev($str,$get,$casesensitive=false) {
        $str=strrev($str);
        $res = ($casesensitive)?strpos($str,$get)
                               :stripos($str,$get);
        return ($res === false)?-1:$res;
    }
 
    function lcase($str) {
        return strtolower($str);
    }
 
    function ucase($str) {
        return strtoupper($str);
    }
 
    function propercase($str) {
        return ucwords(strtolower($str));
    }
 
?>