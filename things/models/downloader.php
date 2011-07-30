<?php
    class Downloader {
        public $url;      // load from
        public $location; // save to
        public $contents; // of the file
        
        function __construct ($url = '', $loc = '') {
            // if nothing is given, nothing is done
            // if url is given, the url is saved, 
            //     but not downloaded
            // if url and loc are given, file will be 
            //     downloaded and saved at loc
            if (strlen ($url) > 0) {
                $this->url = $url;
            }
            if (strlen ($url) > 0 && strlen ($loc) > 0) {
                $this->location = $loc;
                $this->Get ();
            }
        }
        
        function Downloader ($url = '', $loc = '') {
            return __construct ($url, $loc);
        }
        
        function Get () {
            // download the file. Call twice to download again.
            // returns the results, or you can get it later
            // with $this->contents.
            try {
                $this->contents = file_get_contents ($this->url);
                $fp = fopen ($this->location, "w");
                fwrite ($fp, $this->contents);
                fclose ($fp);
                return $this->contents;
            } catch (Exception $e) {
                // "onoz" response
                return null;
            }
        }
		
		//function dl ($u,$d=''){$f=fopen($d?$d:basename($u),'w');fwrite($f,file_get_contents($u));fclose($f);}
    }
?>