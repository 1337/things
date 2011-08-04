<?php
    // Example tests.
    
    require_once ('UnitTestClass.class.php');
    require_once ('UnitTestClass.view.php');
    require_once ('.things.php');
    CheckAuth ();

    class PHPTest extends UnitTestClass {
        // detection of features (requires 'disable_classes' and 'disable_functions' to be off
    
        function test_php_version () {
            $this->assertNotEqual (strlen (phpversion ()), 0);
        }
        function test_safe_mode () {
            $this->assertEqual (ini_get ('safe_mode'), 0);
        }
        function test_error_reporting () {
            $this->assertEqual (ini_get ('error_reporting'), null);
        }
        function test_display_errors () {
            $this->assertEqual (ini_get ('display_errors'), 1);
        }
        function test_display_startup_errors () {
            $this->assertEqual (ini_get ('display_startup_errors'), 0);
        }
        function test_short_open_tag () {
            $this->assertEqual (ini_get ('short_open_tag'), 1);
        }
        function test_allow_url_fopen () {
            $this->assertEqual (ini_get ('allow_url_fopen'), 1);
        }
        function test_detect_unicode () {
            $this->assertEqual (ini_get ('detect_unicode'), 1);
        }
        function test_allow_url_include () {
            $this->assertEqual (ini_get ('allow_url_include'), 1);
        }
        function test_arg_separator_input () {
            $this->assertEqual (ini_get ('arg_separator.input'), '&');
        }
        function test_arg_separator_output () {
            $this->assertEqual (ini_get ('arg_separator.output'), '&');
        }
        function test_asp_tags () {
            $this->assertEqual (ini_get ('asp_tags'), 0);
        }
        function test_y2k_compliance () {
            $this->assertEqual (ini_get ('y2k_compliance'), 1);
        }
        function test_allow_call_time_pass_reference () {
            $this->assertEqual (ini_get ('allow_call_time_pass_reference'), 1);
        }
        function test_disable_functions () {
            $this->assertEqual (ini_get ('disable_functions'), false);
        }
        function test_disable_classes () {
            $this->assertEqual (ini_get ('disable_classes'), false);
        }
        function test_expose_php () {
            $this->assertEqual (ini_get ('expose_php'), 1);
        }
        function test_auto_globals_jit () {
            $this->assertEqual (ini_get ('auto_globals_jit'), 1);
        }
        function test_register_globals () {
            $this->assertEqual (ini_get ('register_globals'), 0);
        }
        function test_gpc_order () {
            $this->assertEqual (ini_get ('gpc_order'), "GPC");
        }
        function test_auto_prepend_file () {
            $this->assertEqual (ini_get ('auto_prepend_file'), null);
        }
        function test_auto_append_file () {
            $this->assertEqual (ini_get ('auto_append_file'), null);
        }
        function test_default_mimetype () {
            $this->assertEqual (ini_get ('default_mimetype'), 'text/html');
        }
        function test_default_charset () {
            $this->assertEqual (ini_get ('default_charset'), '');
        }
        function test_zend_extension () {
            $this->assertEqual (ini_get ('zend_extension'), null);
        }
        function test_file_uploads () {
            $this->assertEqual (ini_get ('file_uploads'), 1);
        }
        function test_max_file_uploads () {
            $this->assertEqual (ini_get ('max_file_uploads'), 20);
        }
        function test_sql_safe_mode () {
            $this->assertEqual (ini_get ('sql.safe_mode'), 0);
        }
        function test_get_magic_quotes_gpc () {
            $this->assertEqual (get_magic_quotes_gpc (), 0);
        }
        function test_get_magic_quotes_runtime () {
            $this->assertEqual (get_magic_quotes_runtime (), 0);
        }
    } $e = new PHPTest ();
    
    class ThingTest extends UnitTestClass {
        public $dummy;
        function setup () {
            // echo ("Let me get the testing environment ready...");
            // clean up previous dummies
            $b = new Things (DUMMY);
            $c = $b->GetObjects ();
            if (sizeof ($c)) {
                foreach ($c as $oid) {
                    $x = new Thing ($oid);
                    $x->Destroy (); 
                }
            }
            
            // make a new one for this test
            $this->dummy = new Thing (NEW_DUMMY);
        }
        function teardown () {
            $this->dummy->Destroy ();
        }
        function test_createobject () {
            $this->assertTrue (is_object ($this->dummy));
            $this->assertTrue (gettype ($this->dummy) == "object");
            $this->assertTrue ($this->dummy->GetType (), 'Dummy');
            $this->assertTrue ($this->dummy->oid > 0);
        }
        function test_auto_objects_by_reference () {
            $a = new Thing ($this->dummy->oid);
            $b = new Thing ($this->dummy->oid);
            $this->assertReference($a, $b);
        }
        function test_SaveProp () {
            $prop = 'prop1';
            $val = 'val1';
            $this->dummy->SetProp ($prop, $val);
            $this->assertEqual ($this->dummy->GetProp ($prop), $val);
        }
        function test_SaveProps () {
            $prop3 = 'prop3';
            $val3 = 'val3';
            $prop2 = 'prop2';
            $val2 = 'val2';
            $this->dummy->SetProps (array ($prop3 => $val3, $prop2 => $val2));
            $this->assertEqual ($this->dummy->GetProp ($prop3), $val3);
            $this->assertEqual ($this->dummy->GetProp ($prop2), $val2);
            
            $props = $this->dummy->GetProps ();
            
            $this->assertEqual ($props[$prop3], $val3);
            $this->assertEqual ($props[$prop2], $val2);
                
        }
        function test_save_long_props () {
            $k = '';
            $props_count = sizeof (glob ($_SERVER['DOCUMENT_ROOT'] . '/things/props/*'));
            for ($i = 0; $i < 1000; $i ++) {
                $k .= md5 ($i);
            }
            $this->dummy->SetProp ('prop4', $k); // basically, a long string
            $this->assertEqual ($this->dummy->GetProp ('prop4'), $k);
            $this->assertEqual (sizeof (glob ($_SERVER['DOCUMENT_ROOT'] . '/things/props/*')), $props_count + 1);
        }
        function test_prop_url () {
            // $this->assertEqual($this->dummy->GetPropFile (), 'prop://');
        }
        function test_type () {
            $this->dummy->SetType (POST);
            $this->assertEqual($this->dummy->GetType (), POST);
            $this->dummy->SetType (DUMMY);
            $this->assertEqual($this->dummy->GetType (), DUMMY);
            $this->dummy->SetType (0);
            $this->assertEqual($this->dummy->GetType (), DUMMY);
            $this->dummy->SetType (-999);
            $this->assertEqual($this->dummy->GetType (), DUMMY);
        }
    } $a = new ThingTest ();

    class ThingsTest extends UnitTestClass {
        public $dummy, $things;
        function setup () {
            // echo ("Let me get the testing environment ready...");
            $this->dummy = new Thing (NEW_DUMMY);
            
            $this->dummy->SetProp ('prop1', 'val1');
            
            $this->things = new Things (DUMMY);
        }       
        function teardown () {
            $this->dummy->Destroy ();
        }
        function test_objectcount () {
            $this->assertEqual (
                sizeof ($this->things->GetObjects ()), 
                1
            );
            $b = new Thing (NEW_DUMMY);
            $this->assertEqual (
                sizeof ($this->things->GetObjects ()), 
                1
            );
            $this->assertEqual (
                sizeof ($this->things->GetObjects (true)), 
                2
            );
            $b->Destroy ();
        }
        
        function test_filter () {
            $this->things = new Things (POST);
        }
		
		function test_sort () {
			$b = new Things (TICKET);
			$orio = sizeof ($b->GetObjects ());
			$b->SetObjectsRaw ($b->Sort ($b->GetObjects (), 'time_needed'));
			$orin = sizeof ($b->GetObjects ());
			$this->assertEqual ($orio, $orin);
		}
        
    } $b = new ThingsTest ();

    class CoreTest extends UnitTestClass {
        function test_defaultto () {
            $this->assertEqual (
                DefaultTo (1,2,3,4,5), 
                1
            );
            $this->assertEqual (
                DefaultTo (null,2,3,4,5), 
                2
            );
            $this->assertEqual (
                DefaultTo (null,'',3,4,5), 
                3
            );
            $this->assertEqual (
                DefaultTo (null,'',false,4,5), 
                4
            );
            $this->assertEqual (
                DefaultTo (0,'',false, null ,5), 
                0
            );
        }
    } $c = new CoreTest ();
    
    class PrivTest extends UnitTestClass {
        
        public $usr;
        public $priv;
        
        function setup () {
            $grp = new Things (USER);
            $usrs = $grp->GetObjects ();
            $this->usr = new User ($usrs[0]); // pick a user

            $grp = new Things (PRIVILEGE);
            $privs = $grp->GetObjects ();
            $this->priv = new Privilege ($privs[0]); // pick a privilege
        }
        
        function test_setup_succeeded () {
            $this->assertIsA($this->usr, 'User');
            $this->assertIsA($this->priv, 'Privilege');
        }
        
        function test_give_priv () {
            $this->usr->SetChildren (array ($this->priv->oid)); // give user this priv
            $this->assertTrue (in_array ($this->priv->oid, $this->usr->GetChildren (PRIVILEGE)));
        }
        
        function test_test_priv () {
            $this->assertTrue ($this->usr->CheckPrivilege ($this->priv->oid));
            $this->assertTrue ($this->usr->CheckPrivileges (array ($this->priv->oid)));
            $priv_name = $this->priv->GetProp ('name');
            // supposed to fail, CheckPrivilege does not handle type string
            $this->assertTrue ($this->usr->CheckPrivilege ($priv_name));
        }
        
        function test_auth () {
            // CheckAuth uses local $user and can't be tested
        }
                
    } $d = new PrivTest (); 
    
    showResults (array ($a, $b, $c, $d, $e));
?>
