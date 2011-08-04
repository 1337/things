<?php
    require_once ('.things.php');
    // require_once (PROOT . 'models/things.php');
    class Tickets extends Things {
        /* functions available as of 2011-06-16
        function GetType () {}
        function SetType ($type) {}
        function GetObjects ($refresh = false) {}
        function GetObjectsTypes () {}
        function AddObjects ($which) {}
        function SetObjectsRaw ($ids) {}
        function SetObjects ($query_more = 'ORDER BY `type` ASC') {}
        function DelObjects ($which) {}
        function DelAllObjects () {}
        function DelObjectsProps ($property_names) {}
        function SetObjectsProps ($properties) {}
        function FindObject ($name) {)
        function FilterByProp ($prop, $propval) {}
        function FilterByPreg ($prop, $preg) {} 
        function PregReplace ($prop, $preg, $replacement) {} */
        function __construct () {
            // override the parent construct and load Tickets automatically
            parent::__construct (TICKET);
        }
        function Tickets () {
            $this->__construct (); // PHP 4
        }
    }
    $a = new Tickets ();
?>