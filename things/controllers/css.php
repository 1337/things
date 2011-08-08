<?php
    // php CSS compressor and grouper
    // usage: .css.php?f=default,jquery
    
    require_once ('.things.php');
    
    if (!ob_start ("ob_gzhandler") && !headers_sent ()) {
        ob_start();  // failsafing?
    }
    
    $expires = "Expires: " . gmdate("D, d M Y H:i:s", time() + 864000) . " GMT";
    header("Content-type: text/css; charset: UTF-8");
    header($expires);

    if (isset ($_GET['f']) || isset ($_GET['file'])) { // get the file name.
        
        $files = isset ($_GET['f']) ? $_GET['f'] : @$_GET['file'];
        
        foreach (explode (',', $files) as $file) {
            /* for security reasons, this function must only
               be valid for .css files, and this limits it */
            $file = THINGS_CSS_DIR . $file . ".css";
            if (file_exists ($file)) {
                echo (css_compress (join ('', file ($file))));
            }
        }
    } 
?>
