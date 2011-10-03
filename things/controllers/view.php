<?php
    class View {
        /*  View handles page templates (Views). put them inside THINGS_TEMPLATE_DIR.
            Normal priority schema:
                 If $_REQUEST[theme_override] exists, use it
            Else If user-specific template found, use it
            Else If page-specific template found (as param $special), use it
            Else If site-specific template found, use it
            Else If site default template found, use it
            Else fail
        */
        var $contents;
        function View ($special = '') {
            $this->__construct ($special);
        }
        
        function __construct ($special = '') {
            // if $special (file name) is specified, then that template will be used instead.
            // note that user pref take precedence over those in page, post, etc.
         
            $template = $this->ResolveTemplateName ($special); // returns full path
            $this->contents = $this->GetParsed ($template);
        }
     
        function GetParsed ($file) {
            ob_start();
            include ($file);
            $buffer = ob_get_contents();
            ob_end_clean();
            return $buffer;
        }
     
        function ResolveTemplateName ($special = '') {
            global $user, $gp;
         
            // if one is specified, use it
            if (strlen ($special) > 0 &&
                is_file (THINGS_TEMPLATE_DIR . $special)) {
                return THINGS_TEMPLATE_DIR . $special;
            }
         
            // if $_REQUEST contains a special sauce, use it
            if (isset ($gp) && strtolower (get_class ($gp)) == "gpvar") {
                $t = $gp->Get ('theme_override');
                if (!is_null ($t) && strlen ($t) > 0 && 
                    file_exists (THINGS_TEMPLATE_DIR . $t) && is_good_path ($t)) {
                    return THINGS_TEMPLATE_DIR . $t;
                }
            }
         
            // if user has a preference, use it
            if (isset ($user) && strtolower (get_class ($user)) == "user") {
                $t = $user->GetProp ('template');
                if (!is_null ($t) && strlen ($t) > 0 && 
                    file_exists (THINGS_TEMPLATE_DIR . $t) && is_good_path ($t)) {
                    return THINGS_TEMPLATE_DIR . $t;
                }
            }
            
            // if site has a setting, use it
            if (file_exists (THINGS_TEMPLATE_DIR . THINGS_SITE_TEMPLATE)) {
                return THINGS_TEMPLATE_DIR . THINGS_SITE_TEMPLATE;
            }
         
            // fall back to the only one included
            if (file_exists (THINGS_TEMPLATE_DIR . THINGS_DEFAULT_TEMPLATE)) {
                return THINGS_TEMPLATE_DIR . THINGS_DEFAULT_TEMPLATE;
            }
         
            // no theme found
            die ('Site upgrade in progress.');
        }
     
        public function BuildPage () {
            // recursively replace tags that look like <!--inherit file="header_and_footer.php" -->
            // with their actual contents.
            $tag_pattern = '/<!--\s*inherit\s+file\s*=\s*"([^"]+)"\s*-->/';
            // for a file that looks like
            //     <!-- inherit file = "template4.inc" -->
            //     <!-- inherit file = "template6.inc" -->
            //     <!-- inherit file = "template6.inc" -->
            // matches will be like
            // Array (
            //     [0] => Array (
            //         [0] => <!-- inherit file = "template4.inc" -->
            //         [1] => <!-- inherit file = "template5.inc" -->
            //         [2] => <!-- inherit file = "template6.inc" -->
            //     )
            //     [1] => Array (
            //         [0] => template4.inc
            //         [1] => template5.inc
            //         [2] => template6.inc
            //     )
            // )

            $matches = array ();
            if (preg_match_all ($tag_pattern, $this->contents, $matches) > 0) { // as long as there is still a tag left...
                if (sizeof ($matches) > 0 && sizeof ($matches[1]) > 0) {
                    foreach ($matches[1] as $filename) { // [1] because (see example)
                        if (is_file (THINGS_TEMPLATE_DIR . $filename)) { // "file exists"
                            $nv = new View ($filename);
                            $nv->BuildPage (); // call buildpage on IT
                         
                            // replace tags in this contents with that contents
                            $this->contents = preg_replace (
                                '/<!--\s*inherit\s+file\s*=\s*"' . addslashes ($filename) . '"\s*-->/', 
                                $nv->contents, 
                                $this->contents
                            );
                            unset ($nv);
                        }
                    }
                }
                $matches = array (); // reset matches for next preg_match
            }
        }
     
        public function ReplaceTags ($tags = array ()) {
            global $user;
         
            $this->BuildPage (); // recursively include files
         
            if (sizeof ($tags) > 0) {
                // replace special tags (e.g. tags that must exist)
                $tags = array_merge (array ('title'=>''), $tags);
             
                if (isset ($user)) {
                    // replace user-level tags (if logged in)
                    $tags = array_merge ($user->GetProps (), $tags);
                }
             
                //user-defined items
                foreach ($tags as $tag => $data) {
                    $data = (file_exists($data))     //decides on
                          ? $this->GetParsed ($data) //file replacement or
                          : $data;                   //string replacement.
                       
                    $this->contents = preg_replace ("/<!--self\." . $tag . "-->/i", $data, $this->contents);
                }
                             
                $this->replaceControls ();     
                $this->contents = str_ireplace("<!--root-->", WEBROOT, $this->contents);
            }
        }
     
        private function replaceControls () {
            // wrong place to put ajaxfield replacement functions.
            global $user;
            $tags = array ();
            $regex = '#<!--\\s*controls\\.([a-z]+)\\.([a-z]+)(\\s*,\\s*name\\s*=\\s*[\\"\\\'](.*[^\\\\])[\\"\\\'])?\\s*-->#i';
                // <!--controls.ctrlname.propname[,name="caption"]-->
            $v = $this->contents;
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
            } catch (Exception $e) { /* meh */ }
            $this->contents = $v;
        }
     
        public function output () {
            echo ($this->html_compress ($this->contents));
        }
     
        public function html_compress ($h) {
            // well, compresses html
            return preg_replace ('/(?:(?)|(?))(\s+)(?=\<\/?)/',' ', $h);
        }
    }
 
    function render ($options = array (), $template = '') {
        // that's why you ob_start at the beginning of Things.
        $content = ob_get_contents (); ob_end_clean ();
        $options = array_merge ($options, array ('content'=>$content));
        $pj = new View ($template);
        $pj->ReplaceTags ($options);
        $pj->output ();
    }
 
    function page_out () { // same as render, deprecated
        $a = func_get_args(); // don't merge $a to next line, it won't work
        call_user_func_array ('render', $a);
    }
?>