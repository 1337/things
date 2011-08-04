<?php
    require_once ('.things.php');
    // supposedly loads any aliases.
    
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
                // if ($post->GetProp('alias') == $path) {
                if ($post->GetProp('permalink') == $path) {
                    $id = $post->oid;
                    header ("HTTP/1.1 301 Moved Permanently");
                    header ("location: /post/$id");
                    exit ();
                }
            }
        }
    }
	include (dirname (PROOT) . '/404.php');
?>