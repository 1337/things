<?php
    class Group extends Thing {
                
        function GetUsers () {
            return $this->GetChildren ();
        }
        
        function AddUser ($oid) {
            // does not create the user.
            // does not check if user object exists.
            return $this->SetChildren (array ($oid));
        }
        
        function RemoveUser ($oid) {
            // removes a user from a group. to delete a user, use the User->Destroy() function.
        }
    }
?>