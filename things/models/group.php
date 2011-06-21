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
			$this->DelChildren (array ($oid));
        }
		
		function CheckPrivilege ($priv_id) {
			// back-compatibility
            // supply a privilege ID, tells you if the user owns this ID.
            $owned_ids = $this->GetChildren (PRIVILEGE);
            if (in_array ($priv_id, $owned_ids)) {
				return true; // if the user has its privileges, then it's true!
			}
		}
    }
?>