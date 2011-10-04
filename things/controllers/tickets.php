<?php
    require_once ('.things.php');
    require_once (PROOT . 'models/ticket.php');
	
	CheckAuth (); // require a login. --> $user is available to you.

    if ($gp->Has ('showall')) {
        $title = "All your tasks";
    } else {
        $title = "Your open tasks";
    }

    $ticket_ids = $user->GetChildren (TICKET, "`child_oid` DESC");
    $kal = new Things ();
    $ticket_ids = array_reverse ($kal->Sort ($ticket_ids, 'status'));
    if (!$gp->Has ('showall')) {
        $kal->SetObjectsRaw ($ticket_ids);
        $kal->FilterByPreg ('status', '/[^Closed]/'); // closed. hide closed ones
        $ticket_ids = $kal->GetObjects ();
    }

    require_once (PROOT . 'views/tickets.php');
    render (array ('title' => $title));
?>
