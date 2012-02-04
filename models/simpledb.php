<?php

    class SimpleDB {
        // PHP 5 only
        
        protected $filename;
        private $db;
        /* $db-> [
                objects (alias) [
                    rows (key => val)
                ]
           ]
        */
        
        function __construct ($filename = 'simple.db') {
            if (strlen ($filename) > 0) {
                $this->load ($filename);
            }
        }
        
        function load ($filename) {
            $this->filename = $filename;
            $str = file_get_contents ($filename);
            if ($str === false || $str == '' || $str == null) {
                $this->db = array (); // init new db
            } else {
                $this->db = unserialize ($str); // init old db
            }
        }
        
        function save () {
            if (file_put_contents ($this->filename, serialize ($this->db)) === false) {
                die (); // silently
            }
        }
        
        function reload () {
            $this->load ($this->filename);
        }
        
        function find ($find = null) {
            // returns all object aliases with matching value somewhere.
            $found = array ();
			if ($find === null) { // don't find stuff
				$found = array_keys ($this->db);
			} else { // find stuff
				foreach ((array) $this->db as $alias => $value) {
					if (is_array ($value) || is_object ($value)) {
						$valstr = serialize ($value); // just to make it simpler to search
					} else {
						$valstr = $value;
					}
					if (stripos ($valstr, $find) !== false) {
						$found[$alias] = $value; // add something to find
					}
				}
			}
            return $found;
        }
		
		function get ($alias) {
			return $this->__get ($alias);
		}
        
		function set ($alias, $value) {
			return $this->__set ($alias, $value);
		}

        function __set ($alias, $value) {
            $this->db[$alias] = $value; // update cache
            $this->save (); // save cache to db
        }

        function __get ($alias) {
            if (array_key_exists ($alias, $this->db)) {
                return $this->db[$alias];
            } else {
                return null;
            }
        }
    }
?>