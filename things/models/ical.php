<?php
    /*  iCal parser classes V1.01 (CC 3.0, MIT) 2011 Brian Lai
        
        Example usage:
            $a = new iCal ();
            $a->addEvent ("title", "description", time (), time ());
            die ($a->toString ());

        Headers:
            header ('Content-type: text/calendar; charset=windows-1252');
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=cal.ics");
    */
    
    class iCalComponent {
        // event, to-do, journal, or...
        // see "Objects" in http://goo.gl/klqvs (as per RFC 2445 p.9)
        
        public $props = array ();
        
        public function __construct ($type = "VCALENDAR") {
            // set the type if you call it with one.
            $this->setType ($type);
            
            // global defaults
            // $this->props["UID"] = substr (md5 (rand ()),0,8) . "@autonomous.com"; // no actual use
            $this->props["sequence"] = "0"; // how many times this event has been modified
            $this->props["UID"] = md5 (time () . rand ()) . '@ohai.ca'; // remove this if you plan to do any serious work
        }
        
        public function setType ($type) {
            // allow only defined component values
            if (in_array ($type, 
                    array ("VCALENDAR", "VEVENT", "VTODO", "VJOURNAL", 
                           "VFREEBUSY", "VTIMEZONE", "VALARM", "/X-(.)+/i", "iana-token"
                ))) {
                $this->props['type'] = $type;
            } else {
                die ("Type '" . $type . "' is not allowed in specification");
            }
        }
        
        public function addProperty ($name, $value) {
            // add a property for this iCalComponent.
            // there is no removal.
			
			$value = str_replace ("\n", "\\n", $value); // replace newlines with \n, then a new line, then a space in front
			$value = chunk_split ($value, 65, "\r\n "); // chunk no more than 75 chars per line
            $this->props[strtoupper ($name)] = $value;
        }
        
        public function addProperties ($props) {
            // same effect as addProperty. supply an array of 
            //     ($name => $value, $name => $value).
            if (sizeof ($props) > 0) {
                foreach ($props as $name => $value) {
                    $this->addProperty ($name, $value);
                }
            }
        }
        
        public function hasProperty ($name) {
            return in_array ($what, $this->props);
        }
        
        
        
        
        
        
        /*  === peripheral property helpers ===
            they don't all apply to subclasses, but here they are.
            function names should be easy enough to read. */
        
        public function setAllDay ($is_it_all_day) {
            if ($is_it_all_day) { // if you gave it true
                $this->addProperties (array (
                    "X-FUNAMBOL-ALLDAY" => "TRUE",
                    "X-MICROSOFT-CDO-ALLDAYEVENT" => "TRUE"
                ));
            } else { // if you gave it false
                unset ($this->props["X-FUNAMBOL-ALLDAY"]);
                unset ($this->props["X-MICROSOFT-CDO-ALLDAYEVENT"]);
            }
        }

        public function setDescription ($what) {
            $this->addProperty ("DESCRIPTION", $what);
        }
        
        public function setTime ($start, $end = null) {
            // give one or two PHP mktime ()s
            // if $end is left null, it will be the same as $start
            // times are added as local time.
            
            if (is_null ($end)) {
                $end = $start;
            }
            
            $this->addProperties (array (
                "DTSTART" => $this->makeIcalTime ($start), // DTSTART = starting time
                "DTSTAMP" => $this->makeIcalTime ($start - 1), // "I created this event one second before it starts"
                "DTEND" => $this->makeIcalTime ($end) // DTEND = ending time
            ));
            
            // make sure all day flags are still correctly set
            // that would be if the event starts 00:00:00 on one day
            // and ends 00:00:00 on the next.
            $time_ss = $this->splitTime ($start);
            $time_se = $this->splitTime ($end);
            if ($time_ss['hour'] == 0 && 
                $time_ss['minute'] == 0 && 
                $time_ss['second'] == 0 && 
                $time_se['hour'] == 0 && 
                $time_se['minute'] == 0 && 
                $time_se['second'] == 0 && 
                (
                    // see if day is more ahead
                    $time_se['day'] > $time_ss['day'] || 
                    $time_se['month'] > $time_ss['month'] || 
                    $time_se['year'] > $time_ss['year']
                )) {
                $this->setAllDay (true);
            } else {
                $this->setAllDay (false);
            }
        }
        
        public function setTitle ($what) {
            $this->addProperty ("SUMMARY", $what);
        }
        
        public function setOwner ($whom) {
            $this->addProperty ("ORGANIZER", $whom);
        }
        
        public function setStatus ($what) {
            // if $what is integer, the indexed status is used (not recommended)
            // if $what is a string, the string will be used as status, BUT
            //     only if the string is one of the allowed values
            
            
            // can be one of the following
            $allowed_statuses = array ("TENTATIVE", "CONFIRMED", "CANCELLED", 
                "NEEDS-ACTION", "COMPLETED", "IN-PROCESS", "DRAFT", "FINAL");
            if (is_int ($what)) {
                $this->addProperty ("STATUS", $allowed_statuses[$what]);
            } elseif (in_array ($what, $allowed_statuses)) {
                $this->addProperty ("STATUS", $what);
            }
        }
        
        public function setAlarm ($text = '', $days = 0, $hours = 0, $minutes = 0, $seconds = 0) {
            // set an alarm for this event - so many days/hours/minutes/seconds in advance.
            // reminder text will be $text.
            // actually creates a VALARM object as a child of the current object.
            $alarm = new iCalComponent ("VAlARM");
            $alarm->addProperties (array (
                'ACTION' => 'DISPLAY',
                'DESCRIPTION' => $text,
                'TRIGGER' => "-P$daysDT$hoursH$minutesM$secondsS"
            ));
            $this->addChild ($alarm);
        }
        
        public function setRecurrence () {
            // "I'll leave it to you as a take-home exercise" - Robert J. Le Roy
        }
        
        /*  === END peripheral property helpers === */
        
        
        
        
        
        
        public function addChild ($child) {
            // add an iCalComponent within this one. example would be
            // adding a VEVENT iCalComponent within a VCALENDAR iCalComponent.
            // child can be both an iCalComponent or a subclass of it.
            // there is no removal.
            if (get_class ($child) == "iCalComponent" || 
                is_subclass_of ($child, "iCalComponent")) {
                $this->props['children'][] = $child;
            } else {
                die ("Child added is not an iCalComponent");
            }
        }
        
        public function addChildren ($children) {
            // same effect as addChild. supply an array of 
            //     ($child, $child, $child).
            if (sizeof ($children) > 0) {
                foreach ($children as $child) {
                    $this->addChild ($child);
                }
            }
        }
        
        public function toString () {
            // returns a string representation of the iCalComponent.
            
            // construct the object.
            $buffer = "BEGIN:" . $this->props['type'];
            
            // export properties of this object (upper case ones only)
            if (sizeof ($this->props) > 0) {
                foreach ($this->props as $key => $value) {
                    if (strtoupper ($key) == $key) {
                        $buffer .= "\r\n" . $key . ":" . $value;
                    }
                }
            }
            
            // export children.
            if (sizeof ($this->props['children']) > 0) {
                foreach ($this->props['children'] as $child) {
                    // BEGIN: line does not have \r\n, so add it for the child
                    $buffer .= "\r\n" . $child->toString ();
                }
            }
            
            // end the object
            $buffer .= "\r\nEND:" . $this->props['type'];
            return $buffer;
        }
        
        private function splitTime ($time) {
            // given $time (made by time ()), return an array of it
            return array (
                "day" => str_pad (date ("j", $time), 2, "0", STR_PAD_LEFT),
                "month" => str_pad (date ("n", $time), 2, "0", STR_PAD_LEFT),
                "year" => str_pad (date ("Y", $time), 4, "0", STR_PAD_LEFT),
                "hour" => str_pad (date ("H", $time), 2, "0", STR_PAD_LEFT),
                "minute" => str_pad (date ("i", $time), 2, "0", STR_PAD_LEFT),
                "second" => str_pad (date ("s", $time), 2, "0", STR_PAD_LEFT)
            );
         }
         
         public function makeIcalTime ($time) {
             // create an iCal time (i.e. "20110713T185610Z" based on a given time.
             $tz = $this->splitTime ($time);
			 // return ($tz['year'] . $tz['month'] . $tz['day'] . 'T' . $tz['hour'] . $tz['minute'] . $tz['second'] . 'Z');
			 return ($tz['year'] . $tz['month'] . $tz['day'] . 'T' . $tz['hour'] . $tz['minute'] . $tz['second']);
         }
    }
    
    class iCal extends iCalComponent {
        // so, VCALENDAR.
        
        function __construct ($props = null) {
            // your object declaration has changed.
            // supply optional properties here instead of the object type.
			
			$this->props['VERSION'] = '2.0'; // "The VERSION property should be the first property on the calendar"
                    
            parent::__construct ();
            
            if (!is_array ($props)) {
                $props = array (); // null needs to become array for later code
            }
            
            // build properties array
            $this->props = array_merge (
                array_change_key_case ($props, CASE_UPPER), 
                $this->props, 
                array (
                    // required defaults
                    'PRODID' =>  '-//Google Inc//Google Calendar 70.9054//EN', // of course
					'X-PUBLISHED-TTL' => '1', // update interval, in some kind of format
                    'CALSCALE' => 'GREGORIAN'/*,
                    'METHOD' => 'PUBLISH',
                    'X-WR-CALNAME' => 'Brians iCal Generator',
                    'CREATED' => $this->makeIcalTime (time () - 1),
                    'LAST-MODIFIED' => $this->makeIcalTime (time () - 1) */
                )
            );
            
            // time zone is not required, is it?
            /*$timezone = new iCalComponent ("VTIMEZONE");
            $timezone->addProperties (array (
                'TZID' => date_default_timezone_get (),
                'X-LIC-LOCATION' => date_default_timezone_get ()
            ));
            $this->addChild ($timezone);*/
        }
        
        function addEvent ($title, $description, $start_time, $end_time = null) {
            // making my life easier
            if (class_exists ("iCalEvent")) {
                $b = new iCalEvent ();
                $b->setTitle ($title);
                $b->setDescription ($description);
                $b->setTime ($start_time, $end_time);
                
                $this->addChild ($b);
            } else {
                die ("Cannot find iCalEvent class");
            }
        }
    }
    
    class iCalEvent extends iCalComponent {
        // so, VEVENT.
        
        function __construct ($props = null) {
            // your object declaration has changed.
            // supply optional properties here instead of the object type.
            parent::__construct ("VEVENT");
            
            if (!is_array ($props)) {
                $props = array (); // null needs to become array for later code
            }
            
            // build properties array
            $this->props = array_merge (
                array_change_key_case ($props, CASE_UPPER), 
                $this->props,
                array (
                    // required defaults
                    'STATUS' => 'CONFIRMED',
                    'SEQUENCE' => '0'/*,
                    'CREATED' =>  $this->makeIcalTime (time () - 1), // "it was created a second ago"
                    'TRANSP' => 'OPAQUE',
                    'CLASS' => 'PRIVATE'*/
                )
            );
        }
    }
    
    class iCalTodo extends iCalComponent {
        // so, VTODO.
        
        function __construct ($props = null) {
            // your object declaration has changed.
            // supply optional properties here instead of the object type.
            parent::__construct ("VTODO");
            
            if (!is_array ($props)) {
                $props = array (); // null needs to become array for later code
            }
            
            // build properties array
            $this->props = array_merge (
                array_change_key_case ($props, CASE_UPPER), 
                $this->props
            );
        }
    }
    
    class iCalJournal extends iCalComponent {
        // so, VJOURNAL.
        
        function __construct ($props = null) {
            // your object declaration has changed.
            // supply optional properties here instead of the object type.
            parent::__construct ("VJOURNAL");
            
            if (!is_array ($props)) {
                $props = array (); // null needs to become array for later code
            }
            
            // build properties array
            $this->props = array_merge (
                array_change_key_case ($props, CASE_UPPER), 
                $this->props
            );
        }
    }
?>
