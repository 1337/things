<?php
    class Post extends Thing {
        private $body;
     
        function GetBody () {
            // function deprecated.
            return $this->GetProp ('body');
        }
     
        function SetBody ($what) {
            // function deprecated.
            return $this->SetProp ('body', $what);
        }
     
        function GetTitle () {
            // function deprecated.
            return $this->GetProp ('name');
        }

        function SetTitle ($what) {
            // function deprecated.
            return $this->SetProps(array ('name'=>$what));
        }
     
        function IsHidden ($threshold = 0) {
            // simple flag check
            return ($this->GetProp('hidden') > $threshold);
        }
     
        function GetTags () {
            // returns only childs that are tags.
            return $this->GetChildren (TAG);
        }
     
        function GetPostTime () {
            $file = $this->GetProp ('bodyhref');
            if (!is_null ($file) && file_exists ($file)) {
                return filemtime ($file); // file's modified time
            } elseif (!is_null ($this->GetProp ('db_time'))) {
                return $this->GetProp ('db_time'); // if recorded in DB, use it
            } else {
                return 0; // epoch?
            }
        }
     
        function GetAuthors ($firstone = true, $default = "Nobody") {
            // technically, gets its parent users.
            // if $firstone, then it gives only the first author found.
            $authors = $this->GetParents(USER);
            if (sizeof ($authors) > 0) {
                if ($firstone) {
                    return $authors[0]; // first guy out always wins the jackpot
                } else {
                    return $authors;
                }
            } else {
                return $default;
            }
        }
     
        function MakeSEO ($alias) {
            // makes a "search engine friendly URL".
            $proposed_alias = preg_replace ('/[^a-z0-9_\-]/i', '', 
                str_replace (' ', '-', 
                    strtolower ("$alias.html")
                )
            );
            return $proposed_alias;
        }
    }
?>
