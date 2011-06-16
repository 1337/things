<?php

    class View {
        /*  View handles page templates (Views). put them inside THINGS_TEMPLATE_DIR.
            Normal priority schema:
                 If user-specific template found, use it
            Else If page-specific template found (as param $special), use it
            Else If site-specific template found, use it
            Else If site default template found, use it
            Else fail
        */

        var $view;

        function __construct ($special = '') {
            // if $special is specified, then that template will be used instead.
            // note that user pref take precedence over those in page, post, etc.
            global $user;
            if (isset ($user) && 
                strtolower (get_class ($user)) == "user") {
                $t = $user->GetProp ('template');
                if ($t != null && strlen ($t) > 0 && 
                    file_exists (THINGS_TEMPLATE_DIR . $t) && strpos ($t, '..') === false) { // basically, you checked if a user has a template defined
                    $template = THINGS_TEMPLATE_DIR . $user->GetProp ('template');
                } else {
                    $template = THINGS_TEMPLATE_DIR . THINGS_DEFAULT_TEMPLATE;
                }
            } elseif (file_exists (THINGS_TEMPLATE_DIR . $special) &&
                    is_file (THINGS_TEMPLATE_DIR . $special)) {
                // if user has no prefs, use special
                $template = THINGS_TEMPLATE_DIR . $special;
/*            } elseif (file_exists (THINGS_TEMPLATE_DIR . THINGS_SITE_TEMPLATE)) {
                // fall back to site-specific
                $template = THINGS_TEMPLATE_DIR . THINGS_SITE_TEMPLATE;
*/            } elseif (file_exists (THINGS_TEMPLATE_DIR . THINGS_DEFAULT_TEMPLATE)) {
                // fall back to the only one included
                $template = THINGS_TEMPLATE_DIR . THINGS_DEFAULT_TEMPLATE;
            } else {
                // no theme, what do?
                die ('Site upgrade in progress.');
            }
            $this->view = join('', file ($template));
        }
        function View ($template = '') {
            $this->__construct ($template);
        }
        
        function parse ($file) {
            ob_start();
            include_once ($file);
            $buffer = ob_get_contents();
            ob_end_clean();
            return $buffer;
        }
        
        public function replace_tags ($tags = array ()) {
            // global $defined_tags; 
            global $user;
            if (sizeof ($tags) > 0) {
                // replace special tags
                $tags = array_merge (array ('title'=>''), $tags);
                //user-defined items
                foreach ($tags as $tag => $data) {
                    $data = (file_exists($data))    //decides on
                          ? $this->parse($data)         //file replacement or
                          : $data;                  //string replacement.
                    //$this->view = str_replace ("<!--self.$tag-->", $data, $this->view);
                    $this->view = preg_replace ("/<!--self\." . $tag . "-->/i", $data, $this->view);
                }
                // replace user-level tags (if logged in)
                if (isset ($user) && is_object ($user) && 
                    sizeof ($user->GetProps ()) > 0 && $user->GetProp ('userheaders') != null) {
                    // userheaders is the thing users can has.
                    $this->view = str_replace (
                        "<!--self.userheaders-->", 
                        $user->GetProp ('userheaders'),
                        $this->view
                    );
                }
                      
                $this->replaceControls ();
                
		$this->view = str_ireplace("<!--root-->", WEBROOT, $this->view);

                /* repeat for defined tags (defined in conf)
                foreach ($defined_tags as $tag => $data) {
                    $this->view = str_ireplace("<!--self.$tag-->", $data, $this->view);
                }*/
            }
        }
        
        private function replaceControls () {
            // wrong place to put ajaxfield replacement functions.
            global $user;
            $tags = array ();
            $regex = '#<!--\\s*controls\\.([a-z]+)\\.([a-z]+)(\\s*,\\s*name\\s*=\\s*[\\"\\\'](.*[^\\\\])[\\"\\\'])?\\s*-->#i';
                // <!--controls.ctrlname.propname[,name="caption"]-->
            $v = $this->view;
            try {
                preg_match_all ($regex, $v, $tags);
                if (class_exists ('AjaxField') 
                    && sizeof ($tags) > 0
                    && is_object ($user)) {
						for ($i = 0; $i < sizeof ($tags[0]); $i++) {
                        $a = new AjaxField ($user->oid);
                        $fn = "New". $tags[1][$i] . "Field"; // tags[1][0] = <!--controls.CONTROLNAME.prop .. >
                        if (method_exists ($a, $fn)) {
                            ob_start (); // local ob
                            $a->$fn ($tags[2][$i], $tags[4][$i]);
                            $code = ob_get_clean (); // get ob
                            $regex2 = '#<!--\\s*controls\\.([a-z]+)\\.' . $tags[2][$i] . '(\\s*,\\s*name\\s*=\\s*[\\"\\\'](.*[^\\\\])[\\"\\\'])?\\s*-->#i';
                            $v = preg_replace ($regex2, $code, $v);
                            $code = '';
                        }
                    }
                }
            } catch (Exception $e) {
                // screw it
                $e->getMessage ();
            }
            $this->view = $v;
        }
        
        public function output () {
            echo ($this->html_compress ($this->view));
        }
        
        public function html_compress ($h) {
            return preg_replace ('/(?:(?)|(?))(\s+)(?=\<\/?)/',' ', $h);
        }
    }
    
    function render ($options = array (), $template = '') {
        // that's why you ob_start at the beginning of Things.
        $content = ob_get_contents (); ob_end_clean ();
        $options = array_merge ($options, array ('content'=>$content));
        $pj = new View ();
        $pj->replace_tags ($options);
        $pj->output ();
    } function page_out ($options = array (), $template = '') {
        // name is deprecated, but nobody cares
        render ($options, $template);
    } 
?>
