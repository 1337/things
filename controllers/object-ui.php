<?php
    require_once ('.things.php');
    CheckAuth ("administrative privilege"); // because nobody wants everyone to see what everything is
 
    if ($gp->Has ('id')) {
        $oid = $gp->Get ('id');
        $obj = new Thing ($oid);
        $oaj = new AjaxField ($oid);
        $otp = GetTypeName ($obj->GetType ());
        echo ("<h1>$otp #$oid</h1>");
        $var_props = $obj->GetProps ();
        if (sizeof ($var_props)) {
            echo ("<div style='overflow:hidden'>");
            foreach ($var_props as $k=>$v) {

                $oaj->NewTextField (array (
                    'prop' => $k,
                    'friendlyname' => $k,
                    'style' => 'width:60%'
                ));
                echo ("<br />");
            }
            echo ("</div>");
        }
    }
 
    render ();
?>