<?php
    require_once (PROOT . 'models/thing.php');
    
	$ticket_priorities = array ( // can't define a constant because this is not scalar
		1 => 'Highest priority',
		2 => 'High priority',
		3 => 'No Priority',
		4 => 'Low priority', 
		5 => 'Lowest priority'
	);
	
	$ticket_statuses = array (
		'Open',
		'Closed',
		'Will not fix',
		'In Progress'
	);
	
    class Ticket extends Thing {
        function SetSubtasks ($ticket_id) {
            // the old ticket would have a new child ticket,
            // suggesting "please see me".
        
            $old_ticket = new Ticket ($ticket_id);
            $old_ticket->SetChildren (array ($this->oid));
        }
		
		function GetSubtasks () {
			return $this->GetChildren (TICKET);
		}
    }
?>