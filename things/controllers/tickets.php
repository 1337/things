<?php
    require_once ('../../.things.php');
    CheckAuth (); // require a login. --> $user is available to you.

	$headers = '<script type="text/javascript" src="/scripts/jquery_optional.js"></script>';
    println ("Tickets assigned to you", 2);
	
	
	$ticket_ids = $user->GetChildren (TICKET);
	
    if (sizeof ($ticket_ids) > 0) {
		foreach ($ticket_ids as $tid) {
	        $tobj = new Ticket ($tid);
			$tp = $tobj->GetProps ();
?>
            <div class="ticket">
                <h3><?php 
				    echo ($tobj->GetProp ('name'));
					switch ($tobj->GetProp ('priority')) {
				        case 1: // 1 = highest priority
						    
						case 2:
						
						case 3:
						
						case 4:
						
						case 5: // 5 = lowest priority
						
						default: // unassigned
					}
				?>
                </h3>
                <p>Description:</p>
            </div>
<?php
		}
	}

	page_out ();
?>