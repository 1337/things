<?php
    // this thing's job is to SWALLOW
	// the files corresponding to the input URL to show where they belong.
	require_once ('.things.php');
	
	function FindObjectByPermalink ($link) {
	    $things = new Things (ALL_OBJECTS);
	    // $things->FilterByPreg ('permalink', '/.+/'); // "has a url"
		$things->FilterByProp ('permalink', $link);
		$found = $things->GetObjects ();
		if (sizeof ($found) == 0) {
			return null;
		} else {
		    return $found[0]; // the function is clearly not plural.
		}
	}
	
?>