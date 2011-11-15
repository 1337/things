<?php
    require_once ('.things.php');
    require_once ('../controllers/validator.php');
    
    if ($auth->IsLoggedIn()) {
        header ('location: /login');
    }

    $headers = '
        <style type="text/css">
            form {
                text-align:center;
            }
            input[type="text"], input[type="password"], input[type="email"] {
                width:150px;
                border:1px solid silver;
                border-radius:3px;
                padding:3px;
                margin: 3px;
            }
            label {
                min-width:150px;
                display:inline-block;
                text-align:right;
            }
        </style>
    ';
    if (isset ($_POST) && isset ($_POST['submit'])) {
        $nname = $_POST['nname'];
        $npass = $_POST['npass'];
        $nmail = $_POST['nmail'];
        $ncapt = $_POST['ncapt'];
        $error = 0;
     
        if (strlen ($nname) == 0 || strlen ($npass) == 0 || 
            strlen ($nmail) == 0 || strlen ($ncapt) == 0) {
            println ("Form is not filled yet.", $fail);
            $error ++;
        }
     
        if (FindObject ($nname, USER) != null) {
            println ("User is taken.", $fail);
            $error ++;
        }
     
        $v = new Validator (array ('email'=>'1'));
        if (!$v->Test ($nmail)) {
            println ("Email is wrong.", $fail);
            $error ++;
        }
     
        if ($ncapt != '1' && $ncapt != 'one') {
            println ("You should check the math again.", $fail);
            $error ++;
        }
     
        if ($error == 0) {
            $user = new User (NEW_USER);
            $auth = new Auth ();
            $user->SetProps (array (
                'name'=>$nname,
                'email'=>$nmail
            ));
            $user->SetPassword ($npass);
            println ("User created! You can now <a href='/menu'>log in</a>.", $win);
        }
     
    }
?>
    <form method="post">
        <label for="nname">Username:</label>
        <input type="text" name="nname" id="nname"
               required="required" placeholder="Pick a user name" 
               <?php
                   if (isset ($nname)) { echo ("value='$nname'"); }
               ?> />
        <br />
     
        <label for="npass">Password:</label>
        <input type="password" name="npass" id="npass" required="required" placeholder="Enter a password" />
        <br />
     
        <label for="nmail">Email:</label>
        <input type="email" name="nmail" id="nmail" required="required" placeholder="(no spam)" />
        <br />

        <label for="ncapt">What is <i>cos(0)</i>?</label>
        <input type="text" name="ncapt" id="ncapt" required="required" placeholder="Enter a number" />
        <br />

        <br />
        <input type="submit" name="submit" value="Register" />
    </form>
<?php
    render (array ('headers'=>$headers));
?>