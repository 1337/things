<?php
    if (count(get_included_files()) <= 1) {
        // this file must be called by the login.php controller.
        // it uses variables only available from login.php.
        die ();
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
                    height:190px;margin-top:-115px;text-align:center;
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
                (or <a href="register">register</a>)
            </form>
        </div>
    </body>
</html>
