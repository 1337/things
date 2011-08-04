<?php
    require_once ('.things.php');

    $styles_dir = WEBROOT . 'styles/images/';
    $new = $gp->Get('new');
    $edit = $gp->Get('edit');
    $id = $gp->Get('id');
    // $ownstr = $gp->Has ('owners') ? $gp->Get ('owners') : '';
 
    // CheckAuth (); // require a login. --> $user is available to you.
 
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
        $is_admin = CheckAuth ("Administrative privilege", null, null, false); // load $user
        if (!isset ($user) ||
            !in_array ($id, $user->GetChildren (TICKET))) {
             // if not logged in or user does not own the ticket, view only
            include (PROOT . 'views/ticket-view.php');
        } elseif (in_array ($id, $user->GetChildren (TICKET)) || 
            $is_admin) { // owner or admin
            include (PROOT . 'views/ticket-edit.php');
        }
    } else {
        println ("Object #$id is not a ticket.", $fail);
    }

    render (array ('title' => "Ticket #$id"));
?>
