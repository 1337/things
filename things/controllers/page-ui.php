<?php
    require_once ('.things.php');
    
    $new = $gp->Get('new');
    $edit = $gp->Get('edit');
    $id = $gp->Get('id');
    
    if (isset ($_POST['submit'])) {
        CheckAuth (); // require a login. --> $user is available to you.
        // someone posted something
        $body = $gp->Get('body');
        $name = $_POST['name'];
        if ($id > 0) { // existing page
            $page = new Page ($id);
        } else {
            $page = new Page (NEW_PAGE);
            $id = $page->oid;
        }
        $page->SetProps (array ('name'=>$name, 'body'=>$body));
        println ("Saved as #$id", $win);
    }
    
    if (strlen ($new)  > 0 || 
       (strlen ($edit) > 0 && $id > 0 && GetObjectType ($id) == PAGE)) {
        // this is if new, or old and valid ID
        CheckAuth (); // require a login. --> $user is available to you.
    
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
        exit();
    } else {
        $path = $_SERVER['REQUEST_URI']; // this seems to work 99% of the time
        if (strlen ($path) > 0) {
            $page_id = FindObject ($path, PAGE);
            if ($page_id > 0) {
                $page = new Page ($page_id);
                eval (" ?>" . $page->GetProp ('body') . "<?php ");
                exit ();
            } else {
                $objgrp = new Things (POST);
                foreach ($objgrp->GetObjects () as $post_id) {
                    $post = new Post ($post_id);
                    if ($post->GetProp('alias') == $path) {
                        $id = $post->oid;
                        header ("HTTP/1.1 301 Moved Permanently");
                        header ("location: /post/$id");
                        exit ();
                    }
                }
            }
            header ("location: /404.php");
        } else {
            header ("location: /404.php");
        }
    }
?>