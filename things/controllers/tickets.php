<?php
    require_once ('../../.things.php');
    CheckAuth (); // require a login. --> $user is available to you.
    
	$headers = '<script type="text/javascript" src="/scripts/jquery_optional.js"></script>';
    println ("Your Job Tickets", 2);
	
	
	$ticket_ids = $user->GetChildren (TICKET);
	
    if (sizeof ($ticket_ids) > 0) {
		foreach ($ticket_ids as $tid) {
	        $ajax = new AjaxField ($tid);
			$tobj = new Ticket ($tid);
			$tp = $tobj->GetProps ();
?>
            <div class='ticket'>
                <?php $ajax->NewTextField ('title', $tp['name']); ?>
                <br />
                <?php 
				    $ajax->NewTextAreaField (array (
				        'prop' => 'description', 
						'friendlyname' => 'Description',
						'style' => 'height:200px;'
				    )); 
				?>
            </div>
<?php
		}
	}

	page_out ();
?>