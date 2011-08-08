<?php
    require_once ('.things.php');
    CheckAuth (); // require a login. --> $user is available to you.

    function GetIcon ($tobj) {
        global $ticket_statuses;
        /*switch ($tobj->GetProp ('priority')) {
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
        }*/
        $styles_dir = WEBROOT . 'styles/images/';
        switch (array_value_key ($ticket_statuses, $tobj->GetProp ('status'))) {
            case 1: // 1 = closed
                $icon = $styles_dir . 'green.png';
                break;
            case 2: // 2 = won't fix
                $icon = $styles_dir . 'blue.png';
                break;
            case 3: // 3 = in progress
                $icon = $styles_dir . 'yellow.png';
                break;
            default: // unassigned or 0 = open
                   $icon = $styles_dir . 'red.png';
                break;
        }
        return $icon;
    }

    if ($gp->Has ('showall')) {
        println ("All your tasks", 1);
    } else {
        println ("Your open tasks", 1);
    }
?>
    <p><a href="<!--root-->new/ticket">New Task</a> &nbsp; | &nbsp; Show:
       <a href="<!--root-->tickets?showall">all</a> /
       <a href="<!--root-->tickets">active</a></p>
<?
    $ticket_ids = $user->GetChildren (TICKET, "`child_oid` DESC");
    $kal = new Things ();
    $ticket_ids = array_reverse ($kal->Sort ($ticket_ids, 'status'));
    if (!$gp->Has ('showall')) {
        $kal->SetObjectsRaw ($ticket_ids);
        $kal->FilterByPreg ('status', '/[^Closed]/'); // closed. hide closed ones
        $ticket_ids = $kal->GetObjects ();
    }
    if (sizeof ($ticket_ids) > 0) {
        ?><ul class='tickets'><?php
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
                <li class="ticket">
                    <p class='task'><?php
                        $icon = GetIcon ($tobj);
                        echo ("<img src='$icon' />&nbsp;&nbsp;");
                        echo ("<a href='<!--root-->ticket/" . $tobj->oid . "'>" . htmlspecialchars ($tobj->GetProp ('name')) .
                            "</a> (" . $tobj->GetProp ('status') . ")");
                        // list subtasks (provided that there are subtasks
                        if (sizeof ($tobj->GetChildren (TICKET)) > 0) {
                            echo ("<ul class='subtasks'>");
                            foreach ((array) $tobj->GetChildren (TICKET) as $child_ticket_oid) {
                                $child_ticket = new Ticket ($child_ticket_oid);
                                $icon = GetIcon ($child_ticket);
                                // defaults have already been set when they were Tobjs
                                echo ("<li><img src='$icon' />&nbsp;&nbsp;<a href='<!--root-->ticket/" .
                                       $child_ticket->oid . "'>" . htmlspecialchars ($child_ticket->GetProp ('name')) . "</a>
                                       (" . $child_ticket->GetProp ('status') . ")</li>");
                            }
                            echo ("</ul>");
                        }
                    ?>
                    </p>
                </li>
<?php
            }
        }
        echo ("</ul>");
    }

    render (array (
        'title' => 'Tasks'
    ));
?>
