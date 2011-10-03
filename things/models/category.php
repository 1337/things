<?php 
    class Category extends Thing {
     
        function GetTags () {
            return $this->GetChildren (TAG);
        }
     
        function GetPosts () {
            // returns all posts 'in this category'.
            // actually just looks at the tags and say "give me all the posts".
            $mytags = $this->GetTags ();
            $myposts = array ();
            if (sizeof ($mytags) > 0) {
                foreach ($mytags as $tag) {
                    $myposts = $myposts + $tag->GetPosts (); // merge the two arrays
                }
            }
            return $myposts;
        }
    }
?>