<?php
    require_once ('.things.php');
    CheckAuth (); // require a login. --> $user is available to you.
                                       // $gp   is available to you.

    // change theme
    if ($gp->Has ('tm')) {
        $tm = $gp->Get ('tm');
        $user->SetProp ('template', $tm . '.inc');
        if ($user) {
            println ("Saved template preference.", $win);
        }
    }
        
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
?>
    <div id="accordion">
        <h3><a href="#">Your personal information</a></h3>
        <div>
            <form method='post' id='ctlpnl'>
                <table><tr>
                    <td rowspan='8' style='padding-right:10px;'>
                        <img src='<?php echo ($user->GetProp('picture')); ?>' 
                             style='max-width:200px;
                                    max-height:200px;
                                    margin:auto;
                                    display:block;' 
                             class='dimg' />
                    </td><td style='width:200px;'>
                        Real name:
                    </td><td>
                        <input style='width:300px;' 
                               type='text' 
                               name='realname' 
                               value='<?php echo ($user->GetProp('realname')); ?>' 
                               class='required' 
                               minlength='2' />
                    </td>
                </tr><tr><td>
                        Profile picture:
                    </td><td>
                        <input style='width:300px;' 
                               type='text' 
                               name='picture' 
                               value='<?php echo ($user->GetProp('picture')); ?>' 
                               class='url' />
                    </td>
                </tr><tr><td>
                        Web site:
                    </td><td>
                        <input style='width:300px;' 
                               type='text' 
                               name='website' 
                               value='<?php echo ($user->GetProp('website')); ?>' 
                               class='url' />
                    </td>
                </tr><tr><td>
                        About you:
                    </td><td>
                        <textarea style='width:300px;height:200px;' 
                                  name='about'><?php echo ($user->GetProp('about')); ?></textarea>
                    </td>
                </tr>
                <tr><td></td><td>
                        <input type='submit' value='Done' />
                    </td>
                </tr></table>
                <input type='hidden' name='ctlpnl' value='omg' />
            </form>
        </div>
        <h3><a href="#">Change theme</a></h3>
        <div>
            <p>Others will not see your theme.</p>
            <form method='post'>
                <select name="tm">
                    <option value="template3">Default</option>
                    <option value="template2">Old</option>
                    <option value="template4">Beta</option>
                </select>
                <input type='submit' />
            </form>
        </div>
        <h3><a href="#">Your saved settings</a></h3>
        <div>
        <?php
            $props = $user->GetProps ();
            if (sizeof ($props) <= 0) {
                println ("You have no saved settings.", $fail);
            } else {
                echo("<pre>");
                print_r ($props);
                echo("</pre>");
            }
            
            if ($user->CheckPrivilege('change_setting_raw')) {  
        ?>
                <form method="post" action="">
                    <fieldset>
                        Setting name: 
                        <input type="text" name="setn" style="width:200px;" value="<?php echo($gp->Get ('setn')); ?>" />
                        Setting value: 
                        <input type="text" name="setv" style="width:300px;" value="<?php echo($gp->Get ('setv')); ?>" /> 
                        <input type='hidden' name='changesettings' value='sorta' />
                        <input type="submit" name="submit" value="Save" />
                    </fieldset>
                </form>         
        <?php
            }
        ?>
        </div>
        <h3><a href="#">Change password</a></h3>
        <div>
            <form id='changepassworddlg' method='post' title='Change password'>
                <table>
                    <tr>
                        <td>Your username: </td>
                        <td><?php echo ($user->GetProp('name')); ?></td>
                    </tr><tr>
                        <td>Your current password: </td>
                        <td><input type='password' name='cp1' id='cp1' /></td>
                    </tr><tr>
                        <td>New password: </td>
                        <td><input type='password' name='np1' id='np1' /></td>
                    </tr><tr>   
                        <td>New password again: </td>
                        <td><input type='password' name='np2' id='np2' /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type='submit' /></td>
                    </tr> 
                </table>
            </form>
        </div>
        <h3><a href="#">Delete account</a></h3>
        <div>
            <form id='deactivate' method='post'>
                <p>This will remove your account and its information. <em>You cannot reactivate your account.</em></p>
                <input type='submit' name='yes' value='Yes' />
                <input type='submit' name='no'  value='No'  /> 
                <input type='hidden' name='deactivateme' value='sorta' />
            </form>
        </div>
    </div>
<?php
    page_out(array('title' => 'Account settings'));
?>
