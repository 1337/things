<?php
    require_once ('.things.php');
	
    $new = $gp->Get('new');
    $edit = $gp->Get('edit');
    $id = $gp->Get('id');
	
	if (!ObjectExists ($id) || $new) {
		// even if $id is specified for a non-object, the created ticket will NOT be of the same ID.
		$ticket = new Ticket (NEW_TICKET); // create the thing.
		$ticket->SetProp ('name', 'CPA-058');
		header ("location: ticket"); 
	} else {
		var_dump (ObjectExists ($id));
		$ticket = new Ticket ($id);
		echo ("Loaded");
	}
	
    render ();
?>