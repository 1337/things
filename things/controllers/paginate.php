<?php
    /*  Universal List Paginator V1.00 (CC 3.0, MIT) 2011 Brian Lai
    
        Calculates the min/max/current/start/other things for you, so you
        can easily put up a pagable list on any html.
        
        Example:
        say $array contains a list
        
        $a = new Paginate (array (
            'objects' => $array,
            'page_size' => 10
        ));
        
        echo ($a->Bar ()); // throw the bar out
        var_dump ($a->GetObjects ()); // get active objects
        
    */    
    
    class Paginate {
        // houses the filtered objects.
        private $objects, $start, $page_size, $control_suffix;
        
        // traps << < 1 2 3 ... 8 9 10 > >>
        function __construct ($options = array ()) {
            /*  $options: array
                    'control_suffix' => use a unique ID if you plan to use this class more than once on a page
                    'page_size' => number of entries per page, defaults to 10.
                    'start' => the item index from which the list will start
                    'objects' => the array of objects that will be paged.
                                 note that this class will not display the list for you.
                    
            */
            $defaults = array (
                'objects' => array (),
                'start' => 0,
                'page_size' => 10,
                'control_suffix' => ''
            );
            $options = array_merge ($defaults, $options); // if setting is not present, use defaults
            $this->control_suffix = $options['control_suffix'];
            $this->start = $options['start'];
            if (isset ($_GET['start' . $this->control_suffix])) {
                $this->start = $_GET['start' . $this->control_suffix]; // automatically assign
            }
            $this->page_size  = $options['page_size'];
            if (isset ($_GET['size' . $this->control_suffix])) {
                $this->page_size = $_GET['size' . $this->control_suffix]; // automatically assign
            }
            $this->objects = $options['objects'];
        }
        
        function Paginate ($options = array ()) {
            $this->__construct ($options);
        }
        
        function GetObjects () {
            // chop the stuff up and serve!
            return array_slice ($this->objects, $this->start, $this->page_size);
        }
        
        function Bar () {
            // generate the << < 1 2 3 ... 8 9 10 > >> thing.
            // get number of pages needed under this setup.
            $buffer = '<div class="paginate_bar">';
            $num_pages = ceil (sizeof ($this->objects) / $this->page_size);
            $links = array ();
            $link_prefix = $_SERVER['SCRIPT_NAME'] . '?start' . $this->control_suffix . '=';
            
            if ($num_pages > 6) { // no point showing if all pages are visible
                $links['<<'] = $link_prefix . '0';
                $links['<']  = $link_prefix . max (0, $this->start - $this->page_size);
            }
            
            for ($i = 0; $i < $num_pages; $i++) {
                if ($i < 3 || $i > $num_pages - 4) {
                    // index + 1 because humans read pages from page 1, not 0
                    $links[$i+1] = $link_prefix . $i * $this->page_size;
                } else {
                    $links['coke_block'] = "...";
                }
            }
            
            if ($num_pages > 6) { // no point showing these if all pages are visible
                $links['>'] = $link_prefix . min (sizeof ($this->objects) - $this->page_size, $this->start + $this->page_size);
                $links['>>'] = $link_prefix . (sizeof ($this->objects) - $this->page_size);
            }
            
            foreach ($links as $text => $link) {
                if ($text == 'coke_block') {
                    $buffer .= '&nbsp;&nbsp;...&nbsp;&nbsp;';    
                } else {
                    $buffer .= sprintf (
                        "<a href='%s' class='paginate_button " . $this->control_suffix . "'>%s</a> ", 
                        htmlspecialchars ($link), 
                        htmlspecialchars ($text)
                    );
                }
            }
			$buffer .= "</div>";
			
            return $buffer;
        }
    }
?>