<?php
    require_once (PROOT . 'models/thing.php');
    
    class Tag extends Thing {
    
        function GetPosts () {
            // returns all posts with this tag.
            return $this->GetParents (POST, "ORDER BY `parent_oid` DESC");
        }
    
        function TagCount () {
            // finds the number of times this tag has appeared in the database.
            $otype = TAG;
            $name = $this->GetProp ('name');
            return SingleFetch ("SELECT COUNT( * ) AS count
                                    FROM `objects` AS ua, `properties` AS ub
                                   WHERE ua.`type` = '$otype'
                                     AND ua.`oid` = ub.`oid` 
                                     AND ub.`name` = 'name'
                                     AND ub.`value` = '$name'",
                                 "count");
        }
    }    
?>