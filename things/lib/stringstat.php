<?php
    /* used almost exclusively by the strings analysis service. */


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