<?php
    // controls BOTH editing and new pages.
        if (count(get_included_files()) <= 1) {
        // this file must be called by page-ui.php.
        // it uses variables only available from page-ui.php.
        die ();
    }        

    require_once ('.things.php');

    CheckAuth (); // require a login. --> $user is available to you.
    
    if (isset ($_POST['submit'])) {
        // CheckAuth (); // require a login. --> $user is available to you.
        // someone posted something
        $body = $gp->Get ('body');
        $name = $_POST['name'];
        
        if (substr ($name, 0, 1) != '/') {
            // $_REQUEST_URI ALWAYS comes with a / in front, so this must also come with...
            $name = '/' . $name;
        }
        
        if ($id > 0) { // existing page
            $page = new Page ($id);
        } else {
            $page = new Page (NEW_PAGE);
            $id = $page->oid;
        }
        
        if (is_null (FindObject ($name, PAGE))) {
            // make sure the URL isn't already used by a fellow page
            //     (not necessarily other objects, sadly)    
            $page->SetProps (array ('name'=>$name, 'body'=>$body));
            $edit = 1; // flag to read later (at around line 50)
            println ("Saved as #$id", $win);
        } else {
            $page->SetProps (array ('name'=> $name . md5 (time ()), 'body'=>$body));
            $edit = 1; // flag to read later (at around line 50)
            println ("URL is already in use. This page was saved with a random URL.", $fail);
        }
        
    }
    
    $namestr = '';
    $bodystr  = '';
    $idstr    = '';
    if ($id > 0 && strlen ($edit) > 0) { // existing page
        $page = new Page ($id);
        $namestr = "value='" . htmlentities ($page->GetProp ('name'), ENT_QUOTES) . "'";
        $bodystr = htmlentities ($page->GetProp ('body'), ENT_QUOTES);
        $idstr = "<input type='hidden' name='id' value='$id' />";
    }
    echo (" <form method='post'>
                <fieldset>
                    $idstr
                    URL:* <input type='text' name='name' $namestr />
                    <input type='submit' name='submit' value='submit' />
                    Body:* 
                    <textarea name='body' id='body' style='width:100%;height: 400px;'>$bodystr</textarea>
                </fieldset>
            </form>");
    render ();
?>