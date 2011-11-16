<?php
    require_once ('.things.php');
    
    require_once (PROOT . 'controllers/options.php'); // <-- business logic
    
    CheckAuth (); // require a login. --> $user is available to you.
    
?>
    <h1>Options</h1>
    
    <hr />
    <h2>Choose a theme</h2>
<?php
    // change theme
    $templates = glob (THINGS_TEMPLATE_DIR . '*.php'); // find template files
    foreach ((array) $templates as $temp) {
        $templates_2[] = basename ($temp); // list them
    }
    $a->NewDropdownField (array (
        'friendlyname' => 'Choose a theme',
        'choices' => $templates_2,
        'prop' => 'template'
    ));
?>
    <hr />
    <h2>Change password</h2>
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
    
    <hr />
    <h2>Delete account</h2>
    <div>
        <form id='deactivate' method='post'>
            <p>This will remove your account and its information. <em>You cannot reactivate your account.</em></p>
            <input type='submit' name='yes' value='Yes' />
            <input type='submit' name='no'  value='No'  /> 
            <input type='hidden' name='deactivateme' value='sorta' />
        </form>
    </div>
<?php
    render (array (
        'title' => 'Options'
    ));
?>