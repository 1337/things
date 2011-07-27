<?php
    require_once ('.things.php');

    $styles_dir = WEBROOT . 'styles/images/';
    $new = $gp->Get('new');
    $edit = $gp->Get('edit');
    $id = $gp->Get('id');
	$ownstr = $gp->Has ('owners') ? $gp->Get ('owners') : '';
    
	CheckAuth (); // require a login. --> $user is available to you.
	
    if ($new == 1 && !$id) { // new ticket, no ID specified
        $ticket = new Ticket (NEW_TICKET);
		$nid = $ticket->oid;
		$ticket->SetParents (array ($user->oid));
		$ticket->SetProps (array (
		    'name' => "Untitled ticket",
			'status' => 0
		));
		// created the ticket, now go to it
		header ("location: " . WEBROOT . "ticket/" . $nid);
	} elseif ($new == 1 && $id) { // new ticket, ID specified
	    // create a new ticket as a child for the specified ID
		$ticket = new Ticket (NEW_TICKET);
		$nid = $ticket->oid;
		$ticket->SetParents (array ($user->oid, $id)); // "parent ticket"
		$ticket->SetProps (array (
		    'name' => "Untitled ticket",
			'status' => 0
		));
		// created the ticket, now go to it
		header ("location: " . WEBROOT . "ticket/" . $nid);
    } elseif ($gp->Has ('delete') && $gp->Get ('delete') == 1) { // delete
        $ticket = new Ticket ($id);
        $ticket->Destroy ();
        header ("location: " . WEBROOT . "tickets");
    } elseif (GetObjectType ($id) == TICKET) {
		echo ("
		    <p><a href='../tickets'>Back to all tickets</a>&nbsp;&nbsp;|&nbsp;&nbsp;
			    <a href='" . WEBROOT . "new/ticket/" . $id . "'>New &quot;See also&quot; ticket</a>&nbsp;&nbsp;|&nbsp;&nbsp;
			    <a href='" . WEBROOT . "delete/ticket/" . $id . "'>Delete ticket</a></p>
		");

        $a = new AjaxField ($id);
        $tobj = new Ticket ($id);
?>
        <div class="ticket">
            <h2><?php 
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
                    // echo ($tobj->GetProp ('name')); 
                    $a->NewTextField ('name', null, false, 'font-size:20px;border:0;width: 90%;', 'text');
            ?></h2>
            <?php
                echo ("<p><b>See also:</b></p><ul>");
                foreach ((array) $a->GetParents (TICKET) as $parent_oid) {
					$parent = new Ticket ($parent_oid);
					echo ('<li><a href="../ticket/' . $parent->oid . '">' . $parent->GetProp ('name') . '</a></li>');
				}
                foreach ((array) $a->GetChildren (TICKET) as $child_oid) {
					$child = new Ticket ($child_oid);
					echo ('<li><a href="../ticket/' . $child->oid . '">' . $child->GetProp ('name') . '</a></li>');
				}
                echo ("</ul><hr />");
				
                $a->NewDropdownField (array (
                    'prop' => 'priority',
                    'friendlyname' => 'Priority (1~5)', 
                    'readonly' => false, 
                    'style' => '', 
                    'choices' => $ticket_priorities,
                    'items' => 1
                ));
                $a->NewDropdownField (array (
                    'prop' => 'status',
                    'friendlyname' => 'Status', 
                    'readonly' => false, 
                    'style' => '', 
                    'choices' => $ticket_statuses,
                    'items' => 1
                ));
                echo ("<br />");
                $a->NewTextAreaField ('description', 'Description', false, 'width: 78%; height: 120px;');
                echo ("<br /><hr />");
				
				$a->NewTextAreaField ('requirements', 'Requirements', false, 'width: 78%; height: 120px;');
                echo ("<br />");
				$a->NewTextField ('requirements_signoff', 'Sign-off', false, 'width: 78%');
                echo ("<br />");
				
				$a->NewTextAreaField ('designs', 'Design/Tests', false, 'width: 78%; height: 120px;');
                echo ("<br />");
				$a->NewTextField ('designs_signoff', 'Sign-off', false, 'width: 78%');
                echo ("<br /><hr />");

                $a->NewTextAreaField ('files_changed', 'Files changed', false, 'width: 78%; height: 120px;');
                echo ("<br />");
				
                $a->NewTextField ('time_needed', 'Time tracker', false, '', 'number');
                echo (" hours");
            ?>      
        </div>
<?php
    } else {
        println ("Object #$id is not a ticket.", $fail);
    }

    render (array ('title' => "Ticket #$id"));
?>