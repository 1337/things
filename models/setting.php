<?php
    require_once (PROOT . 'models/thing.php');
 
    class Setting extends Thing {
        // the Settings class attempts to see if objects' parents have required settings.
        // for example, if a group has a setting, then all users in the group will
        // also have the setting.
        function GetSetting ($name, $inherit = false, $default = null) {
            /*
            retrieve property for a given object.
         
            */
         
            // oh wait, properties are not objects
        }
    } 
?>