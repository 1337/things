<?php
    require_once ('.things.php');
    require_once (PROOT . 'lib/core.php');
    require_once (PROOT . 'models/thing.php');
    
    /* 
        Recommended stylesheet for this object
        
        <style type="text/css">
            .datafield, .datafield-label {
                font-family: sans-serif;
                font-size: 13px;
            }
            .datafield-label {
                display:inline-block;
                min-width: 100px;
                text-align: right;
            }
            .datafield {
                border: 1px solid transparent;
                border-radius: 3px;
                padding: 4px;
                cursor: hand;
            }
            .datafield:focus {
                border: 1px solid silver;
                cursor: text;
            }
            .datafield:hover {
                background-color: #ffd;
            }
        </style>
    */
    
    class AjaxField extends Thing {
        // Field is not an Object. 
        // Use the object ID of the object from which this field will come.
        // Do not use Object functions. I don't know what will happen.
        
        public $url, $prop, $fn, $val, $node, $key, $normalclr, $hoverclr, $successclr, $failclr;
        
        function init_vars ($prop, $friendlyname = '', $readonly = false) {
            // put here to avoid namespace collision with __construct ()
            $this->url = WEBROOT . "things/controllers/ajax.php";
            $this->prop = $prop;
            if (strlen ($friendlyname) > 0) {
                $this->fn = $friendlyname;
            } else {
                $this->fn = $this->prop; // default to just the property name
            }
            $this->val = htmlspecialchars ($this->GetProp ($this->prop));
            $this->node = $this->oid . '/' . $this->prop;
            $this->key = WriteAccessHash ($this->oid, $this->prop); // variable arguments
            $this->successclr = "#dfd";
            $this->failclr = "#fdd";            
        }
        
        function GetAjaxFn () {
            // generates the ajax event that would be called, normally onBlur.
            return "
            function ec (w) { return encodeURIComponent (w); }
            var im = this;
            var xh = (window.XMLHttpRequest) ?
                new XMLHttpRequest() : // IE gt 7
                new ActiveXObject('Microsoft.XMLHTTP'); // IE lt 7
            xh.onreadystatechange = function() {
                im.style.backgroundColor = (xh.readyState==4 && xh.status==200) ? 
                '" . $this->successclr . "' : '" . $this->failclr . "';
            }
            var prs = im.getAttribute('rel').split('/');
            xh.open('POST', '" . $this->url . "', true);
            xh.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xh.send ('oid=' + ec (prs[0]) + '&prop=' + ec (prs[1]) + 
                     '&val=' + ec (im.value) + '&key=' + ec (im.getAttribute ('key')));";
        }
        
        
        function NewTextField ($prop, $friendlyname = '', $readonly = false, $style='', $type='text') {
            // $friendlyname (optional) would be the field label.
			// this function supports variable arguments (read code for details).
			
			if (func_num_args () == 1 && is_array ($prop)) {
				/*  this means NewTextField is being called with parameters like
				    NewTextField (array (
					    'prop' => ...,
						'friendlyname' => ...
					));
			    */
				$friendlyname = @DefaultTo ($prop['friendlyname'], '');
				$readonly = @DefaultTo ($prop['readonly'], false);
				$style = @DefaultTo ($prop['style'], '');
				$type = @DefaultTo ($prop['type'], 'text');
				$prop = @DefaultTo ($prop['prop']); // must be last (notice change of $prop)
			}
			
            $this->init_vars($prop, $friendlyname, $readonly);
            
            if (!$readonly) { // if writable ?>
                <label for="df_<?php echo ($this->fn); ?>_<?php echo ($this->oid); ?>" class="datafield-label">
                    <?php echo ($this->fn); ?>:
                </label>
                <input id="df_<?php echo ($this->fn); ?>_<?php echo ($this->oid); ?>" 
                    class="datafield" 
                    style="<?php echo ($style); ?>"
                    type="<?php echo ($type); ?>"
                    onFocus="this.select ();"
                    onBlur="<? echo ($this->GetAjaxFn ()); ?>"
                    onKeyPress="event.returnValue = (event.keyCode != 13);"
                    rel="<?php echo ($this->node); ?>" 
                    key="<?php echo ($this->key); ?>"
                    value="<?php echo ($this->val); ?>" />
            <?php } else { // if read-only ?>
                <label for="df_<?php echo ($this->fn); ?>" class="datafield-label">
                    <?php echo ($this->fn); ?>:
                </label>
                <input id="df_<?php echo ($this->fn); ?>" 
                    class="datafield" 
                    type="text"
                    disabled="disabled"
                    value="<?php echo ($this->val); ?>" />
            <?php
            }
        }
        
        function NewTextAreaField ($prop, $friendlyname = '', $readonly = false, $style='') {
            // $friendlyname (optional) would be the field label.
			// this function supports variable arguments (read code for details).
			
			if (func_num_args () == 1 && is_array ($prop)) {
				/*  this means NewTextAreaField is being called with parameters like
				    NewTextField (array (
					    'prop' => ...,
						'friendlyname' => ...
					));
			    */
				
				$friendlyname = @DefaultTo ($prop['friendlyname'], '');
				$readonly = @DefaultTo ($prop['readonly'], false);
				$style = @DefaultTo ($prop['style'], '');
				$prop = @DefaultTo ($prop['prop']); // must be last (notice change of $prop)
			}

            $this->init_vars($prop, $friendlyname, $readonly);
            
            if (!$readonly) { // if writable ?>
                <label for="df_<?php echo ($this->fn); ?>_<?php echo ($this->oid); ?>" class="datafield-label">
                    <?php echo ($this->fn); ?>:
                </label>
                <textarea id="df_<?php echo ($this->fn); ?>_<?php echo ($this->oid); ?>" 
                    class="datafield" 
                    style="<?php echo ($style); ?>"
                    onFocus="this.select ();"
                    onBlur="<? echo ($this->GetAjaxFn ()); ?>"
                    onKeyPress="event.returnValue = (event.keyCode != 13);"
                    rel="<?php echo ($this->node); ?>" 
                    key="<?php echo ($this->key); ?>"
                    ><?php echo ($this->val); ?></textarea>
            <?php } else { // if read-only ?>
                <label for="df_<?php echo ($this->fn); ?>" class="datafield-label">
                    <?php echo ($this->fn); ?>:
                </label>
                <textarea id="df_<?php echo ($this->fn); ?>" 
                    class="datafield" 
                    type="text"
                    disabled="disabled"
                ><?php echo ($this->val); ?></textarea>
            <?php
            }
        }

        function NewCheckboxField ($prop, $friendlyname = '', $readonly = false, $style='') {
            // $friendlyname (optional) would be the field label.
            $this->init_vars($prop, $friendlyname, $readonly);
            
            if (!$readonly) { // if writable ?>
                <label for="df_<?php echo ($this->fn); ?>_<?php echo ($this->oid); ?>" class="datafield-label">
                    <?php echo ($this->fn); ?>:
                </label>
                <input id="df_<?php echo ($this->fn); ?>_<?php echo ($this->oid); ?>" 
                    class="datafield" 
                    style="<?php echo ($style); ?>"
                    type="checkbox"
                    onClick="this.value = (this.checked) ? 1:0;
                        <? echo ($this->GetAjaxFn ()); ?>"
                    rel="<?php echo ($this->node); ?>" 
                    key="<?php echo ($this->key); ?>"
                    <?php echo (($this->val) > 0 ? "checked='checked'" : ""); ?> />
            <?php } else { // if read-only ?>
                <label for="df_<?php echo ($this->fn); ?>" class="datafield-label">
                    <?php echo ($this->fn); ?>:
                </label>
                <input id="df_<?php echo ($this->fn); ?>" 
                    class="datafield" 
                    type="checkbox"
                    disabled="disabled"
                    <?php echo (($this->val) > 0 ? "checked='checked'" : ""); ?> />
            <?php
            }
        }

    }
?>
