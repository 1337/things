<?php
    if (count(get_included_files()) <= 1) {
        // this file must be called by post-ui.php.
        // it uses variables only available from post-ui.php.
        die ("Wrong");
    }
 
    $tobj = new Ticket ($id);
?>
    <div class="ticket">
        <h1><?php echo ($tobj->GetProp ('name')); ?></h1>
        <?php
            $tp = $tobj->GetParents (TICKET);
            $tc = $tobj->GetChildren (TICKET);
            if (sizeof ($tp) > 0 || sizeof ($tc) > 0) {
                echo ("<p><b>See also:</b></p><ul>");
                foreach ((array) $tp as $parent_oid) {
                    $parent = new Ticket ($parent_oid);
                    echo ('<li><a href="<!--root-->ticket/' . $parent->oid . '">' . $parent->GetProp ('name') . '</a></li>');
                }
                foreach ((array) $tc as $child_oid) {
                    $child = new Ticket ($child_oid);
                    echo ('<li><a href="<!--root-->ticket/' . $child->oid . '">' . $child->GetProp ('name') . '</a></li>');
                }
                echo ("</ul><hr />");
            }
        ?>
        <p class="col2"><b>Priority</b>: <?php echo ($ticket_priorities[$tobj->GetProp ('priority')]); ?></p>
        <p class="col2"><b>Status</b>: <?php echo ($ticket_statuses[$tobj->GetProp ('status')]); ?></p>
        <p><b>Time spent / needed</b>: <?php echo ($tobj->GetProp ('time_needed')); ?> hours</p>
     
        <?php if (strlen ($tobj->GetProp ('description')) > 0) { ?>
            <hr />
            <h3>Description</h3>
            <div><?php echo (nl2br (htmlspecialchars ($tobj->GetProp ('description')))); ?></div>        
        <?php } ?>
     
        <?php if (strlen ($tobj->GetProp ('requirements')) > 0) { ?>
            <hr />
            <h3>Requirements</h3>
            <p><b>Requirements sign-off</b>: <?php echo ($tobj->GetProp ('requirements_signoff')); ?></p>
            <div><?php echo (nl2br (htmlspecialchars ($tobj->GetProp ('requirements')))); ?></div>
        <?php } ?>
     
        <?php if (strlen ($tobj->GetProp ('designs')) > 0) { ?>
            <hr />
            <h3>Design / Test plan</h3>
            <p><b>Designs sign-off</b>: <?php echo ($tobj->GetProp ('designs_signoff')); ?></p>
            <div><?php echo (nl2br (htmlspecialchars ($tobj->GetProp ('designs')))); ?></div> 
        <?php } ?>
     
        <?php if (strlen ($tobj->GetProp ('files_changed')) > 0) { ?>
            <hr />
            <h3>Files changed</h3>
            <div><?php echo (nl2br (htmlspecialchars ($tobj->GetProp ('files_changed')))); ?></div>
        <?php } ?>
    </div>
