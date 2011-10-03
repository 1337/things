<?php
    require_once ('.things.php');

    $page = new Page ($id);
    eval (" ?>" . $page->GetProp ('body') . "<?php ");
    exit ();
?>