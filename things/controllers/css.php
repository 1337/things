<?php
    // php CSS compressor and grouper
    // usage: .css.php?f=default,jquery
    
    $LESSPHP_location = PROOT . 'tools/lessc.inc.php'; // if present, LESSPHP will be here.
    
    require_once ('.things.php');
    
    $expires = "Expires: " . gmdate("D, d M Y H:i:s", time() + 864000) . " GMT";
    header("Content-type: text/css; charset: UTF-8");
    header($expires);

    if ($gp->Has ('f') || $gp->Has ('file')) { // get the file name.
        
        $files = $gp->Has ('f') ? $gp->Get ('f') : $gp->Get ('file'); // short or long
        
        foreach (explode (',', $files) as $file) {
            /* for security reasons, this function must only
               be valid for .css files, and this limits it */
            $file_css = THINGS_CSS_DIR . $file . ".css";
            $file_less = THINGS_CSS_DIR . $file . ".less";
            if (file_exists ($file_less) &&
               !file_exists ($file_css) && 
                file_exists ($LESSPHP_location)) { 
                // compile LESS if: LESSPHP exists, LESS file exists, CSS file doesn't
                require_once ($LESSPHP_location);
                try {
                    $less = new lessc($file_less);
                    echo css_compress ($less->parse());
                } catch (exception $ex) {
                    exit ('lessc fatal error:<br />'. $ex->getMessage ());
                }
			} elseif (file_exists ($file_css)) {
                echo (css_compress (join ('', file ($file_css))));
            }
        }
    } 
?>