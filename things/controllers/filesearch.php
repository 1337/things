<?php
    require_once ('.things.php');
    require_once ('filesearch.class.php');
 
    if ($gp->Has('q')) {
        $q = $gp->Get('q');
        $fs = new FileSearch ();
        $fss = implode ("|", $fs->FileContentSearch($q));
     
     
        $fss = str_replace ($_SERVER['DOCUMENT_ROOT'], "http://" . $_SERVER['SERVER_NAME'], $fss);
     
     
        $fs = explode ("|", $fss);

        function dummyfunction3354 (&$z) {
            $z = "<a href='$z'>$z</a>";
        }
 
        array_walk ($fs, 'dummyfunction3354');
        $fss = implode ("<br />", $fs);
        echo ("<html><body>$fss</body></html>");
    }
?>