<?php if (isset ($title)) { println ($title, 1); } ?>
<p><a href="<!--root-->new/ticket">New Task</a> &nbsp; | &nbsp; Show:
   <a href="<!--root-->tickets?showall">all</a> /
   <a href="<!--root-->tickets">active</a></p>
<?php if (sizeof ($ticket_ids) > 0) { ?>
    <ul class='tickets'>
    <?php foreach ($ticket_ids as $tid) {
        // $tobj = new Ticket ($tid);
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
        if (sizeof ($tobj->GetParents (TICKET)) == 0) { 
            // if this is a "grandparent" or "married" ticket
            // doing this because I don't want a bigass list of tasks that are actually subtasks
            $icon = $tobj->GetIcon ();
    ?>
            <li class="ticket">
                <p class='task'>
                    <img src='<?php echo ($icon); ?>' />&nbsp;
                    <?php
                        echo ("<a href='<!--root-->ticket/" . $tobj->oid . "'>" . htmlspecialchars ($tobj->GetProp ('name')) .
                            "</a> (" . $tobj->GetProp ('status') . ")");
                        // list subtasks (provided that there are subtasks
                        if (sizeof ($tobj->GetChildren (TICKET)) > 0) {
                            echo ("<ul class='subtasks'>");
                            foreach ((array) $tobj->GetChildren (TICKET) as $child_ticket_oid) {
                                // $child_ticket = new Ticket ($child_ticket_oid);
                                $child_ticket = new Ticket ($child_ticket_oid);
                                $icon = $child_ticket->GetIcon ();
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
    }
?>
</ul>