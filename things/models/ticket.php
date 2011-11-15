<?php
    require_once (PROOT . 'models/thing.php');
 
    class Ticket extends Thing {
		public $ticket_priorities = array ( // can't define a constant because this is not scalar
			1 => 'Highest priority',
			2 => 'High priority',
			3 => 'No Priority',
			4 => 'Low priority', 
			5 => 'Lowest priority'
		);
	 
		public $ticket_statuses = array (
			'In Progress',
			'Waiting for QA',
			'Closed',
			'Will not fix',
			'Open'
		);
		
		function GetIcon () {
			$styles_dir = WEBROOT . 'styles/images/';
			switch (array_value_key ($this->ticket_statuses, $this->GetProp ('status'))) {
				case 0: // in progress
					$icon = $styles_dir . 'orange.png';
					break;
				case 1: // Waiting for QA
					$icon = $styles_dir . 'yellow.png';
					break;
				case 2: // Closed
					$icon = $styles_dir . 'green.png';
					break;
				case 3: // Will not fix
					$icon = $styles_dir . 'blue.png';
					break;
				case 4: // open
					$icon = $styles_dir . 'red.png';
					break;
				default: // unassigned
					   $icon = $styles_dir . 'red.png';
					break;
			}
			return $icon;
		}
    }
?>