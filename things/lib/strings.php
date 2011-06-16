<?php
    /* String helper functions */
    
    function left ($str,$pos) {
        return substr($str,0,$pos);
    }
   
    function first ($str, $fit = 100) {
        // wrapper for left with ellipses 
        if (strlen ($str) > $fit) {
            $str = left ($str, $fit - 3) . "...";
        } 
        return $str;
    }
        
    function right ($str,$len) {
        return substr ($str,strlen($str)-$len,$len);
    }
    
    function last ($str, $fit = 100 ) {
        // wrapper for right with ellipses
        if (strlen ($str) > $fit) {
            $str = right ($str,$fit - 3) . "...";
        } 
        return $str;
    }
   
    function mid ($str,$start,$len) {
        $len=($len==0)?strlen($str):$len;
        return substr($str,$start,$len);
    }
    
    function instr ($str,$get,$casesensitive=false) {
        $res = ($casesensitive)? strpos($str,$get)
                                :stripos($str,$get);
        return ($res === false)?-1:$res;
    } 
    
    function instrrev ($str,$get,$casesensitive=false) {
        $str=strrev($str);
        $res = ($casesensitive) ? strpos($str,$get)
                                   : stripos($str,$get);
        return ($res === false) ? -1 : $res;
    }
    
    function lcase ($str) {
        return strtolower ($str);
    }
    
    function ucase ($str) {
        return strtoupper ($str);
    }
    
    function propercase ($str) {
        return ucwords (strtolower($str));
    }
    
    function case_count ($w, $nocase = '') {
        $a = array();
        return preg_match_all ("/[A-Z]/$nocase", $w, $a);
    }
    
    function consonant_count ($w, $count_y_as_vowel = false) {
        // this is not non_vowel_count so it can't be a simple subtraction
        // e.g. symbols
        $a = array();
        if ($count_y_as_vowel) {
            $m = "/[bcdfghjklmnpqrstvwxz]/i";
        } else { 
            $m = "/[bcdfghjklmnpqrstvwxyz]/i";
        }
        return preg_match_all ($m, $w, $a);
    }
    
    function number_count ($w) {
        $a = array();
        return preg_match_all ("/[0-9]/", $w, $a);
    }
    
    function occurrence_count ($w) {
        $a = array();
        preg_match_all ("/[A-Z0-9]/i", $w, $a);     
        $reduce = array_count_values($a[0]);
        arsort($reduce);
        return implode (", ", array_keys ($reduce));
    }
    
    function space_count ($w) {
        $a = array();
        return preg_match_all ("/\s+/", $w, $a);
    }

    function vowel_count ($w, $including_y = false) {
        $a = array();
        if ($including_y) {
            $m = "/[aeiouy]/i";
        } else {
            $m = "/[aeiou]/i";
        }
        return preg_match_all ($m, $w, $a);
    }
?>