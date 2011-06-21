<?php
    require_once ('.things.php');
    
    if ($auth->IsLoggedIn()) {
        if (!$gp->Has ('from') || strlen ($gp->Get ('from')) == 0) { // directive to return to a page - see CheckAuth in auth.php
            $gp->Set (array ('from'=> WEBROOT . 'menu'));
        }
		header ('location: ' . $gp->Get ('from'));
		exit ();
    }
?>

<html>
    <head><style type='text/css'>
        input {border: 1px solid silver;padding:5px;}
    </style></head>
    <body style='background-color:#eee;font-family:sans-serif;
                 line-height:1.5em;font-size:0.8em;'>
        <div style='background-color:#fff;position:fixed;
                    left:50%;top:50%;width:260px;margin-left:-150px;
                    height:180px;margin-top:-110px;text-align:center;
                    padding:20px;border:1px solid silver;'>
            <div style='color:#999;font-size:3em;
                        padding:10px;'>ohai</div>
            <form method='post'>
                <label for='loginName'>User name: </label><br />
                <input id='loginName' name='loginName' type='text' 
                    <?php 
                        if (isset ($_POST['loginName'])) { 
                            echo (' value="' . $_POST['loginName'] . '"');
                        }
                    ?> 
                /><br />
                <label for='loginPassword'>Password: </label><br />
                <input id='loginPassword' name='loginPassword' type='password'
                    <?php 
                        if (isset ($_POST['loginPassword'])) { 
                            echo (' value="' . $_POST['loginPassword'] . '"');
                        }
                    ?> 
                /><br />
                <br />
                <input type='submit' value='Log in' />
            </form>
        </div>
    </body>
</html>