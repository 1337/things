<?php
    if (count(get_included_files()) <= 1) {
        // this file must be called by post-ui.php.
        // it uses variables only available from post-ui.php.
        die ("Wrong");
    }
	
	$tobj = new Ticket ($id);
?>
    <div class="ticket">
        <h2><?php echo ($tobj->GetProp ('name')); ?></h2>
        <?php
            echo ("<p><b>See also:</b></p><ul>");
            foreach ((array) $tobj->GetParents (TICKET) as $parent_oid) {
                $parent = new Ticket ($parent_oid);
                echo ('<li><a href="<!--root-->ticket/' . $parent->oid . '">' . $parent->GetProp ('name') . '</a></li>');
            }
            foreach ((array) $tobj->GetChildren (TICKET) as $child_oid) {
                $child = new Ticket ($child_oid);
                echo ('<li><a href="<!--root-->ticket/' . $child->oid . '">' . $child->GetProp ('name') . '</a></li>');
            }
            echo ("</ul><hr />");
        ?>
        <p><b>Priority</b>: <?php echo ($ticket_priorities[$tobj->GetProp ('priority')]); ?></p>
        <p><b>Status</b>: <?php echo ($ticket_statuses[$tobj->GetProp ('status')]); ?></p>
        <p><b>Time spent / needed</b>: <?php echo ($tobj->GetProp ('time_needed')); ?> hours</p>
        
        <hr />
        <h3>Description</h3>
        <div><?php echo (nl2br (htmlspecialchars ($tobj->GetProp ('description')))); ?></div>
        
        <hr />
        <h3>Requirements</h3>
        <div><?php echo (nl2br (htmlspecialchars ($tobj->GetProp ('requirements')))); ?></div>
        <p><b>Requirements signoff</b>: <?php echo ($tobj->GetProp ('requirements_signoff')); ?></p>
        
        <hr />
        <h3>Design / Test plan</h3>
        <div><?php echo (nl2br (htmlspecialchars ($tobj->GetProp ('designs')))); ?></div>    
        <p><b>Designs signoff</b>: <?php echo ($tobj->GetProp ('designs_signoff')); ?></p>
        
        <hr />
        <h3>Files changed</h3>
        <div><?php echo (nl2br (htmlspecialchars ($tobj->GetProp ('files_changed')))); ?></div>
    </div>
