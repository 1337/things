<?php
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
            // if $priv_id is a string, will convert the privilege to an ID for you.
            // this means you cannot have a privilege with a numerical name. it makes no sense anyway.
         
            if (!is_numeric ($priv_id)) {
                $priv_id = FindObject ($priv_id, PRIVILEGE);
            }
         
            $owned_ids = $this->GetChildren (PRIVILEGE);
            if (in_array ($priv_id, $owned_ids)) {
                return true; // if the user has its privileges, then it's true!
            } else {
                $user_groups = $this->GetParents (GROUP); // get groups associated with this user.
                // policy: if any group with this user has required privilege, then make true.
                if (sizeof ($user_groups) > 0) {
                    foreach ($user_groups as $gid) {
                        $grp = new Group ($gid);
                        if ($grp->CheckPrivilege ($priv_id)) {
                            return true; // if group has it, then it's true for all users too!
                        }
                    }
                }
            }
            return false;
        }

        function CheckPrivileges ($priv_ids) {
            // supply an array of privilege IDs, and tells you if they all meet.
            // $owned_ids = $this->GetChildren (PRIVILEGE);
            // return (sizeof (array_intersect ($priv_ids, $owned_ids)) >= $priv_ids);
            $good = true;
         
            if (sizeof ($priv_ids) > 0) {
                foreach ($priv_ids as $priv_id) {
                    $good = $good && $this->CheckPrivilege ($priv_id);
                }
            }
         
            return $good;
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