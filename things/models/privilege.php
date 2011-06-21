<?php
    class Privilege extends Thing {
        
        function UserCanHas ($uid) {
            // checks if a user has THIS privilege already.
            // technically, it does not check if the target object is a user at all.
            $u = new User ($uid);
            return ($u->GetChildren ($this->oid));
        }
    }
?>