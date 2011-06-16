<?php
    require_once (PROOT . 'models/thing.php');
    
    class User extends Thing {
        
        function SetProps ($what) {
            ack_r3 ($what); // change all keys to lower case
            if (array_key_exists ('name', $what)) { // check if key exists
                $name = $what['name'];
                if (FindObject ($name, user) !== null) { // if exists, check if user exists
                    die ("User exists");
                } else {
                    $this->SetPropsHelper ($what); // if not, carry on with your lives                
                }
            }
        }
        
        function CheckPrivilege ($priv_id) {
			// back-compatibility
            // supply a privilege ID, tells you if the user owns this ID.
            $owned_ids = $this->GetChildren (PRIVILEGE);
            return array_key_exists ($priv_id, $owned_ids);
        }

        function CheckPrivileges ($priv_ids) {
            // supply an array of privilege IDs, and tells you if they all meet.
            $owned_ids = $this->GetChildren (PRIVILEGE);
            return (sizeof (array_intersect ($priv_ids, $owned_ids)) >= $priv_ids);
        }
		
		function CheckPassword ($pw) {
		    // returns true if password is correct.
            $temp_auth = new Auth ();
            $pw_hash = $temp_auth->SuperSecureHash ($pw);
            return $this->GetProp ('password') == $pw_hash;
		}

        function SetPassword ($pw) {
		    // returns true if password is correctly set.
            $temp_auth = new Auth ();
            $pw_hash = $temp_auth->SuperSecureHash ($pw);
            $this->SetProp ('password', $pw_hash);
            return $this->GetProp ('password') == $pw_hash;
        }
    }
?>