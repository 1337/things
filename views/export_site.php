<?php
    header ("Content-type: application/json");
    require_once ('.things.php');

    $f = new Things(ALL_OBJECTS);
    $objs = $f->GetRealObjects();
    
    echo ('[');
    
    foreach ($objs as $obj) {
        echo $obj->Export();
        echo (',');
    }

    echo (']');
?>
