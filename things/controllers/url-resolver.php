<?php
    // this thing's job is to SWALLOW
    // the files corresponding to the input URL to show where they belong.
    require_once ('.things.php');
    
    // $request_uri = $gp->Get ('q');
    $request_uri = $_SERVER['REQUEST_URI'];
    if (strlen ($request_uri) > 0) {
        $object_id = FindObjectByPermalink ($request_uri);
        if (!is_null ($object_id)) {
            $type = GetObjectType ($object_id);
            header ("HTTP/1.1 301 Moved Permanently");
            header ("location: " . WEBROOT . strtolower (GetTypeName ($type)) . "/" . $object_id);
            // so like http://ohai.ca/post/11
            exit ();
        }
    }
    include (dirname (PROOT) . '/404.php'); // if it gets here, it's not found
?>
