<?php
    require_once (PROOT . 'models/thing.php');
    
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