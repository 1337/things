<?php
    require_once ('.things.php');
    CheckAuth (); // require a login. --> $user is available to you.

    $styles_dir = WEBROOT . 'styles/images/';
?>
    <p><a href="<?php echo (WEBROOT); ?>new/ticket">New Ticket</a></p>
<?
    println ("Tickets assigned to you", 1);

    $ticket_ids = $user->GetChildren (TICKET, "`child_oid` DESC");
    
    if (sizeof ($ticket_ids) > 0) {
        foreach ($ticket_ids as $tid) {
            $tobj = new Ticket ($tid);
			// setting ticket defaults
			if ($tobj->GetProp ('status') == null) {
				$tobj->SetProp ('status', 0);
			}
			// setting ticket defaults
			if ($tobj->GetProp ('name') == null) {
				$tobj->SetProp ('name', 'Untitled ticket');
			}

            $tp = $tobj->GetProps ();
			if (sizeof ($tobj->GetParents (TICKET)) == 0) { // if this is a "grandparent" or "married" ticket
			    // doing this because I don't want a bigass list of tasks that are actually subtasks
?>
                <div class="ticket">
                    <p class='task'><?php 
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
                        
                        echo ("<a href='../ticket/" . $tobj->oid . "'>" . htmlspecialchars ($tobj->GetProp ('name')) . 
						    "</a> (" . $ticket_statuses[$tobj->GetProp ('status')] . ")");
						/* echo ("<a href='../ticket/" . $tobj->oid . "'>" . $tobj->GetProp ('name') . "</a>");
						$a = new AjaxField ($tobj->oid);
						$a->NewDropdownField (array (
							'prop' => 'status',
							'friendlyname' => NULL, 
							'readonly' => false, 
							'style' => '', 
							'choices' => $ticket_statuses,
							'items' => 1
						));*/
						
						// list subtasks (provided that there are subtasks
						if (sizeof ($tobj->GetChildren (TICKET)) > 0) {
							echo ("<ul class='subtasks'>");
							foreach ((array) $tobj->GetChildren (TICKET) as $child_ticket_oid) {
								$child_ticket = new Ticket ($child_ticket_oid);
								// defaults have already been set when they were Tobjs
								echo ("<li><a href='../ticket/" . $child_ticket->oid . "'>" . htmlspecialchars ($child_ticket->GetProp ('name')) . "</a>
								 (" . $ticket_statuses[$child_ticket->GetProp ('status')] . ")</li>");
							}
							echo ("</ul>");
						}
                    ?>
                    </p>
                </div>
<?php
			}
        }
    }

    render ();
?>