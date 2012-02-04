<?php
    class Validator {
        /*
            $methods = array (
                            'minlength' => 3,
                            'maxlength' => 8,
                            'email => 1
                       );
        */
        public $methods;
     
        function Validator ($methods = array ()) {
            $this->methods = $methods;
        }
     
        function Add ($name, $val) {
            // add a validation criterion.
            $this->methods[$name] = $val;
        }
     
        function Remove ($name) {
            // remove a validation criterion, if it exists.
            if (array_key_exists ($name, $this->methods)) {
                unset ($this->methods[$name]);
            }
        }
     
        function Test ($test_str, $test_all = true) {
            // apply all stored requirements to $test_str.
            // if $test_all = true, Test returns true if ALL tests pass
            // if $test_all = false, Test returns true if ANY test passes
            // mixed call_user_func ( callback $function [, mixed $parameter [, mixed $... ]] )
            $passed = true;
            if (sizeof ($this->methods) > 0) {
                foreach ($this->methods as $func => $param) {
                    if ($test_all) {
                        if ($passed) {
                            $result = call_user_func (array (get_class (), "_$func"), $test_str, $param);
                            $passed = $passed && $result;
                        } else {
                            // if $test_all and one test fails, you failed. skip the rest.
                            return false;
                        }
                    } else {
                        // !$test_all, must test all to see if ANY of them are good
                        $result = call_user_func (array (get_class (), "_$func"), $test_str, $param);
                        $passed = $passed || $result;
                    }
                }
            }
            return $passed;
        }
     
     
     
     
     
     
     
        /*  VALIDATION HELPERS
            Inputs: test string, test criterion
            Outputs: boolean
         
            You can also call these separately from outside the class
        */
     
        function _required ($str, $param) {
            if ($param == true) {
                return (strlen ($str) > 0);
            }
        }
     
        function _min ($str, $param) {
            return ($str >= $param);
        }

        function _max ($str, $param) {
            return ($str <= $param);
        }
     
        function _range ($str, $param) {
            // param is in the form of "0~10"
            $numbers = explode ("~", $param);
            if (sizeof ($numbers) == 2) {
                // [0] is min, [1] is max
                return ((float)$numbers[0] >= (float)$str && (float)$str <= (float)$numbers[1]);
            } else {
                return false; // if the program is wrong, the validation is not passed
            }
        }

        function _minLength ($str, $param) {
            return (strlen ($str) >= $param);
        }
     
        function _maxLength ($str, $param) {
            return (strlen ($str) <= $param);
        }
     
        function _match ($str, $param) {
            return (preg_match ($param, $str) > 0);
        }
     
        function _email ($str, $param = null) {
            // param is optional. If you give one, we'll use it as regex.
            if (!is_null ($param)) {
                $param = '/[a-z0-9_\-]+@[a-z0-9_\-\.]+\.[a-z]+/i';
            }
            return (preg_match ($param, $str) > 0);
        }
        
        function _password ($str, $param = null) {
            // param is optional. If you give one, we'll use it as regex.
            if (!is_null ($param)) {
                // http://regexhero.net/library/35/strong-password
                $param = '^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{6,50}$';
            }
            return (preg_match ($param, $str) > 0);            
        }
     
        function _time ($str, $param = null) {
            // param is optional. If you give one, we'll use it as regex.
            if (!is_null ($param)) {
                // basically, [0]1[:59][:59][[ ]PM]
                $param = '/^(((0?\d)|(1[0-2]))(:[0-5]\d){1,2}(\s)*[AP]\.?M\.?|(((0?|1)\d)|(2[0-3]))(:[0-5]\d){0,2})$/i';
            }
            return (preg_match ($param, $str) > 0);
        }
     
        function _url ($str, $param = null) {
            // param is optional. If you give one, we'll use it as regex.
            if (!is_null ($param)) {
                $param = '/^(?:(?:https?:\/\/)?((?:[\w\-]+\.)+[A-Za-z]{2,})|(?:(?:[012]?\d{1,2}\.){3}[012]?\d{1,3}))(?::\d+)?(?:[?#\/]|$)/i';
            }
            return (preg_match ($param, $str) > 0);
        }
        
        function _charset ($str, $param = 'UTF-8') {
            // checks if $str is allowed in the specified character set.
            // see mb_check_encoding
            return mb_check_encoding ($str, $param);
        }
    }
?>