<?php
    require_once (PROOT . 'models/thing.php');
 
    class Tag extends Thing {
 
        function GetPosts () {
            // returns all posts with this tag.
            return $this->GetParents (POST, "ORDER BY `parent_oid` DESC");
        }
    } 
?>