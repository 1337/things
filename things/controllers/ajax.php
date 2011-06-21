<?php
    /*
       input:  REST
               ajax.php?oid=[obj_id]&prop=[prop_name]
               ajax.php?oid=[obj_id]&prop=[prop_name]&val=[value]
               ajax.php?cmd=[command] (planned)
       if you have .htaccess, you can do
               /get/[obj_id]/[prop_name] --> value
               /set/[obj_id]/[prop_name]/value --> null
               /cmd/[command] --> function return
       output: JSON { "value" : "[value]" }
           if function return is an array, it will be imploded with ','.
           that said, if items in your array contains ',' already, this library
           does not give a rat's buttock about that.
    */
    
	$import[] = "things.config.mysql_connect,
	             things.models.thing,
				 things.lib.core";
	
    require_once ('.things.php');
    
    /* if (isset ($_GET)) {
        $args = $_GET; // heh, just giving it a name
    } else { 
        $args = $_POST; // POST-compatible?
    }*/
    $args = array_merge ($_GET, $_POST);
    
    $oid  = (isset ($args['oid']))  ? $args['oid']  : ''; // object id
    $prop = (isset ($args['prop'])) ? $args['prop'] : ''; // what the client is requesting
    $val  = (isset ($args['val']))  ? $args['val']  : ''; // anything the client wants to specify
    $key  = (isset ($args['key']))  ? $args['key']  : ''; // access key (currently used for writes)
    
    header('Content-Type: text/html; charset=UTF-8'); 
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // IE bug: caches JSON
    header('Content-type: application/json'); // you return JSON 
    
    if (isset ($oid) && $oid > 0) { 
        if (isset ($prop) && strlen ($prop) > 0) { // specify property
            if (ObjectExists ($oid)) {
                $the_obj = new Thing ($oid);
                if (isset ($val) && strlen ($val) > 0 && 
                    $key == WriteAccessHash ($oid,$prop)) { // write
                    //
                    $the_obj->SetProps (array ($prop=>$val));
                    echo '{"status":"OK"}';
                } else { // read
                    $val = $the_obj->GetProp ($prop);
                    if (!is_null ($val)) {
                        echo '{"value":"' . htmlentities ($val, ENT_QUOTES) . '"}';
                    }
                }
            }
        } else {
            // get list of avaiable properties
            if (ObjectExists ($oid)) {
                $the_obj = new Obj ($oid);
                $props = $the_obj->GetProps ();
                if (sizeof ($props) > 0) {
                    echo '{["' . implode (array_map('htmlentities', array_keys ($props), array_fill(0, sizeof ($props), ENT_QUOTES)), '","') . '"]}';
                }
            }
        }
    }
?>