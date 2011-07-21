<?php
    require_once ('.things.php');
    CheckAuth (); // require a login. --> $user is available to you.

    $styles_dir = WEBROOT . 'styles/images/';
	$headers = '<script type="text/javascript" src="/scripts/jquery_optional.js"></script>';
    println ("Tickets assigned to you", 2);
	
	
	$ticket_ids = $user->GetChildren (TICKET);
	
    if (sizeof ($ticket_ids) > 0) {
		foreach ($ticket_ids as $tid) {
	        $tobj = new Ticket ($tid);
			$tp = $tobj->GetProps ();
?>
            <div class="ticket">
                <p><?php 
				    switch ($tobj->GetProp ('priority')) {
				        case 1: // 1 = highest priority
						    $icon = $styles_dir . 'red.png';
							break;
						case 2:
    						$icon = $styles_dir . 'orange.png';
							break;
						case 3:
						    $icon = $styles_dir . 'yellow.png';
							break;
						case 4:
						    $icon = $styles_dir . 'green.png';
							break;
						case 5: // 5 = lowest priority
						    $icon = $styles_dir . 'blue.png';
							break;
						default: // unassigned
   						    $icon = $styles_dir . 'grey.png';
							break;
					}
					echo ("<img src='$icon' />&nbsp;&nbsp;");
					echo ("[" . $tobj->GetProp ('status') . "] <a href='../ticket/" . $tobj->oid . "'>" . $tobj->GetProp ('name') . "</a>");
				?>
                </p>
            </div>
<?php
		}
	}

	render ();
?>