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
     
        function TagCount () {
            // finds the number of tags in this category
            $otype = TAG;
            $oid = $this->oid;
            return SingleFetch ("SELECT COUNT( * ) AS count
                                    FROM `objects` AS ua, `hierarchy` AS ub
                                   WHERE ub.`parent_oid` = '$oid'
                                     AND ub.`child_oid` = ua.`oid`
                                     AND ua.`type`='$otype'",
                                "count");
        }
    }
?>