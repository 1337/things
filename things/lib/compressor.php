<?php
    function css_compress($buffer) {
        /* remove comments */
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        /* remove tabs, spaces, newlines, etc. */
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
     
        return $buffer;
    }
 
    function php_compress ($str) {
        $str = str_replace("<?php", '<?php ', $str);
        $str = str_replace("\r", '', $str);
        if (function_exists ("ereg_replace")) { // deprecation
            $str = @ereg_replace ("/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/", '', $str);
            $str = @ereg_replace ("//[\x20-\x7E]*\n", '', $str);
            $str = @ereg_replace ("#[\x20-\x7E]*\n", '', $str);
            $str = @ereg_replace ("\t|\n", '', $str);
        }     
        return $str;
    }
 
    function html_compress ($h) {
        return preg_replace ('/(?:(?)|(?))(\s+)(?=\<\/?)/',' ', $h);
    }
?>