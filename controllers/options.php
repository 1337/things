<?php
    require_once ('.things.php');
    CheckAuth (); // require a login. --> $user is available to you.

    $a = new AjaxField ($user->oid);    

    // change password
    if ($gp->Has ('cp1') && $gp->Has ('np1') && $gp->Has ('np2')) {  //change password
        if ($gp->Get ('np1') != $gp->Get ('np2') ||             //two new passes mismatch
            !$user->CheckPassword ($gp->Get ('cp1')) ||         //old password wrong
            strlen ($gp->Get ('np1')) < 6 ||                 //new pass too short
            strlen ($gp->Get ('np1')) > 30) {                 //new pass too long
            println ("There was a problem with the passwords you 
                     entered. Please try again.",$fail);
?>
               <p>Possible causes:</p>
               <ul>
                   <li>Your old password is incorrect.</li>
                   <li>Password is less than six (6) characters long.</li>
                   <li>Password is longer than thirty (30) characters.</li>
               </ul>
<?php
        } else {                                            //password win
            if ($user->SetPassword ($gp->Get ('np1'))) {
                println ("Password successfully changed! Please keep it safe.", $win);
            } else {
                println ("A problem was encountered when saving your new password.", $fail);
            }  
        }
    }
 
    // deactivate account
    if ($gp->Has ('deactivateme')) { //user clicks something on the deactivation form
        if ($gp->Get ('yes')) { //user clicks yes
            $user->Destroy (); // well, that's it
            $auth->Logout ();
        }
    }

    require_once (PROOT . 'views/options.php');
?>