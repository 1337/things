<?php
    // Example tests.
	
	require_once ('UnitTestClass.class.php');
	require_once ('UnitTestClass.view.php');
	require_once ('../../.things.php');

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
			$this->assertEqual($this->dummy->GetPropFile (), 'prop://');
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
	
	showResults (array ($a, $b, $c, $d));
	
?>