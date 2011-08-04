<?php
    class FileSearch {
        function FileNameSearch ($mask, $path = '') {
            // search file names only.
            // path = search path
            // mask = preg matching criteria
         
            $results = array ();
         
            if (strlen ($path) == 0) {
                $path = $_SERVER['DOCUMENT_ROOT'];
            }
             
            foreach (glob ("$path/*") as $match) {
                if (is_dir ($match)) {
                    $results = array_merge ($results, $this->FileNameSearch ($mask, $match)); // recursion
                } elseif (preg_match ('/' . $mask. '/' , $match) == 1) {
                    $results[] = $match;
                }
            }
            return $results;
        }
     
        function FileContentSearch ($mask, $filemask = '.*', $path = '') {
            // search content in all files under this path.
            // path = search path
            // filemask = select some files only
            // mask = preg matching criteria
            $results = array ();
         
            if (strlen ($path) == 0) {
                $path = $_SERVER['DOCUMENT_ROOT'];
            }
         
            $files = $this->FileNameSearch ($filemask, $path);
         
            foreach ($files  as $file) {
                $contents = file_get_contents ($file);
                if (preg_match ('/' . $mask. '/' , $contents) == 1) {
                    $results[] = $file;
                }
            }
            return $results;
        }
    }
?>
