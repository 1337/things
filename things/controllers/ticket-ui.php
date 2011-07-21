<?php
    require_once ('.things.php');
    CheckAuth (); // require a login. --> $user is available to you.
?>
    <p><a href="../tickets">Back to all tickets</a></p>
<?php
    $styles_dir = WEBROOT . 'styles/images/';
	$tid = $gp->Get('id');
	
    if (GetObjectType ($tid) == TICKET) {
		$a = new AjaxField ($tid);
		$tobj = new Ticket ($tid);
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
				
				$a->NewDropdownField (array (
					'prop' => 'priority',
					'friendlyname' => 'Priority (1~5)', 
					'readonly' => false, 
					'style' => '', 
					'choices' => array (
						1 => '1: highest priority',
						2 => '2: high priority',
						3 => '3: normal',
						4 => '4: low priority', 
						5 => '5: lowest priority'
					),
					'items' => 1
				));
				$a->NewDropdownField (array (
					'prop' => 'status',
					'friendlyname' => 'Status', 
					'readonly' => false, 
					'style' => '', 
					'choices' => array (
						'Open',
						'Closed',
						'Won\'t fix',
						'In Progress'
					),
					'items' => 1
				));
				echo ("<br />");
				$a->NewTextAreaField ('description', 'Description', false, 'width: 78%; height: 120px;');
				echo ("<br />");
				$a->NewTextField ('time_needed', 'Time needed', false, '', 'number');
				echo (" hours <br />");
				
				echo ("<br />");
				
			?>                
		</div>
<?php
	} else {
		println ("Object #$tid is not a ticket.", $fail);
	}

	render (array (
	    'title' => "Ticket #$tid"
	));
?>