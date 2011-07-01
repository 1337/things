<?php
    require_once ('.things.php');
	CheckAuth ();
	
	if ($gp->Has ('oid')) {
		$oid = $gp->Get ('oid');
		$obj = new Thing ($oid);
		$oaj = new AjaxField ($oid);
		$otp = GetTypeName ($obj->GetType ());
		echo ("<h1>$otp #$oid</h1>");
		$var_props = $obj->GetProps ();
		if (sizeof ($var_props)) {
			echo ("<div style='overflow:hidden'>");
			foreach ($var_props as $k=>$v) {
				/*printf ("<b>%s</b>: %s<br />",
				    strtoupper ($k),
					$v
				);*/
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